<?php

namespace App\Policies;

use App\Domain\PPKS\Models\PpksBeneficiary;
use App\Models\User;

class PpksBeneficiaryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, PpksBeneficiary $beneficiary): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->isSuperadmin() || $user->isVerifikator()) {
            return true;
        }

        $unit = $user->unit;
        if (! $unit) {
            return false;
        }

        if ($user->isAdminKecamatan()) {
            return $beneficiary->district_id === $unit->district_id;
        }

        if ($user->isAdminDesa()) {
            return $beneficiary->unit_id === $unit->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->is_active && ($user->isSuperadmin() || $user->isAdminDesa() || $user->isAdminKecamatan());
    }

    public function update(User $user, PpksBeneficiary $beneficiary): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->isSuperadmin()) {
            return true;
        }

        // Data yang sudah verified hanya dapat diedit oleh superadmin
        if ($beneficiary->verification_status === 'verified') {
            return false;
        }

        $unit = $user->unit;
        if (! $unit) {
            return false;
        }

        if ($user->isAdminDesa()) {
            return $beneficiary->unit_id === $unit->id;
        }

        return false;
    }

    public function delete(User $user, PpksBeneficiary $beneficiary): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->isSuperadmin()) {
            return true;
        }

        $unit = $user->unit;
        if (! $unit) {
            return false;
        }

        if ($user->isAdminDesa() && $beneficiary->verification_status === 'pending_verification') {
            return $beneficiary->unit_id === $unit->id;
        }

        return false;
    }

    public function verify(User $user, PpksBeneficiary $beneficiary): bool
    {
        return $user->is_active && ($user->isSuperadmin() || $user->isVerifikator());
    }
}
