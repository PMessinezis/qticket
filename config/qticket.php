<?php

return [

	'helpdeskEmail' => env('qticket_Helpdesk_eMail', ''),
	'aliveEmail' => env('qticket_Alive_eMail', ''),
	// config('qticket.helpdeskEmail')

	'reviewers' => env('qticket_ReviewersGroup', 'IT_Reviewers'),
	// config('qticket.reviewers')

	'admins' => env('qticket_AdminGroup', 'qticketAdmin'),
	// config('qticket.admins')

	'LDAP_USER_DOMAIN' => '',

	'LDAP_DOMAIN_NEW_xxxxxx' => env('LDAP_DOMAIN_NEW_xxxxxx', 'LDAP_DOMAIN'),
	'LDAP_ROOT_NEW_xxxxxx' => env('LDAP_ROOT_NEW_xxxxxx', 'LDAP_ROOT'),
	'LDAP_PASSWORD_NEW_xxxxxx' => env('LDAP_PASSWORD_NEW_xxxxxx', 'LDAP_PASSWORD'),
	'LDAP_USER_NEW_xxxxxx' => env('LDAP_USER_NEW_xxxxxx', 'LDAP_USER'),

	'LDAP_DOMAIN_xxxxxx' => env('LDAP_DOMAIN_xxxxxx', 'LDAP_DOMAIN'),
	'LDAP_ROOT_xxxxxx' => env('LDAP_ROOT_xxxxxx', 'LDAP_ROOT'),
	'LDAP_PASSWORD_xxxxxx' => env('LDAP_PASSWORD_xxxxxx', 'LDAP_PASSWORD'),
	'LDAP_USER_xxxxxx' => env('LDAP_USER_xxxxxx', 'LDAP_USER'),

	'guides' => env('qticket_guides', ''),

];
