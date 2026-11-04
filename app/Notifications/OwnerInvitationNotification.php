<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerInvitationNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $expiresIn = config(
            'auth.passwords.'.config('auth.defaults.passwords').'.expire',
            60,
        );
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
        $firstName = $notifiable->first_name ?: str($notifiable->name)->before(' ')->toString();

        return (new MailMessage)
            ->subject('Tu invitación para administrar un complejo en FaltaUno')
            ->view('emails.owner-invitation', [
                'firstName' => $firstName,
                'url' => $url,
                'expiresIn' => $expiresIn,
            ])
            ->text('emails.owner-invitation-text', [
                'firstName' => $firstName,
                'url' => $url,
                'expiresIn' => $expiresIn,
            ]);
    }
}
