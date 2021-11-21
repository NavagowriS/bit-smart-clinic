<template>

  <div class="">

    <TopNavigationBar/>

    <div class="container">

      <div class="row g-2">
        <div class="col-3">
         <PharmaSidebar/>
        </div><!-- col -->


        <div class="col">
          <router-view/>
        </div>

      </div><!-- row -->

    </div>


  </div><!-- template -->


</template>

<script>
import CardSection from '@/components/CardSection.vue';
import TopNavigationBar from '@/components/TopNavigationBar.vue';
import PharmaSidebar from '@/views/pharma/components/PharmaSidebar.vue';

export default {
  name: 'PagePharmaHome',
  components: { PharmaSidebar, CardSection, TopNavigationBar },
};
</script>

<style scoped>

</style>
