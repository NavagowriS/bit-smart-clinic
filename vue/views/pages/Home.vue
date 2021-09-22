<template>

  <div>

    <TopNavigationBar/>

    <div class="container">
      <div class="row">

        <div class="col">
          <h2 class="text-center text-white">Smart Clinic</h2>
          <hr>
        </div><!-- col -->

      </div>


      <div class="row">

        <div class="col-4">

          <CardSection class="text-center">
            <template v-slot:header>Doctors</template>

            <div class="text-center mb-3">
              <img :src="icons.doctorsIcon" class="icon-64" alt="">
            </div>
            <router-link to="/doctors">Manage doctors</router-link>

          </CardSection>

        </div>

        <div class="col-4">

          <CardSection class="text-center">
            <template v-slot:header>Patients</template>

            <div class="text-center mb-3">
              <img :src="icons.patientsIcon" class="icon-64" alt="">
            </div>

            <router-link to="/patients">Manage patients</router-link>

          </CardSection>

        </div>

        <div class="col-4">

          <CardSection class="text-center">
            <template v-slot:header>Clinics</template>

            <div class="text-center mb-3">
              <img :src="icons.clinicIcon" class="icon-64" alt="">
            </div>
            <router-link to="/clinics">Manage clinics</router-link>

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
  name: 'Home',
  components: { CardSection, TopNavigationBar },

  data() {
    return {

      icons: {
        clinicIcon: require( '@/assets/images/icons/clinic.svg' ).default,
        patientsIcon: require( '@/assets/images/icons/patients.svg' ).default,
        doctorsIcon: require( '@/assets/images/icons/doctor.svg' ).default,
      },

    };
  },

  mounted() {


  },

};
</script>

<style scoped>

</style>
