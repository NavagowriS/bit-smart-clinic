import axios from 'axios';


export const usersStore = {

    namespaced: true,

    state: {

        user: {},
        roles: {
            'ADMIN': 'Administrator',
            'STAFF': 'Staff',
            'USER': 'User',
            'DOCTOR': 'Doctor',
            'PHARMACIST': 'Pharmacist',
        },

        /** @type {User[]} */
        doctorUsers: [],

        /** @type {User[]} */
        users: [],

    },
    /* *** STATE *** */

    getters: {

        getUser: function ( state ) {
            return state.user;
        },

        getUserRoles: function ( state ) {
            return state.roles;
        },

        getDoctorUsers( state ) {
            return state.doctorUsers;
        },

        getUsers( state ) {
            return state.users;
        },

    },
    /* *** GETTERS *** */

    mutations: {

        setUser: function ( state, user ) {
            state.user = user;
        },

    },
    /* *** MUTATIONS *** */

    actions: {


        async fetchUser( context, id ) {

            const response = await axios.get( `users/get.php?id=${ id }` );
            context.state.user = response.data.payload.user;
        },
        /* fetch user */

        async fetchAll( context ) {
            const response = await axios.post( 'users/all.php' );
            context.state.users = response.data.payload;
        },


        async updateUser( context, user ) {
            await axios.post( 'users/update.php', user );
        },
        /* update user details */

        async updatePassword( context, params ) {

            /* params: {id, current_password, new_password} */
            await axios.post( 'users/update-password.php', params );
        },
        /* update user password */


        async createUser( context, user ) {
            await axios.post( 'users/create.php', user );
        },

        /**
         * Fetch all the users whose role = DOCTOR
         * @param context
         */
        async fetchAllDoctorUsers( context ) {
            const response = await axios.post( 'users/all-doctors.php' );
            context.state.doctorUsers = response.data.payload;

        },

    },
    /* *** ACTIONS *** */

};
