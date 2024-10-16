<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCodeResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $code;

    // Constructor that takes the $code as a parameter
    public function __construct($code)
    {
        $this->code = $code;
    }

    // Build the email
    public function build()
    {
        return $this->markdown('emails.reset-code')  // Reference the new email view
        ->with(['code' => $this->code]);
    }
}
