<?php

namespace App\Livewire\Dashboards;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Assignment;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use App\Services\EmailNotificationService;
use Illuminate\Support\Facades\Log;

#[Title('Justice Dashboard')]
class Justice extends Component
{
    use WithFileUploads;

    public $TobeSolved = [];
    public $showModal = null;
    public $showPostponeModal = null;
    public $evidence;
    public $assignment;

    public $form = [
        'victim_resolution' => '',
        'offender_resolution' => '',
        'witnesses' => '',
        'attendees' => '',
        'justice_resolution' => '',
        'ended_at' => '',
        //'offender_mail' => '',
        'complainant_phone' => '',
        'postpone_reason' => '',
        'new_hearing_date' => '',
    ];

    protected EmailNotificationService $emailService;

    public function __construct()
    {
        $this->emailService = app(EmailNotificationService::class);
    }

    public function mount()
    {
        $justiceId = auth()->user()->id;

        $this->TobeSolved = Assignment::with(['dispute.citizen', 'justice'])
            ->where('justice_id', $justiceId)
            ->whereHas('dispute', fn ($q) => $q->where('status', 'kizasomwa'))
            ->get();

    }

    public function openModal($assignmentId)
    {
        $this->showModal = $assignmentId;
        $assignment = $this->TobeSolved->firstWhere('id', $assignmentId);

        if ($assignment && $assignment->dispute) {
            $this->resetForm();
            //$this->form['offender_mail'] = $assignment->dispute->offender_mail ?? '';
            $this->form['complainant_phone'] = $assignment->dispute->citizen->phone ?? '';
        }
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'evidence']);
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->form = [
            'victim_resolution' => '',
            'offender_resolution' => '',
            'witnesses' => '',
            'attendees' => '',
            'justice_resolution' => '',
            'ended_at' => '',
            //'offender_mail' => '',
            'complainant_phone' => '',
        ];
    }

    public function submitResolution($assignmentId)
    {
        $assignment = Assignment::with(['dispute.citizen'])->findOrFail($assignmentId);
        $dispute = $assignment->dispute;

        $this->validate([
            'form.victim_resolution' => 'nullable|string',
            'form.offender_resolution' => 'nullable|string',
            'form.witnesses' => 'nullable|string',
            'form.attendees' => 'nullable|string',
            'form.justice_resolution' => 'nullable|string',
            'form.ended_at' => 'nullable|date',
            //'form.offender_mail' => 'nullable|string|email',
            'form.complainant_phone' => 'nullable|string',
            'evidence' => 'nullable|file|max:10240',
        ]);

        $evidencePath = null;
        if ($this->evidence) {
            $evidencePath = $this->evidence->store('evidences', 'public');
        }

        DB::transaction(function () use ($assignment, $dispute, $evidencePath) {
            Report::create([
                'assignment_id' => $assignment->id,
                'dispute_id' => $dispute->id,
                'victim_resolution' => $this->form['victim_resolution'],
                'offender_resolution' => $this->form['offender_resolution'],
                'witnesses' => $this->form['witnesses'],
                'attendees' => $this->form['attendees'],
                'justice_resolution' => $this->form['justice_resolution'],
                'evidence_path' => $evidencePath,
                'ended_at' => $this->form['ended_at'],
            ]);

            $dispute->status = 'cyakemutse';
            //$dispute->offender_mail = $this->form['offender_mail'];
            $dispute->save();

            if ($dispute->citizen) {
                $dispute->citizen->phone = $this->form['complainant_phone'];
                $dispute->citizen->save();
            }
        });

        // Reload updated data to ensure correct values
        $updatedDispute = Assignment::with('dispute.citizen')->find($assignmentId)->dispute;

        // Logging for debugging
        Log::info('Dispute resolved. Attempting to notify:', [
            'offender_email' => $updatedDispute->offender_mail,
            'citizen_email' => $updatedDispute->citizen->email ?? null,
        ]);

        // Send email only if both addresses are present
        if (!empty($updatedDispute->offender_mail) && !empty($updatedDispute->citizen?->email)) {
            $this->emailService->notifyDisputeResolved(
                [
                    'email' => $updatedDispute->offender_mail,
                    'name' => $updatedDispute->offender_name,
                ],
                [
                    'email' => $updatedDispute->citizen->email,
                    'name' => $updatedDispute->citizen->name,
                ],
                $this->form['justice_resolution'],
                $assignment->dispute_id
            );
        } else {
            Log::error('Email notification skipped due to missing address.', [
                'offender_email' => $updatedDispute->offender_mail,
                'citizen_email' => $updatedDispute->citizen->email ?? null,
            ]);
        }

        session()->flash('message', 'Raporo yoherejwe neza.');
        $this->closeModal();

        $this->mount(); // refresh list
    }

    public function submitPostponement($assignmentId)
{
    $this->validate([
        'form.postpone_reason' => 'required|string|min:5',
        'form.new_hearing_date' => 'required|date|after:now',
    ]);

    $assignment = Assignment::findOrFail($assignmentId);
    
    $assignment->update([
        'postponed_reason' => $this->form['postpone_reason'],
        'meeting_time' => $this->form['new_hearing_date'],
    ]);

    // Optional: Update dispute status too (only if needed)
    $assignment->dispute()->update([
        'status' => 'kizasomwa', // this means postponed?
    ]);

    $this->reset(['form', 'showPostponeModal']);
    session()->flash('message', 'Inama yasubitswe neza.');


    // Notify involved parties about the postponement
    $emailSent = $this->emailService->notifyDisputePostponed(
        [
            'email' => $assignment->dispute->offender_mail,
            'name' => $assignment->dispute->offender_name,
        ],
        [
            'email' => $assignment->dispute->citizen->email,
            'name' => $assignment->dispute->citizen->name,
        ],
        [
            'email' => auth()->user()->email,
            'name' => auth()->user()->name,
        ],
        $assignment->meeting_time,
        $assignment->meeting_time,
        $assignment->postponed_reason,
        $assignment->dispute->cell,
        $assignment->dispute->title,
        $assignmentId
    );
    if ($emailSent) {
        Log::info('Postponement notification sent successfully.');
    } else {
        Log::error('Failed to send postponement notification email.');
    }





}



    public function render()
    {
        return view('livewire.dashboards.justice');
    }
}
