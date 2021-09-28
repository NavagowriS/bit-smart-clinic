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
              
              {{ clinicPatient.patient.full_name }}'s {{ clinic.title }} Details
                </div>
              </div>
            </template>
                
                <div class="">

              <table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Date</th>
                  <th>Token No.</th>
                  <th class="text-end">Weight/Height</th>
                  <th class="text-end">SBP/DBP</th>
                  <th class="text-end">Sugar Level</th>
                  <th class="text-center">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in visitDetails" :key="item.id">
                  <td>
                    <router-link :to="_renderClinicVisitDetailsLink(item)">
                      {{ item.visit_date }}
                    </router-link>
                  </td>
                  <td>{{ item.token_number }}</td>
                  <td class="text-end">{{ item.param_weight }} kg / {{ item.param_height }} m</td>
                  <td class="text-end">{{ item.param_sbp }} / {{ item.param_dbp }} mmHg</td>
                  <td class="text-end">{{ item.param_blood_sugar }} mg/dL</td>
                  <td class="text-center">{{ item.status }}</td>
                </tr>
                </tbody>
              </table>

            </div>

          </CardSection>


        </div><!-- col -->
      </div><!-- row -->

    </div><!-- container -->
  </div><!-- template -->

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import TopNavigationBar from '@/components/TopNavigationBar.vue';
import {showErrorDialog} from '@/helpers/common.js';

export default {
  name: 'PageClinicPatientDetails',
  components: { CardSection, TopNavigationBar },

  data() {
    return {

      loaded: false,

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
    /** @returns {number} */
    clinicId() {
      return parseInt( this.$route.params[ 'clinicId' ] );
    },

    /** @returns {number} */
    clinicPatientId() {
      return parseInt( this.$route.params[ 'clinicVisitPatientId' ] );
    },

    /** @return {ClinicPatient} */
    clinicPatient() {
      return this.$store.getters[ 'clinicPatients/getClinicPatient' ];
    },

    /** @returns {Clinic} */
    clinic() {
      return this.clinicPatient.clinic;
    },

    /** @returns {ClinicVisitPatient[]} */
    visitDetails() {
      return this.clinicPatient.visit_details;
    },

  },


  async mounted() {

    try {

      await this.$store.dispatch( 'clinicPatients/fetch', this.clinicPatientId );

      this.loaded = true;

    } catch ( e ) {
      showErrorDialog( e.response, 'Failed to fetch patient details' );
    }

  },


  methods: {

    /**
     * Render clinic visit detail page url for individual patient
     *
     * @param visitPatient
     */
    _renderClinicVisitDetailsLink( visitPatient ) {
      return {
        name: 'pageClinicVisitDetails',
        params: {
          clinicId: this.clinicId,
          visitId: visitPatient.clinic_visit_id,
          clinicVisitPatientId: visitPatient.id,
        },
      };
    },

  },


};
</script>

<style scoped>

</style>
