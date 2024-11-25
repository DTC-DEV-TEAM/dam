<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailErfClose extends Mailable
{
    use Queueable, SerializesModels;
    public $infos;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($infos)
    {
        $this->infos = $infos;
        $this->subject = $infos['subject'];
        $this->ref = $infos['reference_number'];
        $this->email = $infos['email'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Notification of status of '.$this->ref)
        ->view('emails.send-email-erf-close');
    }
}
