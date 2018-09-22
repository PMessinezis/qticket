
try {
    if ($) console.log('jquery loaded ', $.fn.jquery);
    $=jQuery=JQuery=window.$ = window.JQuery = window.jQuery = ( $ ? $ : require('jquery') );
    require('bootstrap-sass');
} catch (e) {}

require('select2');
require('bootstrap-notify');

// import 'jquery-ui/ui/widgets/datepicker.js';

import Vue from 'vue';


window.Vue=Vue;

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

