<?php

namespace App\Mail;

use App\Models\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class AllMails extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {

        Log::info($this->details);

        if($this->details['front_end_id'] == 1){
            return new Envelope(
                subject:  $this->details['subject'],
                from: new Address('info@ecutech.gr', 'ECU Tech')
            );
        }
        else{
            return new Envelope(
                subject:  $this->details['subject'],
                from: new Address('info@tuning-x.com', 'Tuning-X | Performance Excellence')
            );
        }
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'files.email',
            with: [
                'html' => $this->details['html'],
                
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
