<template>

  <div>
    <TopNavigationBar/>


    <div class="container">
      <div class="row">
        <div class="col">


          <div class="card">
            <div class="card-body">

              <div class="card-title">Manage clinics</div>

              <div class="mb-3">
                <router-link to="/clinics/create" class="btn btn-primary">Create a clinic</router-link>
              </div>


              <table class="table table-bordered">
                <thead>
                <tr>
                  <th>Title</th>
                  <th>Doctor in-charge</th>
                  <th>Number of patients</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in clinics" :key="item.id">
                  <td>
                    <router-link :to="'/clinics/' + item.id">{{ item.title }}</router-link>
                  </td>
                  <td>
                    <router-link :to="'/doctors/edit/' + item.doctor_in_charge.id">{{ item.doctor_in_charge.name }}
                      ({{ item.doctor_in_charge.doctor_speciality.speciality }})
                    </router-link>
                  </td>
                  <td>{{ item.clinicPatientsCount }}</td>
                </tr>
                </tbody>
              </table>


            </div>
          </div>


        </div>
      </div>
    </div>


  </div><!-- template -->

</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog';
import TopNavigationBar from '@/components/TopNavigationBar';

export default {
  name: 'PageClinicsList',
  components: { TopNavigationBar },

  data() {
    return {
      //
    };
  },

  computed: {

    /** @returns {Clinic[]} */
    clinics() {
      return this.$store.getters[ 'clinics/getClinicsList' ];
    },
  },

  async mounted() {
    try {
      await this.$store.dispatch( 'clinics/fetchAll' );
    } catch ( e ) {
      errorDialog( { message: 'Failed to fetch clinics details' } );
    }
  },

};
</script>

<style scoped>

</style>
