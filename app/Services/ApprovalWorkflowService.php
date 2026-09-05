<?php

namespace App\Services;

use App\Domain\Content\Models\ContentApprovalLog;
use App\Models\User;
use App\Notifications\ContentWorkflowNotification;
use Filament\Notifications\Notification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ApprovalWorkflowService
{
    /**
     * Submit content for approval (from draft or revision_required to pending_approval).
     */
    public function submitForApproval(Model $model, User $submitter, string $contentType): bool
    {
        return DB::transaction(function () use ($model, $submitter, $contentType) {
            $model->update([
                'approval_status' => 'pending_approval',
                'rejection_reason' => null,
            ]);

            ContentApprovalLog::create([
                'content_type' => $contentType,
                'content_id' => $model->id,
                'submitted_by' => $submitter->id,
                'reviewed_by' => null,
                'action_status' => 'submitted',
                'review_notes' => 'Konten diajukan untuk ditinjau oleh Kabupaten.',
                'action_timestamp' => now(),
            ]);

            // Notify Kabupaten verifiers/superadmins
            $this->notifyCountyReviewers($model, $contentType, $submitter);

            return true;
        });
    }

    /**
     * Approve content and optionally publish it to the public website.
     */
    public function approveContent(Model $model, User $reviewer, string $contentType, ?string $notes = null): bool
    {
        $this->guardCanReview($reviewer);

        return DB::transaction(function () use ($model, $reviewer, $contentType, $notes) {
            $updateData = [
                'approval_status' => 'approved',
                'approved_by' => $reviewer->id,
                'approved_at' => now(),
                'rejection_reason' => null,
            ];

            // If model has is_published column, set it to true
            if (array_key_exists('is_published', $model->getAttributes()) || in_array('is_published', $model->getFillable())) {
                $updateData['is_published'] = true;
                if (array_key_exists('published_at', $model->getAttributes()) || in_array('published_at', $model->getFillable())) {
                    $updateData['published_at'] = now();
                }
            }

            $model->update($updateData);

            ContentApprovalLog::create([
                'content_type' => $contentType,
                'content_id' => $model->id,
                'submitted_by' => $model->user_id ?? $model->submitted_by ?? $reviewer->id,
                'reviewed_by' => $reviewer->id,
                'action_status' => 'approved',
                'review_notes' => $notes ?? 'Disetujui dan dipublikasikan.',
                'action_timestamp' => now(),
            ]);

            $this->notifyContentCreator($model, 'Konten Anda Disetujui', 'Konten telah disetujui oleh Pengurus Kabupaten dan kini aktif di website publik.', 'success', 'approved', $notes);

            return true;
        });
    }

    /**
     * Request revision with mandatory feedback notes.
     */
    public function requestRevision(Model $model, User $reviewer, string $contentType, string $revisionNotes): bool
    {
        $this->guardCanReview($reviewer);

        if (blank(trim($revisionNotes))) {
            throw new InvalidArgumentException('Catatan instruksi revisi wajib diisi.');
        }

        return DB::transaction(function () use ($model, $reviewer, $contentType, $revisionNotes) {
            $model->update([
                'approval_status' => 'revision_required',
                'rejection_reason' => $revisionNotes,
            ]);

            ContentApprovalLog::create([
                'content_type' => $contentType,
                'content_id' => $model->id,
                'submitted_by' => $model->user_id ?? $model->submitted_by ?? $reviewer->id,
                'reviewed_by' => $reviewer->id,
                'action_status' => 'revision_required',
                'review_notes' => $revisionNotes,
                'action_timestamp' => now(),
            ]);

            $this->notifyContentCreator($model, 'Permintaan Revisi Konten', 'Catatan: '.$revisionNotes, 'warning', 'revision_required', $revisionNotes);

            return true;
        });
    }

    /**
     * Reject content with mandatory reason.
     */
    public function rejectContent(Model $model, User $reviewer, string $contentType, string $rejectionReason): bool
    {
        $this->guardCanReview($reviewer);

        if (blank(trim($rejectionReason))) {
            throw new InvalidArgumentException('Alasan penolakan konten wajib diisi.');
        }

        return DB::transaction(function () use ($model, $reviewer, $contentType, $rejectionReason) {
            $model->update([
                'approval_status' => 'rejected',
                'rejection_reason' => $rejectionReason,
            ]);

            ContentApprovalLog::create([
                'content_type' => $contentType,
                'content_id' => $model->id,
                'submitted_by' => $model->user_id ?? $model->submitted_by ?? $reviewer->id,
                'reviewed_by' => $reviewer->id,
                'action_status' => 'rejected',
                'review_notes' => $rejectionReason,
                'action_timestamp' => now(),
            ]);

            $this->notifyContentCreator($model, 'Konten Ditolak', 'Alasan penolakan: '.$rejectionReason, 'danger', 'rejected', $rejectionReason);

            return true;
        });
    }

    protected function guardCanReview(User $user): void
    {
        if (! $user->isSuperadmin() && ! $user->isVerifikator()) {
            throw new AuthorizationException('Hanya Superadmin atau Verifikator Kabupaten yang berhak melakukan tindakan ini.');
        }
    }

    protected function notifyCountyReviewers(Model $model, string $contentType, User $submitter): void
    {
        $countyUsers = User::whereHas('role', function ($q) {
            $q->whereIn('slug', ['superadmin', 'verifikator']);
        })->get();

        $title = 'Pengajuan Konten Baru';
        $body = "Terdapat pengajuan {$contentType} baru dari {$submitter->name} (".($submitter->unit?->unit_name ?? 'Unit').').';

        foreach ($countyUsers as $reviewer) {
            // 1. Filament in-app real-time notification
            Notification::make()
                ->title($title)
                ->body($body)
                ->icon('heroicon-o-clock')
                ->iconColor('warning')
                ->sendToDatabase($reviewer);

            // 2. Queued Laravel notification
            $reviewer->notify(new ContentWorkflowNotification(
                title: $title,
                message: $body,
                actionType: 'submitted',
                contentType: $contentType,
            ));
        }
    }

    protected function notifyContentCreator(Model $model, string $title, string $message, string $color, string $actionType = 'approved', ?string $notes = null): void
    {
        $creator = null;
        if (isset($model->user_id)) {
            $creator = User::find($model->user_id);
        } elseif (isset($model->submitted_by)) {
            $creator = User::find($model->submitted_by);
        }

        if ($creator) {
            // 1. Filament in-app database notification
            Notification::make()
                ->title($title)
                ->body($message)
                ->color($color)
                ->sendToDatabase($creator);

            // 2. Queued Laravel notification
            $creator->notify(new ContentWorkflowNotification(
                title: $title,
                message: $message,
                actionType: $actionType,
                notes: $notes,
            ));
        }
    }
}
