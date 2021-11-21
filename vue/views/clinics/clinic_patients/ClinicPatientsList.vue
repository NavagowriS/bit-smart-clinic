<template>

  <div class="mb-5">

    <CardSection v-if="clinicPatientsList">
      <template v-slot:header>Patients in the clinic</template>

      <!-- charts -->
      <div class="patients_charts" v-show="patientsList.length > 0">

        <div class="row justify-content-end">
          <div class="col-3">
            <canvas id="chart_pie_patients_genders" height="150"></canvas>
          </div>
        </div>

      </div>
      <!-- end: charts -->


      <div class="mb-3" v-if="isSTAFF">
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal__add_patients">Add patients</button>
      </div>

      <table class="table table-bordered" v-if="patientsList.length > 0">
        <thead>
        <tr>
          <th>Patient</th>
          <th class="column_date">DoB</th>
          <th>Age</th>
          <th>Nic</th>
          <th>Address</th>
          <th class="column_date">Since</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in patientsList">
          <td>
            <router-link :to="_renderPatientDetailsLink(item)">{{ item.patient.full_name }}</router-link>
          </td>
          <td>{{ item.patient.dob }}</td>
          <td>{{ item.patient.age }}</td>
          <td>{{ item.patient.nic }}</td>
          <td>{{ item.patient.address }}</td>
          <td>{{ item.since }}</td>
        </tr>
        </tbody>
      </table>

      <div class="" v-else>
        No patents in the clinic. Start adding patients by clicking on the add patient button above.
      </div>

    </CardSection>

    <!-- modal: add patients to clinic -->
    <ModalWindow id="modal__add_patients" title="Add patients" size="lg">

      <div class="mb-3">
        <label class="form-label">Search for patients</label>
        <input type="text" class="form-control" v-model.trim="keyword">
      </div>

      <div class="text-center">
        <button class="btn btn-primary" @click="onSearch()">Search</button>
      </div>

      <hr>

      <table class="table table-sm table-bordered">
        <thead>
        <tr>
          <th>Full name</th>
          <th>Nic/Phone</th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in patientsResult">
          <td>
            <div>{{ item.full_name }}</div>
            <div>{{ item.dob }} ({{ renderAge( item.dob, item.age ) }})</div>
          </td>
          <td>
            <div>{{ item.nic }}</div>
            <div>{{ item.phone }}</div>
          </td>
          <td style="width: 20px">
            <button class="btn btn-sm btn-outline-primary" @click="onAddPatientToClinic(item)">
              <i class="bi bi-plus-lg"></i>
            </button>
          </td>
        </tr>
        </tbody>
      </table>

    </ModalWindow>
    <!-- modal: add patients to clinic -->

  </div>

</template>

<script>
import {drpDatePrompt, errorDialog} from '@/assets/libs/bs-dialog';
import CardSection from '@/components/CardSection';
import ModalWindow from '@/components/ModalWindow';
import {authMixins} from '@/mixins/authMixins.js';
import Chart from 'chart.js/auto';
import lodash from 'lodash';
import moment from 'moment';

export default {
  name: 'ClinicPatientsList',
  components: { ModalWindow, CardSection },

  mixins: [authMixins],

  props: {

    clinicId: {
      type: Number,
      required: true,
    },

  },

  data() {
    return {
      keyword: 'hello',
    };
  },
  /* ___data___ */

  computed: {


    /** @returns {ClinicPatientsList} */
    clinicPatientsList() {
      return this.$store.getters[ 'clinicPatients/getClinicPatientsList' ];
    },

    /** @returns {ClinicPatient[]} */
    patientsList() {
      return this.clinicPatientsList.patients;
    },

    /** @returns {Patient[]} */
    patientsResult() {
      return this.$store.getters[ 'patients/getPatients' ];
    },

    patientsStats() {

      const output = {
        genders: {
          male: 0,
          female: 0,
          other: 0,
        },
      };

      const patients = lodash.groupBy( this.patientsList, 'patient["gender"]' );

      if ( patients.hasOwnProperty( 'MALE' ) ) output.genders.male = patients[ 'MALE' ].length;
      if ( patients.hasOwnProperty( 'FEMALE' ) ) output.genders.female = patients[ 'FEMALE' ].length;
      if ( patients.hasOwnProperty( 'OTHER' ) ) output.genders.other = patients[ 'OTHER' ].length;

      return output;

    },


  },
  /* ___computed___ */


  async mounted() {
    try {

      await this.$store.dispatch( 'clinicPatients/fetchByClinic', this.clinicId );

    } catch ( e ) {
      errorDialog( { message: 'Failed to fetch clinic patients details' } );
    }

    this.$nextTick( () => {

      const chart = new Chart( 'chart_pie_patients_genders', {
        type: 'doughnut',
        data: {
          labels: [
            'Male',
            'Female',
            'Other',
          ],
          datasets: [{
            data: [this.patientsStats.genders.male, this.patientsStats.genders.female, this.patientsStats.genders.other],
            backgroundColor: [
              'rgb(54, 162, 235)',
              'rgb(255, 99, 132)',
              'rgb(255, 205, 86)',
            ],
            hoverOffset: 4,
          }],
        },
        options: {
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right',
            },
          },
        },
      } );

      // chart.canvas.parentNode[ 'style' ].height = '100px';


    } );

  },
  /* ___mounted___ */

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


    /*
     * Search for patients to add
     *
     */
    async onSearch() {
      try {
        await this.$store.dispatch( 'patients/search', this.keyword );
      } catch ( e ) {
        errorDialog( { message: 'Failed to get search results' } );
      }
    },


    /*
    * On click add patient to the clinic
    * */
    async onAddPatientToClinic( patient ) {


      drpDatePrompt(
          {
            message: 'Date patient added to the clinic',
            title: 'Enter date',
            defaultValue: moment().format( 'YYYY-MM-DD' ),
          },
          async ( data ) => {

            try {

              const params = {
                clinic_id: this.clinicId,
                patient_id: patient.id,
                since: data,
              };

              await this.$store.dispatch( 'clinicPatients/add', params );

              await this.$store.dispatch( 'clinicPatients/fetchByClinic', this.clinicId );

            } catch ( e ) {
              let errorMessage = `<p class="lead">${ e.response.data.payload.error }</p>`;
              errorDialog( { message: errorMessage } );
            }

          } );
    }, /* on add patient to the clinic */


    _renderPatientDetailsLink( clinicPatient ) {
      return {
        name: 'pageClinicPatientDetails',
        params: {
          clinicId: this.clinicId,
          clinicVisitPatientId: clinicPatient.id,
        },
      };
    },

  },
  /* ___methods___ */

};
</script>

<style scoped>


</style>
