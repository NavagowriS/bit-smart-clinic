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
                  <input type="text" class="form-control" v-model="patient.full_name">
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
                        <option v-for="(item, key) in genders" :value="key">{{ item }}</option>
                      </select>
                    </div>

                  </div>

                </div><!-- row -->

                <div class="row">
                  <div class="col">
                    <div class="mb-3">
                      <label class="form-label">Phone</label>
                      <input type="text" class="form-control" v-model="patient.phone">
                    </div>
                  </div>

                  <div class="col">
                    <div class="mb-3">
                      <label class="form-label">Nic</label>
                      <input type="text" class="form-control" v-model="patient.nic">
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
                  <button class="btn btn-primary" @click="onUpdate()">Update</button>
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

export default {
  name: 'PagePatientEdit',
  components: { DateField, TopNavigationBar },

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
      return this.$store.getters[ 'patients/getGenders' ];
    },

  },

  async mounted() {

    try {

      this.patientId = this.$route.params.id;

      await this.$store.dispatch( 'patients/fetch', this.patientId );

      this.patient = this.$store.getters[ 'patients/getSelectedPatient' ];

    } catch ( e ) {
      alert( 'Failed to fetch patient data' );
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

        await this.$store.dispatch( 'patients/update', params );
        await this.$router.push( '/patients' );

      } catch ( e ) {

        errorDialog( {
          message: 'Failed to update patient details',
        } );

      }

    }, /* on update */

  },


};
</script>

<style scoped>

</style>
