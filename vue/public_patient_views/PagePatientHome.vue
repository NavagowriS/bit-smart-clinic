<template>

  <div class="container my-5">

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

            <table class="table table-sm table-bordered">
              <thead>
              <tr>
                <th style="width: 100px" class="text-end">Date</th>
                <th style="width: 50px" class="text-end">Token</th>
                <th style="width: 120px" class="text-center">Status</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="visit in item.visitDetails">
                <td class="text-end">{{ visit.visit_date }}</td>
                <td class="text-end">{{ visit.token_number }}</td>
                <td class="text-center">{{ visit.status }}</td>
                <th></th>
              </tr>
              </tbody>
            </table>

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

export default {
  name: 'PagePatientHome',
  components: { CardSection },


  data() {
    return {
      //
    };
  },

  computed: {
    isLoggedIn() {
      return this.$store.getters[ 'publicPatient/isPatientLoggedIn' ];
    },

    /** @returns {Patient} */
    patient() {
      return this.$store.getters[ 'publicPatient/getLoggedInPatient' ];
    },

    /** @returns {ClinicAndVisits[]} */
    clinicsAndVisitsList() {
      return this.$store.getters[ 'publicPatient/getPatientClinicsAndVisitList' ];
    },

  },

  async mounted() {


    try {

      await this.$store.dispatch( 'publicPatient/fetchAssociatedClinics', this.patient.id );

    } catch ( e ) {
      showErrorDialog( e.response, 'Failed...' );
    }


  },


  methods: {
    //
  },


  /* Check the login state before entering the route */
  beforeRouteEnter( to, from, next ) {
    const isLoggedIn = store.getters[ 'publicPatient/isPatientLoggedIn' ];
    if ( !isLoggedIn ) {
      next( '/patient-login' );
    }
    next();
  },

};
</script>

<style scoped>

</style>
