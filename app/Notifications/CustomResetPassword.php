<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', ['token' => $this->token, 'email' => $this->email], false));

        return (new MailMessage)
            ->subject('Permintaan Reset Password Akun Anda') // 🔴 Judul diubah di sini
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mereset password akun Anda.')
            ->action('Reset Password Sekarang', $resetUrl)
            ->line('Jika Anda tidak meminta ini, abaikan saja email ini.')
            ->salutation('Salam hangat, Admin');
    }
}
