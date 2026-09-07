<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DocumentChangeRequest;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DocumentChangeRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DocumentChangeRequest');
    }

    public function view(AuthUser $authUser, DocumentChangeRequest $documentChangeRequest): bool
    {
        return $authUser->can('View:DocumentChangeRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DocumentChangeRequest');
    }

    public function update(AuthUser $authUser, DocumentChangeRequest $documentChangeRequest): bool
    {
        return $authUser->can('Update:DocumentChangeRequest');
    }

    public function delete(AuthUser $authUser, DocumentChangeRequest $documentChangeRequest): bool
    {
        return $authUser->can('Delete:DocumentChangeRequest');
    }

    public function restore(AuthUser $authUser, DocumentChangeRequest $documentChangeRequest): bool
    {
        return $authUser->can('Restore:DocumentChangeRequest');
    }

    public function forceDelete(AuthUser $authUser, DocumentChangeRequest $documentChangeRequest): bool
    {
        return $authUser->can('ForceDelete:DocumentChangeRequest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DocumentChangeRequest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DocumentChangeRequest');
    }

    public function replicate(AuthUser $authUser, DocumentChangeRequest $documentChangeRequest): bool
    {
        return $authUser->can('Replicate:DocumentChangeRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DocumentChangeRequest');
    }
}
