import axios from 'axios';

export const clinicPatientsStore = {

    namespaced: true,

    state: function () {
        return {
            /** @type {ClinicPatient|null} */
            clinicPatient: null,

            /** @type {ClinicPatientsList|null} */
            clinicPatientsList: null,

        };
    },

    getters: {
        getClinicPatientsList( state ) {
            return state.clinicPatientsList;
        },

        getClinicPatient( state ) {
            return state.clinicPatient;
        },
    },

    actions: {
        async fetchByClinic( context, clinicId ) {
            const response = await axios.get( `clinics/get-patients.php?id=${ clinicId }` );
            context.state.clinicPatientsList = response.data.payload;
        },

        async add( context, params ) {
            await axios.post( 'clinics/add-patient.php', params );
        },

        async fetch( context, id ) {
            const response = await axios.get( `clinics/get-patient.php?id=${ id }` );
            context.state.clinicPatient = response.data.payload.data;
        },
    },

};
