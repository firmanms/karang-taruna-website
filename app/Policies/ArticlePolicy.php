<?php

namespace App\Policies;

use App\Domain\Content\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, Article $article): bool
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
            return $article->district_id === $unit->district_id || $article->unit_id === $unit->id || $article->unit?->district_id === $unit->district_id;
        }

        if ($user->isAdminDesa()) {
            return $article->unit_id === $unit->id;
        }

        return $article->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, Article $article): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->isSuperadmin()) {
            return true;
        }

        // Verifikator tidak mengedit isi konten desa/kecamatan langsung
        if ($user->isVerifikator()) {
            return false;
        }

        $unit = $user->unit;
        if (! $unit) {
            return false;
        }

        if ($user->isAdminKecamatan()) {
            return $article->unit_id === $unit->id || ($article->district_id === $unit->district_id && $article->unit?->unit_level === 'desa');
        }

        if ($user->isAdminDesa()) {
            return $article->unit_id === $unit->id;
        }

        return $article->user_id === $user->id;
    }

    public function delete(User $user, Article $article): bool
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

        if ($user->isAdminKecamatan()) {
            return $article->unit_id === $unit->id;
        }

        if ($user->isAdminDesa()) {
            return $article->unit_id === $unit->id;
        }

        return false;
    }

    public function approve(User $user, Article $article): bool
    {
        return $user->is_active && ($user->isSuperadmin() || $user->isVerifikator());
    }
}
