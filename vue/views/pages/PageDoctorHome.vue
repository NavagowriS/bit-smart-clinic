<template>

  <div class="">

    <TopNavigationBar/>


    <div class="container" v-if="loaded">
      <div class="row">
        <div class="col">

          <CardSection :no-title="true" class="mb-3">
            <div class="">

              <h2>{{ associatedDoctor.name }}</h2>
              <h5 class="text-black-50">{{ associatedDoctor.email }}</h5>

            </div>
          </CardSection>

          <CardSection>
            <template v-slot:header>Clinics</template>

            <div class="">

              <table class="table table-bordered">
                <tbody>
                <tr v-for="item in associatedDoctor.clinics" :key="item.id">
                  <td>
                    <router-link :to="{name: 'pageClinic', params:{id: item.id} }">{{ item.title }}</router-link>
                  </td>
                </tr>
                </tbody>
              </table>

            </div>

          </CardSection>


        </div><!-- col -->
      </div><!-- row -->


    </div><!-- template -->

  </div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import TopNavigationBar from '@/components/TopNavigationBar.vue';
import {showErrorDialog} from '@/helpers/common.js';

export default {
  name: 'PageDoctorHome',
  components: { CardSection, TopNavigationBar },

  data() {
    return {

      loaded: false,

    };
  },

  computed: {

    loggedInUser() {
      return this.$store.getters[ 'auth/getLoggedInUser' ];
    },

    /** @returns {Doctor} */
    associatedDoctor() {
      return this.$store.getters[ 'doctors/getDoctorAssociatedWithUser' ];
    },

  },

  async mounted() {

    try {

      await this.$store.dispatch( 'doctors/fetchByAssociatedUser', this.loggedInUser.id );

      this.loaded = true;

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
