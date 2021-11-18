<template>

  <div>

    <TopNavigationBar/>


    <div class="container">


      <div class="row mb-3">

        <div class="col">
          <!-- search and filter for the appointments -->
          <CardSection>

            <template v-slot:header>Filter appointments</template>

            <div class="row justify-content-center">

              <div class="col-4">
                <div class="mb-3">
                  <label class="form-label">Date range</label>
                  <DateRangeField v-model="formFilterAppointments.dateRange"/>
                </div>
              </div>

              <div class="col-4">
                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select class="form-select" v-model="formFilterAppointments.statusFilter">
                    <option v-for="(item, key) in statusFilters" :value="key">{{ item }}</option>
                  </select>
                </div>
              </div>

            </div><!-- row -->

            <div class="text-center">
              <button class="btn btn-primary" @click="onSearch()">Search</button>
            </div>


          </CardSection>

        </div><!-- col -->

      </div><!-- row -->

      <div class="row">

        <div class="col">


          <CardSection class="mb-3" v-for="(items, date) in appointmentsByDates" :key="date">

            <template v-slot:header>Appointments on {{ date }}</template>

            <div class="mb-3">

              <table class="table table-hover table-sm table-bordered">
                <thead>
                <tr>
                  <th style="width: 50px">Token</th>
                  <th>Patient Name</th>
                  <th style="width: 200px" class="text-center">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in items" :key="item.id">
                  <td>
                    <router-link
                        class="btn btn-sm p-0 px-3 btn-link"
                        :to="{name: 'pageSingleAppointment', params: {clinicId: clinicId, appointmentId: item.id}}">
                      {{ item.token_number }}
                    </router-link>
                  </td>
                  <td>{{ item.clinic_patient.patient.full_name }}</td>
                  <td class="text-center">{{ item.status }}</td>
                </tr>
                </tbody>
              </table>

            </div>

          </CardSection>

        </div><!-- col -->

      </div><!-- row -->

      <div class="row justify-content-center">
        <div class="col">
          <div class="alert alert-warning" v-if="appointments && appointments.length === 0">

            <div class="text-center" style="font-size: 3em">
              <i class="bi bi-exclamation-diamond-fill"></i>
            </div>

            <div class="lead text-center">
              There are no appointments with
              {{ formFilterAppointments.statusFilter }} status between {{ formFilterAppointments.dateRange.startDate }} and
              {{ formFilterAppointments.dateRange.endDate }}
            </div>
          </div>
        </div>
      </div>

    </div><!-- container -->


  </div>


</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';
import DateRangeField from '@/components/fields/DateRangeField.vue';
import TopNavigationBar from '@/components/TopNavigationBar.vue';
import moment from 'moment';

const _ = require( 'lodash' );

export default {
  name: 'PageListAppointments',
  components: { DateRangeField, TopNavigationBar, CardSection },

  data() {
    return {

      statusFilters: {
        'ALL': 'All',
        'ACTIVE': 'Active',
        'COMPLETED': 'Completed',
        'CANCELLED': 'Cancelled',
        'MISSED': 'Missed',
      },

      formFilterAppointments: {
        dateRange: {
          startDate: moment().format( 'YYYY-MM-DD' ),
          endDate: moment().add( 2, 'days' ).format( 'YYYY-MM-DD' ),
        },
        statusFilter: 'ALL',
      },


    };
  },

  computed: {

    clinicId() {
      return this.$route.params[ 'clinicId' ];
    },

    /** @returns {ClinicAppointment[]} */
    appointments() {
      return this.$store.getters[ 'clinicAppointments/getClinicAppointments' ][ 'appointments' ];
    },

    /** @returns {Object.<string, ClinicAppointment[]>} */
    appointmentsByDates() {
      return _.groupBy( this.appointments, 'clinic_date' );
    },

  },

  async mounted() {

    try {

      const params = {
        clinic_id: this.clinicId,
        start_date: this.formFilterAppointments.dateRange.startDate,
        end_date: this.formFilterAppointments.dateRange.endDate,
        status: this.formFilterAppointments.statusFilter,
      };

      await this.$store.dispatch( 'clinicAppointments/fetchByClinicBetweenDates', params );

    } catch ( e ) {
      errorDialog( {
        message: 'Failed to fetch appointment data',
      } );
    }

  },

  methods: {

    async onSearch() {

      try {

        const params = {
          clinic_id: this.clinicId,
          start_date: this.formFilterAppointments.dateRange.startDate,
          end_date: this.formFilterAppointments.dateRange.endDate,
          status: this.formFilterAppointments.statusFilter,
        };

        await this.$store.dispatch( 'clinicAppointments/fetchByClinicBetweenDates', params );

      } catch ( e ) {
        errorDialog( {
          message: 'Failed to fetch appointment data',
        } );
      }

    }, /* onSearch */


  },


};
</script>

<style scoped>

</style>
