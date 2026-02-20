<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordQueued extends ResetPasswordNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(#[\SensitiveParameter] $token)
    {
        parent::__construct($token);

        // Use the 'default' queue
        $this->queue = 'default';
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('auth.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password - '.config('app.name'))
            ->view('emails.reset-password', [
                'userName' => $notifiable->name,
                'userEmail' => $notifiable->email,
                'resetUrl' => $resetUrl,
                'subject' => 'Reset Password',
            ]);
    }
}
