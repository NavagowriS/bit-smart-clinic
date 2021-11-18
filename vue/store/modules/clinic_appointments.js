import axios from 'axios';

export const clinicAppointmentsStore = {

    namespaced: true,

    state() {
        return {

            /* all clinic appointments */
            clinicAppointments: [],

            /* selected clinic appointment */
            clinicAppointment: {},

            /* appointments for selected clinic patient */
            clinicPatientAppointments: [],
        };
    },

    getters: {
        getClinicAppointments( state ) {
            return state.clinicAppointments;
        },

        getClinicAppointment( state ) {
            return state.clinicAppointment;
        },

        getClinicPatientAppointments( state ) {
            return state.clinicPatientAppointments;
        },

    },

    actions: {

        /**
         *
         * @param context
         * @param params - [clinic_id, start_date, end_date]
         */
        async fetchByClinicBetweenDates( context, params ) {
            const response = await axios.post( 'clinics/appointments/get-by-clinic.php', params );
            context.state.clinicAppointments = response.data.payload[ 'appointments' ];
        },

        async fetchByClinicPatient( context, clinicPatientId ) {
            const response = await axios.post( 'clinics/appointments/get-by-clinic-patient.php', { clinic_patient_id: clinicPatientId } );
            context.state.clinicPatientAppointments = response.data.payload[ 'appointments' ];

        },

        async fetch( context, id ) {
            const response = await axios.post( 'clinics/appointments/get.php', { id: id } );
            context.state.clinicAppointment = response.data.payload[ 'data' ];
        },

        async update( context, params ) {
            await axios.post( 'clinics/appointments/update.php', params );
        },

        /**
         *
         * @param context
         * @param params - [id, status]
         */
        async updateStatus( context, params ) {
            await axios.post( 'clinics/appointments/update-status.php', params );
        },


        async remove( context, id ) {
            await axios.post( 'clinics/appointments/remove.php', { id: id } );
        },

    },

};
