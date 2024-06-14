<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $object;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($object)
    {
        $this->object=$object;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('po@wegcambodia.com')->markdown('notification');
    }
}
