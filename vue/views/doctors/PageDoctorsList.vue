<template>


  <div>

    <TopNavigationBar/>


    <div class="container">
      <div class="row">
        <div class="col-8">

          <CardSection>
            <template v-slot:header>Manage doctors</template>

            <router-link class="btn btn-sm btn-primary mb-3" to="/doctors/create">Create new doctor profile</router-link>


            <table class="table table-sm table-bordered">
              <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Speciality</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="doctor in doctors" :key="doctor.id">
                <td>
                  <router-link :to="'/doctors/edit/' + doctor.id">{{ doctor.name }}</router-link>
                </td>
                <td>{{ doctor.email }}</td>
                <td>{{ doctor.phone }}</td>
                <td>{{ doctor.doctor_speciality.speciality }}</td>
              </tr>
              </tbody>
            </table>

          </CardSection>

        </div><!-- col -->


        <div class="col-4">
          <SpecialitiesCard/>
        </div><!-- col -->

      </div>
    </div>

  </div><!-- template -->

</template>

<script>
import CardSection from '@/components/CardSection';
import TopNavigationBar from '@/components/TopNavigationBar';
import SpecialitiesCard from '@/views/doctors/components/SpecialitiesCard.vue';

export default {
  name: 'PageDoctorsList',
  components: { SpecialitiesCard, CardSection, TopNavigationBar },

  data() {
    return {};
  },

  computed: {

    /** @returns {Doctor[]} */
    doctors() {
      return this.$store.getters['doctors/getDoctors'];
    },

  },

  async mounted() {

    try {

      await this.$store.dispatch( 'doctors/fetchAll' );

    } catch ( e ) {
      console.log( e );
    }

  },

  methods: {},


};
</script>

<style scoped>

</style>
