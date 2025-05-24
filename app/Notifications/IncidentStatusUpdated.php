<?php
namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class IncidentStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $incident;

    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }

    public function via($notifiable)
    {
        return ['database']; // Or add 'mail' if you want email too
    }

    public function broadcastOn()
    {
        return new PrivateChannel('users.' . $this->incident->created_by);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Status for your incident "' . $this->incident->title . '" has been updated to ' . $this->incident->status,
            'incident_id' => $this->incident->id,
        ];
    }
}
