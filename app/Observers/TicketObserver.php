<?php

namespace App\Observers;

use Carbon\Carbon;
use Auth;
use App\User;
use App\Ticket;

class TicketObserver
{
    /**
     * Listen to the Ticket created event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function created(Ticket $ticket)
    {
        //
    }

    /**
     * Listen to the Ticket saving event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function saving(Ticket $ticket)
    {
       $now=Carbon::now();
       if (Auth::user()) {
        	$ticket->lastUpdatedBy_uid=Auth::user()->uid;
        	if (Auth::user()->isResolver && (Auth::user()->uid != $ticket->onBehalfOf_uid) &&  ( ! Auth::user()->isReviewer) ) {
            	$ticket->lastUpdatedByResolver_uid=Auth::user()->uid;
            	$ticket->lastUpdatedByResolver_at=$now;
        	}
       }
    }
}