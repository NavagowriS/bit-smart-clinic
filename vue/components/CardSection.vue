<template>

  <div class="card">
    <div class="card-body">

      <div class="card-title" v-if="!noTitle">
        <slot name="header"></slot>
      </div>

      <slot></slot>
    </div>
  </div>

</template>

<script>
export default {
  name: 'CardSection',
  props: {
    noTitle: {
      type: Boolean,
      defaultValue: false,
    },
  },
};
</script>

<style scoped>

</style>
