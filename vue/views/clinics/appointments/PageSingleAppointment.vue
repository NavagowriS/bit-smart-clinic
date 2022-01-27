<template>

  <div class="">
    <TopNavigationBar/>

    <div class="container" v-if="loaded">

      <div class="row">
        <div class="col">

          <!-- CARD: Patient details -->
          <CardSection :no-title="true" class="mb-3">

            <div class="section__patient_details d-flex justify-content-between">

              <div class="d-flex gap-5">
                <div class="d-flex flex-column align-items-center gap-2 bg-dark text-white p-3 rounded">
                  <div class="lead">TOKEN</div>
                  <div class="token_number fw-bold">
                    {{ appointment.token_number }}
                  </div>
                </div>

                <div class="d-flex flex-column">
                  <h2 class="">{{ patient.full_name }}</h2>
                  <div class="lead">DoB: {{ patient.dob }}</div>
                  <div class="">Gender: {{ patient.gender }}</div>
                </div>

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
          <!-- CARD: patient details -->


          <div class="d-flex justify-content-between align-items-center text-white mb-3">

            <div class="d-flex gap-2">
              <button class="btn btn-primary" @click="onUpdateStatus('ACTIVE')">Active</button>
              <button class="btn btn-success" @click="onUpdateStatus('COMPLETED')">Completed</button>
              <button class="btn btn-warning" @click="onUpdateStatus('CANCELLED')">Cancelled</button>
              <button class="btn btn-danger" @click="onUpdateStatus('MISSED')">Missed</button>
            </div>

            <!-- right side -->


          </div>


          <!-- CARD: Visit details -->
          <CardSection class="mb-3">

            <template v-slot:header>Visit details</template>

            <div class="section__visit_details">

              <div class="d-flex flex-column">

                <div class="visit_status d-flex gap-2 mb-3">
                  <div class="lead fw-bold">STATUS:</div>
                  <div class="lead fw-bold" :style="statusColorClass">{{ appointment.status }}</div>
                </div>
                <!-- visit status -->

                <div class="visit_params d-flex gap-3">

                  <div class="mb-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" class="form-control" v-model="appointment.param_weight" :disabled="formDisabled">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Height (m)</label>
                    <input type="number" class="form-control" v-model="appointment.param_height" :disabled="formDisabled">
                  </div>


                  <div class="mb-3">
                    <label class="form-label">SBP (mmHg)</label>
                    <input type="number" class="form-control" v-model="appointment.param_sbp" :disabled="formDisabled">
                  </div>


                  <div class="mb-3">
                    <label class="form-label">DBP (mmHg)</label>
                    <input type="number" class="form-control" v-model="appointment.param_dbp" :disabled="formDisabled">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Sugar Level (mg/dL)</label>
                    <input type="number" class="form-control" v-model="appointment.param_blood_sugar" :disabled="formDisabled">
                  </div>

                </div>
                <!-- visit params -->

                <div class="visit_doctor_remarks">
                  <div class="mb-3">
                    <label class="form-label">Doctor remarks</label>
                    <textarea rows="10" class="form-control" v-model="appointment.doctor_remarks" :disabled="formDisabled"></textarea>
                  </div>
                </div>

              </div>

              <div class="d-flex justify-content-between">

                <!-- left -->


                <!-- center -->
                <div class="">
                  <button class="btn btn-primary" @click="onUpdate()"><i class="bi bi-check2"></i> Update</button>
                </div>

                <!-- right -->
                <div class="">
                  <button class="btn btn-danger" @click="$refs.modal_remove_appointment.show()">
                    <i class="bi bi-trash-fill"></i> Remove
                  </button>
                </div>

              </div>


            </div>
            <!-- section: visit details -->

          </CardSection>
          <!-- CARD: Visit details -->

        </div><!-- col -->
      </div><!-- row -->


      <!-- section: prescription -->
      <AddPrescription :appointment="appointment"/>

      <div style="margin-bottom: 20rem"></div>


    </div><!-- container -->
    <div class="text-white text-center mt-5" v-else>
      <h3>Loading...</h3>

    </div>


    <!-- MODAL: Confirm delete visit detail -->
    <ModalWindow id="modal_remove_appointment" ref="modal_remove_appointment" title="Confirm removal">

      <div class="text-center">
        <p>Are you sure to remove the appointment details?</p>
        <p class="text-danger fw-bold">This action is irreversible</p>

        <button class="btn btn-danger" @click="onRemove()">
          Confirm Removal
        </button>
      </div>

    </ModalWindow>

  </div>

