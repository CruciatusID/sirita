<?php

namespace App\Filament\Support;

use App\Models\User;

class AdminAccess
{
    public const CONTENT_MANAGERS = ['Super Admin', 'Admin Humas'];

    public const EDITORIAL = ['Super Admin', 'Admin Humas', 'Editor'];

    public const CONTRIBUTORS = ['Super Admin', 'Admin Humas', 'Editor', 'Kontributor'];

    public static function hasAnyRole(array $roles): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasAnyRole($roles);
    }
}
