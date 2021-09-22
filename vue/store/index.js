import {clinicPatientsStore} from '@/store/modules/clinic_patients';
import Vue from 'vue';
import Vuex from 'vuex';

import {authStore} from './modules/auth';
import {clinicVisitsStore} from './modules/clinic_visits';
import {clinicsStore} from './modules/clinics';
import {doctorsStore} from './modules/doctors';
import {patientsStore} from './modules/patients';
import {usersStore} from './modules/users';

Vue.use( Vuex );

export default new Vuex.Store( {

    namespaced: true,
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth: authStore,
        users: usersStore,
        doctors: doctorsStore,
        patients: patientsStore,
        clinics: clinicsStore,
        clinicVisits: clinicVisitsStore,
        clinicPatients: clinicPatientsStore,
    },
} );
