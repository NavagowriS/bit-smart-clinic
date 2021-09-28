export const authMixins = {

    computed: {
        isSTAFF() {
            const userType = this.$store.getters[ 'auth/getUserType' ];
            return ['ADMIN', 'STAFF'].includes( userType );
        },
    },

};


