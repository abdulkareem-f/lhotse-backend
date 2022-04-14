<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequisitionSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public array $requisitionData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($requisitionData)
    {
        $this->requisitionData = $requisitionData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $requisitionAttachName = str_replace(' ', '_', $this->requisitionData['requisition']['name']);
        return $this->view('emails.requisition_submitted')
                    ->with(['requisition' => $this->requisitionData['requisition']])
                    ->attach($this->requisitionData['attach_file'], [
                        'as'    => "{$requisitionAttachName}.pdf",
                        'mime'  => 'application/pdf',
                    ]);
    }
}
