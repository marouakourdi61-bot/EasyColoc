<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invitation;

class InvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invitation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; 
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invitation à rejoindre une colocation')
            ->greeting('Bonjour !')
            ->line("Vous avez été invité(e) à rejoindre la colocation : {$this->invitation->colocation->name}")
            ->action('Rejoindre la colocation', route('colocation.join', $this->invitation->token))
            ->line('Merci et à bientôt !');

    }

    /**
     
     * Get the array representation of the notification (optional).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'colocation_id' => $this->invitation->colocation_id,
            'token' => $this->invitation->token,
            'message' => 'Vous avez reçu une invitation à rejoindre une colocation.',
        ];
    }
}