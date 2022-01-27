<template>
  <div class="row mb-3 justify-content-end">
    <div class="col text-end">
      <button class="btn btn-danger" @click="onLogout">Logout</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LogOut',
  methods: {
    async onLogout() {
      try {
        await this.$store.dispatch('publicPatient/logout');
        await this.$router.push({name: 'PagePatientLogin'});
      } catch (e) {

      }
    },
  },
};
</script>

<style scoped>

</style>