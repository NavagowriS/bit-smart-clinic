import axios from 'axios';

export const publicPatientStore = {

    namespaced: true,

    state() {
        return {

            loggedInPatient: null,
            patientClinicsAndVisitList: {},

        };
    },

    getters: {

        getLoggedInPatient( state ) {
            return state.loggedInPatient;
        },

        isPatientLoggedIn( state ) {
            return state.loggedInPatient !== null;
        },

        getPatientClinicsAndVisitList( state ) {
            return state.patientClinicsAndVisitList;
        },

    },

    actions: {

        /**
         * Authenticate the patient
         * @param context
         * @param params {Object}
         */
        async authenticate( context, params ) {
            const response = await axios.post( 'auth/login-patient.php', params );
            context.state.loggedInPatient = response.data.payload.data;
        },

        async fetchAssociatedClinics( context, patientId ) {
            const response = await axios.post( 'public/patient-get-clinics.php', { id: patientId } );
            context.state.patientClinicsAndVisitList = response.data.payload;
        },

    },

};
