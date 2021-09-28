import axios from 'axios';

export const specialityStore = {

    namespaced: true,

    state() {
        return {

            allSpecialities: [],

            selectedSpeciality: {},
        };
    },

    getters: {
        getAllSpecialities( state ) {
            return state.allSpecialities;
        },

        getSelectedSpeciality( state ) {
            return state.selectedSpeciality;
        },
    },

    actions: {

        async fetchAll( context ) {
            const response = await axios.post( 'doctors/all-speciality.php' );
            context.state.allSpecialities = response.data.payload;
        },

        async fetch( context, id ) {
            const response = await axios.post( 'doctors/get-speciality.php', { id: id } );
            context.state.selectedSpeciality = response.data.payload.data;
        },

        async update( context, params ) {
            await axios.post( 'doctors/update-speciality.php', params );
        },

        async create( context, params ) {
            await axios.post( 'doctors/create-speciality.php', params );
        },

    },

};
