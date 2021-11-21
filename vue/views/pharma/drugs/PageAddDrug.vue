<template>

  <div class="">

    <CardSection>

      <template v-slot:header>Add a new drug</template>


      <div class="">

        <div class="row">
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


        <div class="text-center">
          <button class="btn btn-primary" @click="onSave()">Save</button>
        </div>

      </div>


    </CardSection>

  </div>


</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';

export default {
  name: 'PageAddDrug',
  components: { CardSection },

  data() {
    return {

      formSaveDrug: {
        drug_name: '',
        generic_name: '',
        brand_name: '',
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

        console.log( response.data.payload );

      } catch ( e ) {
        errorDialog( {
          message: 'Failed to save the drug details',
        } );
      }

    },

  },

};
</script>

<style scoped>

</style>
