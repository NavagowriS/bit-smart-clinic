<template>


  <div class="">

    <CardSection>

      <template v-slot:header>All Drugs</template>


      <div class="">

        <table class="table table-hover table-sm table-bordered">
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
                <a href="">{{ tag.tag.tag }}</a>
              </div>
            </td>
          </tr>

          </tbody>

        </table>

      </div>

    </CardSection>

  </div>

</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';

export default {
  name: 'PageViewAllDrugs',
  components: { CardSection },

  data() {
    return {

      /** @type Drug[] */
      drugsList: [],

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
    //
  },

};
</script>

<style scoped>

</style>
