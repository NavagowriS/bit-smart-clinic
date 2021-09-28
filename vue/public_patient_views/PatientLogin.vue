<template>

  <div class="container">

    <div class="row justify-content-center">
      <div class="col-5">


        <div class="card">

          <div class="card-header">
            <div class="fw-bold text-center">Patient Login : Smart Clinic</div>
          </div>

          <div class="img-fluid text-center my-2">
            <img :src="icons.hospital" alt="Clinic" class="login-logo">
          </div>

          <div class="card-body">

            <div class="row justify-content-center">
              <div class="col-8">

                <form @submit.prevent="onLogin">
                  <div class="mb-3">
                    <label for="txt-username" class="form-label fw-bold">Username</label>
                    <input id="txt-username" type="text" class="form-control" v-model="username">
                  </div>

                  <div class="mb-3">
                    <label for="txt-password" class="form-label fw-bold">Password</label>
                    <input id="txt-password" type="password" class="form-control" v-model="password">
                  </div>

                  <div class="text-center">
                    <button class="btn btn-success" type="submit">Login</button>
                  </div>
                </form>

              </div>
            </div>

            <div v-if="error" class="mt-3">
              <div class="alert alert-danger">
                {{ error }}
              </div>
            </div>

          </div>

        </div><!-- card -->


      </div>
    </div>

  </div>

</template>

<script>
import {showErrorDialog} from '@/helpers/common.js';

export default {
  name: 'PatientLogin',

  data() {
    return {

      icons: {
        hospital: require( '@/assets/images/icons/hospital.svg' ).default,
      },

      username: '58ADRIA',
      password: '2aa411',

      error: '',


    };
  },

  computed: {
    //
  },


  methods: {

    async onLogin() {

      try {

        const params = {
          username: this.username,
          password: this.password,
        };

        await this.$store.dispatch( 'publicPatient/authenticate', params );

        await this.$router.push( { name: 'PagePatientHome' } );

      } catch ( e ) {
        showErrorDialog( e.response, 'Failed to login' );
      }


    },

  },


};
</script>

<style scoped>
.login-logo {
  width: 150px;
}

.container {
  margin-top: 10em;
}

</style>
