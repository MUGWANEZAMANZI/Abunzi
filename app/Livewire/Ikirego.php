<?php

namespace App\Livewire;

use App\Services\EmailNotificationService;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Dispute;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail;
use Illuminate\Support\Facades\Auth;
//use App\Services\SmsNotificationService; // Make sure this service exists and works

class Ikirego extends Component
{
    // Location tree data loaded from JSON
    public $location = [];
    // Arrays holding options for each dropdown level
    public $provinces = [], $districts = [], $sectors = [], $cells = [], $villages = [];

    // Properties bound to dropdown selections
    // Using wire:model.live in Blade handles triggering updates as you select
    public $selectedProvince, $selectedDistrict, $selectedSector, $selectedCell, $selectedVillage;

    // Dispute fields with validation rules
    #[Validate('required', message: 'Andika umutwe w\'ikirego')]
    #[Validate('min:10', message: 'Umutwe w\'ikirego ugomba kuba ufite nibura inyuguti 10')]
    #[Validate('max:100', message: 'Umutwe w\'ikirego ugomba kuba ufite nibura inyuguti 100')]
    #[Validate('string', message: 'Umutwe w\'ikirego ugomba kuba inyuguti')]
    public $title;

    #[Validate('required', message: 'Andika izina ry\'uwo urega')]
    public $offender;

    // Basic phone format validation example - adjust regex as needed
    #[Validate('required', message: 'Andika telefoni y\'uwo urega')]
    //#[Validate('regex:/^\+250[0-9]{9}$/', message: 'Nimero ya telefoni igomba kuba itangiye na +250 igakurikirwa n\'imibare 9')]
    #[Validate('email', message: 'Andika email y\'uwo urega')]
    public $offender_mail;

    #[Validate('required', message: 'Andika ibikubiye mu ikirego')]
    #[Validate('min:20', message: 'Ibikubiye mu ikirego bigomba kuba bifite nibura inyuguti 20')]
    #[Validate('max:500', message: 'Ibikubiye mu ikirego bigomba kuba bifite nibura inyuguti 500')]
    #[Validate('string', message: 'Ibikubiye mu ikirego bigomba kuba inyuguti')]
    public $content;

    // Witness is optional per UI, but required per validation.
    // Let's keep it required based on the provided validation, or make it nullable if optional.
    #[Validate('required', message: 'Andika izina ry\'abatangabuhamya')]
    #[Validate('string', message: 'Amazina y\'abatangabuhamya agomba kuba inyuguti')]
    public $witness;

    // Other dispute properties
    public $status, $citizen_id, $location_name, $dispute_id;
    public bool $isEditing = false; // Flag to indicate if we are editing an existing draft
    public bool $isClosed = false; // Flag to control modal visibility

    //protected SmsNotificationService $smsService;
    protected EmailNotificationService $emailService;

    // Constructor is less common in Livewire 3+, prefer mount().
    // If you need to inject services, use dependency injection directly in the methods or mount.
    // However, this constructor approach works if SmsNotificationService is bound in the container.
    public function __construct()
    {
        // Ensure the service is bound in Laravel's service container
        //$this->smsService = app(SmsNotificationService::class);
        $this->emailService = app(EmailNotificationService::class);
        
    }

    public function mount($dispute_id = null)
    {


        //Loading current user
        $this->user = auth()->user();

        Log::info('Ikirego Component Mounted', ['dispute_id' => $dispute_id]);
        // Load locations data when the component mounts
        $this->loadLocations();

        // If a dispute ID is provided, attempt to load it for editing
        if ($dispute_id) {
            $this->loadDispute($dispute_id);
        } else {
             // Ensure location arrays are reset if not loading a dispute
             $this->reset(['selectedProvince', 'selectedDistrict', 'selectedSector', 'selectedCell', 'selectedVillage']);
             $this->reset(['districts', 'sectors', 'cells', 'villages']);
        }
    }

