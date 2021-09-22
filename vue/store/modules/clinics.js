import axios from 'axios';

export const clinicsStore = {

    namespaced: true,

    state: function () {
        return {

            /** @type {Clinic|null} */
            clinic: null,

            /** @type {Clinic[]} */
            clinicsList: [],


        };
    },

    getters: {
        getClinicsList( state ) {
            return state.clinicsList;
        },

        getClinic( state ) {
            return state.clinic;
        },



    },

    actions: {

        async fetchAll( context ) {
            const response = await axios.get( 'clinics/all.php' );
            context.state.clinicsList = response.data.payload;
        }, /* fetch all */

        async fetch( context, id ) {
            const response = await axios.get( `clinics/get.php?id=${ id }` );
            context.state.clinic = response.data.payload.data;
        }, /* fetch */

        async create( context, params ) {
            await axios.post( `clinics/create.php`, params );
        }, /* create */

        async update( context, params ) {
            await axios.post( `clinics/update.php`, params );
        }, /* update */


    },

};
