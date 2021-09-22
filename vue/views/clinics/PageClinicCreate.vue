<template>

  <div>

    <TopNavigationBar/>

    <div class="container">

      <div class="row justify-content-center">

        <div class="col-12 col-md-6">

          <div class="card">
            <div class="card-body">

              <div class="card-title">Create a new clinic</div>

              <div id="form-create-clinic">

                <div class="mb-3">
                  <label class="form-label">Clinic title</label>
                  <input type="text" class="form-control" v-model.trim="clinicToAdd.title">
                </div>

                <div class="mb-3">
                  <label class="form-label">Doctor in-charge</label>
                  <select class="form-select" v-model.number="clinicToAdd.doctor_in_charge_id">
                    <option value="0" disabled>CHOOSE ONE</option>
                    <option v-for="item in doctors" :value="item.id">{{ item.name }} ({{ item.doctor_speciality.speciality }})</option>
                  </select>
                </div>


                <div class="text-end">
                  <button class="btn btn-primary" @click="onSave()" :disabled="!validated">Save</button>
                  <router-link to="/clinics" class="btn btn-secondary">Cancel</router-link>
                </div>

                <div class="mt-3" v-if="errors">
                  <div class="alert alert-danger">{{ errors }}</div>
                </div>

              </div>

            </div>
          </div>

        </div>

      </div><!-- row -->

    </div><!-- container -->

  </div><!-- template -->

</template>

<script>
import {errorDialog, successDialog} from '@/assets/libs/bs-dialog';
import TopNavigationBar from '@/components/TopNavigationBar';

export default {
  name: 'PageClinicCreate',
  components: { TopNavigationBar },

  data() {
    return {

      errors: '',

      clinicToAdd: {
        title: '',
        doctor_in_charge_id: 0,
      },

    };
  },
  /* -- data -- */

  computed: {
    validated() {
      return this.clinicToAdd.title !== '' && this.clinicToAdd.doctor_in_charge_id !== 0;
    },


    doctors() {
      return this.$store.getters[ 'doctors/getDoctors' ];
    },

  },
  /* -- computed -- */

  async mounted() {

    try {

      await this.$store.dispatch( 'doctors/fetchAll' );

    } catch ( e ) {

      errorDialog( { message: 'Failed to fetch doctors details' } );

    }

  },
  /* -- mounted -- */

  methods: {

    async onSave() {

      try {

        const params = {
          title: this.clinicToAdd.title,
          doctor_in_charge_id: this.clinicToAdd.doctor_in_charge_id,
        };

        await this.$store.dispatch( 'clinics/create', params );
        successDialog( { message: 'Clinic details saved' } );

        await this.$router.push( '/clinics' );

      } catch ( e ) {
        errorDialog( { message: 'Failed to save the clinic detail' } );
      }

    }, /* save */

  },
  /* -- methods -- */

};
</script>

<style scoped>

</style>
