<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


use App\Ticket;
use App\TicketUpdate;

class TicketOpened extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $ticket;
    public $url;
    public $justReviewers;
    public $helpdeskEmail;
    public $update;

    public function __construct( $t , $JR, $upd)
    {

        $this->ticket=$t;
        $this->justReviewers=$JR;
        $this->update=$upd;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->url=url('/');
        $this->helpdeskEmail=config('qticket.helpdeskEmail');
        if ($this->justReviewers) {
            return $this->subject('New Ticket ' . $this->ticket->refid . ' : ' . $this->ticket->title )->view('emails.ticketOpened')->onQueue('emails');
        } else {            
            return $this->subject('New Ticket ' . $this->ticket->refid . ' : ' . $this->ticket->title )->view('emails.ticketOpened')->onQueue('emails');
        }
    }
}
