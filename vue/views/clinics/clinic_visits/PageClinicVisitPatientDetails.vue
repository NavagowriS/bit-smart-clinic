<template>

  <div class="h-100">

    <TopNavigationBar/>


    <div class="container" v-if="loaded">

      <div class="row">

        <div class="col">

          <!-- CARD: Patient details -->
          <CardSection :no-title="true" class="mb-3">

              <div class="left_side">
                <router-link :to="backLink" class="btn btn-secondary">
                  <i class="bi bi-arrow-left"></i>
                </router-link>
              </div>
            <div class="section__patient_details d-flex justify-content-between">

              <div class="d-flex flex-column">
                <h2 class="">{{ patient.full_name }}</h2>
                <div class="lead">NIC: {{ patient.nic }}</div>
                <div class="lead">DoB: {{ patient.dob }} ({{ patient.age }})</div>
                <div class="">Gender: {{ patient.gender }}</div>
              </div>

              <div class="d-flex flex-column">
                <h4>Phone: {{ patient.phone }}</h4>
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
            <div class="d-flex flex-column align-items-center gap-2">
              <div class="lead">TOKEN</div>
              <div class="token_number fw-bold">
                {{ clinicVisitPatient.token_number }}
              </div>
            </div>

          </div>

          <!-- CARD: Visit details -->
          <CardSection class="mb-5">

            <template v-slot:header>Visit details</template>

            <div class="section__visit_details">

              <div class="d-flex flex-column">

                <div class="visit_status d-flex gap-2 mb-3">
                  <div class="lead fw-bold">STATUS:</div>
                  <div class="lead fw-bold" :style="statusColorClass">{{ clinicVisitPatient.status }}</div>
                </div>
                <!-- visit status -->

                <div class="visit_params d-flex gap-3">

                  <div class="mb-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" class="form-control" v-model="clinicVisitPatient.param_weight" :disabled="formDisabled">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Height (m)</label>
                    <input type="number" class="form-control" v-model="clinicVisitPatient.param_height" :disabled="formDisabled">
                  </div>


                  <div class="mb-3">
                    <label class="form-label">SBP (mmHg)</label>
                    <input type="number" class="form-control" v-model="clinicVisitPatient.param_sbp" :disabled="formDisabled">
                  </div>


                  <div class="mb-3">
                    <label class="form-label">DBP (mmHg)</label>
                    <input type="number" class="form-control" v-model="clinicVisitPatient.param_dbp" :disabled="formDisabled">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Sugar Level (mg/dL)</label>
                    <input type="number" class="form-control" v-model="clinicVisitPatient.param_blood_sugar" :disabled="formDisabled">
                  </div>

                </div>
                <!-- visit params -->

                <div class="visit_doctor_remarks">
                  <div class="mb-3">
                    <label class="form-label">Doctor remarks</label>
                    <textarea rows="20" class="form-control" v-model="clinicVisitPatient.doctor_remarks" :disabled="formDisabled"></textarea>
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
                  <button class="btn btn-danger" @click="$refs.modal_confirm_remove_patient_visit.show()">
                    <i class="bi bi-trash-fill"></i> Remove
                  </button>
                </div>

              </div>


            </div>
            <!-- section: visit details -->

          </CardSection>
          <!-- CARD: Visit details -->

        </div>

      </div>
    </div><!-- container -->

    <div class="text-white text-center mt-5" v-else>
      <h3>Loading...</h3>

    </div>

    <!-- MODAL: Confirm delete visit detail -->
    <ModalWindow id="modal_confirm_remove_patient_visit" ref="modal_confirm_remove_patient_visit" title="Confirm removal">

      <div class="text-center">
        <p>Are you sure to remove the visit details?</p>
        <p class="text-danger fw-bold">This action is irreversible</p>

        <button class="btn btn-danger" @click="onRemove()">
          Confirm Removal</button>
      </div>

    </ModalWindow>


  </div><!-- template -->

</template>

<script>
import {successDialog} from '@/assets/libs/bs-dialog';
import CardSection from '@/components/CardSection';
import ModalWindow from '@/components/ModalWindow.vue';
import TopNavigationBar from '@/components/TopNavigationBar';
import {showErrorDialog} from '@/helpers/common';

export default {
  name: 'PageClinicVisitPatientDetails',
  components: { ModalWindow, CardSection, TopNavigationBar },

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

    backLink() {
      return {
        name: 'pageClinicVisitManage', params: { id: this.clinicId },
      };
    },
    /** @returns {number} */
    clinicVisitPatientId() {
      return parseInt( this.$route.params[ 'clinicVisitPatientId' ] );
    },

    /** @returns {ClinicVisitPatient} */
    clinicVisitPatient() {
      return this.$store.getters[ 'clinicVisits/getClinicVisitPatient' ];
    },

    /** @returns {Patient} */
    patient() {
      return this.clinicVisitPatient.clinic_patient.patient;
    },

    statusColorClass() {
      return `color: ${ this.status.colorCodes[ this.clinicVisitPatient.status ] }`;
    },


    formDisabled() {
      return this.clinicVisitPatient.status !== 'ACTIVE';
    },

  },

  async mounted() {

    try {

      /* fetch clinic patient details */
      await this.$store.dispatch( 'clinicVisits/fetchPatientByClinicVisit', this.clinicVisitPatientId );

      this.loaded = true;

    } catch ( e ) {
      showErrorDialog( e.response, 'Failed to fetch patient\'s visit details' );
    }

  },

  methods: {

    /**
     * Event handler: Update clinic visit patient details
     */
    async onUpdate() {

      try {

        const params = {
          id: this.clinicVisitPatient.id,
          status: this.clinicVisitPatient.status,
          param_weight: this.clinicVisitPatient.param_weight,
          param_height: this.clinicVisitPatient.param_height,
          param_sbp: this.clinicVisitPatient.param_sbp,
          param_dbp: this.clinicVisitPatient.param_dbp,
          param_blood_sugar: this.clinicVisitPatient.param_blood_sugar,
          doctor_remarks: this.clinicVisitPatient.doctor_remarks,
        };

        await this.$store.dispatch( 'clinicVisits/updateVisitPatient', params );

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
          id: this.clinicVisitPatient.id,
          status: status,
        };

        await this.$store.dispatch( 'clinicVisits/updateVisitPatientStatus', params );

        successDialog( { message: 'Status updated!' } );

        /* fetch clinic visit patient details */
        await this.$store.dispatch( 'clinicVisits/fetchPatientByClinicVisit', this.clinicVisitPatientId );


      } catch ( e ) {
        showErrorDialog( e.response, 'Failed to update the status' );
      }

    },

    /**
     * Delete the current patient's clinic visit detail
     */
    async onRemove() {

      try {

        await this.$store.dispatch( 'clinicVisits/removePatient', this.clinicVisitPatientId );

        this.$refs.modal_confirm_remove_patient_visit.close();

        await this.$router.push( {
          name: 'pageClinicVisitManage',
          params: {
            clinicId: this.$route.params.clinicId,
            visitId: this.$route.params.visitId,
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
