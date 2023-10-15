import * as Popper from '@popperjs/core'
window.Popper = Popper

import 'bootstrap'

// import jQuery from 'jquery';
import $ from '../../node_modules/jquery/dist/jquery';
window.jQuery = window.$ = $

// DataTablesBootstrap5.net
import DataTable from "../../node_modules/datatables.net/js/jquery.dataTables"
import "../../node_modules/datatables.net-bs5/js/dataTables.bootstrap5"
import "../../node_modules/datatables.net-autofill-bs5/js/autoFill.bootstrap5"
import "../../node_modules/datatables.net-responsive-bs5/js/responsive.bootstrap5"
import "../../node_modules/datatables.net-colreorder-bs5/js/colReorder.bootstrap5"
import "../../node_modules/datatables.net-fixedheader-bs5/js/fixedHeader.bootstrap5"

// select2
import select2 from "../../node_modules/select2/dist/js/select2.full"
select2();

// jqueryMinicolors
import "../../node_modules/@claviska/jquery-minicolors/jquery.minicolors"

// jquery-chained -- cant make it work for the time being
// import "../../node_modules/jquery-chained/jquery.chained";
// import "../../node_modules/jquery-chained/jquery.chained.remote";

// jquery-ui
// import "../../node_modules/jquery-ui/dist/jquery-ui"

// moment
// import {  } from "../../public/js/moment/moment";
// import moment from '../../node_modules/moment/moment';
// import moment from 'moment';
// moment().format();
// (method) moment.Moment.format(format?: string | undefined): string

// pc-bootstrap4-datetimepicker
// import "pc-bootstrap4-datetimepicker";

// sweetalert2
import swal from "sweetalert2";
window.Swal = swal;


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import jqueryMinicolors from '../../node_modules/@claviska/jquery-minicolors/jquery.minicolors';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

