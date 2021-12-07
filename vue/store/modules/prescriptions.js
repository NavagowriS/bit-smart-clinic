import axios from 'axios';

export const prescriptionsStore = {

    namespaced: true,

    state() {
        return {};
    },

    actions: {

        async fetchByAppointment( context, appointmentId ) {
            const response = await axios.post( 'clinics/prescriptions/get-prescription.php', { appointment_id: appointmentId } );
            return response.data.payload[ 'data' ];
        },

        /**
         *
         * @param context
         * @param params [appointment_id, prescription_date, remarks]
         */
        async createPrescription( context, params ) {
            await axios.post( 'clinics/prescriptions/create-prescription.php', params );
        },

        /**
         *
         * @param context
         * @param params [remarks]
         */
        async updatePrescription( context, params ) {
            await axios.post( 'clinics/prescriptions/create-prescription.php', params );
        },

        /**
         *
         * @param context
         * @param prescriptionId
         */
        async fetchAllPrescriptionItems( context, prescriptionId ) {
            const response = await axios.post(
                'clinics/prescriptions/all-prescription-items.php',
                { prescription_id: prescriptionId },
            );
            return response.data.payload;
        },

        /**
         *
         * @param context
         * @param params [prescription_id, drug_id]
         */
        async addPrescriptionItem( context, params ) {
            await axios.post( 'clinics/prescriptions/add-prescription-item.php', params );
        },

        /**
         *
         * @param context
         * @param params [id, dose, period, remarks]
         */
        async updatePrescriptionItem( context, params ) {
            await axios.post( 'clinics/prescriptions/update-prescription-item.php', params );
        },

    },

};
