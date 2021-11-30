<template>

  <div class="">

    <CardSection>

      <template v-slot:header>All Tags</template>

      <div class="">

        <table class="table table-sm table-bordered table-striped">
          <thead>
          <tr>
            <th>Tag</th>
            <th>Drugs Count</th>
          </tr>
          </thead>

          <tbody>
          <tr v-for="item in tagsList" :key="item.id">
            <td>
              <router-link :to="{name: 'PageEditTag', params:{id: item.id}}">
                {{ item.tag }}
              </router-link>
            </td>
            <td>-</td>
          </tr>
          </tbody>

        </table>

      </div>

    </CardSection>

  </div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';

export default {
  name: 'PageViewAllTags',
  components: { CardSection },

  data() {
    return {

      /** @type DrugTag[] */
      tagsList: [],

    };
  },

  computed: {
    //
  },

  async mounted() {

    try {
      this.tagsList = await this.$store.dispatch( 'pharmacyDrugs/fetchAllTags' );
    } catch ( e ) {

    }


  },

  methods: {
    //
  },

};
</script>

<style scoped>

</style>
