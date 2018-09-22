<html>
<head>
<style>
div , p,  body , span {
	font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
	font-size: 14px;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 20px;

}

span {
	color:#07148E;
	font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
	font-size: 14px;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 20px;
}
</style>
</head>
<body>
<div>
<br>

@if( ! $justReviewers )

Καταχωρήθηκε εκ μέρους σας το qticket <a href="{{ $url . '/' . $ticket->refid }}"> {{ $ticket->refid }}</a> από <a href="mailto:{{$ticket->requestedBy->email}}?subject=Ticket {{ $ticket->refid }} : {{ $ticket->title  }}"><u>{{ $ticket->requestedBy->firstlastname }}</u></a>. 

@else

Καταχωρήθηκε το qticket <a href="{{ $url . '/' . $ticket->refid }}"> {{ $ticket->refid }}</a> από <a href="mailto:{{$ticket->requestedBy->email}}?subject=Ticket {{ $ticket->refid }} : {{ $ticket->title  }}"><u>{{ $ticket->requestedBy->firstlastname }}</u></a>. 

@endif

</div>

<p>
Θέμα: <span> {{ $ticket->title }} </span>
</p>

<div>Περιγραφή:</div>
<p><span>{!! nl2br($ticket->description) !!}</span>
</p>
<br>
<p><span>{!! nl2br($update->update) !!}</span>
<p>
Μπορείτε να παρακολουθείτε την εξέλιξη του, να προσθέσετε σχόλια ή και να καταχωρήσετε νέο <b><a href="{{ $url }}" target="_blank">qticket</a></b> μέσω της ιστοσελίδας : <u><a href="{{ $url . '/' . $ticket->refid }}" target="_blank"> {!! $url !!}  </a> </u>
<p>
Αυτό είναι ένα αυτοματοποιημένο μήνυμα. Μην απαντάτε σε αυτό το e-mail.
</p>
<p>
Με εκτίμηση,
<br>
PQH Technology Helpdesk <br>
email: <a href="mailto:{{ $helpdeskEmail }}?subject=Ticket {{ $ticket->refid }} : {{ $ticket->title  }}" > {{ $helpdeskEmail }}</a>



</p>

</body>
</html>