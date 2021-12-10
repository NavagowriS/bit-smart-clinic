import axios from 'axios';

export const prescriptionsStore = {

    namespaced: true,

    state() {
        return {};
    },

    actions: {

        async fetch( context, id ) {
            const response = await axios.post( 'clinics/prescriptions/get.php', { id: id } );
            return response.data.payload[ 'data' ];
        },


        async fetchByAppointment( context, appointmentId ) {
            const response = await axios.post( 'clinics/prescriptions/get-by-appointment.php', { appointment_id: appointmentId } );
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
            await axios.post( 'clinics/prescriptions/update.php', params );
        },

        async updatePrescriptionStatusCompleted( context, id ) {
            await axios.post( 'clinics/prescriptions/update.php', {
                id: id,
                status: 'COMPLETED',
            } );
        },

        async updatePrescriptionStatusPending( context, id ) {
            await axios.post( 'clinics/prescriptions/update.php', {
                id: id,
                status: 'PENDING',
            } );
        },


        /*
        * -----------------------------------------------------------------------------------------------
        * Prescription Items
        * -----------------------------------------------------------------------------------------------
        * */

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

        /**
         *
         * @param context
         * @param params [id]
         */
        async deletePrescriptionItem( context, id ) {
            await axios.post( 'clinics/prescriptions/delete-prescription-item.php', { id: id } );
        },

    },

};
