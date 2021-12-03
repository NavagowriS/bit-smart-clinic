<template>


  <div class="">

    <CardSection>

      <template v-slot:header>All Drugs</template>


      <div class="">

        <div class="mb-3 d-flex justify-content-end">
          <div class="input-group">
            <span class="input-group-text">Search</span>
            <input type="text" class="form-control" @keydown="onDebouncedSearch" placeholder="search with keywords..." v-model.trim="searchKeyword">
            <button class="btn btn-primary" @click="onSearch">Search</button>
          </div>
        </div>

        <div class="mb-3" v-if="searched && searchKeyword.length > 0">
          <p class="lead">You've searched for {{ searchKeyword }}</p>
        </div>

        <table class="table table-hover table-sm table-bordered" v-if="drugsList.length > 0">
          <thead>
          <tr>
            <th>Drug</th>
            <th>Generic Name</th>
            <th>Brand Name</th>
            <th class="text-end" style="width: 120px">Total Count</th>
            <th>Tags</th>
          </tr>
          </thead>

          <tbody>

          <tr v-for="item in drugsList" :key="item.id">
            <td>
              <router-link :to="{name: 'PageEditDrug', params: {id: item.id}}">
                {{ item.drug_name }}
              </router-link>
            </td>
            <td>{{ item.generic_name }}</td>
            <td>{{ item.brand_name }}</td>
            <td class="text-end">{{ item.total_count }}</td>
            <td>
              <div v-for="tag in item.drugTags" class="p-1 me-1 border border-1 d-inline-block">
                <router-link :to="{name: 'PageFilterDrugs', params:{tagId: tag.tag_id}}">{{ tag.tag.tag }}</router-link>
              </div>
            </td>
          </tr>

          </tbody>
        </table>

        <div class="" v-else>
          No results found.
        </div>

      </div>

    </CardSection>

  </div>

</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';
import {showErrorDialog} from '@/helpers/common.js';

const _ = require( 'lodash' );

export default {
  name: 'PageViewAllDrugs',
  components: { CardSection },

  data() {
    return {

      /** @type Drug[] */
      drugsList: [],

      searchKeyword: '',
      searched: false,

    };
  },


  computed: {
    //
  },

  async mounted() {

    try {

      this.drugsList = await this.$store.dispatch( 'pharmacyDrugs/fetchAll' );

    } catch ( e ) {
      errorDialog( {
        message: 'Failed to fetch drugs',
      } );
    }

  },

  methods: {

    onDebouncedSearch: _.debounce( async function () {
      await this.onSearch();
    }, 500 ),


    onSearch: async function () {
      try {

        console.log( this.searchKeyword );

        this.drugsList = await this.$store.dispatch( 'pharmacyDrugs/search', this.searchKeyword );

        this.searched = true;

      } catch ( e ) {

        if ( e.response ) {
          showErrorDialog( { message: e.response.data.payload.error } );
        } else {
          showErrorDialog( { message: 'Failed to fetch search results' } );
        }

      }
    },

  },

};
</script>

<style scoped>

</style>
