<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Buat instance baru.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Bangun pesan email.
     */
    public function build()
    {
        return $this->subject('Welcome to CourtPlay')
                    ->from('support@courtplay.my.id', 'CourtPlay Team')
                    ->view('emails.welcome')
                    ->with([
                        'user' => $this->user,
                    ])
                    ->withSymfonyMessage(function ($message) {
                        // Tambahkan custom header kategori
                        $headers = $message->getHeaders();
                        $headers->addTextHeader('X-Entity-Ref-ID', 'courtplay-user-welcome');
                        $headers->addTextHeader('Category', 'user_welcome');
                        $headers->addTextHeader('X-Mailtrap-Categories', 'user_welcome');
                    });
    }
}

