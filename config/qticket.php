<?php

return [

	'helpdeskEmail' => env('qticket_Helpdesk_eMail', 'helpdeskquant@quant.gr'),
	'aliveEmail' => env('qticket_Alive_eMail', 'helpdeskquant@quant.gr'),
	// config('qticket.helpdeskEmail')

	'reviewers' => env('qticket_ReviewersGroup', 'IT_Reviewers'),
	// config('qticket.reviewers')

	'admins' => env('qticket_AdminGroup', 'qticketAdmin'),
	// config('qticket.admins')

	'LDAP_DOMAIN' => env('LDAP_DOMAIN', 'LDAP_DOMAIN'),
	'LDAP_ROOT' => env('LDAP_ROOT', 'LDAP_ROOT'),
	'LDAP_PASSWORD' => env('LDAP_PASSWORD', 'LDAP_PASSWORD'),
	'LDAP_USER' => env('LDAP_USER', 'LDAP_USER'),

	'guides' => env('qticket_guides', ''),

];