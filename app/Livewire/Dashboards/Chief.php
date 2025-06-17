<?php

namespace App\Livewire\Dashboards;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Dispute;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Log;
use App\Models\Assignment;
//use App\Services\SmsNotificationService;
use App\Services\EmailNotificationService;

#[Title('Umwunzi mukuru')]
class Chief extends Component
{
    use WithPagination;

    public $selectedDispute;
    public $justices = [];
    public $assignedJustices = [];
    public $meetingDate;
    public $activeTab = 'all';
    public $recieved;
    public $assigned;
    public $resolved;
    public $inProgress;

    protected $queryString = ['activeTab'];

    protected $listeners = ['refreshDisputes' => 'loadDisputes'];

    protected $rules = [
        'assignedJustices' => 'required|array|min:1|max:3',
        'meetingDate' => 'required|date|after:now',
    ];
    
    protected $messages = [
        'assignedJustices.required' => 'Ukeneye guhitamo abacamanza nibura umwe.',
        'assignedJustices.array' => 'Ukeneye guhitamo abacamanza nibura umwe.',
        'assignedJustices.min' => 'Ukeneye guhitamo abacamanza nibura umwe.',
        'assignedJustices.max' => 'Ukeneye guhitamo abacamanza batarenze 3.',
        'meetingDate.required' => 'Ukeneye gushyiraho itariki y\'inama.',
        'meetingDate.date' => 'Itariki y\'inama igomba kuba itariki nyayo.',
        'meetingDate.after' => 'Itariki y\'inama igomba kuba nyuma y\'iki gihe.',
    ];
    
    public ?User $user = null;



    //protected SmsNotificationService $smsService;
    public function __construct()
    {
        //$this->smsService = app(SmsNotificationService::class);
        $this->emailService = app(EmailNotificationService::class);

    }
    public function mount()
    {
        $this->loadDisputes();
        $this->justices = User::where('role', 'justice')->get();

    }

    public function loadDisputes()
    {
        $this->assigned = Dispute::where('status', 'Kizasomwa')->get();
        $this->recieved = Dispute::where('status', 'Cyoherejwe')->get();
        $this->resolved = Dispute::where('status', 'cyakemutse')->get();
        $this->inProgress = Dispute::where('status', 'Kiraburanishwa')->get();
        // Map citizen_id from disputes to the corresponding user records
        $citizenIds = Dispute::where('status', 'Kizasomwa')->pluck('citizen_id')->unique();

        $this->user = User::whereIn('id', $citizenIds)->first();

        Log::info('User loaded for dispute:', [
            'Citizen Id ' => $citizenIds,

            'user_id' => $this->user?->id,
            'dispute_id' => $this->selectedDispute?->id,
        ]);

        $this->dispatch('disputesUpdated');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->loadDisputes();
    }

    public function getDisputesForTab()
    {
        return match($this->activeTab) {
            'received' => Dispute::where('status', 'Cyoherejwe'),
            'assigned' => Dispute::where('status', 'Kizasomwa'),
            // 'inProgress' => Dispute::where('status', 'Kiraburanishwa'),
            'resolved' => Dispute::where('status', 'cyakemutse'),
            default => Dispute::query(),



            //Send SMS notifications
        };
    }

    public function selectDispute($id)
    {
        $this->selectedDispute = Dispute::find($id);
        $this->dispatch('open-dispute-modal');

    }

    public function assignDispute()
    {
        try {
            Log::info('Starting dispute assignment process.', [
                'selectedDispute' => $this->selectedDispute,
                'assignedJustices' => $this->assignedJustices,
            ]);

            $this->validate();

            foreach ($this->assignedJustices as $justiceId) {
                Assignment::create([
                    'dispute_id' => $this->selectedDispute->id,
                    'justice_id' => $justiceId,
                    'meeting_time' => $this->meetingDate,
                    'level' => 'Sector',
                ]);
            }
            
            Dispute::where('id', $this->selectedDispute->id)
                ->update(['chief' => auth()->user()->id]);

            $this->selectedDispute->update([
                'status' => 'Kizasomwa',
            ]);

            // Send SMS notifications
            //Send email notifications
                $venue = 'Akagari ka ' . $this->selectedDispute->cell;

    foreach ($this->assignedJustices as $justiceId) {
    $justice = User::find($justiceId);
    if ($justice) {
        $this->emailService->notifyDisputeAssigned(
            [
                'email' => $this->selectedDispute->offender_mail,
                'name'  => $this->selectedDispute->offender_name,
            ],
            [
                'email' => $this->selectedDispute->citizen->email,
                'name'  => $this->selectedDispute->citizen->name,
            ],
            [
                'email' => $justice->email,
                'name'  => $justice->name,
            ],
            $this->meetingDate,
            $venue,
            $this->selectedDispute->title,
            $this->selectedDispute->id
        );
    }
}



            $this->loadDisputes();
            session()->flash('message', 'Dispute assigned successfully!');
            $this->dispatch('close-dispute-modal');
            $this->reset(['assignedJustices', 'meetingDate']);

        } catch (\Exception $e) {
            Log::error('Error during dispute assignment process.', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'An error occurred while assigning the dispute.');
        }
    }


    public function render()
    {
        $disputes = $this->getDisputesForTab()->cursorPaginate(10);
        
        return view('livewire.dashboards.chief', [
            'disputes' => $disputes,
            'disputeCounts' => [
                'all' => Dispute::count(),
                'received' => $this->recieved->count(),
                'assigned' => $this->assigned->count(),
                // 'inProgress' => $this->inProgress->count(),
                'resolved' => $this->resolved->count(),
            ],
        ]);
    }
} 