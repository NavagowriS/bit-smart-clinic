<template>

  <div>
    <CardSection>
      <template v-slot:header>Doctor Specialities</template>

      <button class="btn btn-sm btn-primary mb-3" @click="$refs.modal_create_speciality.show()">
        Create new speciality
      </button>

      <div class="">
        <table class="table table-sm table-bordered align-middle">
          <tbody>
          <tr v-for="item in allSpecialities" :key="item.id">
            <td>{{ item.speciality }}</td>
            <td style="width: 20px">
              <button class="btn btn-sm btn-secondary" @click="openEditModal(item)">
                <i class="bi bi-pencil-fill"></i>
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </div>


    </CardSection>


    <!-- Modal: Edit speciality window -->
    <ModalWindow id="modal_edit_speciality" ref="modal_edit_speciality" title="Edit speciality">

      <div class="mb-3">
        <label class="form-label">Speciality</label>
        <input type="text" class="form-control" v-model.trim="specialityToEdit.speciality">
      </div>

      <div class="text-center">
        <button class="btn btn-success" @click="onUpdate()" :disabled="specialityToEdit.speciality === ''">Update</button>
      </div>

    </ModalWindow>

    <!-- Modal: Create new speciality -->
    <ModalWindow id="modal_create_speciality" ref="modal_create_speciality" title="Add new speciality">

      <div class="mb-3">
        <label class="form-label">Speciality</label>
        <input type="text" class="form-control" v-model="specialityToAdd.speciality">
      </div>

      <div class="text-center">
        <button class="btn btn-success" @click="onCreate()" :disabled="specialityToAdd.speciality === ''">Create</button>
      </div>
    </ModalWindow>

  </div>


</template>

<script>
import CardSection from '@/components/CardSection.vue';
import ModalWindow from '@/components/ModalWindow.vue';
import {showErrorDialog} from '@/helpers/common.js';

const _ = require('lodash');

export default {
  name: 'SpecialitiesCard',
  components: {ModalWindow, CardSection},


  data() {
    return {

      specialityToEdit: {
        id: null,
        speciality: '',
      },

      specialityToAdd: {
        speciality: '',
      },

    };
  },

  computed: {

    /** @returns {DoctorSpeciality[]} */
    allSpecialities() {
      return this.$store.getters['specialities/getAllSpecialities'];
    },

  },

  async mounted() {

    try {

      await this._fetchAll();

    } catch (e) {
      showErrorDialog(e.response, 'Failed to fetch speciality details');
    }

  },

  methods: {

    /**
     * @param item {DoctorSpeciality}
     */
    openEditModal(item) {

      this.specialityToEdit = _.cloneDeep(item);

      this.$refs.modal_edit_speciality.show();

    },

    async onUpdate() {
      try {

        const params = {
          id: this.specialityToEdit.id,
          speciality: this.specialityToEdit.speciality,
        };

        await this.$store.dispatch('specialities/update', params);

        this.$refs.modal_edit_speciality.close();

        await this._fetchAll();

        await this.$store.dispatch('doctors/fetchAll');

      } catch (e) {
        showErrorDialog(e.response, 'Failed to update');
      }
    }, /* onUpdate */

    async onCreate() {

      try {

        const params = {
          speciality: this.specialityToAdd.speciality,
        };

        await this.$store.dispatch('specialities/create', params);

        this.$refs.modal_create_speciality.close();

        await this._fetchAll();

      } catch (e) {
        showErrorDialog(e.response, 'Failed to create');
      }

    }, /* onCreate */


    async _fetchAll() {
      try {
        await this.$store.dispatch('specialities/fetchAll');
      } catch (e) {
        showErrorDialog(e.response);
      }
    },

  },

};
</script>

<style scoped>

</style>
