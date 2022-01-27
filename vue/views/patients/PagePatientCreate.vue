<template>

  <div>

    <TopNavigationBar/>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-md-6">


          <div class="card">
            <div class="card-body">

              <div class="card-title">Create new patient profile</div>

              <div id="form-create-patient">

                <div class="mb-3">
                  <label>Full name</label>
                  <input type="text" class="form-control" :class="{'is-invalid': patient.full_name === ''}" v-model.trim="patient.full_name">
                  <div class="invalid-feedback">Name cannot be empty</div>
                </div>

                <div class="row">

                  <div class="col">

                    <div class="mb-3">
                      <label>Date of birth</label>
                      <DateField v-model="patient.dob"/>
                    </div>

                  </div>
                  <div class="col">

                    <div class="mb-3">
                      <label>Gender</label>
                      <select class="form-select" v-model="patient.gender">
                        <option value="-1" selected disabled>CHOOSE ONE</option>
                        <option v-for="(item, key) in genders" :value="key" :key="key">{{ item }}</option>
                      </select>
                    </div>

                  </div>

                </div><!-- row -->

                <div class="row">
                  <div class="col">
                    <div class="mb-3">
                      <label class="form-label">Phone</label>
                      <input type="text" class="form-control" :class="{'is-invalid': !isPhoneValid}"
                             placeholder="Eg. 065-1234567" v-model.trim="patient.phone">
                      <div class="invalid-feedback">Invalid. (Use XXX-XXXXXXX)</div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="mb-3">
                      <label class="form-label">Nic</label>
                     <input type="text" class="form-control" :class="{'is-invalid': !isValidNic}" v-model="patient.nic">
                     <div class="invalid-feedback">Invalid (Use 9 digits + V or 12 digits)</div>
                    </div>
                  </div>

                </div><!-- row -->

                <div class="row">
                  <div class="col">

                    <div class="mb-3">
                      <label class="form-label">Address</label>
                      <textarea rows="3" class="form-control" v-model="patient.address"></textarea>
                    </div>

                  </div>
                </div><!-- row -->

                <div class="row">
                  <div class="col">
                    <div class="mb-3">
                      <label class="form-label">Guardian name</label>
                      <input type="text" class="form-control" v-model="patient.guardian_name">
                    </div>
                  </div>

                  <div class="col">
                    <div class="mb-3">
                      <label class="form-label">Guardian phone</label>
                      <input type="text" class="form-control" v-model="patient.guardian_phone">
                    </div>
                  </div>

                </div><!-- row -->

                <div class="text-center">
                  <button class="btn btn-primary" :disabled="!isFormValid" @click="onSave()">Save</button>
                  <router-link class="btn btn-secondary" to="/patients">Cancel</router-link>
                </div>


              </div>

            </div><!-- body -->
          </div><!-- card -->


        </div><!-- col -->
      </div><!-- row -->
    </div><!-- container -->

  </div><!-- template -->

</template>

<script>
import DateField from '@/components/fields/DateField';
import TopNavigationBar from '@/components/TopNavigationBar';

import moment from 'moment';

let _ = require( 'lodash' );

export default {
  name: 'PagePatientCreate',
  components: { TopNavigationBar, DateField },

  data() {
    return {
      patient: {
        full_name: '',
        dob: moment().format( 'YYYY-MM-DD' ),
        age: '',
        phone: '',
        nic: '',
        address: '',
        guardian_name: '',
        guardian_phone: '',
        gender: '-1',
      },
    };
  },

  computed: {
    genders() {
      return this.$store.getters['patients/getGenders'];
    },

    isValidNic() {

      if ( this.patient.nic === '' ) {
				return true;
			} else {
				const oldPattern = /^[0-9]{9}[V]$/;
        const newPattern = /^[0-9]{12}$/;

      if (oldPattern.test(this.patient.nic)) {
        return true;
      } else if (newPattern.test(this.patient.nic)) {
        return true;
      }
      return false;
			}
      
    },

    isPhoneValid() {
       if ( this.patient.phone === '' ) {
				return true;
			} else {  
			const pattern = /^[0-9]{3}-[0-9]{7}$/;
      return pattern.test(this.patient.phone);
      }
    },

    isFormValid() {
      return this.isValidNic && !_.isEmpty(this.patient.full_name) && this.isPhoneValid && this.patient.gender !== -1;
    }

  },

  mounted() {
    //
  },

  methods: {

    async onSave() {
      try {

        const params = {
          full_name: this.patient.full_name,
          dob: this.patient.dob,
          age: this.patient.age,
          phone: this.patient.phone,
          nic: this.patient.nic,
          address: this.patient.address,
          guardian_name: this.patient.guardian_name,
          guardian_phone: this.patient.guardian_phone,
          gender: this.patient.gender,
        };

        await this.$store.dispatch( 'patients/create', params );
        await this.$router.push( '/patients' );

      } catch ( e ) {
        alert( 'Failed to save new patient' );
      }
    },

  },

};
</script>

<style scoped>

</style>
