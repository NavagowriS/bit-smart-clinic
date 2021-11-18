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

        <CardSection class="mb-3">
          <template v-slot:header>
            Book an appointment for {{ clinic.title }}
          </template>

          <div class="" id="form_book_appointment">

            <div class="row g-1 justify-content-center">
              <div class="col-2">
                <div>
                  <label class="form-label">Appointment Date</label>
                  <DateField v-model="formAppointment.clinic_date"/>
                </div>
              </div>

              <div class="col-2">
                <div>
                  <label class="form-label">&nbsp;</label>
                  <button class="btn btn-primary form-control" @click="onCheckAppointment()">Check</button>
                </div>
              </div>

            </div>

            <!-- confirm appointment section -->
            <div class="row my-3 justify-content-center">

              <div class="col-6">

                <div class="" v-if="formAppointment.token_number !== 0">

                  <div class="alert alert-primary text-center">
                    <p class="lead">Available token on {{ formAppointment.clinic_date }}</p>
                    <h1>{{ formAppointment.token_number }}</h1>

                    <button class="btn btn-success" @click="onBookAppointment()">Book this appointment</button>

                  </div>

                </div>


              </div>

            </div>


          </div>

        </CardSection>

      </div>
    </div>
  </div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import DateField from '@/components/fields/DateField.vue';
import {showErrorDialog} from '@/helpers/common.js';
import store from '@/store';
import moment from 'moment';

export default {
  name: 'BookAppointment',
  components: { DateField, CardSection },

  data() {
    return {

      formAppointment: {
        clinic_date: moment().format( 'YYYY-MM-DD' ),
        token_number: 0,
      },


      bookingSuccessStatus: false,

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

    /** @returns {Clinic} */
    clinic() {
      return this.$store.getters[ 'publicPatient/getClinic' ];
    },

    clinicPatientId() {
      return parseInt( this.$route.params[ 'clinicPatientId' ] );
    },

  },

  async mounted() {

    try {

      await this.$store.dispatch( 'publicPatient/fetchClinicByClinicPatientId', this.$route.params.clinicPatientId );

    } catch ( e ) {
      showErrorDialog( e.response, 'Failed...' );
    }
  },


  methods: {

    async onCheckAppointment() {

      try {

        const params = {
          clinic_id: this.clinic.id,
          clinic_date: this.formAppointment.clinic_date,
        };

        this.formAppointment.token_number = await this.$store.dispatch( 'publicPatient/checkAppointmentToken', params );

      } catch ( e ) {
        console.log( e );
      }

    },


    async onBookAppointment() {

      this.bookingSuccessStatus = false;

      try {

        const params = {
          clinic_id: this.clinic.id,
          clinic_date: this.formAppointment.clinic_date,
          clinic_patient_id: this.clinicPatientId,
          token_number: this.formAppointment.token_number,
        };

        this.formAppointment.token_number = await this.$store.dispatch( 'publicPatient/bookAppointment', params );

        this.bookingSuccessStatus = true;

      } catch ( e ) {
        console.log( e );
      }
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
