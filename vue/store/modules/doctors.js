import axios from 'axios';

export const doctorsStore = {

    namespaced: true,

    state() {
        return {

            /** @type {Doctor} */
            doctor: {},

            /** @type {Doctor[]} */
            doctors: [],

            /** @type {DoctorSpeciality[]} */
            doctorSpecialities: [],
        };
    },

    getters: {

        getDoctor( state ) {
            return state.doctor;
        },

        getDoctors( state ) {
            return state.doctors;
        },

        getDoctorsSpecialities( state ) {
            return state.doctorSpecialities;
        },
    },

    mutations: {

        setDoctor( state, data ) {
            state.doctor = data;
        },

        setDoctors( state, data ) {
            state.doctors = data;
        },

        setDoctorSpecialities( state, data ) {
            state.doctorSpecialities = data;
        },

    },

    actions: {

        async fetchAll( context ) {
            const response = await axios.get( `doctors/all.php` );
            context.commit( 'setDoctors', response.data.payload );
        },

        async fetch( context, id ) {
            const response = await axios.get( `doctors/get.php?id=${ id }` );
            context.commit( 'setDoctor', response.data.payload.data );
        },

        async getAllSpecialities( context ) {
            const response = await axios.get( `doctors/speciality-all.php` );
            context.commit( 'setDoctorSpecialities', response.data.payload );
        },

        async create( context, params ) {
            await axios.post( 'doctors/create.php', params );
        },

        async update( context, params ) {
            await axios.post( 'doctors/update.php', params );
        },

    },
};
