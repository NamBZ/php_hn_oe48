<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportOrder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $report;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($report)
    {
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::SUNDAY)->toCookieString();
        $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::SATURDAY)->toCookieString();
        
        return $this->markdown('emails.reportOrder', [
            'reports' => $this->report,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }
}
