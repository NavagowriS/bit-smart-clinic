import {clinicAppointmentsStore} from '@/store/modules/clinic_appointments.js';
import {clinicPatientsStore} from '@/store/modules/clinic_patients';
import {pharmacyDrugsStore} from '@/store/modules/pharmacy_drugs.js';
import {pharmacyStatsStore} from '@/store/modules/pharmacy_stats.js';
import {prescriptionsStore} from '@/store/modules/prescriptions.js';
import {publicPatientStore} from '@/store/modules/public_patient.js';
import {specialityStore} from '@/store/modules/speciality.js';
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
        publicPatient: publicPatientStore,
        users: usersStore,
        doctors: doctorsStore,
        specialities: specialityStore,
        patients: patientsStore,
        clinics: clinicsStore,
        clinicVisits: clinicVisitsStore,
        clinicPatients: clinicPatientsStore,
        clinicAppointments: clinicAppointmentsStore,

        pharmacyDrugs: pharmacyDrugsStore,
        prescriptions: prescriptionsStore,
        pharmacyStats: pharmacyStatsStore,

    },
} );
