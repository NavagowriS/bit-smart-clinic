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

        /*
        ------------------
        drug tags
        ------------------
        */

        /**
         *
         * @param context
         * @param id
         */
        async fetchAllDrugTags( context, id ) {
            const response = await axios.post( 'pharmacy/drugs/all-tags.php', { drug_id: id } );
            return response.data.payload;
        },

        /**
         *
         * @param context
         * @param params [drug_id, tag_id]
         */
        async addDrugTag( context, params ) {
            return await axios.post( 'pharmacy/drugs/add-tag.php', params );
        },

        /**
         *
         * @param context
         * @param id
         */
        async removeDrugTag( context, id ) {
            return await axios.post( 'pharmacy/drugs/remove-tag.php', { tag_id: id } );
        },


        /*
        ------------------
        pharmacy tags
        ------------------
        */


        async fetchTag( context, id ) {
            const response = await axios.post( 'pharmacy/tags/get.php', { id: id } );
            return response.data.payload.data;
        },

        async fetchAllTags( context ) {
            const response = await axios.post( 'pharmacy/tags/all.php' );
            return response.data.payload;
        },

        /**
         *
         * @param context
         * @param params [tag]
         */
        async createTag( context, params ) {
            return await axios.post( 'pharmacy/tags/create.php', params );
        },

        /**
         *
         * @param context
         * @param params [id, tag]
         */
        async updateTag( context, params ) {
            return await axios.post( 'pharmacy/tags/update.php', params );
        },

        async deleteTag( context, id ) {
            return await axios.post( 'pharmacy/tags/delete.php', {id: id} );
        },


    },

};
