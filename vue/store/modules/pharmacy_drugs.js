import axios from 'axios';

export const pharmacyDrugsStore = {

    namespaced: true,

    state() {
        return {};
    },

    getters: {},

    mutations: {},

    actions: {

        async fetch( context, id ) {
            const response = await axios.post( 'pharmacy/drugs/get.php', { id: id } );
            return response.data.payload.data;
        },

        async fetchAll( context ) {
            const response = await axios.post( 'pharmacy/drugs/all.php' );
            return response.data.payload;
        },

        /**
         *
         * @param context
         * @param params [drug_name, generic_name, brand_name]
         */
        async create( context, params ) {
            return await axios.post( 'pharmacy/drugs/create.php', params );
        },

        /**
         *
         * @param context
         * @param params [id, drug_name, generic_name, brand_name]
         */
        async update( context, params ) {
            return await axios.post( 'pharmacy/drugs/update.php', params );
        },

    },

};
