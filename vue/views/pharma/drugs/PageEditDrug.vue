<template>

  <div class="" v-if="loaded">

    <!-- Edit Drug -->
    <CardSection class="mb-3" v-if="editVisible">
      <template v-slot:header>
        <div class="d-flex justify-content-between">
          <div class="">
            Edit {{ editDrug.drug_name }}
          </div>
          <div class="">
            <button class="btn btn-sm btn-secondary" @click="editVisible = false">
              <i class="bi bi-x"></i>
            </button>
          </div>
        </div>
      </template>

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

          <div class="row g-2">
            <div class="col">
              <div class="mb-3">
                <label class="form-label">G.N</label>
                <input type="text" class="form-control" v-model.trim="editDrug.generic_name">
              </div>
            </div><!-- col -->
            <div class="col">
              <div class="mb-3">
                <label class="form-label">B.N</label>
                <input type="text" class="form-control" v-model.trim="editDrug.brand_name">
              </div>
            </div><!-- col -->
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Min.</label>
                <input type="text" class="form-control" v-model.trim="editDrug.min_quantity">
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
    <!-- Edit Drug -->


    <CardSection class="mb-3" v-if="!editVisible">
      <template v-slot:header>
        <div class="d-flex justify-content-between">
          <div class="">
            {{ editDrug.drug_name }}
          </div>
          <div class="">
            <button class="btn btn-sm btn-secondary" @click="editVisible = true">
              <i class="bi bi-pencil-fill"></i>
            </button>
          </div>
        </div>
      </template>

      <div class="row g-2 mb-3">

        <div class="col">
          <div class="input-group">
            <span class="input-group-text">G.N</span>
            <input type="text" disabled class="form-control bg-white" :value="editDrug.generic_name">
          </div>
        </div>
        <div class="col">
          <div class="input-group">
            <span class="input-group-text">B.N</span>
            <input type="text" disabled class="form-control bg-white" :value="editDrug.brand_name">
          </div>
        </div>

      </div><!-- row -->

      <div class="row g-2 mb-3">
        <div class="col">
          <div class="input-group">
            <span class="input-group-text">Available</span>
            <input type="text" class="form-control" v-model.trim="editDrug.total_count">
          </div>
        </div>
        <div class="col">
          <div class="input-group">
            <span class="input-group-text">Min.Threshold</span>
            <input type="text" class="form-control" v-model.trim="editDrug.min_quantity">
          </div>
        </div>
      </div>


      <div class="row mb-3" v-if="addedTags.length > 0">
        <div class="col">
          <div>Tags</div>
          <div class="d-flex flex-wrap gap-2">

            <div class="" v-for="item in addedTags">
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">{{ item.tag.tag }}</span>
              </div>
            </div>

          </div>

        </div>
      </div><!-- row -->


      <div class="alert alert-danger text-center" v-if="lowQuantityWarning">
        <div class="text-center">
          <i class="bi bi-exclamation-triangle-fill" style="font-size: 48px"></i>
        </div>
        <p class="lead">Stock quantity available is lower than minimum quantity threshold.</p>
        <p>Available Quantity: {{ editDrug.total_count }}</p>
      </div>

    </CardSection>


    <DrugPurchaseOrders :drug-id="drugId" @updated="fetchDrugDetails"/>

  </div>

</template>

<script>
import {successDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';
import {showErrorDialog} from '@/helpers/common.js';
import DrugPurchaseOrders from '@/views/pharma/drugs/components/DrugPurchaseOrders.vue';

export default {
  name: 'PageEditDrug',
  components: { DrugPurchaseOrders, CardSection },

  data() {
    return {

      loaded: false,

      editDrug: {
        id: null,
        drug_name: '',
        brand_name: '',
        generic_name: '',
        min_quantity: 0,
        total_count: 0,
      },

      /** @type DrugTag[] */
      allTags: [],


      addedTags: [],

      selectedTag: null,

      editVisible: false,

    };
  },

  computed: {

    drugId() {
      return parseInt( this.$route.params[ 'id' ] );
    },

    lowQuantityWarning() {
      if ( this.editDrug.total_count < this.editDrug.min_quantity ) return true;
      return false;
    },

  },


  async mounted() {

    try {

      this.editDrug = await this.$store.dispatch( 'pharmacyDrugs/fetch', this.drugId );

      this.allTags = await this.$store.dispatch( 'pharmacyDrugs/fetchAllTags' );

      this.addedTags = await this.$store.dispatch( 'pharmacyDrugs/fetchAllDrugTags', this.drugId );

      this.loaded = true;

    } catch ( e ) {
      showErrorDialog( e.response, 'Failed.' );
    }

  },

  methods: {

    async fetchDrugDetails() {
      try {
        this.editDrug = await this.$store.dispatch( 'pharmacyDrugs/fetch', this.drugId );
      } catch ( e ) {
        showErrorDialog( e.response, 'Failed.' );
      }
    },

    async onUpdate() {

      try {

        await this.$store.dispatch( 'pharmacyDrugs/update', this.editDrug );

        successDialog( {
          message: 'Drug details updated',
        } );


      } catch ( e ) {
        showErrorDialog( e.response, 'Failed.' );
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

        showErrorDialog( e.response, 'Failed.' );

      }

    }, /* onAddTag */


    async onRemoveTag( tagId ) {

      try {

        await this.$store.dispatch( 'pharmacyDrugs/removeDrugTag', tagId );
        this.addedTags = await this.$store.dispatch( 'pharmacyDrugs/fetchAllDrugTags', this.drugId );

      } catch ( e ) {
        showErrorDialog( e.response, 'Failed.' );
      }

    }, /* onRemoveTag */


  },


};
</script>

<style scoped>

</style>
