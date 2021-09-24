<template>

  <div>

    <TopNavigationBar/>

    <div class="container" v-if="loaded">
      <div class="row justify-content-center">

        <div class="col-6">


          <CardSection class="mb-3">
            <template v-slot:header>Edit Doctor</template>

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

            </div>

          </CardSection><!-- card: edit doctor -->


          <CardSection class="mb-3">
            <template v-slot:header>Associate user profile</template>

            <div class="row">
              <div class="col">

                <p>
                  Select doctor user for login purpose. Choose NO USER for remove association.
                </p>

                <div class="mb-3">
                  <label class="form-label">Doctor User</label>
                  <select class="form-select" v-model="associatedUserId">
                    <option :value="null">CHOOSE ONE / NO USER</option>
                    <option v-for="doc in doctorUsers" :value="doc.id" :key="doc.id">{{ doc.full_name }}</option>
                  </select>
                </div>

                <div class="alert alert-info my-3" v-if="associatedUser">
                  <table class="table table-bordered border-info mb-0">
                    <tbody>
                    <tr>
                      <td class="w-50">Username: {{ associatedUser.username }}</td>
                      <td class="w-50">Email: {{ associatedUser.email }}</td>
                    </tr>
                    </tbody>
                  </table>

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

          </CardSection>


        </div><!-- col -->

      </div><!-- row -->
    </div><!-- container -->


  </div><!-- template -->

</template>

<script>

import CardSection from '@/components/CardSection.vue';
import DateField from '@/components/fields/DateField';
import TopNavigationBar from '@/components/TopNavigationBar';

const _ = require( 'lodash' );

export default {
  name: 'PageDoctorEdit',
  components: { CardSection, DateField, TopNavigationBar },

  data() {
    return {

      loaded: false,

      doctor: {
        id: undefined,
        name: '',
        dob: '',
        email: '',
        phone: '',
        speciality_id: -1,
      },

      errors: '',

      associatedUserId: null,

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
      return !(
          this.doctor.name === '' ||
          this.doctor.email === '' ||
          this.doctor.speciality_id === -1 ||
          this.doctor.phone.length !== 10
      );
    },

    associatedUser() {
      return _.find( this.doctorUsers, { id: this.associatedUserId } );
    },


    /** @return {User[]} */
    doctorUsers() {
      return this.$store.getters[ 'users/getDoctorUsers' ];
    },

  },

  async mounted() {

    try {

      await this.$store.dispatch( 'doctors/getAllSpecialities' );
      await this.$store.dispatch( 'doctors/fetch', this.doctorId );

      /* fetch all doctor users */
      await this.$store.dispatch( 'users/fetchAllDoctorUsers' );

      this.doctor = this.$store.getters[ 'doctors/getDoctor' ];

      /* set the associated user */
      if ( this.doctor.hasOwnProperty( 'user' ) ) {
        this.associatedUserId = this.doctor.user.id;
      }

      this.loaded = true;

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
          user_id: this.associatedUserId,
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
