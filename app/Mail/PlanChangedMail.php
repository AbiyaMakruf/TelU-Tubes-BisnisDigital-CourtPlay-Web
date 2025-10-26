<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $oldPlan;
    public $newPlan;
    public $dashboardUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $oldPlan, $newPlan)
    {
        $this->user = $user;
        $this->oldPlan = $oldPlan ?? 'Free';
        $this->newPlan = $newPlan;
        $this->dashboardUrl = route('dashboard');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Your CourtPlay Plan has been updated to " . ucfirst($this->newPlan))
                    ->view('emails.plan-changed');
    }
}
