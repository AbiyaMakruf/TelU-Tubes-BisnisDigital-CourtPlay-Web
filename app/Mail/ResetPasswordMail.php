<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;

    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->resetUrl = url('/reset-password/' . $token);
    }

    public function build()
    {
        return $this->subject('Reset Your Password | CourtPlay')
                    ->from('support@courtplay.my.id', 'CourtPlay Team')
                    ->view('emails.reset-password')
                    ->with([
                        'user' => $this->user,
                        'resetUrl' => $this->resetUrl,
                    ]);
    }
}
