<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


use App\Ticket;
use App\TicketUpdate;

class TicketClosed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public $ticket;
    public $url;
    public $user;
    public $comment;
    public $update;
    public $prefix;
    public $helpdeskEmail;


    /**
     * Create a new message instance.
     *
     * @return void
     */
   public function __construct( $t,$u,$c,$upd,$pref)
    {

        $this->ticket=$t;
        $this->user=$u;
        $this->comment=$c;
        $this->update=$upd;
        $this->prefix=$pref;
        $this->helpdeskEmail=config('qticket.helpdeskEmail');
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
        return $this->subject($this->prefix . 'Ticket ' . $this->ticket->refid . ' -  ' . $this->ticket->title )->view('emails.ticketClosed')->onQueue('emails');
    }
}
