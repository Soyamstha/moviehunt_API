<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
       $resetUrl = url("api/reset-password?token={$this->token}&email={$notifiable->email}");

        return (new MailMessage)
            ->subject('Reset Your Password - MovieHunt')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We received a request to reset your password for your MovieHunt account.')
            ->action('Reset Password', $resetUrl)
            ->line('If you did not request a password reset, please ignore this email.')
            ->salutation('Regards, MovieHunt Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
