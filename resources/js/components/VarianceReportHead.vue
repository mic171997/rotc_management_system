<!-- @format -->
<template>
  <div id="page-body">
    <div id="page-content" style="padding: 0px 20px 0">
      <div class="panel" id="demo-panel-collapse-default">
        <div class="table-responsive panel-body">
          <div class="panel-body row pad-top">
            <table
              class="table table-condensed table-vcenter table-hover"
              id="datatable"
            >
              <thead>
                <tr>
                  <th class="text-main">Item Code</th>
                  <th class="text-main">Description</th>
                  <th class="text-main">Uom</th>
                  <th class="text-main">App QTY</th>
                  <th class="text-main">Nav QTY</th>
                  <th class="text-main">Variance</th>
                  <th class="text-main" style="width: 20%">Reason</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="!data.data.length">
                  <td colspan="8" style="text-align: center;">
                    <div class="sk-cube-grid" v-if="isLoading">
                      <div class="sk-cube sk-cube1"></div>
                      <div class="sk-cube sk-cube2"></div>
                      <div class="sk-cube sk-cube3"></div>
                      <div class="sk-cube sk-cube4"></div>
                      <div class="sk-cube sk-cube5"></div>
                      <div class="sk-cube sk-cube6"></div>
                      <div class="sk-cube sk-cube7"></div>
                      <div class="sk-cube sk-cube8"></div>
                      <div class="sk-cube sk-cube9"></div>
                    </div>
                    <span class="text-thin text-main" v-if="isLoading"
                      >Loading please wait... :)
                    </span>

                    <div v-else>No data available.</div>
                  </td>
                </tr>
                <tr v-for="(data, index) in returnData" :key="index">
                  <td class="text-main text-thin">
                    {{ data.itemcode }}
                  </td>
                  <td class="text-main text-thin">
                    {{ data.description }}
                  </td>
                  <td class="text-main text-thin">
                    {{ data.uom }}
                  </td>
                  <td class="text-main text-thin">
                    {{ data.app_qty | numberFormat }}
                  </td>
                  <td class="text-main text-thin">
                    {{ data.nav_qty | numberFormat }}
                  </td>
                  <td class="text-main text-thin">
                    <span
                      :class="{
                        'text-danger text-bold': data.variance < 0,
                        '': data.variance < 0
                      }"
                    >
                      {{ data.variance | numberFormat }}
                    </span>
                  </td>
                  <td class="text-main text-thin">
                    <!-- <input
                      type="text"
                      placeholder="Input reason"
                      class="form-control text-xl"
                      style="font-style: 14px;"
                      v-model="reason[data.id]"
                      ref="reason"
                    /> -->
                    <!-- :disabled="data.variance > 0 || data.variance == 0" -->
                    <!-- :on-change="() => inputReason(data)" -->
                    <!-- v-model="selected[data.id]" -->

                    <v-select
                      :filterable="false"
                      :options="Options"
                      placeholder="Select Reason"
                      label="reason"
                      @input="inputReason(data, $event, index)"
                      :value="data.reason"
                    >
                      <template slot="no-options">
                        <strong>Select Reason</strong>
                      </template>
                      <template slot="option" slot-scope="option">{{
                        `${option.reason}`
                      }}</template>
                      <template slot="selected-option" slot-scope="option">{{
                        `${option.reason}`
                      }}</template>
                    </v-select>
                    <!-- <select
                      id="company"
                      class="form-control"
                      tabindex="1"
                      style="width: 100%;"
                      @change="inputReason(data, $event)"
                    >
                      <option value="">Select</option>
                      <option
                        v-for="opt in Options"
                        :key="opt.id"
                        :value="opt.id"
                      >
                        {{ opt.reason }}
                      </option>
                    </select> -->
                  </td>
                </tr>
                <tr
                  v-if="
                    returnData.length && returnData.length != data.data.length
                  "
                >
                  <td class="text-main text-center" colspan="7">
                    <div class="bord-btm pad-btm">
                      <button
                        class="btn btn-block btn-rounded btn-default v-middle text-thin"
                        :disabled="isLoading"
                        @click="limit += 100"
                      >
                        Show more
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  Showing {{ returnData.length }} out of
                  <strong> {{ data.data.length }} entries </strong>
                </div>
                <div class="col-md-6">
                  <div class="text-right"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
Vue.component('v-select', vSelect)
export default {
  props: ['test'],
  data() {
    return {
      data: {
        data: [],
        current_page: null,
        from: null,
        to: null,
        total: null,
        per_page: null
      },
      Options: [],
      reason: {},
      isLoading: false,
      limit: 100,
      filteredData: [],
      selected: {}
    }
  },
  created() {
    this.getData()
  },
  watch: {},
  computed: {
    returnData() {
      return this.filteredData.filter((el, index) => index < this.limit)
    }
  },
  methods: {
    inputReason(data, val, index) {
      const id = data.id,
        reason = val.id
      console.log(id, reason, index)

      axios
        .post(`reports/justification/postReason?id=${id}&reason=${reason}`)
        .then(response => {
          if (response.data.message == 'Success') {
            this.filteredData = this.filteredData.map(function(element) {
              if (element.id == response.data.result.id) {
                return response.data.result
              }
              return element
            })
            $.niftyNoty({
              type: 'success',
              icon: 'pli-cross icon-2x',
              message: '<i class="fa fa-check"></i> Generate successful!',
              container: 'floating',
              timer: 5000
            })
            // this.filteredData[index] = response.data.result
          }
        })
    },
    async getReasons() {
      return await axios
        .get(`reports/justification/getReasons`)
        .then(response => {
          this.Options = response.data
        })
    },
    async getResults(page = 1) {
      return await axios
        .get(`reports/justification/getLines?id=${this.test.id}&page=${page}`)
        .then(response => {
          this.data = response.data
          this.filteredData = this.data.data
        })
    },
    getData() {
      this.isLoading = true

      Promise.all([this.getResults(), this.getReasons()]).then(response => {
        this.isLoading = false
      })
    }
  },
  mounted() {}
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}

#container .table-hover > tbody > tr:hover {
  background-color: rgb(2 2 2 / 5%);
}

span.text-danger.text-bold {
  font-weight: 400;
}
</style>
