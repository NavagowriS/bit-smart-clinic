<template>

  <div>

    <TopNavigationBar/>

    <div class="container">


      <div class="row mb-3">
        <div class="col">

          <div class="d-flex gap-2">

            <div class="flex-grow-1">
              <input type="text" class=form-control placeholder="Search for patients..." v-model="searchKeyword">
            </div>

            <div class="">
              <button class="btn btn-primary" @click="onSearchPatients()">Search</button>
            </div>

          </div>

        </div>
      </div><!-- row -->

      <div class="row">

        <div class="col">

          <CardSection class="mb-5">
            <template v-slot:header>Patients List</template>

            <router-link class="btn btn-primary btn-sm mb-3" to="/patients/create">Create new patient profile</router-link>

            <div v-if="! isEmptyPatientsList">
              <table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Full name</th>
                  <th>Gender</th>
                  <th class="text-center">DoB</th>
                  <th class="text-center">Age</th>
                  <th>Phone</th>
                  <th>NIC</th>
                  <th>Address</th>
                  <th>Guardian</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="patient in patients" :key="patient.id">
                  <td>
                    <router-link :to="{name: 'PagePatientEdit', params: {id: patient.id}}">{{ patient.full_name }}</router-link>
                  </td>
                  <td>{{ patient.gender | capitalize }}</td>
                  <td class="text-center">{{ patient.dob | dashNull }}</td>
                  <td class="text-center">{{ renderAge( patient.dob, patient.age ) }}</td>
                  <td>{{ patient.phone }}</td>
                  <td>{{ patient.nic }}</td>
                  <td style="white-space: pre-line">{{ patient.address }}</td>
                  <td>{{ patient.guardian_name }}</td>
                </tr>

                </tbody>
              </table>
            </div>

            <div v-else>
              <p>There are no patients in the system</p>
            </div>


          </CardSection>


        </div><!-- col -->

      </div><!-- row -->

    </div><!-- container -->


  </div><!-- template -->

</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection';
import TopNavigationBar from '@/components/TopNavigationBar';
import moment from 'moment';

const _ = require( 'lodash' );

export default {
  name: 'PagePatientsList',
  components: { CardSection, TopNavigationBar },

  data() {
    return {

      searchKeyword: '',

    };
  },

  computed: {

    patients() {
      return this.$store.getters[ 'patients/getPatients' ];
    },
    isEmptyPatientsList() {
      return _.isEmpty( this.patients );
    },

  },

  async mounted() {

    try {

      await this.$store.dispatch( 'patients/fetchAll' );

    } catch ( e ) {
      alert( 'Failed to get patients' );
    }

  },

  filters: {

    dashNull( value ) {
      if ( _.isNull( value ) ) return '-';
      else return value;
    },

    capitalize( value ) {
      return _.capitalize( value );
    },

  },

  methods: {

    renderAge( dob, age ) {

      if ( !_.isNull( age ) ) return age;
      if ( _.isNull( dob ) ) return '-';

      const currentDate = moment();
      const dateOfBirth = moment( dob );
      const diff = currentDate.diff( moment( dateOfBirth ) );
      const duration = moment.duration( diff );

      return Math.round( duration.asYears() );
    }, /* renderAge */


    async onSearchPatients() {

      try {

        await this.$store.dispatch( 'patients/search', this.searchKeyword );

      } catch ( e ) {
        errorDialog( {
          message: 'Failed to fetch search result',
        } );
      }

    },


  },

};
</script>

<style scoped>

</style>
