<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ContentWorkflowNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public string $actionType, // 'submitted', 'approved', 'revision_required', 'rejected'
        public ?string $contentType = null,
        public ?string $notes = null,
        public ?string $url = null
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'action_type' => $this->actionType,
            'content_type' => $this->contentType,
            'notes' => $this->notes,
            'url' => $this->url,
            'sent_at' => now()->toIso8601String(),
        ];
    }
}
