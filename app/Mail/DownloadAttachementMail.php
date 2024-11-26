<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DownloadAttachementMail extends Mailable {

    use Queueable,
        SerializesModels;

    public $csvfile;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($csvfile, $subject, $body) {
        $this->csvfile = $csvfile;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->from('hiteshv253@gmail.com')
                        ->view('emails.attachments')
                        ->with(['body' => $this->body])
                        ->subject($this->subject)
                        ->attachData(base64_decode($this->csvfile), 'sample.csv');
    }
}
