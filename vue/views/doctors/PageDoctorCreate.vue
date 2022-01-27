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
                  <input type="text" class="form-control" :class="{'is-invalid': formCreateDoctor.name === ''}" v-model.trim="formCreateDoctor.name">
                  <div class="invalid-feedback">Doctor name cannot be empty</div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Date of birth</label>
                  <DateField v-model="formCreateDoctor.dob"/>
                </div>

                <div class="mb-3">
                  <label class="form-label">Email*</label>
                  <input type="text" class="form-control" :class="{'is-invalid': formCreateDoctor.email === ''}" v-model.trim="formCreateDoctor.email">
                  <div class="invalid-feedback">Doctor Email cannot be empty</div>
                </div>

                <div class="mb-3">
                  <label class="form-label">NIC</label>
                  <input type="text" class="form-control" :class="{'is-invalid': !isValidNic}" v-model="formCreateDoctor.nic">
                  <div class="invalid-feedback">Invalid (Use 9 digits + V or 12 digits)</div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Phone</label>
                  <input type="text" class="form-control" :class="{'is-invalid': !isPhoneValid}"
                             placeholder="Eg. 077-4562874" v-model="formCreateDoctor.phone">
                      <div class="invalid-feedback">Invalid. (Use XXX-XXXXXXX)</div>
                </div>


                <div class="mb-3">
                  <label class="form-label">Speciality* (Selecting a Speciality is must)</label> 
                  <select class="form-select" v-model.number="formCreateDoctor.speciality_id">
                    <option value="-1" disabled>SELECT ONE</option>
                    <option v-for="speciality in allSpecialities" :value="speciality.id" :key="speciality.id">
                      {{ speciality.speciality }}
                    </option>
                  </select>
                </div>

                <div class="text-end">
                  <button class="btn btn-primary" @click="onSave()" :disabled="!isFormValid">Save</button>
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

const _ = require('lodash');

export default {
  name: 'PageDoctorCreate',
  components: { DateRangeField, DateField, TopNavigationBar },

  data() {
    return {

      formCreateDoctor: {
        name: '',
        dob: moment().format( 'YYYY-MM-DD' ),
        email: '',
        nic: '',
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

    isValidNic() {

      if ( this.formCreateDoctor.nic === '' ) {
				return true;
			} else {
				const oldPattern = /^[0-9]{9}[V]$/;
        const newPattern = /^[0-9]{12}$/;

      if (oldPattern.test(this.formCreateDoctor.nic)) {
        return true;
      } else if (newPattern.test(this.formCreateDoctor.nic)) {
        return true;
      }
      return false;
			}
      
    },

    isPhoneValid() {
       if ( this.formCreateDoctor.phone === '' ) {
				return true;
			} else {  
			const pattern = /^[0-9]{3}-[0-9]{7}$/;
      return pattern.test(this.formCreateDoctor.phone);
      }
    },

    isFormValid() {
      return this.isValidNic && !_.isEmpty(this.formCreateDoctor.name) && !_.isEmpty(this.formCreateDoctor.email) 
      && this.isPhoneValid && this.formCreateDoctor.speciality_id !== -1;
    }

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
          nic: this.formCreateDoctor.nic,
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
