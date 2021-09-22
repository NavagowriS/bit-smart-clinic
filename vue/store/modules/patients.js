import axios from 'axios';

export const patientsStore = {

    namespaced: true,

    state: {

        /** @type {Patient} */
        selectedPatient: {},

        /** @type {Patient[]} */
        patients: [],

        genders: {
            'MALE': 'Male',
            'FEMALE': 'Female',
            'OTHER': 'Other',
        },

    },

    getters: {

        getPatients( state ) {
            return state.patients;
        },
        getSelectedPatient( state ) {
            return state.selectedPatient;
        },
        getGenders( state ) {
            return state.genders;
        },

    },

    mutations: {

        setPatients( state, data ) {
            state.patients = data;
        },
        setSelectedPatient( state, data ) {
            state.selectedPatient = data;
        },

    },

    actions: {

        async fetchAll( context ) {

            const response = await axios.get( 'patients/all.php' );
            context.commit( 'setPatients', response.data.payload );

        }, /* fetch all */

        async fetch( context, id ) {
            const response = await axios.get( 'patients/get.php?id=' + id );
            context.commit( 'setSelectedPatient', response.data.payload.data );

        }, /* fetch */

        async create( context, params ) {
            await axios.post( 'patients/create.php', params );
        }, /* create */


        async update( context, params ) {
            await axios.post( 'patients/update.php', params );
        },


        async search( context, keyword ) {
            const response = await axios.get( `patients/search.php?keyword=${ keyword }` );
            context.commit( 'setPatients', response.data.payload );
        },

    },

};
