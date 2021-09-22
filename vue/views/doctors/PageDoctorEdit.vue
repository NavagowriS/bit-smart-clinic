<template>

  <div>

    <TopNavigationBar/>

    <div class="container">
      <div class="row justify-content-center">

        <div class="col-6">


          <div class="card">
            <div class="card-header">Create a doctor</div>
            <div class="card-body">

              <div id="form-create-doctor">

                <div class="mb-3">
                  <label class="form-label">Doctor name*</label>
                  <input type="text" class="form-control" v-model.trim="doctor.name">
                </div>

                <div class="mb-3">
                  <label class="form-label">Date of birth</label>
                  <DateField v-model="doctor.dob"/>
                </div>

                <div class="mb-3">
                  <label class="form-label">Email*</label>
                  <input type="email" class="form-control" v-model.trim="doctor.email">
                </div>

                <div class="mb-3">
                  <label class="form-label">Phone</label>
                  <input type="text" class="form-control" v-model="doctor.phone">
                </div>

                <div class="mb-3">
                  <label class="form-label">Speciality*</label>
                  <select class="form-select" v-model.number="doctor.speciality_id">
                    <option value="-1" disabled>SELECT ONE</option>
                    <option v-for="speciality in allSpecialities" :value="speciality.id" :key="speciality.id">
                      {{ speciality.speciality }}
                    </option>
                  </select>
                </div>

                <div class="text-end">
                  <button class="btn btn-primary" @click="onUpdate()" :disabled="!validated">Update</button>
                  <router-link to="/doctors" class="btn btn-secondary">Cancel</router-link>
                </div>

                <div class="mt-3" v-if="errors">
                  <div class="alert alert-danger">{{ errors }}</div>
                </div>

              </div>

            </div>
          </div>

        </div><!-- col -->

      </div><!-- row -->
    </div><!-- container -->


  </div><!-- template -->

</template>

<script>

import DateField from '@/components/fields/DateField';
import TopNavigationBar from '@/components/TopNavigationBar';

export default {
  name: 'PageDoctorEdit',
  components: { DateField, TopNavigationBar },

  data() {
    return {

      doctor: {
        id: undefined,
        name: '',
        dob: '',
        email: '',
        phone: '',
        speciality_id: -1,
      },

      errors: '',

    };
  },

  computed: {

    doctorId() {
      return parseInt( this.$route.params.id );
    },

    allSpecialities() {
      return this.$store.getters[ 'doctors/getDoctorsSpecialities' ];
    },

    validated() {

      if (
          this.doctor.name === '' ||
          this.doctor.email === '' ||
          this.doctor.speciality_id === -1 ||
          this.doctor.phone.length !== 10
      ) return false;

      return true;
    },

  },

  async mounted() {

    try {

      await this.$store.dispatch( 'doctors/getAllSpecialities' );
      await this.$store.dispatch( 'doctors/fetch', this.doctorId );

      this.doctor = this.$store.getters[ 'doctors/getDoctor' ];

    } catch ( e ) {
      alert( 'Failed to fetch doctor details' );
    }

  },

  methods: {

    async onUpdate() {

      try {

        const params = {
          id: this.doctor.id,
          name: this.doctor.name,
          email: this.doctor.email,
          dob: this.doctor.dob,
          phone: this.doctor.phone,
          speciality_id: this.doctor.speciality_id,
        };

        await this.$store.dispatch( 'doctors/update', params );
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
