<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasTenantScope
{
    /**
     * Scope query to the current authenticated user's tenant/unit hierarchy.
     */
    public function scopeForUser(Builder $query, ?User $user = null): Builder
    {
        $user = $user ?? auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        // Superadmin & Verifikator Kabupaten: Akses data se-kabupaten
        if ($user->isSuperadmin() || $user->isVerifikator()) {
            return $query;
        }

        $unit = $user->unit;
        if (! $unit) {
            return $query->whereRaw('1 = 0');
        }

        // Admin Kecamatan: Akses data unit kecamatan dan unit desa di kecamatannya
        if ($user->isAdminKecamatan()) {
            if ($unit->district_id) {
                return $query->where(function (Builder $q) use ($unit) {
                    $q->where('district_id', $unit->district_id)
                        ->orWhere('unit_id', $unit->id)
                        ->orWhereHas('unit', function (Builder $qu) use ($unit) {
                            $qu->where('district_id', $unit->district_id);
                        });
                });
            }

            return $query->where('unit_id', $unit->id);
        }

        // Admin Desa: Hanya akses data unit desa miliknya
        if ($user->isAdminDesa()) {
            return $query->where('unit_id', $unit->id);
        }

        // Default: filter by user creator
        return $query->where('user_id', $user->id);
    }
}
