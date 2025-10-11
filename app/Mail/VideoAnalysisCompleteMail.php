<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project; // Menggunakan model Project sesuai tabel Anda

class VideoAnalysisCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $user;
    public $reportUrl;

    /**
     * Buat instance baru.
     * Mengambil model Project yang sudah berelasi dengan User dan ProjectDetail.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
        // Asumsikan relasi user sudah didefinisikan pada model Project
        $this->user = $project->user;
        // Buat URL untuk melihat hasil analisis
        // Menggunakan id dari tabel projects
        $this->reportUrl = url('/analysis/' . $project->id);
    }

    /**
     * Bangun pesan email.
     */
    public function build()
    {
        // Pastikan Anda telah mendefinisikan relasi projectDetails() di model Project
        $projectDetails = $this->project->projectDetails;

        return $this->subject('Your Analysis is Ready! | CourtPlay')
                    ->from('support@courtplay.my.id', 'CourtPlay Team')
                    ->view('emails.video-analysis-complete') // Nama view blade
                    ->with([
                        'project' => $this->project,
                        'user' => $this->user,
                        'reportUrl' => $this->reportUrl,
                        'details' => $projectDetails, // Mengirim detail analisis ke view
                    ])
                    ->withSymfonyMessage(function ($message) {
                        // Tambahkan custom header kategori untuk pelacakan
                        $headers = $message->getHeaders();
                        $headers->addTextHeader('X-Entity-Ref-ID', 'courtplay-analysis-complete');
                        $headers->addTextHeader('Category', 'analysis_complete');
                        $headers->addTextHeader('X-Mailtrap-Categories', 'analysis_complete');
                    });
    }
}
