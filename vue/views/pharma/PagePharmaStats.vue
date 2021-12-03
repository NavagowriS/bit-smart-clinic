<template>

  <div class="">

    <CardSection>
      <template v-slot:header>Drugs Low Threshold Warnings</template>

      <div v-if="thresholdWarnings && thresholdWarnings.length > 0">
        <table class="table table-striped table-bordered table-sm">
          <thead>
          <tr>
            <th>Drug</th>
            <th style="width: 120px" class="text-end">Avl. Qty</th>
            <th style="width: 120px" class="text-end">Min. Thresh.</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="item in thresholdWarnings">
            <td>
              <router-link :to="{name: 'PageEditDrug', params: {id: item.id}}">
                {{ item.drug_name }}
              </router-link>
            </td>
            <td class="text-end">{{ item.total_count }}</td>
            <td class="text-end">{{ item.min_quantity }}</td>
          </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        There are no low threshold warnings.
      </div>

    </CardSection>

  </div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import {showErrorDialog} from '@/helpers/common.js';

export default {
  name: 'PagePharmaStats',
  components: { CardSection },

  data() {
    return {

      drugStats: [],

    };
  },

  computed: {

    /** @returns {Drug[]} */
    thresholdWarnings() {
      return this.drugStats[ 'threshold_warnings' ];
    },

  },

  methods: {
    //
  },

  async mounted() {
    try {

      this.drugStats = await this.$store.dispatch( 'pharmacyDrugs/stats' );

    } catch ( e ) {
      showErrorDialog( e.response, 'Failed to fetch stats' );
    }
  },
};
</script>

<style scoped>

</style>
