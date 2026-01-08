<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AthleteNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $messageContent;

    /**
     * Create a new message instance.
     */
    public function __construct($title, $messageContent)
    {
        $this->title = $title;
        $this->messageContent = $messageContent;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->title)
                    ->view('emails.athlete_notification')
                    ->with([
                        'title' => $this->title,
                        'messageContent' => $this->messageContent,
                    ]);
    }
}