    // Fetches location data from JSON file
    public function loadLocations()
    {
        $filePath = public_path('locations.json');
        Log::info('Loading locations from', ['path' => $filePath]);

        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            if ($json === false) {
                Log::error('Failed to read locations.json');
                session()->flash('error', 'Could not read locations data file.');
                $this->provinces = [];
                $this->location = [];
            } else {
                $decodedJson = json_decode($json, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Failed to decode locations.json', ['json_last_error' => json_last_error_msg()]);
                    session()->flash('error', 'Could not decode locations data. Check JSON format.');
                    $this->provinces = [];
                    $this->location = [];
                } else {
                    $this->location = $decodedJson;
                    // Populate the initial provinces array
                    $this->provinces = array_keys($this->location);
                    Log::info('Locations loaded successfully', ['province_count' => count($this->provinces)]);
                }
            }
        } else {
            Log::error('locations.json file not found', ['path' => $filePath]);
            session()->flash('error', 'Location data file not found.');
            $this->provinces = [];
            $this->location = [];
        }
    }

    // Method called automatically by Livewire when $selectedProvince changes
    public function updatedSelectedProvince($value)
    {
        Log::info('Updated Selected Province', ['province' => $value]);
        // Reset subsequent levels when province changes
        $this->reset(['selectedDistrict', 'selectedSector', 'selectedCell', 'selectedVillage']);
        $this->reset(['sectors', 'cells', 'villages']); // Reset arrays too

        if (!empty($value) && isset($this->location[$value])) {
            // Populate the districts array based on the selected province
            $this->districts = array_keys($this->location[$value]);
            Log::info('Districts populated', ['district_count' => count($this->districts)]);
        } else {
            $this->districts = []; // Clear districts if province is empty or not found
            Log::info('Districts reset (province empty or not found)');
        }
    }

    // Method called automatically by Livewire when $selectedDistrict changes
    public function updatedSelectedDistrict($value)
    {
        Log::info('Updated Selected District', ['district' => $value, 'selectedProvince' => $this->selectedProvince]);
        // Reset subsequent levels when district changes
        $this->reset(['selectedSector', 'selectedCell', 'selectedVillage']);
        $this->reset(['cells', 'villages']); // Reset arrays too

        if (!empty($this->selectedProvince) && !empty($value) && isset($this->location[$this->selectedProvince][$value])) {
            // Populate the sectors array based on the selected province and district
            $this->sectors = array_keys($this->location[$this->selectedProvince][$value]);
            Log::info('Sectors populated', ['sector_count' => count($this->sectors)]);
        } else {
            $this->sectors = []; // Clear sectors if district is empty or not found
            Log::info('Sectors reset (district empty or not found)');
        }
    }

    // Method called automatically by Livewire when $selectedSector changes
    public function updatedSelectedSector($value)
    {
        Log::info('Updated Selected Sector', ['sector' => $value, 'selectedDistrict' => $this->selectedDistrict, 'selectedProvince' => $this->selectedProvince]);
        // Reset subsequent levels when sector changes
        $this->reset(['selectedCell', 'selectedVillage']);
        $this->reset(['villages']); // Reset array too

         if (!empty($this->selectedProvince) && !empty($this->selectedDistrict) && !empty($value) && isset($this->location[$this->selectedProvince][$this->selectedDistrict][$value])) {
             // Populate the cells array based on the selected province, district, and sector
             $this->cells = array_keys($this->location[$this->selectedProvince][$this->selectedDistrict][$value]);
             Log::info('Cells populated', ['cell_count' => count($this->cells)]);
         } else {
             $this->cells = []; // Clear cells if sector is empty or not found
             Log::info('Cells reset (sector empty or not found)');
         }
    }

    // Method called automatically by Livewire when $selectedCell changes
    public function updatedSelectedCell($value)
    {
        Log::info('Updated Selected Cell', ['cell' => $value, 'selectedSector' => $this->selectedSector, 'selectedDistrict' => $this->selectedDistrict, 'selectedProvince' => $this->selectedProvince]);
        // Reset subsequent levels when cell changes
        $this->reset(['selectedVillage']);

        if (!empty($this->selectedProvince) && !empty($this->selectedDistrict) && !empty($this->selectedSector) && !empty($value) && isset($this->location[$this->selectedProvince][$this->selectedDistrict][$this->selectedSector][$value])) {
            // Populate the villages array based on the selected province, district, sector, and cell
            // Assuming the village level is an array of strings (village names)
            $this->villages = $this->location[$this->selectedProvince][$this->selectedDistrict][$this->selectedSector][$value];
            Log::info('Villages populated', ['village_count' => count($this->villages)]);
        } else {
            $this->villages = []; // Clear villages if cell is empty or not found
            Log::info('Villages reset (cell empty or not found)');
        }
    }


    // Load existing dispute data for editing a draft
    public function loadDispute($id)
    {
        Log::info('Attempting to load dispute', ['dispute_id' => $id]);
        // Find the dispute and check if it's a draft owned by the current user
        $dispute = Dispute::where('id', $id)
                         ->where('citizen_id', auth()->id())
                         ->where('status', 'kirabitse')
                         ->first();


        if ($dispute) {
            Log::info('Dispute found and authorized for editing', ['dispute_id' => $id]);
            $this->isEditing = true;
            $this->dispute_id = $id;
            $this->title = $dispute->title;
            $this->offender = $dispute->offender_name;
            $this->offender_mail = $dispute->offender_mail;
            $this->content = $dispute->content;
            $this->witness = $dispute->witness_name;

            // Manually set selected location properties and trigger updates
            // to populate the dropdowns dynamically based on the loaded data
            $this->selectedProvince = $dispute->province;
            // Directly call the update methods with the loaded value
            $this->updatedSelectedProvince($dispute->province);

            $this->selectedDistrict = $dispute->district;
             // Check if district is now available before triggering update
             if (!empty($dispute->district) && in_array($dispute->district, $this->districts)) {
                  $this->updatedSelectedDistrict($dispute->district);
             } else {
                  Log::warning('Loaded dispute district not found in populated districts after province load', ['dispute_id' => $id, 'district' => $dispute->district, 'available_districts' => $this->districts]);
             }

            $this->selectedSector = $dispute->sector;
            // Check if sector is now available before triggering update
            if (!empty($dispute->sector) && in_array($dispute->sector, $this->sectors)) {
                 $this->updatedSelectedSector($dispute->sector);
            } else {
                 Log::warning('Loaded dispute sector not found in populated sectors after district load', ['dispute_id' => $id, 'sector' => $dispute->sector, 'available_sectors' => $this->sectors]);
            }

            $this->selectedCell = $dispute->cell;
            // Check if cell is now available before triggering update
            if (!empty($dispute->cell) && in_array($dispute->cell, $this->cells)) {
                 $this->updatedSelectedCell($dispute->cell);
            } else {
                 Log::warning('Loaded dispute cell not found in populated cells after sector load', ['dispute_id' => $id, 'cell' => $dispute->cell, 'available_cells' => $this->cells]);
            }

            // Set village directly after cells are populated
            $this->selectedVillage = $dispute->village;
            Log::info('Dispute location loaded', ['province' => $this->selectedProvince, 'district' => $this->selectedDistrict, 'sector' => $this->selectedSector, 'cell' => $this->selectedCell, 'village' => $this->selectedVillage]);

        } else {
            Log::warning('Dispute not found or not authorized for editing (not draft or wrong user)', ['dispute_id' => $id, 'auth_id' => auth()->id()]);
             // If dispute not found or not a draft for this user, ensure editing mode is off and form is reset
             $this->isEditing = false;
             $this->dispute_id = null;
             $this->resetForm();
             // Optionally redirect the user or show an error message that the dispute couldn't be loaded for editing.
             session()->flash('error', 'Ikirego ushaka guhindura ntikiboneka cyangwa ntago wemerewe kugihindura.');
        }
    }


    // Handles submitting the dispute


    

    public function save()
    {
        Log::info('Attempting to save dispute (send)');
        Log::info('Before validation', ['offender_mail' => $this->offender_mail]);
        try {
            // Validate all fields including the selected village
            $this->validate([
                 'title' => $this->getRules()['title'],
                 'offender' => $this->getRules()['offender'],
                 'offender_mail' => 'required|email',
                 'content' => $this->getRules()['content'],
                 'witness' => $this->getRules()['witness'],
                 'selectedProvince' => 'required', // Add validation for location levels
                 'selectedDistrict' => 'required',
                 'selectedSector' => 'required',
                 'selectedCell' => 'required',
                 'selectedVillage' => 'required',
            ], [
                 'selectedProvince.required' => 'Hitamo Intara',
                 'selectedDistrict.required' => 'Hitamo Akarere',
                 'selectedSector.required' => 'Hitamo Umurenge',
                 'selectedCell.required' => 'Hitamo Akagari',
                 'selectedVillage.required' => 'Hitamo Umudugudu',
            ]);


            $data = $this->prepareData('cyoherejwe');
            Log::info('Prepared data for saving (send)', ['data' => $data]);

            if ($this->isEditing && $this->dispute_id) {
                // Update existing draft
                $updated = Dispute::where('id', $this->dispute_id)
                    ->where('citizen_id', auth()->id())
                    ->where('status', 'kirabitse') // Ensure we only update drafts
                    ->update($data);

                if ($updated) {
                    Log::info('Dispute updated successfully', ['dispute_id' => $this->dispute_id]);
                } else {
                    Log::warning('Dispute update failed (might not exist, not owned, or status changed)', ['dispute_id' => $this->dispute_id]);
                     session()->flash('error', 'Kubika ntibyashobotse cyangwa ikirego ntikiboneka.');
                     return; // Stop execution if update failed
                }
            } else {
                 // Create new dispute
                 // Ensure no dispute_id is set if creating
                 $this->dispute_id = null;
                $dispute = Dispute::create($data);
                Log::info('New dispute created', ['dispute_id' => $dispute->id]);
                $this->dispute_id = $dispute->id; // Set ID after creation
                Log::info('Offender email', [$this->offender_mail]);


                // Notify via SMS
                try {
                    // $sent = $this->smsService->notifyDisputeCreated(
                    //     $this->offender_phone,
                    //     $this->offender,                        
                    //      $this->title, // Pass dispute ID if needed for SMS message
                    // );

                    // if (!$sent) {
                    //     Log::warning('SMS notification failed', ['phone' => $this->offender_phone]);
                    //     session()->flash('warning', 'Ikirego cyoherejwe ariko ubutumwa bwa SMS ntibwagezeyo.');
                    // } else {
                    //     Log::info('SMS notification sent', ['phone' => $this->offender_phone]);
                    // }
                    //Log::info($this->user->email);
                    //Log::info($this->user->name);
                    $this->emailService->notifyDisputeCreated(
                       [
                        'email' => $this->offender_mail,
                        'name' => $this->offender,
                       ],
                       [
                        'email' => auth()->user()->email,
                        'name' => auth()->user()->name, 
                       ],
                       $this->title                   
                );


                } catch (\Exception $mailException) {
                     Log::error('Email sending failed', ['exception' => $mailException->getMessage()]);
                     session()->flash('warning', 'Ikirego cyoherejwe ariko habaye ikibazo mu kohereza ubutumwa bwa Imeli.');
                }

            }

            $this->resetForm(); // Clear form after successful submission/update
            $this->dispatch('saved', ['message' => 'Ikirego cyanditswe neza.']);
            $this->close(); // Close the modal

        } catch (\Illuminate\Validation\ValidationException $e) {
             Log::warning('Validation failed during save', ['errors' => $e->errors()]);
             // Livewire automatically handles showing validation errors in the Blade
             session()->flash('error', 'Hari ibitagenda neza mu byo wujuje. Reba ahabujuje neza.');
        }
        catch (\Exception $e) {
            Log::error('Error saving dispute (send)', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Habaye ikibazo mu kwandika ikirego: ' . $e->getMessage());
        }
    }

    // Handles saving the dispute as a draft
    public function draft()
    {
        Log::info('Attempting to draft dispute');
        try {
             // Basic validation required for a draft, less strict than sending
             // You might make some fields optional for drafts
             $this->validate([
                 'title' => 'required|string|min:10|max:100', // Title is usually required even for draft
                 'selectedProvince' => 'required', // Location is probably required for a draft
                 'selectedDistrict' => 'required',
                 'selectedSector' => 'required',
                 'selectedCell' => 'required',
                 'selectedVillage' => 'required',
             ], [
                 'title.required' => 'Andika umutwe w\'ikirego',
                 'selectedProvince.required' => 'Hitamo Intara',
                 'selectedDistrict.required' => 'Hitamo Akarere',
                 'selectedSector.required' => 'Hitamo Umurenge',
                 'selectedCell.required' => 'Hitamo Akagari',
                 'selectedVillage.required' => 'Hitamo Umudugudu',
                 'title.min' => 'Umutwe w\'ikirego ugomba kuba ufite nibura inyuguti 10',
                 'title.max' => 'Umutwe w\'ikirego ugomba kuba ufite nibura inyuguti 100',
             ]);


            $data = $this->prepareData('kirabitse');
             // Ensure non-required fields are included if they have values, but don't fail validation if empty
             $data['offender_name'] = $this->offender;
             $data['offender_mail'] = $this->offender_mail;
             $data['content'] = $this->content;
             $data['witness_name'] = $this->witness;

             Log::info('Prepared data for drafting', ['data' => $data]);


            if ($this->isEditing && $this->dispute_id) {
                 // Update existing draft
                $updated = Dispute::where('id', $this->dispute_id)
                    ->where('citizen_id', auth()->id())
                    ->where('status', 'kirabitse') // Ensure we only update drafts
                    ->update($data);

                if ($updated) {
                    Log::info('Dispute draft updated successfully', ['dispute_id' => $this->dispute_id]);
                } else {
                     Log::warning('Dispute draft update failed (might not exist, not owned, or status changed)', ['dispute_id' => $this->dispute_id]);
                     session()->flash('error', 'Kubika ntibyashobotse cyangwa ikirego ntikiboneka.');
                     return; // Stop execution if update failed
                }
            } else {
                 // Create new draft
                 // Ensure no dispute_id is set if creating
                 $this->dispute_id = null;
                $dispute = Dispute::create($data);
                Log::info('New dispute drafted', ['dispute_id' => $dispute->id]);
                 $this->dispute_id = $dispute->id; // Set ID after creation
            }

            $this->resetForm(); // Clear form after saving draft
            $this->dispatch('saved', ['message' => 'Ikirego cyabitswe neza.']);
            $this->close(); // Close the modal

        } catch (\Illuminate\Validation\ValidationException $e) {
             Log::warning('Validation failed during draft', ['errors' => $e->errors()]);
             session()->flash('error', 'Hari ibitagenda neza mu byo wujuje. Reba ahabujuje neza.');
             // Do not reset the form or close modal on validation failure
        }
        catch (\Exception $e) {
            Log::error('Error saving draft', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Habaye ikibazo mu kubika ikirego: ' . $e->getMessage());
        }
    }

     // Handles deleting a draft
    public function delete()
    {
        Log::info('Attempting to delete dispute', ['dispute_id' => $this->dispute_id, 'isEditing' => $this->isEditing]);

        // Check if we are in editing mode and have a dispute ID
        if (!$this->isEditing || !$this->dispute_id) {
            Log::warning('Attempted delete on non-editing or missing dispute');
            session()->flash('error', 'Ntago wemerewe gusiba iki kirego.');
            return; // Cannot delete if not editing or no ID
        }

        try {
             // Find and delete the dispute, ensure it's a draft owned by the user
            $deleted = Dispute::where('id', $this->dispute_id)
                             ->where('citizen_id', auth()->id())
                             ->where('status', 'kirabitse') // Only allow deleting drafts
                             ->delete();

            if ($deleted) {
                Log::info('Dispute deleted successfully', ['dispute_id' => $this->dispute_id]);
                $this->resetForm(); // Clear form after deletion
                $this->dispatch('saved', ['message' => 'Ikirego cyawe cyafunzwe (cyasibwe).']);
                $this->close(); // Close the modal
            } else {
                 Log::warning('Dispute deletion failed (not found, not owned, or status not draft)', ['dispute_id' => $this->dispute_id]);
                 session()->flash('error', 'Gusiba ntibyashobotse cyangwa ntago wemerewe gusiba iki kirego.');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting dispute', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Habaye ikibazo mu gusiba ikirego: ' . $e->getMessage());
        }
    }


    // Helper to prepare data array for saving/drafting
    private function prepareData($status)
    {
        // Construct the full location path string
        $locationParts = array_filter([ // Use array_filter to remove empty parts if a lower level isn't selected (e.g., for drafts)
            $this->selectedProvince,
            $this->selectedDistrict,
            $this->selectedSector,
            $this->selectedCell,
            $this->selectedVillage
        ]);
        $locationPath = implode('; ', $locationParts);

        Log::info('Preparing data', ['status' => $status, 'location_path' => $locationPath]);

        $data = [
            'title' => $this->title,
            'offender_name' => $this->offender,
            'offender_mail' => $this->offender_mail,
            'content' => $this->content,
            'witness_name' => $this->witness,
            'location_name' => $locationPath, // Store the full path string
            'province' => $this->selectedProvince, // Store individual levels
            'district' => $this->selectedDistrict,
            'sector' => $this->selectedSector,
            'cell' => $this->selectedCell,
            'village' => $this->selectedVillage,
            'status' => $status,
            'citizen_id' => auth()->id(), // Associate dispute with the logged-in user
        ];

        return $data;
    }

    // Resets all form fields and state
    public function resetForm()
    {
        Log::info('Resetting form');
        $this->reset([
            'title', 'offender', 'offender_mail', 'content', 'witness',
            'selectedProvince', 'selectedDistrict', 'selectedSector',
            'selectedCell', 'selectedVillage',
             // Reset location arrays to hide dependent dropdowns
             'districts', 'sectors', 'cells', 'villages',
            'status', 'location_name', 'dispute_id', 'isEditing'
        ]);
        // No need to reloadLocations here, mount does it initially.
        // Resetting the arrays is enough to hide the dropdowns.
    }

    // Closes the modal/component view
    public function close()
    {
        Log::info('Closing component');
        $this->isClosed = true;
        $this->resetForm(); // Reset form state before closing
        // Dispatch event to parent component or JavaScript to close the modal
        $this->dispatch("closeModal"); // Use a specific event name, e.g., "closeModal" or "disputeModalClosed"
    }

     // Custom method to define validation rules, useful for dynamic validation calls
     public function getRules()
     {
         return [
             'title' => 'required|string|min:10|max:100',
             'offender' => 'required|string',
             'offender_mail' => 'required|email',
             'content' => 'required|string|min:20|max:500',
             'witness' => 'required|string', // Keep required based on attribute, or change if optional
             // Location fields are validated directly in save/draft methods
         ];
     }


    // Render method loads the view and passes necessary data
    public function render()
    {
        // Log::debug('Rendering Ikirego Component', ['provinces_count' => count($this->provinces), 'districts_count' => count($this->districts), 'sectors_count' => count($this->sectors), 'cells_count' => count($this->cells), 'villages_count' => count($this->villages)]); // Can be very verbose

        return view('livewire.ikirego', [
            'provinces' => $this->provinces,
            'districts' => $this->districts,
            'sectors' => $this->sectors,
            'cells' => $this->cells,
            'villages' => $this->villages,
        ]);
    }
}