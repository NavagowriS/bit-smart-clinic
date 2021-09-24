<template>

  <div>

    <TopNavigationBar/>

    <div class="container">

      <div class="row justify-content-center">

        <div class="col-12 col-md-6">

          <div class="card">
            <div class="card-body">

              <div class="card-title">Create a new doctor</div>

              <div id="form-create-doctor">

                <div class="mb-3">
                  <label class="form-label">Doctor name*</label>
                  <input type="text" class="form-control" v-model.trim="formCreateDoctor.name">
                </div>

                <div class="mb-3">
                  <label class="form-label">Date of birth</label>
                  <DateField v-model="formCreateDoctor.dob"/>
                </div>

                <div class="mb-3">
                  <label class="form-label">Email*</label>
                  <input type="email" class="form-control" v-model.trim="formCreateDoctor.email">
                </div>

                <div class="mb-3">
                  <label class="form-label">Phone</label>
                  <input type="text" class="form-control" v-model="formCreateDoctor.phone">
                </div>

                <div class="mb-3">
                  <label class="form-label">Speciality*</label>
                  <select class="form-select" v-model.number="formCreateDoctor.speciality_id">
                    <option value="-1" disabled>SELECT ONE</option>
                    <option v-for="speciality in allSpecialities" :value="speciality.id" :key="speciality.id">
                      {{ speciality.speciality }}
                    </option>
                  </select>
                </div>

                <div class="text-end">
                  <button class="btn btn-primary" @click="onSave()" :disabled="!validated">Save</button>
                  <router-link to="/doctors" class="btn btn-secondary">Cancel</router-link>
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
import DateField from '@/components/fields/DateField';
import DateRangeField from '@/components/fields/DateRangeField';
import TopNavigationBar from '@/components/TopNavigationBar';
import moment from 'moment';


export default {
  name: 'PageDoctorCreate',
  components: { DateRangeField, DateField, TopNavigationBar },

  data() {
    return {

      formCreateDoctor: {
        name: '',
        dob: moment().format( 'YYYY-MM-DD' ),
        email: '',
        phone: '',
        speciality_id: -1,
      },

      errors: '',

    };
  },

  computed: {

    allSpecialities() {
      return this.$store.getters['doctors/getDoctorsSpecialities'];
    },

    validated() {

      return (
          this.formCreateDoctor.name !== '' ||
          this.formCreateDoctor.email !== '' ||
          this.formCreateDoctor.speciality_id !== -1
      );
    },

  },

  async mounted() {

    try {

      await this.$store.dispatch( 'doctors/getAllSpecialities' );

    } catch ( e ) {
      console.log( e );
    }

  },

  methods: {

    async onSave() {

      try {

        const params = {
          name: this.formCreateDoctor.name,
          email: this.formCreateDoctor.email,
          dob: this.formCreateDoctor.dob,
          phone: this.formCreateDoctor.phone,
          speciality_id: this.formCreateDoctor.speciality_id,
        };

        await this.$store.dispatch( 'doctors/create', params );

        await this.$router.push( '/doctors' );

      } catch ( e ) {
        this.errors = e.response.data.payload.error;
      }

    },

  },

};
</script>

<style scoped>

</style>
