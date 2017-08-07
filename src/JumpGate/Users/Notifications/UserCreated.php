<?php

namespace JumpGate\Users\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreated extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('auth.password.confirm', [$notifiable->getPasswordResetToken()->token]);

        return (new MailMessage)
            ->subject('Welcome to '. config('app.name') .'!')
            ->line('An account was created for you at '. config('app.name'))
            ->action('Set your password', $url)
            ->line('If you did not request an account, no further action is required.');
    }
}
