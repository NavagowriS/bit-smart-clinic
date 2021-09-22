<template>

  <div class="modal fade" :data-bs-backdrop="backdropMode" data-bs-keyboard="false" tabindex="-1" :id="id">
    <div class="modal-dialog" :class="modalSize">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">{{ title }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
        <div class="modal-footer">
          <slot name="footer"></slot>
        </div>
      </div>
    </div>
  </div>

</template>

<script>

export default {
  name: 'ModalWindow',
  props: {
    id: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      default: 'Simple modal',
    },
    size: {
      type: String,
      default: '', /* values: sm, lg, xl */
    },
    backdrop: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      instance: null,
    };
  },

  computed: {
    /*
    * calculate modal size based on the size property
    * by default = "",
    * sm = "small size"
    * lg = "large size"
    * xl = "extra large size"
    * */
    modalSize() {

      if ( this.size === '' ) {
        return '';
      } else if ( this.size === 'sm' ) {
        return 'modal-sm';
      } else if ( this.size === 'xl' ) {
        return 'modal-xl';
      } else if ( this.size === 'lg' ) {
        return 'modal-lg';
      }
    },

    /*
    * If backdrop is set as true, modal wont be closed by clicking outside
    * of modal window
    * */
    backdropMode() {
      if ( this.backdrop ) return 'static';
      return false;
    },

  },

  mounted() {

  },

  methods: {

    close() {
      const el = document.getElementById( this.id );
      this.instance = bootstrap.Modal.getOrCreateInstance( el );
      this.instance.hide();
    },

    show() {
      const el = document.getElementById( this.id );
      this.instance = bootstrap.Modal.getOrCreateInstance( el );
      this.instance.show();
    },

  },

};
</script>

<style scoped>

</style>
