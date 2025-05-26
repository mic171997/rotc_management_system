<!-- @format -->
<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel">
        <div class="panel-body">
          <div class="panel-heading pad-all">
            <h3
              class="panel-heading bord-btm text-thin"
              style="font-size: 20px;/* padding: 15px 0px 0px 25px; */"
            >
              <i class="demo-pli-printer icon-lg"></i> {{ $root.currentPage }}
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <table class="table table-striped table-vcenter" id="data-table">
                <thead>
                  <tr>
                    <th>Description</th>
                    <th>Actual Count Date</th>
                    <th>Advance Count Date</th>
                    <th>Date Created</th>
                    <th>Created By</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!logs.data.length">
                    <td colspan="7" style="text-align: center;">
                      <div
                        class="sk-wave"
                        v-if="isLoading"
                        style="width: 100%; height: 50px; font-size: 30px; margin: 30px auto;"
                      >
                        <div class="sk-rect sk-rect1"></div>
                        <div class="sk-rect sk-rect2"></div>
                        <div class="sk-rect sk-rect3"></div>
                        <div class="sk-rect sk-rect4"></div>
                        <div class="sk-rect sk-rect5"></div>

                        <div
                          class="text-thin text-main"
                          style="font-size: 15px; margin: 10px 0px 10px 0px;"
                        >
                          Loading please wait... :)
                        </div>
                      </div>
                      <div v-else>No data available.</div>
                    </td>
                  </tr>
                  <tr
                    v-if="
                      logs.hasOwnProperty('items') && logs.items.hasData == true
                    "
                  >
                    <td class="text-main text-center" colspan="7">
                      <div class="bord-btm pad-btm">
                        <button
                          class="btn btn-block btn-rounded btn-lg btn-success v-middle text-thin"
                          :disabled="isLoading"
                          @click="generateBtn($event, 'Excel')"
                        >
                          Download file
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-else v-for="(data, index) in logs.data" :key="index">
                    <td class="text-main text-thin">
                      <a
                        href="javascript:;"
                        class="list-group-item list-item-sm"
                        style="border:0px"
                        @click="batchID(data)"
                      >
                        <span
                          class="badge badge-purple badge-icon badge-fw pull-left"
                        ></span>

                        {{ data.company }} {{ data.business_unit }}
                        {{ data.department }} {{ data.section }}
                      </a>
                    </td>
                    <td class="text-main text-thin">
                      {{ data.actual_count_date | formatDate }}
                    </td>
                    <td class="text-main text-thin">
                      {{ data.advance_count_date | formatDate }}
                    </td>
                    <td class="text-main text-thin">
                      {{ data.created_at | formatDate }}
                    </td>
                    <td class="text-main text-thin">
                      {{ data.name }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    Showing {{ logs.from }} to {{ logs.to }} of
                    {{ logs.total }} entries
                    <span v-if="searchProducts && !date"
                      >(Filtered from {{ logs_total_result }} total
                      entries)</span
                    >
                    <span v-if="searchProducts && date"
                      >(Filtered from {{ logs_total_result }} total
                      entries)</span
                    >
                  </div>
                  <div class="col-md-6">
                    <div class="text-right">
                      <pagination
                        style="margin: 0 0 20px 0"
                        :limit="1"
                        :show-disabled="true"
                        :data="logs"
                        @pagination-change-page="getResults"
                      ></pagination>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row" v-if="data.data.length">
            <div class="col-md-6 table-toolbar-left">
              <div class="btn-group pull-left">
                <div class="dropdown">
                  <button
                    class="btn btn-info btn-rounded text-thin mar-lft dropdown-toggle"
                    data-toggle="dropdown"
                    type="button"
                    aria-expanded="false"
                    :disabled="!data.data.length || isLoading"
                  >
                    <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate
                    Report
                    <i class="dropdown-caret"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right" style="">
                    <li class="dropdown-header">Actual Count (APP)</li>
                    <li>
                      <a
                        href="javscript:;"
                        @click="generateBtn($event, 'Excel')"
                        v-if="
                          !data.data.length ||
                            (data.hasOwnProperty('items') &&
                              data.items.hasData == false)
                        "
                      >
                        Generate Excel
                      </a>
                    </li>
                    <li>
                      <a href="javscript:;" @click="generateBtn($event, 'PDF')">
                        Generate PDF
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6 table-toolbar-right">
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-md-6 control-label text-main text-bold"
                    >Search:</label
                  >
                  <div class="col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      @keyup="search"
                      v-model="searchItems"
                      placeholder="Search via Item Code"
                    />
                  </div>
                </div>
              </form>
            </div>
            <table
              class="table table-striped table-vcenter panel-body"
              id="data-table"
            >
              <thead>
                <tr>
                  <th>Item Code</th>
                  <th>Barcode</th>
                  <th>Description</th>
                  <th>Uom</th>
                  <th>Actual Count Qty</th>
                  <th>Advance Count Qty</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="!data.data.length">
                  <td colspan="7" style="text-align: center;">
                    <div
                      class="sk-wave"
                      v-if="isLoading"
                      style="width: 100%; height: 50px; font-size: 30px; margin: 30px auto;"
                    >
                      <div class="sk-rect sk-rect1"></div>
                      <div class="sk-rect sk-rect2"></div>
                      <div class="sk-rect sk-rect3"></div>
                      <div class="sk-rect sk-rect4"></div>
                      <div class="sk-rect sk-rect5"></div>

                      <div
                        class="text-thin text-main"
                        style="font-size: 15px; margin: 10px 0px 10px 0px;"
                      >
                        Loading please wait... :)
                      </div>
                    </div>
                    <div v-else>No data available.</div>
                  </td>
                </tr>
                <tr
                  v-if="
                    data.hasOwnProperty('items') && data.items.hasData == true
                  "
                >
                  <td class="text-main text-center" colspan="7">
                    <div class="bord-btm pad-btm">
                      <button
                        class="btn btn-block btn-rounded btn-lg btn-success v-middle text-thin"
                        :disabled="isLoading"
                        @click="generateBtn($event, 'Excel')"
                      >
                        Download file
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-else v-for="(data, index) in data.data" :key="index">
                  <td class="text-main text-thin">{{ data.itemcode }}</td>
                  <td class="text-main text-thin">{{ data.barcode }}</td>
                  <td class="text-main text-thin">
                    {{ data.description }}
                  </td>
                  <td class="text-main text-normal text-center">
                    {{ data.uom }}
                  </td>
                  <td class="text-main text-normal text-center">
                    {{ data.actualCountQty | numberFormat }}
                  </td>
                  <td class="text-main text-normal text-center">
                    {{
                      data.advanceCountQty
                        ? data.advanceCountQty
                        : 0 | numberFormat
                    }}
                  </td>
                  <td class="text-main text-normal text-center">
                    {{ data.totalQty | numberFormat }}
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  Showing {{ data.from }} to {{ data.to }} of
                  {{ data.total }} entries
                  <span v-if="searchItems && !batch_id"
                    >(Filtered from {{ total_result }} total entries)</span
                  >
                  <span v-if="searchItems && batch_id"
                    >(Filtered from {{ total_result }} total entries)</span
                  >
                </div>
                <div class="col-md-6">
                  <div class="text-right">
                    <pagination
                      style="margin: 0 0 20px 0"
                      :limit="1"
                      :show-disabled="true"
                      :data="data"
                      @pagination-change-page="retrieveData"
                    ></pagination>
                  </div>
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
import { debounce } from 'lodash'
Vue.component('pagination', require('laravel-vue-pagination'))
Vue.component('v-select', vSelect)
export default {
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
      logs: {
        data: [],
        current_page: null,
        from: null,
        to: null,
        total: null,
        per_page: null
      },
      searchProducts: null,
      name: null,
      date: null,
      date2: null,
      logs_total_result: null,
      total_result: null,
      notFoundItems: 0,
      export: [],
      isLoading: false,
      batch_id: null,
      searchItems: null
    }
  },
  components: {},
  watch: {
    date() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        // this.vendor &&
        // this.category &&
        this.date2
      ) {
        this.getResults()
      }
    },
    date2() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        // this.vendor &&
        // this.category &&
        this.date
      ) {
        this.getResults()
      }
    },
    business_unit() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.date &&
        this.date2
        // this.vendor &&
        // this.category
      )
        this.getResults()
    },
    department() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.date &&
        this.date2
        // this.vendor &&
        // this.category
      )
        this.getResults()
    },
    section() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.date &&
        this.date2
        // this.vendor &&
        // this.category
      )
        this.getResults()
    },
    vendor(newValue) {
      if (newValue?.length == 0) this.vendor = null
      if (newValue) {
        const res = newValue.find(val => val.vendor_name === 'ALL VENDORS')

        if (res) {
          this.filteredvendorList = this.vendorList.filter(
            categ => categ.vendor_name === res.vendor_name
          )

          if (
            this.business_unit &&
            this.department &&
            this.section &&
            this.vendor &&
            this.category
          )
            this.getResults()
        } else {
          this.filteredvendorList = this.vendorList.filter(
            categ => categ.vendor_name !== 'ALL VENDORS'
          )
          let value = []

          newValue.forEach((element, index) => {
            value.push("'" + element.vendor_name + "'")
          })
          this.forPrintVendor = value.join(' , ')
          if (
            this.business_unit &&
            this.department &&
            this.section &&
            this.vendor &&
            this.category
          )
            this.getResults()
        }
      } else {
        this.filteredvendorList = this.vendorList
      }
    },
    category(newValue) {
      if (newValue?.length == 0) this.category = null
      if (newValue) {
        const res = newValue.find(val => val.category === 'ALL CATEGORIES')

        if (res) {
          this.filteredCategoryList = this.categoryList.filter(
            categ => categ.category === res.category
          )

          if (
            this.business_unit &&
            this.department &&
            this.section &&
            this.vendor &&
            this.category
          )
            this.getResults()
        } else {
          this.filteredCategoryList = this.categoryList.filter(
            categ => categ.category !== 'ALL CATEGORIES'
          )
          let value = []

          newValue.forEach((element, index) => {
            value.push("'" + element.category + "'")
          })
          this.forPrintCategory = value.join(' , ')
          if (
            this.business_unit &&
            this.department &&
            this.section &&
            this.vendor &&
            this.category
          )
            this.getResults()
        }
      } else {
        this.filteredCategoryList = this.categoryList
      }
    }
  },
  methods: {
    search() {
      this.searchForItem(this.searchItems, this)
    },
    searchForItem: _.debounce((search, vm) => {
      axios
        .get(
          `/reports/consolidate_adv_actual/variance/getLineData?batch_id=${vm.batch_id}&search=${search}&page=1`
        )
        .then(response => {
          vm.data = response.data
        })
    }, 300),
    batchID(data) {
      this.batch_id = data.batch_id
      this.searchItems = null
      this.retrieveData()
    },
    async retrieveData(page = 1) {
      await axios
        .get(
          `/reports/consolidate_adv_actual/variance/getLineData?batch_id=${this.batch_id}&page=` +
            page
        )
        .then(response => {
          this.data = response.data
          this.total_result = response.data.total
        })
    },
    async generateBtn(e, reportType) {
      Swal.fire({
        html: "Please wait, don't close the browser.",
        title: 'Generating report in progress',
        timerProgressBar: true,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
          Swal.showLoading()
        },
        willClose: () => {}
      }).then(result => {
        if (result.isConfirmed) {
        }
      })
      const countdata = this.data.items
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML

      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'

      let WebRoute = null

      reportType == 'PDF'
        ? (WebRoute = '/reports/consolidate_adv_actual/generate')
        : (WebRoute = '/reports/consolidate_adv_actual/generateExcel')

      const { headers, data } = await axios.post(
        WebRoute + `?`,
        {
          data: countdata,
          company: this.company,
          bu: this.business_unit,
          dept: this.department,
          section: this.section,
          date: btoa(this.date),
          date2: btoa(this.date2)
        },
        {
          responseType: 'blob'
        }
      )
      // return console.log(headers)
      const { 'content-disposition': contentDisposition } = headers
      const [attachment, file] = contentDisposition.split(' ')
      const [key, fileName] = file.split('=')

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement('a')
      link.href = url
      let section = null
      // console.log(fileName)
      this.section ? (section = '-' + this.section) : (section = '')

      let fileTypeExt = null
      reportType == 'PDF' ? (fileTypeExt = '.pdf') : (fileTypeExt = '.xlsx')
      link.setAttribute(
        'download',
        `Consolidated Advance & Actual Count (APP) as of ${this.date} ${this.business_unit} ${this.department} ${section}${fileTypeExt}`
      )
      // console.log(link)
      document.body.appendChild(link)
      link.click()

      thisButton.disabled = false
      thisButton.innerHTML = oldHTML
      Swal.close()
      this.getResults()
      $.niftyNoty({
        type: 'success',
        icon: 'pli-cross icon-2x',
        message: '<i class="fa fa-check"></i> Generate successful!',
        container: 'floating',
        timer: 5000
      })
    },

    getFormattedDateToday() {
      return new Date()
        .toJSON()
        .slice(0, 10)
        .replace(/-/g, '-')
    },
    async getCountData(page = 1) {
      let url = `/reports/consolidate_adv_actual/variance/getLogs?page=`

      return await axios.get(url + page)
    },
    async getExport() {
      let url = `/reports/advance_count/getResults/?date=${btoa(
        this.date
      )}&date2=${btoa(this.date2)}&vendors=${btoa(
        this.forPrintVendor
      )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${
        this.department
      }&section=${this.section}&countType=${this.countType}&forExport=true`

      return await axios.get(url)
    },
    getResults() {
      this.isLoading = true
      this.logs.data = []
      Promise.all([this.getCountData()]).then(response => {
        // this.export = []
        this.logs = response[0].data
        this.logs_total_result = response[0].data.total
        this.isLoading = false
      })
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.name = this.$root.authUser.name
    this.getResults()
  }
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}

#container .table > tbody > tr:hover {
  background-color: rgb(2 2 2 / 5%);
}

h5 {
  font-size: 15px;
}
</style>