</template>

<script>
import {successDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';
import ModalWindow from '@/components/ModalWindow.vue';
import TopNavigationBar from '@/components/TopNavigationBar.vue';
import {showErrorDialog} from '@/helpers/common.js';
import AddPrescription from '@/views/clinics/appointments/components/AddPrescription.vue';
import _ from 'lodash';

export default {
  name: 'PageSingleAppointment',
  components: { AddPrescription, ModalWindow, CardSection, TopNavigationBar },

  data() {
    return {

      loaded: false,

      /* status data */
      status: {
        colorCodes: {
          'ACTIVE': '#3498db',
          'COMPLETED': '#27ae60',
          'CANCELLED': '#f1c40f',
          'MISSED': '#e74c3c',
        },
      },

    };
  },

  computed: {
    clinicId() {
      return _.toNumber( this.$route.params[ 'clinicId' ] );
    },

    appointmentId() {
      return _.toNumber( this.$route.params[ 'appointmentId' ] );
    },

    backLink() {
      return {
        name: 'pageListAppointments', params: { id: this.clinicId },
      };
    },

    /** @returns {Clinic} */
    clinic() {
      return this.$store.getters[ 'clinics/getClinic' ];
    },

    /** @returns {ClinicAppointment} */
    appointment() {
      return this.$store.getters[ 'clinicAppointments/getClinicAppointment' ];
    },

    /** @returns {Patient} */
    patient() {
      return this.appointment.clinic_patient.patient;
    },

    statusColorClass() {
      return `color: ${ this.status.colorCodes[ this.appointment.status ] }`;
    },

    formDisabled() {
      return this.appointment.status !== 'ACTIVE';
    },

  },

  async mounted() {

    try {

      // fetch clinic details
      await this.$store.dispatch( 'clinics/fetch', this.clinicId );

      await this.$store.dispatch( 'clinicAppointments/fetch', this.appointmentId );

      this.loaded = true;

    } catch ( e ) {

    }

  },

  methods: {

    /**
     * Event handler: Update clinic visit patient details
     */
    async onUpdate() {

      try {

        const params = {
          id: this.appointment.id,
          status: this.appointment.status,
          param_weight: this.appointment.param_weight,
          param_height: this.appointment.param_height,
          param_sbp: this.appointment.param_sbp,
          param_dbp: this.appointment.param_dbp,
          param_blood_sugar: this.appointment.param_blood_sugar,
          doctor_remarks: this.appointment.doctor_remarks,
        };

        await this.$store.dispatch( 'clinicAppointments/update', params );

        successDialog( {
          message: 'Details updated successfully',
        } );

      } catch ( e ) {
        showErrorDialog( e.response, 'Failed to update visit details' );
      }

    },

    /**
     * Event handler: Update visit status
     * @param status
     */
    async onUpdateStatus( status ) {

      try {

        const params = {
          id: this.appointment.id,
          status: status,
        };

        await this.$store.dispatch( 'clinicAppointments/updateStatus', params );

        successDialog( { message: 'Status updated!' } );

        await this.$store.dispatch( 'clinicAppointments/fetch', this.appointmentId );


      } catch ( e ) {
        showErrorDialog( e.response, 'Failed to update the status' );
      }

    },


    /**
     * Delete the current patient's clinic visit detail
     */
    async onRemove() {

      try {

        await this.$store.dispatch( 'clinicAppointments/remove', this.appointment.id );

        this.$refs.modal_remove_appointment.close();

        await this.$router.push( {
          name: 'pageListAppointments',
          params: {
            clinicId: this.$route.params.clinicId,
          },
        } );

      } catch ( e ) {
        showErrorDialog( e.response, 'Failed to remove the visit detail' );
      }

    },

  },

};
</script>

<style scoped>

.token_number {
  font-size: 5rem;
  line-height: 3.5rem;
}
</style>
