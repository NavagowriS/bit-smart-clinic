<template>

  <div class="">

    <CardSection>
      <template v-slot:header>Add a tag</template>

      <div class="mb-3">

        <div class="mb-3">
          <label class="form-label">Tag</label>
          <input type="text" class="form-control" v-model.trim="formTag.tag">
        </div>

        <div class="text-center">
          <button class="btn btn-primary" @click="onAdd()">Add</button>
        </div>

      </div>

      <div class="mb-3">
        <hr>
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
import {errorDialog, successDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';
import {showErrorDialog} from '@/helpers/common.js';

export default {
  name: 'PageAddTag',
  components: { CardSection },

  data() {
    return {

      formTag: {
        tag: '',
      },

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
      if ( e.response ) {

        showErrorDialog( {
          message: e.response.data.payload.error,
        } );
      } else {
        showErrorDialog( {
          message: 'Failed to load all tags',
        } );
      }
    }

  },

  methods: {

    async onAdd() {

      try {

        await this.$store.dispatch( 'pharmacyDrugs/createTag', this.formTag );
        this.tagsList = await this.$store.dispatch( 'pharmacyDrugs/fetchAllTags' );

        successDialog( {
          message: 'Tag added',
        } );

        this.formTag.tag = '';

      } catch ( e ) {
        if ( e.response ) {

          errorDialog( {
            message: e.response.data.payload.error,
          } );
        } else {

          errorDialog( {
            message: 'Failed',
          } );
        }
      }

    },

  },


};
</script>

<style scoped>

</style>
