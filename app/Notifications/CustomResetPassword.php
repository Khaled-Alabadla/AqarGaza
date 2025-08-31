<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class CustomResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $token;

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
    public function toMail($notifiable)
    {
        // Set locale based on user preference or default to Arabic
        App::setLocale($notifiable->language ?? 'ar');

        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(__('إعادة تعيين كلمة المرور'))
            ->view('emails.reset-password', ['actionUrl' => $url]);
        // ->text('emails.reset-password-text', ['actionUrl' => $url]);
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
