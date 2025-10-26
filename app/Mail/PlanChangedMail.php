<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

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
    public function __construct(User $user, $oldPlan, $newPlan)
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
        return $this->subject('Your CourtPlay Plan has been updated to ' . ucfirst($this->newPlan))
                    ->from('support@courtplay.my.id', 'CourtPlay Team')
                    ->view('emails.plan-changed')
                    ->with([
                        'user' => $this->user,
                        'oldPlan' => $this->oldPlan,
                        'newPlan' => $this->newPlan,
                        'dashboardUrl' => $this->dashboardUrl,
                    ])
                    ->withSymfonyMessage(function ($message) {
                        $headers = $message->getHeaders();
                        $headers->addTextHeader('X-Entity-Ref-ID', 'courtplay-plan-update');
                        $headers->addTextHeader('X-MT-Category', 'plan_changed');
                        $headers->addTextHeader('X-Mailtrap-Categories', 'plan_changed');
                    });
    }
}
