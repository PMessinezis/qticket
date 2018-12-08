<html>
<head>
<style>
div , p,  body , span {
	font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
	font-size: 16px;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 20px;

}

span {
	color:#07148E;
	font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
	font-size: 16px;
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
 Έγινε ενημέρωση του qticket <a href="{{ $url . '/' . $ticket->refid }}"> {{ $ticket->refid }}</a> από <a href="mailto:{{ $user->email }}?subject=Ticket {{ $ticket->refid }} : {{ $ticket->title  }}"><u>{{ $user->firstlastname }}</u></a> . 
</div>
<br>
<div>Ενημέρωση:</div>
<p><span>{!! nl2br($update->update) !!}</span>
</p>
<p>
Αυτό είναι ένα αυτοματοποιημένο μήνυμα. Μην απαντάτε σε αυτό το e-mail.
</p>
<p>
<br>
QTicket system
<br>
</p>

</body>
</html>