<?php

namespace App\Policies;

use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;

class KarangTarunaUnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, KarangTarunaUnit $unit): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->isSuperadmin() || $user->isVerifikator()) {
            return true;
        }

        $userUnit = $user->unit;
        if (! $userUnit) {
            return false;
        }

        if ($user->isAdminKecamatan()) {
            return $unit->id === $userUnit->id || $unit->district_id === $userUnit->district_id;
        }

        if ($user->isAdminDesa()) {
            return $unit->id === $userUnit->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->is_active && ($user->isSuperadmin() || $user->isAdminKecamatan());
    }

    public function update(User $user, KarangTarunaUnit $unit): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->isSuperadmin()) {
            return true;
        }

        $userUnit = $user->unit;
        if (! $userUnit) {
            return false;
        }

        if ($user->isAdminKecamatan()) {
            return $unit->id === $userUnit->id || ($unit->district_id === $userUnit->district_id && $unit->unit_level === 'desa');
        }

        if ($user->isAdminDesa()) {
            return $unit->id === $userUnit->id;
        }

        return false;
    }

    public function delete(User $user, KarangTarunaUnit $unit): bool
    {
        return $user->is_active && $user->isSuperadmin();
    }
}
