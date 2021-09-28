<template>

  <div>

    <TopNavigationBar/>

    <div class="container" v-if="loaded">

      <div class="row">
        <div class="col">

          <CardSection>
            <template v-slot:header>
              <div class="d-flex gap-2 align-items-center mb-2">
                <router-link :to="backLink" class="btn btn-secondary">
                  <i class="bi bi-arrow-left"></i>
                </router-link>
                <div class="">
                  Clinic visit on {{ visit.visit_date }} for {{ clinic.title }}
                </div>
              </div>
            </template>

            <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modal__add_patient_to_visit" v-if="isSTAFF">
              Add patients for the visit
            </button>

            <table class="table table-bordered">
              <thead>
              <tr>
                <th>Token</th>
                <th>Patient name</th>
                <th>Gender</th>
                <th>NIC</th>
                <th class="text-center">Status</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="item in visitPatientsList" :key="item.id">
                <td>{{ item.token_number }}</td>
                <td>
                  <router-link :to="renderClinicVisitDetailsLink(item)">
                    {{ item.clinic_patient.patient.full_name }}
                  </router-link>
                </td>
                <td>{{ item.clinic_patient.patient.gender }}</td>
                <td>{{ item.clinic_patient.patient.nic }}</td>
                <td class="fw-bold text-center" :style="renderStatusColor(item.status)">{{ item.status }}</td>
              </tr>
              </tbody>
            </table>


          </CardSection>


          <!--
           # MODAL WINDOWS ---------------------------------------------------------------
           -->

          <!-- MODAL: add patient -->
          <ModalWindow id="modal__add_patient_to_visit" size="lg" title="Add patients into this visit">

            <table class="table table-bordered" v-if="clinicPatientsList">
              <thead>
              <tr>
                <th>Full name</th>
                <th>DoB</th>
                <th>NIC</th>
                <th>Since</th>
                <th>-</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="item in clinicPatientsList.patients" :key="item.id">
                <td>{{ item.patient.full_name }}</td>
                <td>{{ item.patient.dob }}</td>
                <td>{{ item.patient.nic }}</td>
                <td>{{ item.since }}</td>
                <td>
                  <button class="btn btn-sm btn-primary" @click="onAddPatient(item)">
                    <i class="bi bi-person-plus-fill"></i>
                  </button>
                </td>
              </tr>
              </tbody>
            </table>

          </ModalWindow>


          <!-- MODAL: confirm remove patient -->
          <ModalWindow id="modal__remove_clinic_visit_patient" ref="modal__remove_patient" title="Confirm removal">

            <p>Type 'remove' in the text box to delete the patient record.</p>
            <div class="mb-3">
              <input type="text" class="form-control" v-model="removePatient.textBoxDeleteConfirm">
            </div>

            <div class="text-center">
              <button class="btn btn-danger"
                      :disabled="removePatient.textBoxDeleteConfirm !== 'remove'"
                      @click="onRemovePatient()">
                Confirm Removal
              </button>
            </div>

          </ModalWindow>

        </div><!-- col -->
      </div><!-- row -->

    </div><!-- container -->

  </div><!-- template -->

</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog';
import CardSection from '@/components/CardSection';
import ModalWindow from '@/components/ModalWindow';
import TopNavigationBar from '@/components/TopNavigationBar';
import {showErrorDialog} from '@/helpers/common';
import {authMixins} from '@/mixins/authMixins.js';

let _ = require( 'lodash' );

export default {
  name: 'PageClinicVisitManage',
  components: { ModalWindow, CardSection, TopNavigationBar },

  mixins: [authMixins],

  data() {
    return {

      loaded: false,

      /* details about remove patients & modal window */
      removePatient: {
        patientToRemove: {},
        textBoxDeleteConfirm: '',
      },

      statusColors: {
        'ACTIVE': 'rgba(13,110,253,0.4)',
        'COMPLETED': 'rgba(25,135,84,0.4)',
        'CANCELLED': 'rgba(255,193,7,0.4)',
        'MISSED': 'rgba(220,53,69,0.4)',

      },


    };
  },

  computed: {

    clinicId() {
      return _.toNumber( this.$route.params.clinicId );
    },

    visitId() {
      return _.toNumber( this.$route.params.visitId );
    },

    backLink() {
      return {
        name: 'pageClinic', params: { id: this.clinicId },
      };
    },

    /** @returns {Clinic} */
    clinic() {
      return this.$store.getters[ 'clinics/getClinic' ];
    },

    /** @returns {ClinicVisit} */
    visit() {
      return this.$store.getters[ 'clinicVisits/getSelectedClinicVisit' ];
    },

    /** @returns {ClinicPatientsList} */
    clinicPatientsList() {
      return this.$store.getters[ 'clinicPatients/getClinicPatientsList' ];
    },

    /** @returns {ClinicVisitPatient[]} */
    visitPatientsList() {
      return this.$store.getters[ 'clinicVisits/getClinicVisitPatients' ];
    },

  },

  async mounted() {

    try {

      // fetch clinic details
      await this.$store.dispatch( 'clinics/fetch', this.clinicId );

      // fetch visit details
      await this.$store.dispatch( 'clinicVisits/fetch', this.visitId );

      // fetch all clinic patients
      await this.$store.dispatch( 'clinicPatients/fetchByClinic', this.clinicId );

      // fetch visit patients
      await this.$store.dispatch( 'clinicVisits/fetchPatientsByClinicVisit', this.visitId );

      this.loaded = true;

    } catch ( e ) {
      errorDialog( {
        message: 'Failed to fetch data',
      } );
    }

  },

  methods: {

    /**
     * Render clinic visit detail page for individual patient
     *
     * @param visitPatient
     */
    renderClinicVisitDetailsLink( visitPatient ) {
      return {
        name: 'pageClinicVisitDetails',
        params: {
          clinicId: this.clinicId,
          visitId: this.visitId,
          clinicVisitPatientId: visitPatient.id,
        },
      };
    },

    /**
     * Render status table td area color
     * @param status
     */
    renderStatusColor( status ) {
      return `background-color: ${ this.statusColors[ status ] } !important`;
    },


    /**
     * Event Listener: Add new patient into the visit
     *
     * @param clinicPatient
     */
    async onAddPatient( clinicPatient ) {

      try {

        const params = {
          clinic_patient_id: clinicPatient.id,
          clinic_visit_id: this.visitId,
        };

        await this.$store.dispatch( 'clinicVisits/addPatient', params );
        await this.$store.dispatch( 'clinicVisits/fetchPatientsByClinicVisit', this.visitId );

      } catch ( e ) {

        showErrorDialog( e.response, 'Failed to add patient to the visit' );
      }

    }, /* on add patient */


    /**
     * Event Listener: Show remove conformation modal window
     *
     * @param visitPatient
     */
    onShowRemoveConformation( visitPatient ) {

      this.removePatient.patientToRemove = visitPatient;
      this.$refs.modal__remove_patient.show();

    },

    /**
     * Event Listener: Remove patient from the current visit
     *
     */
    async onRemovePatient() {
      try {

        await this.$store.dispatch( 'clinicVisits/removePatient', this.removePatient.patientToRemove.id );

        await this.$store.dispatch( 'clinicVisits/fetchPatientsByClinicVisit', this.visitId );

        /* close the remove conform modal window and reset patientToRemove */
        this.$refs.modal__remove_patient.close();
        this.removePatient.patientToRemove = {};
        this.removePatient.textBoxDeleteConfirm = '';

      } catch ( e ) {
        showErrorDialog( e.response, 'Failed to remove patient from the visit' );
      }
    },

  },

};
</script>

<style scoped>

</style>
