<?php

namespace App\Livewire\Dashboards;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Assignment;
use App\Models\Report;
use App\Services\SmsNotificationService;
use Illuminate\Support\Facades\DB;


#[Title('Justice')]
class Justice extends Component
{
    use WithFileUploads;

    public $TobeSolved = [];
    public $showModal = null;
    public $evidence;

    public $form = [
        'victim_resolution' => '',
        'offender_resolution' => '',
        'witnesses' => '',
        'attendees' => '',
        'justice_resolution' => '',
        'ended_at' => '',
        'offender_phone' => '',
        'complainant_phone' => '',
    ];

    //rotected SmsNotificationService $smsService;

     public function mount()
    {
        $this->smsService = app(SmsNotificationService::class);

        $justiceId = auth()->user()->id;

        $this->TobeSolved = Assignment::with(['dispute.citizen', 'justice'])
            ->where('justice_id', $justiceId)
            ->whereHas('dispute', function ($query) {
                $query->where('status', 'kizasomwa');
            })
            ->get();
    }

    public function openModal($assignmentId)
    {
        $this->showModal = $assignmentId;
        $assignment = $this->TobeSolved->firstWhere('id', $assignmentId);

        if ($assignment && $assignment->dispute) {
            $this->form['victim_resolution'] = '';
            $this->form['offender_resolution'] = '';
            $this->form['witnesses'] = '';
            $this->form['attendees'] = '';
            $this->form['justice_resolution'] = '';
            $this->form['ended_at'] = '';
            $this->form['offender_phone'] = $assignment->dispute->offender_phone ?? '';
            $this->form['complainant_phone'] = $assignment->dispute->citizen->phone ?? '';
        }
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'form', 'evidence']);
    }

    public function submitResolution($assignmentId)
    {
        $assignment = Assignment::with(['dispute.citizen'])->findOrFail($assignmentId);
        $dispute = $assignment->dispute;

        // Validate form (add your validation rules as needed)
        $this->validate([
            'form.victim_resolution' => 'nullable|string',
            'form.offender_resolution' => 'nullable|string',
            'form.witnesses' => 'nullable|string',
            'form.attendees' => 'nullable|string',
            'form.justice_resolution' => 'nullable|string',
            'form.ended_at' => 'nullable|date',
            'form.offender_phone' => 'nullable|string',
            'form.complainant_phone' => 'nullable|string',
            'evidence' => 'nullable|file|max:10240', // max 10MB
        ]);

        // Save evidence file if uploaded
        $evidencePath = null;
        if ($this->evidence) {
            $evidencePath = $this->evidence->store('evidences', 'public');
        }

        // Use transaction to safely update related models
        DB::transaction(function () use ($assignment, $dispute, $evidencePath) {
            // Update Report
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

            // Update dispute status
            $dispute->status = 'cyakemutse';

            // Update offender phone directly on dispute
            $dispute->offender_phone = $this->form['offender_phone'];
            $dispute->save();

            // Update complainant phone on the linked citizen (user)
            if ($dispute->citizen) {
                $dispute->citizen->phone = $this->form['complainant_phone'];
                $dispute->citizen->save();
            }
        });

        // Send SMS notifications
        $smsService = app(SmsNotificationService::class);
        $this->smsService->notifyDisputeResolved(
            [
                'phone' => $this->form['offender_phone'],
                'name' => $assignment->dispute->offender_name,
            ],
            [
                'phone' => $this->form['complainant_phone'],
                'name' => $assignment->dispute->citizen->name,
            ],
            $this->form['justice_resolution']
        );

        session()->flash('message', 'Raporo yoherejwe neza.');
        $this->closeModal();

        // Refresh list to get updated status
        $this->mount();
    }

    public function render()
    {
        return view('livewire.dashboards.justice');
    }
}
