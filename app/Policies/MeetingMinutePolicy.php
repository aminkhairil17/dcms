<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MeetingMinute;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingMinutePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MeetingMinute');
    }

    public function view(AuthUser $authUser, MeetingMinute $meetingMinute): bool
    {
        return $authUser->can('View:MeetingMinute');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MeetingMinute');
    }

    public function update(AuthUser $authUser, MeetingMinute $meetingMinute): bool
    {
        return $authUser->can('Update:MeetingMinute');
    }

    public function delete(AuthUser $authUser, MeetingMinute $meetingMinute): bool
    {
        return $authUser->can('Delete:MeetingMinute');
    }

    public function restore(AuthUser $authUser, MeetingMinute $meetingMinute): bool
    {
        return $authUser->can('Restore:MeetingMinute');
    }

    public function forceDelete(AuthUser $authUser, MeetingMinute $meetingMinute): bool
    {
        return $authUser->can('ForceDelete:MeetingMinute');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MeetingMinute');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MeetingMinute');
    }

    public function replicate(AuthUser $authUser, MeetingMinute $meetingMinute): bool
    {
        return $authUser->can('Replicate:MeetingMinute');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MeetingMinute');
    }

}