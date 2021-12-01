<template>

  <div class="" v-if="loaded">

    <CardSection>

      <template v-slot:header>Filtered by: {{ tag.tag }}</template>


      <div class="">

        <table class="table table-hover table-sm table-bordered" v-if="drugsList.length > 0">
          <thead>
          <tr>
            <th>Drug</th>
            <th>Generic Name</th>
            <th>Brand Name</th>
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
import CardSection from '@/components/CardSection.vue';
import {showErrorDialog} from '@/helpers/common.js';

export default {
  name: 'PageFilterDrugs',
  components: { CardSection },
  data() {
    return {

      drugsList: [],
      tag: null,

      loaded: false,

    };
  },

  computed: {

    tagId() {
      return this.$route.params[ 'tagId' ];
    },

  },

  watch: {
    async tagId() {
      await this.fetchDrugs();
    },
  },

  async mounted() {
    await this.fetchDrugs();
  },

  methods: {

    async fetchDrugs() {

      try {

        this.tag = await this.$store.dispatch( 'pharmacyDrugs/fetchTag', this.tagId );
        this.drugsList = await this.$store.dispatch( 'pharmacyDrugs/findDrugsByTag', this.tagId );

        this.loaded = true;

      } catch ( e ) {
        if ( e.response ) {
          showErrorDialog( { message: e.response.data.payload.error } );
        } else {
          showErrorDialog( { message: 'Failed to fetch data' } );
        }
      }
    },

  },


};
</script>

<style scoped>

</style>
