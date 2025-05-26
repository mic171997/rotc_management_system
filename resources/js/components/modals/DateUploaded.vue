<!-- @format -->

<template>
  <div>
    <div class="modal-dialog modal-lg" style="width: 1200px">
      <div class="modal-content">
        <div class="modal-header">
          <h5
            class="modal-title bord-btm text-thin"
            id="mdlTitle"
            style="padding: 5px 15px 15px 15px; font-size: 20px"
          >
            <i class="demo-pli-information icon-lg"></i>
            {{ title }}
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeBtn()"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-vcenter table-hover">
              <thead>
                <th class="text-thin" style="font-weight: 400">Company</th>
                <th class="text-thin" style="font-weight: 400">
                  Business Unit
                </th>
                <th class="text-thin" style="font-weight: 400">Department</th>
                <th class="text-thin" style="font-weight: 400">Section</th>
                <template
                  v-if="title == 'Select Consolidated Actual vs Advance Date'"
                >
                  <th class="text-thin" style="font-weight: 400">
                    Advance Count Start Date
                  </th>
                  <th class="text-thin" style="font-weight: 400">
                    Advance Count End Date
                  </th>
                  <th class="text-thin" style="font-weight: 400">
                    Actual Count Start Date
                  </th>
                  <th class="text-thin" style="font-weight: 400">
                    Actual Count End Date
                  </th>
                </template>
                <template
                  v-if="title == 'Select Consolidated Old Warehouse Date'"
                >
                  <th class="text-thin" style="font-weight: 400">
                    Count Start Date
                  </th>
                  <th class="text-thin" style="font-weight: 400">
                    Count End Date
                  </th>
                </template>
                <template
                  v-if="title == 'Select Consolidated New Warehouse Date'"
                >
                  <th class="text-thin" style="font-weight: 400">
                    Count Start Date
                  </th>
                  <th class="text-thin" style="font-weight: 400">
                    Count End Date
                  </th>
                </template>
                <template v-else>
                  <th class="text-thin" style="font-weight: 400">Date</th>
                </template>
                <th class="text-thin" style="font-weight: 400">Uploaded By</th>
                <th class="text-thin" style="font-weight: 400">Action</th>
              </thead>
              <tbody>
                <tr v-if="!data.length">
                  <td colspan="9" style="text-align: center">
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

                    <span v-else>No data available.</span>
                  </td>
                </tr>
                <tr v-for="(data, index) in data" :key="index">
                  <td class="text-thin" style="padding: 0">
                    {{ data.company }}
                  </td>
                  <td class="text-thin" style="padding: 0">
                    {{ data.business_unit }}
                  </td>
                  <td class="text-thin" style="padding: 0">
                    {{ data.department }}
                  </td>
                  <td class="text-thin" style="padding: 0">
                    {{ data.section }}
                  </td>
                  <template
                    v-if="title == 'Select Consolidated Actual vs Advance Date'"
                  >
                    <td class="text-thin" style="padding: 0">
                      {{ data.advance_count_date | formatDate }}
                    </td>
                    <td class="text-thin" style="padding: 0">
                      {{ data.advance_count_end | formatDate }}
                    </td>
                    <td class="text-thin" style="padding: 0">
                      {{ data.actual_count_date | formatDate }}
                    </td>
                    <td class="text-thin" style="padding: 0">
                      {{ data.actual_count_end | formatDate }}
                    </td>
                  </template>
                  <template
                    v-if="title == 'Select Consolidated Old Warehouse Date'"
                  >
                    <td class="text-thin" style="padding: 0">
                      {{ data.actual_count_date | formatDate }}
                    </td>
                    <td class="text-thin" style="padding: 0">
                      {{ data.actual_count_end | formatDate }}
                    </td>
                  </template>
                  <template
                    v-if="title == 'Select Consolidated New Warehouse Date'"
                  >
                    <td class="text-thin" style="padding: 0">
                      {{ data.actual_count_date | formatDate }}
                    </td>
                    <td class="text-thin" style="padding: 0">
                      {{ data.actual_count_end | formatDate }}
                    </td>
                  </template>
                  <template v-else>
                    <td class="text-thin" style="padding: 0">
                      {{ data.date | formatDate }}
                    </td>
                  </template>
                  <td class="text-thin" style="padding: 0">
                    {{ data.name }}
                  </td>
                  <td>
                    <button
                      class="btn btn-default btn-rounded"
                      @click="submitBtn(data)"
                    >
                      Select
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeBtn()"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  data() {
    return {
      data: [],
      date: null,
      message: null,
      isLoading: false
    }
  },
  props: {
    showModal: Boolean,
    title: String,
    business_unit: String,
    department: String,
    section: String,
    dateStart: String
  },
  watch: {
    showModal(value) {
      if (value) this.getResults()
    }
  },
  methods: {
    submitBtn(value) {
      this.$emit('passData', {
        batch_id: value.id,
        selectedData: value,
        type:
          this.title == 'Select Consolidated Actual vs Advance Date' ? 'APP' : this.title == 'Select Consolidated Old Warehouse Date' ? 'OLD' : this.title == 'Select Consolidated New Warehouse Date' ? 'NEW' : 'NAV'
      })
      this.data = []
    },
    getResults() {
      let title = null
      let section = this.section
      if (this.title == 'Select Consolidated Actual vs Advance Date' || this.title == 'Select Consolidated Old Warehouse Date' || this.title == 'Select Consolidated New Warehouse Date') {
        title = 'tbl_consolidate_adv_actual'
        section = this.title == 'Select Consolidated Old Warehouse Date' ? 'OLD WAREHOUSE' : this.title == 'Select Consolidated New Warehouse Date' ? 'NEW WAREHOUSE' : section
      } else {
        title = 'tbl_nav_upload'
      }
      this.isLoading = true
      axios
        .get(
          `/reports/variance_report/getDate?title=${title}&bu=${this.business_unit}&dept=${this.department}&section=${section}&date=${this.dateStart}`
        )
        .then(response => {
          this.data = response.data
          this.isLoading = false
        })
    },
    closeBtn() {
      this.data = []
      this.$emit('closeMdl', false)
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
</style>
