<template>


  <div>

    <TopNavigationBar/>


    <div class="container">
      <div class="row">
        <div class="col">

          <CardSection>
            <template v-slot:header>Manage doctors</template>

            <p>Create a new
              <router-link to="/doctors/create">doctor profile</router-link>
            </p>


            <table class="table table-sm table-bordered">
              <thead>
              <tr>
                <th>Name</th>
                <th>DoB</th>
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
                <td>{{ doctor.dob }}</td>
                <td>{{ doctor.email }}</td>
                <td>{{ doctor.phone }}</td>
                <td>{{ doctor.doctor_speciality.speciality }}</td>
              </tr>
              </tbody>
            </table>

          </CardSection>


        </div>
      </div>
    </div>

  </div><!-- template -->

</template>

<script>
import CardSection from '@/components/CardSection';
import TopNavigationBar from '@/components/TopNavigationBar';

export default {
  name: 'PageDoctorsList',
  components: { CardSection, TopNavigationBar },

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
