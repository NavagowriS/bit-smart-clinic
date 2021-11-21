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

            <router-link class="btn btn-sm btn-primary mb-2"
                         :to="generateBookAppointmentLink(item)">Book appointment
            </router-link>

            <table class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Date</th>
                <th>Token No.</th>
                <th class="text-end">Weight/Height</th>
                <th class="text-end">SBP/DBP</th>
                <th class="text-end">Sugar Level</th>
                <th class="text-center">Status</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="appointment in item.appointments" :key="appointment.id">
                <td>
                  {{ appointment.clinic_date }}
                </td>
                <td>{{ appointment.token_number }}</td>
                <td class="text-end">
                  <span v-if="appointment.status !== 'ACTIVE'">
                    {{ appointment.param_weight }} kg / {{ appointment.param_height }} m
                  </span>
                </td>
                <td class="text-end">
                  <span v-if="appointment.status !== 'ACTIVE'">
                    {{ appointment.param_sbp }} / {{ appointment.param_dbp }} mmHg
                  </span>
                </td>
                <td class="text-end">
                  <span v-if="appointment.status !== 'ACTIVE'">
                    {{ appointment.param_blood_sugar }} mg/dL
                  </span>
                </td>
                <td class="text-center">{{ appointment.status }}</td>
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

    /**
     *
     * @param {ClinicAndVisits} data
     */
    generateBookAppointmentLink( data ) {
      return {
        name: 'PageBookAppointment',
        params: { clinicPatientId: data.clinicPatient.id },
      };
    },


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
