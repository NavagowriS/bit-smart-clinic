<template>

  <div class="">

    <TopNavigationBar/>


    <h1>Doctor's home</h1>

  </div>

</template>

<script>
import TopNavigationBar from '@/components/TopNavigationBar.vue';
import {showErrorDialog} from '@/helpers/common.js';

export default {
  name: 'PageDoctorHome',
  components: { TopNavigationBar },

  data() {
    return {
      //
    };
  },

  computed: {

    loggedInDoctor() {
      return this.$store.getters[ 'auth/getLoggedInUser' ];
    },

    doctor() {
      return this.$store.getters[ 'doctors/getDoctor' ];
    },

  },

  async mounted() {

    try {

      await this.$store.dispatch( 'doctors/fetch', this.loggedInDoctor.id );

    } catch ( e ) {
      showErrorDialog( e.response, 'Failed to get doctor details' );
    }

  },

  methods: {
    //
  },


};
</script>

<style scoped>

</style>
