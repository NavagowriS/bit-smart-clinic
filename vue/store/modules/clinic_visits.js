import axios from 'axios';

export const clinicVisitsStore = {

    namespaced: true,

    state() {
        return {

            /** @type {ClinicVisit[]} */
            clinicVisits: [],

            /** @type {ClinicVisit} */
            selectedClinicVisit: {},

            /** @type {ClinicPatient[]} */
            clinicVisitPatients: [],

            /** @type {ClinicVisitPatient} */
            clinicVisitPatient: {},

        };
    },

    getters: {

        getClinicVisits( state ) {
            return state.clinicVisits;
        },

        getSelectedClinicVisit( state ) {
            return state.selectedClinicVisit;
        },

        getClinicVisitPatients( state ) {
            return state.clinicVisitPatients;
        },

        getClinicVisitPatient( state ) {
            return state.clinicVisitPatient;
        },
    },

    actions: {

        /**
         * Fetch clinic visit by id
         * @param context
         * @param id
         */
        async fetch( context, id ) {
            const response = await axios.post( 'clinics/visits/get.php', { id: id } );
            context.state.selectedClinicVisit = response.data.payload.data;
        }, /* fetch by id */

        /**
         * Fetch all visits by clinic
         * @param context
         * @param clinicId
         */
        async fetchByClinic( context, clinicId ) {
            const response = await axios.post( 'clinics/visits/get-by-clinic.php', { clinic_id: clinicId } );
            context.state.clinicVisits = response.data.payload.visits;
        },


        /**
         * Create a new visit for the clinic
         * @param context
         * @param params - {clinic_id, visit_date}
         */
        async create( context, params ) {
            await axios.post( 'clinics/visits/create.php', params );
        }, /* create */


        /**
         * Add a patient to the given visit
         * @param context
         * @param params - {clinic_patient_id, clinic_visit_id}
         */
        async addPatient( context, params ) {
            await axios.post( 'clinics/visits/add-patient.php', params );
        },

        /**
         * Remove selected patient from the visit
         * @param context
         * @param id
         */
        async removePatient( context, id ) {
            await axios.post( 'clinics/visits/remove-patient.php', { id: id } );
        },

        /**
         * Fetch all patients for the current visit
         * @param context
         * @param clinicVisitId
         */
        async fetchPatientsByClinicVisit( context, clinicVisitId ) {
            const response = await axios.post( 'clinics/visits/all-patients.php', { clinic_visit_id: clinicVisitId } );
            context.state.clinicVisitPatients = response.data.payload;
        },


        /**
         * Fetch a single clinic visit patient
         * @param context
         * @param clinicVisitPatientId
         */
        async fetchPatientByClinicVisit( context, clinicVisitPatientId ) {
            const response = await axios.post( 'clinics/visits/get-patient.php', { id: clinicVisitPatientId } );
            context.state.clinicVisitPatient = response.data.payload.data;
        },


        /**
         * Update clinic visit patient details
         *
         * @param context
         * @param {Object} params
         */
        async updateVisitPatient( context, params ) {
            await axios.post( 'clinics/visits/update-visit-patient.php', params );
        },


        /**
         *
         * @param context
         * @param {Object} params - id, status
         */
        async updateVisitPatientStatus( context, params ) {
            await axios.post( 'clinics/visits/update-visit-patient-status.php', params );
        },

    },

};
