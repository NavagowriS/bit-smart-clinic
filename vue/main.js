/* BOOTSTRAP 5 */
import '@popperjs/core';

import axios from 'axios';
import 'bootstrap-icons/font/bootstrap-icons.css';
import Chart from 'chart.js/auto';
import Vue from 'vue';

import App from './App';
import './assets/libs/daterangepicker/daterangepicker';
/* datetimepicker */
import './assets/libs/daterangepicker/daterangepicker.css';


/* local common scss */
import './assets/scss/common.scss';
import router from './router/index';
import store from './store/index';

window.bootstrap = require( 'bootstrap/dist/js/bootstrap.bundle.min' );


/* Axios configurations */

axios.defaults.baseURL = 'http://localhost/api';
axios.defaults.headers[ 'auth' ] = store.getters[ 'auth/getAuthKey' ];

/*
* Axios intercepting incoming responses, to check if it is 401,
* then route to login page, because auth token is expired, or
* not logged in
* */

axios.interceptors.response.use( undefined, ( error => {
    if ( error.response.status === 401 ) {
        store.dispatch( 'auth/auth_logout' ).then( () => {
            router.push( '/login' ).then();
        } );
    }

    return Promise.reject( error );
} ) );


Chart.defaults.font.family = 'Sora';

Vue.config.productionTip = false;

/* Vue initialization */
new Vue( {
    store: store,
    router: router,
    render: h => h( App ),
} ).$mount( '#app' );
