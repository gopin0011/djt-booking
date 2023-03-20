<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarBooking extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $url;
    public function __construct($data, $url)
    {
        $this->data = $data;
        $this->url = $url;
        $this->subject("Booking System");
    }

    public function build()
    {
        return $this->markdown('pages.notify.car');
    }
}
