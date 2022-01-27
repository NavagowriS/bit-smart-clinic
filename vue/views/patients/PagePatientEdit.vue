<template>

  <div>

    <TopNavigationBar/>


    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-md-6">


          <div class="card shadow">
            <div class="card-body">

              <div class="card-title">Edit {{ patient.full_name }}</div>

              <div id="form-edit-patients">

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

                <div class="alert alert-primary">
                  <div class="row">
                    <div class="col">

                      <div class="mb-3">
                        <label class="form-label">Login name</label>
                        <input type="text" class="form-control" readonly :value="patient.login_name">
                      </div>

                    </div>

                    <div class="col">

                      <div class="mb-3">
                        <label class="form-label">Login pass</label>
                        <input type="text" class="form-control" v-model="patient.login_pass">
                      </div>

                    </div>

                  </div>
                </div>


                <div class="text-center">
                  <button class="btn btn-primary" @click="onUpdate()" :disabled="!isFormValid">Update</button>
                  <router-link class="btn btn-secondary" to="/patients">Cancel</router-link>
                </div>


              </div><!-- form-edit-patients -->

            </div>
          </div>


        </div>
      </div>
    </div>

  </div><!-- template -->

</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog';
import DateField from '../../components/fields/DateField';
import TopNavigationBar from '../../components/TopNavigationBar';

const _ = require('lodash');

export default {
  name: 'PagePatientEdit',
  components: {DateField, TopNavigationBar},

  data() {
    return {

      patientId: null,

      patient: {
        full_name: '',
        dob: '',
        age: '',
        phone: '',
        nic: '',
        address: '',
        guardian_name: '',
        guardian_phone: '',
        gender: '',
        login_pass: '',
        login_name: '',
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
      return this.isValidNic && !_.isEmpty(this.patient.full_name) && this.isPhoneValid;
    }


  },

  async mounted() {

    try {

      this.patientId = this.$route.params.id;

      await this.$store.dispatch('patients/fetch', this.patientId);

      this.patient = this.$store.getters['patients/getSelectedPatient'];

    } catch (e) {
      alert('Failed to fetch patient data');
    }

  },

  methods: {

    async onUpdate() {

      try {

        const params = {
          id: this.patient.id,
          full_name: this.patient.full_name,
          dob: this.patient.dob,
          age: this.patient.age,
          phone: this.patient.phone,
          nic: this.patient.nic,
          address: this.patient.address,
          guardian_name: this.patient.guardian_name,
          guardian_phone: this.patient.guardian_phone,
          gender: this.patient.gender,
          login_pass: this.patient.login_pass,
        };

        await this.$store.dispatch('patients/update', params);
        await this.$router.push('/patients');

      } catch (e) {

        errorDialog({
          message: 'Failed to update patient details',
        });

      }

    }, /* on update */

  },


};
</script>

<style scoped>

</style>
