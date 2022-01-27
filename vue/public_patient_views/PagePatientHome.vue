<template>

  <div class="container mt-3">

    <LogOut/>


    <div class="row">
      <div class="col">

        <!-- CARD: Patient details -->
        <CardSection :no-title="true" class="mb-3">

          <div class="section__patient_details d-flex justify-content-between">

            <div class="d-flex flex-column">
              <h2 class="">{{ patient.full_name }}</h2>
              <div class="lead">DoB: {{ patient.dob }} ({{ patient.age }})</div>
              <div class="">Gender: {{ patient.gender }}</div>
            </div>

            <div class="d-flex flex-column">
              <h4>Phone: {{ patient.phone }}</h4>
              <div class="lead">NIC: {{ patient.nic }}</div>
              <div class="">{{ patient.address }}</div>
              <hr>
              <div class="">Guardian: {{ patient.guardian_name }}</div>
              <div class="">Phone: {{ patient.guardian_phone }}</div>
            </div>

          </div>
          <!-- section: patient_details -->
        </CardSection>


        <CardSection v-for="item in clinicsAndVisitsList" :key="item.clinic.id" class="mb-3">
          <template v-slot:header>{{ item.clinic.title }} Details</template>

          <div class="">

            <ClinicAppointmentsTable :clinic-visit="item"/>

          </div>

        </CardSection>

      </div>
    </div>

  </div>

</template>

<script>

import CardSection from '@/components/CardSection.vue';
import {showErrorDialog} from '@/helpers/common.js';
import store from '@/store/index.js';
import LogOut from '@/public_patient_views/components/LogOut';
import ClinicAppointmentsTable from '@/public_patient_views/components/ClinicAppointmentsTable';

export default {
  name: 'PagePatientHome',
  components: {ClinicAppointmentsTable, LogOut, CardSection},


  data() {
    return {};
  },
  /* DATA */

  computed: {
    isLoggedIn() {
      return this.$store.getters['publicPatient/isPatientLoggedIn'];
    },

    /** @returns {Patient} */
    patient() {
      return this.$store.getters['publicPatient/getLoggedInPatient'];
    },

    /** @returns {ClinicAndVisits[]} */
    clinicsAndVisitsList() {
      return this.$store.getters['publicPatient/getPatientClinicsAndVisitList'];
    },

  },
  /* COMPUTED */

  async mounted() {

    try {

      await this.$store.dispatch('publicPatient/fetchAssociatedClinics', this.patient.id);

    } catch (e) {
      showErrorDialog(e.response, 'Failed...');
    }
  },
  /* MOUNTED */

  methods: {



  },
  /* METHODS */


  /* Check the login state before entering the route */
  beforeRouteEnter(to, from, next) {
    const isLoggedIn = store.getters['publicPatient/isPatientLoggedIn'];
    if (!isLoggedIn) {
      next('/patient-login');
    }
    next();
  },
  /* BEFORE ROUTER ENTER */

};
</script>

<style scoped>

</style>
