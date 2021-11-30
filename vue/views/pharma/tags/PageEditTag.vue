<template>

  <div class="">

    <CardSection>
      <template v-slot:header>Update {{ formTag.tag }}</template>

      <div class="">

        <div class="mb-3">
          <label class="form-label">Tag</label>
          <input type="text" class="form-control" v-model.trim="formTag.tag">
        </div>

        <div class="text-center">
          <button class="btn btn-primary" @click="onUpdate()" :disabled="!tagChanged">Update</button>
        </div>

        <button class="btn btn-sm btn-link text-danger" @click="confirmDelete = true">Delete this tag</button>

        <div class="mt-3 alert alert-danger text-center" v-if="confirmDelete">
          <p>Are you sure to delete? This tag will be removed from all drugs.</p>
          <button class="btn btn-sm btn-danger" @click="onDelete()">Delete</button>
        </div>

      </div>

    </CardSection>

  </div>

</template>

<script>
import {errorDialog, successDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';

export default {
  name: 'PageEditTag',
  components: { CardSection },

  data() {
    return {

      formTag: {
        id: null,
        tag: '',
      },

      originalTagName: '',

      confirmDelete: false,

    };
  },

  computed: {
    tagId() {
      return parseInt( this.$route.params[ 'id' ] );
    },

    tagChanged() {
      return this.formTag.tag !== this.originalTagName;
    },

  },

  async mounted() {

    try {

      this.formTag = await this.$store.dispatch( 'pharmacyDrugs/fetchTag', this.tagId );

      this.originalTagName = this.formTag.tag;

    } catch ( e ) {
      errorDialog( {
        message: 'Failed to fetch tag details',
      } );
    }

  },

  methods: {

    async onUpdate() {

      try {

        await this.$store.dispatch( 'pharmacyDrugs/updateTag', this.formTag );

        successDialog( {
          message: 'Tag updated',
        } );

        await this.$router.push( { name: 'PageViewAllTags' } );

      } catch ( e ) {
        errorDialog( {
          message: e.response.data.payload.error,
        } );
      }

    }, /* onUpdate */

    async onDelete() {
      try {

        await this.$store.dispatch( 'pharmacyDrugs/deleteTag', this.formTag.id );

        await this.$router.push( { name: 'PageViewAllTags' } );

      } catch ( e ) {
        if ( e.response ) {
          errorDialog( { message: e.response.data.payload.error } );
        } else {
          errorDialog( { message: 'Failed to delete' } );
        }
      }
    },

  },

};
</script>

<style scoped>

</style>
