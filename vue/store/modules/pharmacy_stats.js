import axios from 'axios';

export const pharmacyStatsStore = {

    namespaced: true,

    actions: {

        async stats( context ) {
            const response = await axios.post( 'pharmacy/drugs/stats.php' );
            return response.data.payload;
        },

        /**
         *
         * @param context
         * @param params [start_date, end_date, clinic_id]
         */
        async statsDispensedCounts( context, params ) {
            const response = await axios.post( 'pharmacy/drugs/stats-dispensed.php', params );
            return response.data.payload;
        },

    },

};
