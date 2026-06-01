<?php

namespace App\Notifications;

use App\Models\RisHeader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RisStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private RisHeader $ris, private string $status)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("RIS {$this->ris->ris_no} {$this->status}")
            ->line("RIS {$this->ris->ris_no} is now {$this->status}.");
    }

    public function toArray(object $notifiable): array
    {
        return ['ris_id' => $this->ris->id, 'ris_no' => $this->ris->ris_no, 'status' => $this->status];
    }
}
