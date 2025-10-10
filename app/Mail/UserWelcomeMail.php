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
        return $this->subject('Welcome to CourtPlay 🎾')
                    ->from('support@courtplay.my.id', 'CourtPlay Team')
                    ->view('emails.welcome');
    }
}
