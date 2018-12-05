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
Το qticket <a href="{{ $url . '/' . $ticket->refid }}"> {{ $ticket->refid }}</a> έκλεισε.  <br>
</div>
<br>

<div>Τελευταία Ενημέρωση:</div>
<p><span>{!! nl2br($update->update) !!}</span>
</p>

Μπορείτε να το επαναφέρετε σε ενεργή κατάσταση ή και να καταχωρήσετε νέο <b><a href="{{ $url }}" target="_blank">qticket</a></b> μέσω της ιστοσελίδας : <u><a href="{{ $url . '/' . $ticket->refid }}" target="_blank"> {!! $url !!}  </a> </u>
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