<template>

  <div class="">

    <CardSection>

      <template v-slot:header>Add a new drug</template>


      <div class="">

        <div class="row g-2">
          <div class="col">

            <div class="mb-3">
              <label class="form-label">Drug name</label>
              <input type="text" class="form-control" v-model.trim="formSaveDrug.drug_name">
            </div>

          </div><!-- col -->
        </div><!-- row -->

        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label class="form-label">Generic name</label>
              <input type="text" class="form-control" v-model.trim="formSaveDrug.generic_name">
            </div>
          </div><!-- col -->
          <div class="col">
            <div class="mb-3">
              <label class="form-label">Brand name</label>
              <input type="text" class="form-control" v-model.trim="formSaveDrug.brand_name">
            </div>
          </div><!-- col -->
        </div><!-- row -->

        <div class="row">
          <div class="col-4">
            <div class="mb-3">
              <label class="form-label">Minimum Quantity Threshold</label>
              <input type="number" class="form-control" v-model.number="formSaveDrug.min_quantity">
            </div>
          </div>
        </div>


        <div class="text-center">
          <button class="btn btn-primary" @click="onSave()" :disabled="formSaveDrug.drug_name === ''">Save</button>
        </div>

      </div>


    </CardSection>

  </div>


</template>

<script>
import CardSection from '@/components/CardSection.vue';
import {showErrorDialog} from '@/helpers/common.js';

export default {
  name: 'PageAddDrug',
  components: { CardSection },

  data() {
    return {

      formSaveDrug: {
        drug_name: '',
        generic_name: '',
        brand_name: '',
        min_quantity: 500,
      },

    };
  },

  computed: {
    //
  },

  mounted() {
    //
  },

  methods: {

    async onSave() {

      try {

        const response = await this.$store.dispatch( 'pharmacyDrugs/create', this.formSaveDrug );

        const drugAdded = response.data.payload.data;

        await this.$router.push( { name: 'PageEditDrug', params: { id: drugAdded.id } } );

      } catch ( e ) {

        showErrorDialog( e.response, 'Failed to save' );

      }

    },

  },

};
</script>

<style scoped>

</style>
