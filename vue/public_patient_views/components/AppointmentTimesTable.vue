<template>

  <div class="my-3" v-if="showTable">

    <div class="row">
      <div class="col">

        <table class="table table-bordered">
          <thead>
          <tr>
            <th style="width: 20px">Token</th>
            <th>Time</th>
            <th>-</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(item, token) in tokenDataAM">
            <td class="align-middle">
              <span class="lead fw-bold">{{ token }}</span>
            </td>
            <td class="align-middle">{{ item[0] }}</td>
            <td class="align-middle">
              <div v-if="item[1]" class="d-grid">
                <button class="btn btn-sm btn-primary" @click="onChoose(token)">Choose</button>
              </div>
              <div class="text-center" v-else>
                Booked
              </div>
            </td>
          </tr>
          </tbody>
        </table>

      </div>
      <div class="col">

        <table class="table table-bordered">
          <thead>
          <tr>
            <th style="width: 20px">Token</th>
            <th>Time</th>
            <th>-</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(item, token) in tokenDataPM">
            <td class="align-middle">
              <span class="lead fw-bold">{{ token }}</span>
            </td>
            <td class="align-middle">{{ item[0] }}</td>
            <td class="align-middle">
              <div v-if="item[1]" class="d-grid">
                <button class="btn btn-sm btn-primary" @click="onChoose(token)">Choose</button>
              </div>
              <div class="text-center" v-else>
                Booked
              </div>
            </td>
          </tr>
          </tbody>
        </table>

      </div>
    </div>

  </div>

</template>

<script>
export default {
  name: 'AppointmentTimesTable',
  props: {
    usedTokens: {
      type: Array
    },
    showTable: {
      type: Boolean,
      default: false,
    }
  },

  data() {
    return {

      tokenDataAM: {
        1: ['8.00am - 8.15am', true],
        2: ['8.15am - 8.30am', true],
        3: ['8.30am - 8.45am', true],
        4: ['8.45am - 9.00am', true],
        5: ['9.00am - 8.15am', true],
        6: ['9.15am - 9.30am', true],
        7: ['9.30am - 9.45am', true],
        8: ['9.45am - 10.00am', true],
        9: ['10.00am - 10.15am', true],
        10: ['10.15am - 10.30am', true],
        11: ['10.30am - 10.45am', true],
        12: ['10.45am - 11.00am', true],
        13: ['11.00am - 11.15am', true],
        14: ['11.15am - 11.30am', true],
        15: ['11.30am - 11.45am', true],
        16: ['11.45am - 12.00am', true],

      },

      tokenDataPM: {
        17: ['1.00pm - 1.15pm', true],
        18: ['1.15pm - 1.30pm', true],
        19: ['1.30pm - 1.45pm', true],
        20: ['1.45pm - 2.00pm', true],
        21: ['2.00pm - 2.15pm', true],
        22: ['2.15pm - 2.30pm', true],
        23: ['2.30pm - 2.45pm', true],
        24: ['2.45pm - 3.00pm', true],
        25: ['3.00pm - 3.15pm', true],
        26: ['3.15pm - 3.30pm', true],
        27: ['3.30pm - 3.45pm', true],
        28: ['3.45pm - 4.00pm', true],
      }

    };
  },
  /* DATA */

  watch: {
    usedTokens(newVal) {

      /* reset all tokens to be available */
      for (const key in this.tokenDataAM) {
        this.tokenDataAM[key][1] = true;
      }
      for (const key in this.tokenDataPM) {
        this.tokenDataPM[key][1] = true;
      }

      /* going through used tokens to disable book button */
      newVal.forEach(item => {
        if (item < 17) {
          this.tokenDataAM[item][1] = false;
        } else {
          this.tokenDataPM[item][1] = false;
        }
      });
    }
  },
  /* WATCH */

  methods: {

    async onChoose(tokenNumber) {
      this.$emit('token-selected', tokenNumber);
    },


  },
  /* METHODS */

};
</script>

<style scoped>

</style>