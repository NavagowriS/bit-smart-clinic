import PagePatientHome from '@/public_patient_views/PagePatientHome.vue';
import PatientLogin from '@/public_patient_views/PatientLogin.vue';
import Vue from 'vue';
import VueRouter from 'vue-router';

import store from '../store/index';
import Login from '../views/Login';
import Page404 from '../views/pages/Page404';
import {clinicsRoutes} from './groups/clinics';
import {doctorsRoutes} from './groups/doctors';
import {pagesRoutes} from './groups/pages';
import {patientsRoutes} from './groups/patients';

import {usersRoutes} from './groups/users';

Vue.use( VueRouter );


const routes = [

    {
        path: '/login',
        name: 'Login',
        component: Login,
    },

    ...pagesRoutes,
    ...usersRoutes,
    ...doctorsRoutes,
    ...patientsRoutes,
    ...clinicsRoutes,

    {
        path: '*',
        name: 'Page404',
        component: Page404,
    },

    {
        path: '/patient-login',
        name: 'PagePatientLogin',
        component: PatientLogin,
    },
    {
        path: '/public/patient',
        name: 'PagePatientHome',
        component: PagePatientHome,
    },

];


const router = new VueRouter( {
    routes: routes,
} );


/**
 * To make sure only authenticated pages can be viewed if logged in
 * otherwise redirect to login page
 */
router.beforeEach( ( to, from, next ) => {
    const userType = store.getters[ 'auth/getUserType' ];
    const isLoggedIn = store.getters[ 'auth/getLoginStatus' ];


    /* check if the route requires auth */
    if ( to.matched.some( record => record.meta.requiresAuth ) ) {

        /* 1. check if the logged in user has the access */
        if ( to.meta.hasOwnProperty( 'hasAccess' ) ) {
            if ( !to.meta.hasAccess.includes( userType ) ) {
                next( '/login' );
            }
        }

        /* 2. user has access type and is logged in */
        if ( isLoggedIn ) {
            next();
        } else {
            next( '/login' );
        }

    } else {
        next();
    }
} );


export default router;
