<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use App\Models\TeamInvitation;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Contracts\AcceptsTeamInvitations;

class AcceptTeamInvitation 
{
    
    public function accept(User $user, string $invitationId): void
    {
        $invitation = TeamInvitation::findOrFail($invitationId);
    
        $invitation->team->users()->attach($user, ['role' => $invitation->role]);
    
        $user->update(['current_team_id' => $invitation->team->id]);
    
        $invitation->delete();
    }
}
