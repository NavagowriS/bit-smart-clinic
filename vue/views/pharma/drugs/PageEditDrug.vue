<template>

  <div class="">

    <CardSection>
      <template v-slot:header>Edit {{ editDrug.drug_name }}</template>

      <div class="">

        <div class="">
          <div class="row">
            <div class="col">

              <div class="mb-3">
                <label class="form-label">Drug name</label>
                <input type="text" class="form-control" v-model.trim="editDrug.drug_name">
              </div>

            </div><!-- col -->
          </div><!-- row -->

          <div class="row">
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Generic name</label>
                <input type="text" class="form-control" v-model.trim="editDrug.generic_name">
              </div>
            </div><!-- col -->
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Brand name</label>
                <input type="text" class="form-control" v-model.trim="editDrug.brand_name">
              </div>
            </div><!-- col -->
          </div><!-- row -->


          <div class="text-center">
            <button class="btn btn-primary" @click="onUpdate()">Save</button>
          </div>

        </div><!-- drug form -->

        <!-- ADD TAGS -->
        <div class="alert alert-secondary p-2 mt-3">

          <div class="row justify-content-center mb-3">

            <div class="col-6">

              <div class="input-group">
                <select class="form-select" v-model="selectedTag">
                  <option :value="null" disabled>CHOOSE TAG TO ADD</option>
                  <option v-for="item in allTags" :value="item.id" :key="item.id">{{ item.tag }}</option>
                </select>
                <button class="btn btn-primary" @click="onAddTag()">Add</button>
              </div>

            </div>
          </div>

          <div class="">

            <div class="d-flex flex-wrap gap-2 justify-content-center">

              <div class="" v-for="item in addedTags">
                <div class="input-group input-group-sm">
                  <span class="input-group-text bg-white">{{ item.tag.tag }}</span>
                  <button class="btn btn-outline-dark" @click="onRemoveTag(item.id)">
                    <i class="bi bi-x"></i>
                  </button>
                </div>
              </div>

            </div>

          </div>


        </div><!-- drug tags -->


      </div>

    </CardSection>

  </div>

</template>

<script>
import {errorDialog, successDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';

export default {
  name: 'PageEditDrug',
  components: { CardSection },

  data() {
    return {

      editDrug: {
        id: null,
        drug_name: '',
        brand_name: '',
        generic_name: '',
      },

      /** @type DrugTag[] */
      allTags: [],


      addedTags: [],

      selectedTag: null,

    };
  },

  computed: {

    drugId() {
      return parseInt( this.$route.params[ 'id' ] );
    },

  },


  async mounted() {

    try {

      this.editDrug = await this.$store.dispatch( 'pharmacyDrugs/fetch', this.drugId );

      this.allTags = await this.$store.dispatch( 'pharmacyDrugs/fetchAllTags' );

      this.addedTags = await this.$store.dispatch( 'pharmacyDrugs/fetchAllDrugTags', this.drugId );

    } catch ( e ) {
      errorDialog( {
        message: 'Failed to fetch drug details',
      } );
    }

  },

  methods: {

    async onUpdate() {

      try {

        await this.$store.dispatch( 'pharmacyDrugs/update', this.editDrug );

        successDialog( {
          message: 'Drug details updated',
        } );


      } catch ( e ) {
        errorDialog( {
          message: 'Failed to update drug details',
        } );
      }

    }, /* onUpdate */


    async onAddTag() {

      try {

        const params = {
          drug_id: this.drugId,
          tag_id: this.selectedTag,
        };

        await this.$store.dispatch( 'pharmacyDrugs/addDrugTag', params );

        this.addedTags = await this.$store.dispatch( 'pharmacyDrugs/fetchAllDrugTags', this.drugId );

      } catch ( e ) {

        /* error originated from server side */
        if ( e.response ) {

          errorDialog( {
            message: e.response.data.payload.error,
          } );

        } else {
          errorDialog( {
            message: 'Failed to add a tag to the drug',
          } );
        }

      }

    }, /* onAddTag */


    async onRemoveTag( tagId ) {

      try {

        await this.$store.dispatch( 'pharmacyDrugs/removeDrugTag', tagId );
        this.addedTags = await this.$store.dispatch( 'pharmacyDrugs/fetchAllDrugTags', this.drugId );

      } catch ( e ) {
        /* error originated from server side */
        if ( e.response ) {

          errorDialog( {
            message: e.response.data.payload.error,
          } );

        } else {
          errorDialog( {
            message: 'Failed to add a tag to the drug',
          } );
        }
      }

    }, /* onRemoveTag */


  },


};
</script>

<style scoped>

</style>
