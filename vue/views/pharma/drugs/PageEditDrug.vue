<template>

  <div class="">

    <CardSection>
      <template v-slot:header>Edit drug_name</template>

      <div class="">

        <div class="">
          <div class="row">
            <div class="col">

              <div class="mb-3">
                <label class="form-label">Drug name</label>
                <input type="text" class="form-control">
              </div>

            </div><!-- col -->
          </div><!-- row -->

          <div class="row">
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Generic name</label>
                <input type="text" class="form-control">
              </div>
            </div><!-- col -->
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Brand name</label>
                <input type="text" class="form-control">
              </div>
            </div><!-- col -->
          </div><!-- row -->


          <div class="text-center">
            <button class="btn btn-primary">Save</button>
          </div>

        </div><!-- drug form -->

        <div class="">



        </div><!-- drug tags -->


      </div>

    </CardSection>

  </div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';
export default {
  name: 'PageEditDrug',
  components: { CardSection },
};
</script>

<style scoped>

</style>
