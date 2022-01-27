<template>

  <div>

    <div class="d-flex justify-content-between gap-2">
      <div class="left">
        <router-link class="btn btn-sm btn-primary mb-2 d-print-none"
                     :to="generateBookAppointmentLink(clinicVisit)">
          Book appointment
        </router-link>
      </div><!-- left -->

      <div class="right">
        <div class="form-check form-check-inline">
          <input class="form-check-input"
                 type="radio"
                 :name="generateCheckFilterId('chk_filter')"
                 :id="generateCheckFilterId('completed')"
                 value="COMPLETED"
                 v-model="checkFilter">
          <label class="form-check-label" :for="generateCheckFilterId('completed')">Completed</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input"
                 type="radio"
                 :name="generateCheckFilterId('chk_filter')"
                 :id="generateCheckFilterId('active')"
                 value="ACTIVE"
                 v-model="checkFilter">
          <label class="form-check-label" :for="generateCheckFilterId('active')">Active</label>
        </div>
      </div><!-- right -->
    </div>

    <table class="table table-bordered table-striped">
      <thead>
      <tr>
        <th>Date</th>
        <th>Token No.</th>
        <th v-if="checkFilter === 'ACTIVE'">Time</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="appointment in filteredAppointments" :key="appointment.id">
        <td>

          <div v-if="checkFilter==='COMPLETED'">
            <router-link
                :to="{name: 'PageViewAppointment', params: {appointmentId: appointment.id, clinicId: clinicVisit.clinic.id}}">
              {{ appointment.clinic_date }}
            </router-link>
          </div>
          <div v-else>
            {{ appointment.clinic_date }}
          </div>

        </td>
        <td>
          {{ appointment.token_number }}

        </td>
        <td v-if="checkFilter === 'ACTIVE'">
          {{ generateTime(appointment.token_number) }}
        </td>
      </tr>
      </tbody>
    </table>

  </div>

</template>

<script>

const _ = require('lodash');

export default {
  name: 'ClinicAppointmentsTable',

  props: {
    clinicVisit: {
      type: Object,
    }
  },

  data() {
    return {
      checkFilter: 'ACTIVE',


      tokenTimes: {
        1: '8.00am',
        2: '8.15am',
        3: '8.30am',
        4: '8.45am',
        5: '9.00am',
        6: '9.15am',
        7: '9.30am',
        8: '9.45am',
        9: '10.00am',
        10: '10.15am',
        11: '10.30am',
        12: '10.45am',
        13: '11.00am',
        14: '11.15am',
        15: '11.30am',
        16: '11.45am',
        17: '1.00pm',
        18: '1.15pm',
        19: '1.30pm',
        20: '1.45pm',
        21: '2.00pm',
        22: '2.15pm',
        23: '2.30pm',
        24: '2.45pm',
        25: '3.00pm',
        26: '3.15pm',
        27: '3.30pm',
        28: '3.45pm',

      },

    };
  },

  computed: {

    clinicId() {
      return this.clinicVisit['clinic']['id'];
    },

    appointments() {
      return this.clinicVisit['appointments'];
    },

    filteredAppointments() {
      return _.filter(this.appointments, {'status': this.checkFilter});
    },

  },

  methods: {

    generateBookAppointmentLink(data) {
      return {
        name: 'PageBookAppointment',
        params: {clinicPatientId: data.clinicPatient.id},
      };
    },

    generateCheckFilterId(key) {
      return `chk_${key}_${this.clinicId}`;
    },

    generateTime(tokenNumber) {
      return this.tokenTimes[tokenNumber];
    },

  }

};
</script>

<style scoped>

</style>