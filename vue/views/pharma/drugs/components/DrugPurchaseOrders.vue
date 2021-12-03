<template>

  <div>

    <CardSection>
      <template v-slot:header>Purchase Orders</template>


      <div class="mb-3">
        <button class="btn btn-primary" @click="$refs.modal_add_purchase_order.show()">Add Purchase Order</button>
      </div>

      <table class="table table-bordered table-striped" v-if="purchaseOrdersList.length > 0">
        <thead>
        <tr>
          <th>Date</th>
          <th style="width: 150px" class="text-end">Quantity</th>
          <th style="width: 10px"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in purchaseOrdersList" :key="item.id">
          <td class="align-middle">{{ item.order_date }}</td>
          <td class="text-end align-middle">{{ item.quantity }}</td>
          <td>
            <button class="btn btn-sm btn-outline-danger" @click="onDelete(item.id)">
              <i class="bi bi-trash-fill"></i>
            </button>
          </td>
        </tr>
        </tbody>
      </table>

      <div v-else>
        <p class="lead">No purchase orders found.</p>
      </div>

    </CardSection>


    <!-- MODAL: Add Purchase Order -->
    <ModalWindow id="mdl_add_purchase_order" ref="modal_add_purchase_order" title="Add Purchase Order" size="md">

      <div class="row mb-3">
        <div class="col-4">
          <label class="form-label">Purchase Date</label>
          <DateField v-model="formAddPurchaseOrder.order_date"/>
        </div>
        <div class="col">
          <label class="form-label">Quantity</label>
          <input type="number" class="form-control" v-model.number="formAddPurchaseOrder.quantity">
        </div>
      </div>

      <div class="text-center">
        <button class="btn btn-success" @click="onAddPurchaseOrder()">Add Purchase Order</button>
      </div>

    </ModalWindow>

  </div>


</template>

<script>
import CardSection from '@/components/CardSection.vue';
import DateField from '@/components/fields/DateField.vue';
import ModalWindow from '@/components/ModalWindow.vue';
import {showErrorDialog} from '@/helpers/common.js';

import moment from 'moment';


export default {
  name: 'DrugPurchaseOrders',
  components: { DateField, ModalWindow, CardSection },

  props: {
    drugId: {
      required: true,
    },
  },

  data() {
    return {

      formAddPurchaseOrder: {
        drug_id: this.drugId,
        order_date: moment().format( 'YYYY-MM-DD' ),
        quantity: 0,
      },

      purchaseOrdersList: [],

    };
  },

  computed: {
    //
  },

  methods: {

    async onAddPurchaseOrder() {

      try {

        await this.$store.dispatch( 'pharmacyDrugs/createPurchaseOrder', this.formAddPurchaseOrder );
        this.purchaseOrdersList = await this.$store.dispatch( 'pharmacyDrugs/fetchPurchaseOrdersByDrug', this.drugId );

        this.$refs.modal_add_purchase_order.close();

        this.$emit( 'updated' );

      } catch ( e ) {
        showErrorDialog( e.response, 'Failed.' );
      }

    }, /* onAddPurchaseOrder */

    async onDelete( id ) {
      try {

        await this.$store.dispatch( 'pharmacyDrugs/deletePurchaseOrder', id );

        this.purchaseOrdersList = await this.$store.dispatch( 'pharmacyDrugs/fetchPurchaseOrdersByDrug', this.drugId );

        this.$emit( 'updated' );

      } catch ( e ) {
        showErrorDialog( e.response, 'Failed.' );
      }
    }, /* onDelete */

  },

  async mounted() {

    try {
      this.purchaseOrdersList = await this.$store.dispatch( 'pharmacyDrugs/fetchPurchaseOrdersByDrug', this.drugId );
    } catch ( e ) {
      showErrorDialog( e.response, 'Failed.' );
    }

  },

};
</script>

<style scoped>

</style>
