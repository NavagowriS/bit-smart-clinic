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
         * @param params [drug_name, generic_name, brand_name, min_quantity]
         */
        async create( context, params ) {
            return await axios.post( 'pharmacy/drugs/create.php', params );
        },

        /**
         *
         * @param context
         * @param params [id, drug_name, generic_name, brand_name, min_quantity]
         */
        async update( context, params ) {
            return await axios.post( 'pharmacy/drugs/update.php', params );
        },

        /**
         *
         * @param context
         * @param keyword
         */
        async search( context, keyword ) {
            const response = await axios.post( 'pharmacy/drugs/search.php', { keyword: keyword } );
            return response.data.payload;
        },

        async stats( context ) {
            const response = await axios.post( 'pharmacy/drugs/stats.php' );
            return response.data.payload;
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
            return await axios.post( 'pharmacy/tags/delete.php', { id: id } );
        },

        /**
         *
         * @param context
         * @param id - drugId
         */
        async findDrugsByTag( context, id ) {
            const response = await axios.post( 'pharmacy/tags/filter-by.php', { id: id } );
            return response.data.payload;
        },

        /*
        ------------------
        purchase orders
        ------------------
        */

        /**
         *
         * @param context
         * @param params [drug_id, order_date, quantity]
         */
        async createPurchaseOrder( context, params ) {
            return await axios.post( 'pharmacy/po/create.php', params );
        },

        async fetchPurchaseOrdersByDrug( context, drugId ) {
            const response = await axios.post( 'pharmacy/po/by-drug.php', { drug_id: drugId } );
            return response.data.payload;
        },

        async deletePurchaseOrder( context, id ) {
            return await axios.post( 'pharmacy/po/delete.php', { id: id } );
        },

        /*
        ------------------
        prescriptions
        ------------------
        */

        /**
         *
         * @param context
         * @param params [start_date, end_date, status(optional)]
         */
        async fetchPrescriptions( context, params ) {
            const response = await axios.post( 'clinics/prescriptions/find-between-dates.php', params );
            return response.data.payload;
        },

    },

};
