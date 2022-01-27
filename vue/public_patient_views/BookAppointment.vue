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
                  <DateField v-model="formAppointment.clinic_date" :min-date="formAppointment.clinic_date"/>
                </div>
              </div>

              <div class="col-2">
                <div>
                  <label class="form-label">&nbsp;</label>
                  <button class="btn btn-primary form-control" @click="onCheckAppointment()">Check</button>
                </div>
              </div>

            </div>


            <!-- show already existing appointment -->

            <div class="row my-3 justify-content-center" v-if="alreadyExist">
              <div class="col-8">

                <div class="alert alert-warning text-center">
                  <p class="lead">
                    You already have an appointment on {{ formAppointment.existing.clinic_date }}
                  </p>
                  <h1>Token Number: {{ formAppointment.existing.token_number }}</h1>
                  <p>
                    Please visit the clinic on time with identification.
                  </p>
                  <button class="btn btn-sm btn-danger" @click="$refs.modal_cancel_ap.show()">Cancel this appointment
                  </button>
                </div>

              </div><!-- col -->
            </div><!-- row -->

          </div>


          <div class="row justify-content-center">
            <div class="col-12">

              <AppointmentTimesTable
                  :used-tokens="formAppointment.usedTokens"
                  :show-table="formAppointment.showTable"
                  @token-selected="onOpenConfirmModal"
              />

            </div>
          </div>

        </CardSection>


        <!-- MODAL: Confirm booking the appointment -->
        <ModalWindow title="Confirm Appointment" size="md" id="mdl_ca" ref="modal_confirm_ap">

          <div class="d-flex justify-content-center">
            <div class="token_number text-center bg-dark text-white rounded">{{ chosenToken }}</div>
          </div>
          <p class="lead text-center">Are you sure to confirm the appointment?</p>

          <div class="text-center">
            <button class="btn btn-success" @click="onBookAppointment">Confirm</button>
            <button class="btn btn-secondary" @click="$refs.modal_confirm_ap.close()">Cancel</button>
          </div>

        </ModalWindow>

        <ModalWindow id="mdl_suc_ap" title="Appointment Booked" ref="modal_success">
          <div class="alert alert-success text-center">
            <p class="lead">
              Your appointment is booked on {{ formAppointment.clinic_date }}
            </p>
            <h1>Token Number: {{ formAppointment.token_number }}</h1>
            <p>
              Please visit the clinic on time with identification.
            </p>
          </div>
        </ModalWindow>


        <ModalWindow id="mdl_cnl_ap" title="Cancel Appointment" ref="modal_cancel_ap">
          <p class="lead text-center">Are you sure to cancel this appointment?</p>
          <div class="text-center">
            <button class="btn btn-danger" @click="onCancelAppointment">Confirm Cancel</button>
          </div>
        </ModalWindow>


      </div>
    </div>
  </div>

</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';
import DateField from '@/components/fields/DateField.vue';
import {showErrorDialog} from '@/helpers/common.js';
import store from '@/store';
import moment from 'moment';
import AppointmentTimesTable from '@/public_patient_views/components/AppointmentTimesTable';
import ModalWindow from '@/components/ModalWindow';

export default {
  name: 'BookAppointment',
  components: {ModalWindow, AppointmentTimesTable, DateField, CardSection},

  data() {
    return {


      formAppointment: {
        clinic_date: moment().format('YYYY-MM-DD'),
        token_number: 0,
        usedTokens: [],
        existing: null,
        showTable: false,
      },

      alreadyExist: false,
      showBookingConfirm: false,
      bookingSuccessStatus: false,


      chosenToken: 0

    };
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

    /** @returns {Clinic} */
    clinic() {
      return this.$store.getters['publicPatient/getClinic'];
    },

    clinicPatientId() {
      return parseInt(this.$route.params['clinicPatientId']);
    },

    clinicDate() {
      return this.formAppointment.clinic_date;
    },

  },
  /* COMPUTED */


  watch: {

    clinicDate() {
      this.showBookingConfirm = false;
    },

  },
  /* WATCH */


  async mounted() {

    try {
      await this.$store.dispatch('publicPatient/fetchClinicByClinicPatientId', this.$route.params.clinicPatientId);
    } catch (e) {
      showErrorDialog(e.response, 'Failed...');
    }
  },
  /* MOUNTED */


  methods: {

    async onCheckAppointment() {

      this.alreadyExist = false;

      try {

        const params = {
          clinic_id: this.clinic.id,
          clinic_date: this.formAppointment.clinic_date,
          clinic_patient_id: this.clinicPatientId,
        };

        const data = await this.$store.dispatch('publicPatient/getUsedTokens', params);

        this.formAppointment.usedTokens = data['usedTokens'];
        this.formAppointment.existing = data['existing'];

        /* if existing is not null, then appointment for the
        *  the patient is existed for this day */
        if (this.formAppointment.existing !== null) {
          this.alreadyExist = true;
          this.formAppointment.showTable = false;
        } else {
          this.formAppointment.showTable = true;
        }

      } catch (e) {
        errorDialog({
          message: e.response.data.payload.error,
        });
      }
    }, /* on check appointments */

    /**
     * Open confirm modal window before approving the appointment
     * @param tokenNumber
     */
    onOpenConfirmModal(tokenNumber) {
      this.chosenToken = tokenNumber;
      this.$refs.modal_confirm_ap.show();
    },

    /**
     *
     */
    async onBookAppointment() {

      this.bookingSuccessStatus = false;

      try {

        this.formAppointment.token_number = this.chosenToken;

        const params = {
          clinic_id: this.clinic.id,
          clinic_date: this.formAppointment.clinic_date,
          clinic_patient_id: this.clinicPatientId,
          token_number: this.formAppointment.token_number,
        };

        await this.$store.dispatch('publicPatient/bookAppointment', params);

        this.formAppointment.isChecked = false;

        this.$refs.modal_confirm_ap.close();
        this.$refs.modal_success.show();
        this.formAppointment.showTable = false;

        await this.$router.push({name: 'PagePatientHome'});

      } catch (e) {
        errorDialog({
          message: e.response.data.payload.error,
        });
      }
    }, /* on book appointment */


    async onCancelAppointment() {
      try {
        await this.$store.dispatch('publicPatient/cancelAppointment', this.formAppointment.existing.id);

        this.$refs.modal_cancel_ap.close();

        await this.$router.push({name: 'PagePatientHome'});
      } catch (e) {

      }
    },

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

};
</script>

<style scoped>

.token_number {
  font-size: 3em;
  padding: 1rem;
}

</style>
