<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public ?string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(?string $password = null)
    {
        $this->password = $password;
        $this->onQueue('default');
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'auth.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Alamat Email - '.config('app.name'))
            ->view('emails.verify-email', [
                'userName' => $notifiable->name,
                'userEmail' => $notifiable->email,
                'password' => $this->password,
                'verificationUrl' => $verificationUrl,
                'subject' => 'Verifikasi Alamat Email',
                'title' => 'Verifikasi Alamat Email Anda',
                'emailContent' => 'Terima kasih telah mendaftar. Silakan klik tombol di bawah untuk memverifikasi alamat email Anda.',
            ]);
    }
}
