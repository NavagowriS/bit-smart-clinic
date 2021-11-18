<template>

  <div>
    <TopNavigationBar/>


    <div class="container" v-if="loaded">

      <!-- section: clinic details -->
      <div class="row">
        <div class="col">
          <CardSection class="mb-3" v-if="selectedClinic" :no-title="true">

            <div class="d-flex justify-content-between">

              <div class="left_side">
                <h1>{{ selectedClinic.title }}</h1>
                <h4>Doctor <i class="bi bi-arrow-right"></i> {{ selectedClinic.doctor_in_charge.name }}</h4>
              </div>

              <div class="right_side" v-if="isSTAFF">
                <button class="btn btn-secondary" @click="$refs.modal_update_clinic_details.show()">
                  <i class="bi bi-pencil"></i> Edit
                </button>
              </div>

            </div>

          </CardSection>
        </div><!-- col -->
      </div><!-- row -->

      <!-- section: add clinic visits -->
      <div class="row mb-3">
        <div class="col">
          <router-link class="btn btn-success btn-lg" :to="{name: 'pageListAppointments', params: {clinicId: selectedClinic.id}}">Appointments</router-link>
        </div>
      </div>

      <!-- section: clinic patients list -->
      <div class="row">
        <div class="col">
          <ClinicPatientsList :clinic-id="clinicId"/>
        </div>
      </div><!-- row -->


      <!-- MODAL: Update Clinic Details -->
      <ModalWindow id="modal_update_clinic_details" ref="modal_update_clinic_details">

        <div class="row">
          <div class="col">

            <div class="mb-3">
              <label class="form-label">Clinic Title</label>
              <input type="text" class="form-control" v-model.trim="selectedClinic.title">
            </div>

          </div>
          <div class="col">

            <div class="mb-3">
              <label class="form-label">Doctor in-charge</label>
              <select class="form-select" v-model="selectedClinic.doctor_in_charge_id">
                <option v-for="item in doctors" :value="item.id">{{ item.name }}</option>
              </select>
            </div>

          </div>

          <div class="text-center">
            <button class="btn btn-primary" @click="onUpdate()">
              <i class="bi bi-check2"></i> Update
            </button>
          </div>

        </div>

      </ModalWindow>


    </div><!-- container -->

  </div><!-- template -->

</template>

<script>
import {errorDialog, successDialog} from '@/assets/libs/bs-dialog';
import CardSection from '@/components/CardSection';
import ModalWindow from '@/components/ModalWindow';
import TopNavigationBar from '@/components/TopNavigationBar';
import {showErrorDialog} from '@/helpers/common.js';

import {authMixins} from '@/mixins/authMixins.js';
import ClinicPatientsList from '@/views/clinics/clinic_patients/ClinicPatientsList';
import AddClinicVisit from '@/views/clinics/clinic_visits/AddClinicVisit';

let lodash = require( 'lodash' );
let moment = require( 'moment' );

export default {
  name: 'PageClinicManage',
  components: { AddClinicVisit, CardSection, ModalWindow, ClinicPatientsList, TopNavigationBar },
  mixins: [authMixins],

  data() {
    return {

      loaded: false,

      keyword: 'hello',
    };
  },


  computed: {
    clinicId() {
      return lodash.toNumber( this.$route.params.id );
    },

    doctors() {
      return this.$store.getters[ 'doctors/getDoctors' ];
    },

    /** @returns {Clinic} */
    selectedClinic() {
      return this.$store.getters[ 'clinics/getClinic' ];
    },


  },


  async mounted() {

    try {

      await this.$store.dispatch( 'doctors/fetchAll' );

      await this.$store.dispatch( 'clinics/fetch', this.clinicId );

      this.loaded = true;

    } catch ( e ) {
      errorDialog( 'Failed to load data' );
    }

  },


  methods: {

    renderAge( dob, age ) {

      if ( !lodash.isNull( age ) ) return age;
      if ( lodash.isNull( dob ) ) return '-';

      const currentDate = moment();
      const dateOfBirth = moment( dob );
      const diff = currentDate.diff( moment( dateOfBirth ) );
      const duration = moment.duration( diff );

      return Math.round( duration.asYears() );
    },


    async onUpdate() {

      try {

        const params = {
          id: this.selectedClinic.id,
          title: this.selectedClinic.title,
          doctor_in_charge_id: this.selectedClinic.doctor_in_charge_id,
        };

        await this.$store.dispatch( 'clinics/update', params );

        this.$refs.modal_update_clinic_details.close();

        await this.$store.dispatch( 'clinics/fetch', this.clinicId );

        successDialog( { message: 'Clinic details updated' } );

      } catch ( e ) {
        showErrorDialog( e.response, 'Failed to update clinic details' );
      }

    },


  },
};
</script>

<style scoped>

</style>
