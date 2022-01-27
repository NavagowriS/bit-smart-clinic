import axios from 'axios';

const PATIENT_LOGGED_IN = 'patient_logged_in';
const PATIENT = 'patient';

export const publicPatientStore = {

    namespaced: true,

    state() {
        return {

            loggedInPatient: null,
            patientClinicsAndVisitList: {},

            /* local storage variables */
            localStoragePatientLoggedIn: localStorage.getItem(PATIENT_LOGGED_IN) || false,
            localStoragePatient: localStorage.getItem(PATIENT) || null,

            clinic: {},

        };
    },

    getters: {

        getLoggedInPatient(state) {

            if (state.localStoragePatientLoggedIn === 'true') {
                state.loggedInPatient = JSON.parse(state.localStoragePatient);
            }

            return state.loggedInPatient;
        },

        isPatientLoggedIn(state) {

            if (state.localStoragePatientLoggedIn === 'true') {
                state.loggedInPatient = JSON.parse(state.localStoragePatient);
            }

            return state.loggedInPatient !== null;
        },

        getPatientClinicsAndVisitList(state) {
            return state.patientClinicsAndVisitList;
        },

        getClinic(state) {
            return state.clinic;
        },

    },

    actions: {

        /**
         * Authenticate the patient
         * @param context
         * @param params {Object}
         */
        async authenticate(context, params) {
            const response = await axios.post('auth/login-patient.php', params);
            context.state.loggedInPatient = response.data.payload.data;

            localStorage.setItem(PATIENT_LOGGED_IN, 'true');
            localStorage.setItem(PATIENT, JSON.stringify(context.state.loggedInPatient));
        },

        async logout(context) {
            localStorage.setItem(PATIENT_LOGGED_IN, 'false');
            localStorage.setItem(PATIENT, null);
        },

        async fetchAssociatedClinics(context, patientId) {
            const response = await axios.post('public/patients/patient-get-clinics.php', {id: patientId});
            context.state.patientClinicsAndVisitList = response.data.payload;
        },


        async fetchClinicByClinicPatientId(context, clinicPatientId) {
            const response = await axios.post('public/patients/get-clinic-by-clinic-patient.php', {clinic_patient_id: clinicPatientId});
            context.state.clinic = response.data.payload.data;

        },

        async checkAppointmentToken(context, params) {
            const response = await axios.post('public/patients/appointment-check-token.php', params);
            return response.data.payload.token_number;
        },

        async getUsedTokens(context, params) {
            const response = await axios.post('public/patients/appointment-get-tokens.php', params);
            return response.data.payload;
        },

        async bookAppointment(context, params) {
            await axios.post('public/patients/appointment-book.php', params);
        },

        async cancelAppointment(context, id) {
            await axios.post('public/patients/appointment-cancel.php', {id: id});
        },

    },

};
