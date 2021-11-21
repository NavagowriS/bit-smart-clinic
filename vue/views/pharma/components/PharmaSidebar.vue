<template>

  <div class="">
    <CardSection>

      <ul class="list-group mb-3">
        <router-link to="/pharmacy" class="list-group-item">Pharmacy home</router-link>
      </ul>

      <ul class="list-group mb-3">
        <router-link to="/drugs" class="list-group-item"><i class="bi bi-grid-fill"></i> All drugs</router-link>
        <router-link to="/drugs/add" class="list-group-item"><i class="bi bi-plus-circle-fill"></i> Add a drug</router-link>
      </ul>

      <ul class="list-group mb-3">
        <router-link to="/tags" class="list-group-item"><i class="bi bi-palette2"></i> All tags</router-link>
        <router-link to="/tags/add" class="list-group-item"><i class="bi bi-plus-circle-fill"></i> Add a tag</router-link>
      </ul>

    </CardSection>
  </div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';

export default {
  name: 'PharmaSidebar',
  components: { CardSection },
};
</script>

<style scoped>

</style>
