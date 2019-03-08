<?php

return [

	'helpdeskEmail' => env('qticket_Helpdesk_eMail', 'helpdeskquant@quant.gr'),
	'aliveEmail' => env('qticket_Alive_eMail', 'helpdeskquant@quant.gr'),
	// config('qticket.helpdeskEmail')

	'reviewers' => env('qticket_ReviewersGroup', 'IT_Reviewers'),
	// config('qticket.reviewers')

	'admins' => env('qticket_AdminGroup', 'qticketAdmin'),
	// config('qticket.admins')

	'LDAP_USER_DOMAIN' => '',

	'LDAP_DOMAIN_NEW_QUALCO' => env('LDAP_DOMAIN_NEW_QUALCO', 'LDAP_DOMAIN'),
	'LDAP_ROOT_NEW_QUALCO' => env('LDAP_ROOT_NEW_QUALCO', 'LDAP_ROOT'),
	'LDAP_PASSWORD_NEW_QUALCO' => env('LDAP_PASSWORD_NEW_QUALCO', 'LDAP_PASSWORD'),
	'LDAP_USER_NEW_QUALCO' => env('LDAP_USER_NEW_QUALCO', 'LDAP_USER'),

	'LDAP_DOMAIN_QQUANT' => env('LDAP_DOMAIN_QQUANT', 'LDAP_DOMAIN'),
	'LDAP_ROOT_QQUANT' => env('LDAP_ROOT_QQUANT', 'LDAP_ROOT'),
	'LDAP_PASSWORD_QQUANT' => env('LDAP_PASSWORD_QQUANT', 'LDAP_PASSWORD'),
	'LDAP_USER_QQUANT' => env('LDAP_USER_QQUANT', 'LDAP_USER'),

	'guides' => env('qticket_guides', ''),

];