<template>

  <div>

    <CardSection class="mb-3">
      <template v-slot:header>Clinic visits</template>

      <div class="mb-3">

        <div class="mb-3" v-if="isSTAFF">
          <button class="btn btn-sm btn-primary" data-bs-target="#modal__add_clinic_visit" data-bs-toggle="modal">Add new visit</button>
        </div>

        <h4>Latest visits</h4>
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>Clinic Date</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="item in latestVisits" :key="item.id">
            <td>
              <router-link :to="{name:'pageClinicVisitManage', params: {clinicId: clinicId, visitId: item.id}}">
                {{ item.visit_date }}
              </router-link>
            </td>
          </tr>
          </tbody>
        </table>


      </div>

    </CardSection>

    <ModalWindow id="modal__add_clinic_visit" title="Add new visit" ref="mdl_clinic_visit">
      <div class="mb-3">
        <label class="form-label">Date</label>
        <DateField v-model="clinicVisitToAdd.visit_date"/>
      </div>

      <div class="text-center">
        <button class="btn btn-secondary" @click="onAddVisit()">Add</button>
      </div>

    </ModalWindow>

  </div><!-- template -->

</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog';
import CardSection from '@/components/CardSection';
import DateField from '@/components/fields/DateField';
import ModalWindow from '@/components/ModalWindow';
import {authMixins} from '@/mixins/authMixins.js';
import moment from 'moment';

const _ = require( 'lodash' );

export default {
  name: 'AddClinicVisit',
  components: { DateField, ModalWindow, CardSection },
  mixins: [authMixins],

  props: {

    clinicId: {
      type: Number,
      required: true,
    },

  },

  data() {
    return {

      clinicVisitToAdd: {
        visit_date: moment().format( 'YYYY-MM-DD' ),
      },


    };
  },

  computed: {

    /** @returns {ClinicVisit[]} */
    clinicVisits() {
      return this.$store.getters[ 'clinicVisits/getClinicVisits' ].visits;
    },


    latestVisits() {
      return _.slice( this.clinicVisits, 0, 7 );
    },

  },
  /* __computed__ */

  async mounted() {

    try {

      await this.$store.dispatch( 'clinicVisits/fetchByClinic', this.clinicId );

    } catch ( e ) {
      errorDialog( {
        message: 'Failed to fetch clinic visit details',
      } );
    }

  },
  /* __mounted__ */

  methods: {

    async onAddVisit() {


      try {

        const params = {
          clinic_id: this.clinicId,
          visit_date: this.clinicVisitToAdd.visit_date,
        };

        await this.$store.dispatch( 'clinicVisits/create', params );

      } catch ( e ) {
        errorDialog( {
          message: 'Error',
        } );
      }

      await this.$store.dispatch( 'clinicVisits/fetchByClinic', this.clinicId );

      this.$refs.mdl_clinic_visit.close();

    },

  },

};
</script>

<style scoped>

</style>
