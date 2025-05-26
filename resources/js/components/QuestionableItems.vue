<!-- @format -->
<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel">
        <div class="panel-body">
          <div class="panel-heading pad-all">
            <h3
              class="panel-heading bord-btm text-thin"
              style="font-size: 20px; /* padding: 15px 0px 0px 25px; */"
            >
              <i class="demo-pli-printer icon-lg"></i> {{ $route.meta.name }}
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <div class="row pad-top">
                <div class="col-md-6 table-toolbar-left form-horizontal">
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-thin"
                      style="text-align: right"
                    >
                      <h5>Company :</h5></label
                    >
                    <div class="col-md-6">
                      <v-select
                        :options="companyList"
                        :reduce="companyList => companyList.acroname"
                        label="acroname"
                        v-model="company"
                        placeholder="Company"
                        @input="companySelected($event)"
                      ></v-select>
                    </div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-thin"
                      style="text-align: right"
                    >
                      <h5>Business Unit :</h5></label
                    >
                    <div class="col-md-6">
                      <v-select
                        v-model="business_unit"
                        label="business_unit"
                        :options="buList"
                        placeholder="Search for Business Unit"
                        :reduce="buList => buList.business_unit"
                        @input="buSelected($event)"
                        :disabled="!company"
                      >
                      </v-select>
                    </div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-thin"
                      style="text-align: right"
                    >
                      <h5>Department :</h5></label
                    >
                    <div class="col-md-6">
                      <v-select
                        :options="deptList"
                        :reduce="deptList => deptList.dept_name"
                        label="dept_name"
                        v-model="department"
                        placeholder="Department"
                        :disabled="!business_unit"
                        @input="departmentSelected($event)"
                      >
                      </v-select>
                    </div>
                  </div>
                  <div class="row pad-all" style="padding-left: 10px">
                    <label class="col-lg-3 control-label text-thin">
                      <h5>
                        <i class="icon-lg demo-pli-file-edit icon-fw"></i>
                        Count Type :
                      </h5>
                    </label>
                    <div class="col-lg-6">
                      <v-select
                        :options="['ACTUAL', 'ADVANCE']"
                        v-model="type"
                        placeholder="Select Count Type"
                        :disabled="!business_unit || !department || !section"
                      >
                      </v-select>
                    </div>
                  </div>
                  <div class="row pad-all" style="padding-left: 10px">
                    <label class="col-lg-3 control-label text-thin">
                      <h5>
                        <i class="icon-lg demo-pli-file-edit icon-fw"></i>
                        Count Type :
                      </h5>
                    </label>
                    <div class="col-lg-6">
                      <v-select
                        :options="countTypes"
                        label="countTypes"
                        v-model="countType"
                        placeholder="Count Type"
                        :disabled="
                          !business_unit || !department || !section || !type
                        "
                      ></v-select>
                    </div>
                  </div>
                  <div class="row pad-all" style="padding-left: 10px">
                    <label class="col-lg-3 control-label text-thin">
                      <h5>
                        <i class="icon-lg demo-pli-calendar-4 icon-fw"></i>
                        Count Date :
                      </h5>
                    </label>
                    <div class="col-lg-6">
                      <!-- <input
                        class="form-control"
                        v-model="date"
                        type="date"
                        style="border-radius: 4px"
                        :disabled="!business_unit || !department || !section"
                        id="maxDate"
                        name="maxDate"
                        max="dateMax"
                      /> -->

                      <v-select
                        :options="countDateList"
                        v-model="date"
                        :placeholder="
                          loadingDate
                            ? 'RETRIEVING DATE PLS WAIT...'
                            : 'Select Count Date'
                        "
                        :disabled="
                          !business_unit ||
                          !department ||
                          !section ||
                          !type ||
                          !countType ||
                          loadingDate
                        "
                        label="batchDate"
                        :reduce="countDateList => countDateList.batchDate"
                      >
                        <template slot="selected-option" slot-scope="option">
                          {{ formatDate(option.batchDate) }}
                        </template>
                      </v-select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 table-toolbar-right form-horizontal">
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-md-3 control-label text-thin">
                      <h5></h5>
                    </label>
                    <div class="col-md-6 pad-all"></div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-md-3 control-label text-thin">
                      <h5></h5>
                    </label>
                    <div class="col-md-6 pad-all"></div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-thin"
                      style="text-align: right"
                    >
                      <h5>Section :</h5></label
                    >
                    <div class="col-md-6">
                      <v-select
                        :options="sectionList"
                        :reduce="sectionList => sectionList.section_name"
                        label="section_name"
                        v-model="section"
                        placeholder="Section"
                        :disabled="!department"
                      ></v-select>
                    </div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-md-3 control-label text-thin">
                      <h5></h5>
                    </label>
                    <div class="col-md-6 pad-all"></div>
                  </div>
                </div>
              </div>
              <div class="row pad-all">
                <div class="btn-group pull-right">
                  <div class="dropdown">
                    <button
                      class="btn btn-info btn-rounded text-thin mar-lft dropdown-toggle"
                      :disabled="!data.data.length"
                      data-toggle="dropdown"
                      type="button"
                      aria-expanded="false"
                    >
                      <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate
                      Report
                      <i class="dropdown-caret"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" style="">
                      <li class="dropdown-header">Report Type</li>
                      <li>
                        <a
                          href="javascript:;"
                          @click="generateBtnEXCEL($event, 'Excel')"
                        >
                          Generate Excel
                        </a>
                      </li>
                      <!-- <li>
                        <a
                          href="javascript:;"
                          @click="generateBtnEXCEL($event, 'PDF')"
                        >
                          Generate PDF
                        </a>
                      </li> -->
                    </ul>
                  </div>
                </div>
                <button
                  class="btn btn-info btn-rounded mar-lft text-thin pull-right"
                  @click="showRackSetup = !showRackSetup"
                  :disabled="
                    !company ||
                    !business_unit ||
                    !department ||
                    !section ||
                    !countType
                  "
                >
                  <i class="demo-pli-add-user-star icon-lg"></i> Add Items
                </button>
              </div>
              <table class="table table-striped table-vcenter" id="data-table">
                <thead>
                  <tr>
                    <th>Item Code</th>
                    <th>Barcode</th>
                    <th>Variant Code</th>
                    <th>Description</th>
                    <th>Uom</th>
                    <th>Qty</th>
                    <th>Added By</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!data.data.length">
                    <td colspan="8" style="text-align: center">
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
                  <tr v-for="(data, index) in data.data" :key="index">
                    <td class="text-main text-thin" style="width: 10%">
                      {{
                        data.item_code
                          ? data.item_code
                          : data.itemcode
                          ? data.itemcode
                          : '-'
                      }}
                    </td>
                    <td class="text-main text-thin">
                      {{ data.barcode ? data.barcode : '-' }}
                    </td>
                    <td class="text-main text-thin">
                      {{ data.variant_code ? data.variant_code : '-' }}
                    </td>
                    <td class="text-main text-thin">
                      {{ data.description }}
                    </td>
                    <td class="text-main text-normal">
                      {{ data.uom }}
                    </td>
                    <td class="text-main text-normal">
                      {{ data.qty | numberFormat }}
                    </td>
                    <td class="text-main text-normal">
                      {{ data.name }}
                    </td>
                    <td class="text-main text-normal">
                      <!-- <button
                        class="btn btn-xs text-white font-medium bg-red-500 focus:outline-none border-red-500"
                        @click="editNavQty(data)"
                      >
                        <i class="demo-pli-pen-5"></i>
                      </button> -->
                      <button
                        class="btn btn-xs btn-danger pull-right text-white font-medium bg-red-500 focus:outline-none border-red-500"
                        :class="{
                          'cursor-not-allowed opacity-50': editItem != null
                        }"
                        :disabled='data.user_id != user.id'
                        @click="removeBtn(data)"
                      >
                        <span aria-hidden="true">×</span>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <!-- Showing {{ data.from }} to {{ data.to }} of
                    {{ data.total }} entries
                    <span v-if="searchProducts && !date"
                      >(Filtered from {{ total_result }} total entries)</span
                    >
                    <span v-if="searchProducts && date"
                      >(Filtered from {{ total_result }} total entries)</span
                    > -->
                  </div>
                  <div class="col-md-6">
                    <div class="text-right">
                      <!-- <pagination
                        style="margin: 0 0 20px 0"
                        :limit="1"
                        :show-disabled="true"
                        :data="data"
                        @pagination-change-page="getResults"
                      ></pagination> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Modal
      v-show="showRackSetup == true"
      :company="company"
      :business_unit="business_unit"
      :department="department"
      :section="section"
      :date="date"
      :editItem="editItem"
      class="modal fade"
      id="rack-setup"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
      data-keyboard="false"
      data-backdrop="static"
      :showRackSetup="showRackSetup"
      @getResults="getResults"
      @closeMdl="status"
      @saveSuccess="saveSuccess"
      :type="'QuestionableItems'"
    ></Modal>
  </div>
</template>

<script>
import Vue from 'vue'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'
import Modal from './modals/ItemSetup.vue'
Vue.component('v-select', vSelect)
var dayjs = require('dayjs')

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
      searchProducts: null,
      name: null,
      date: null,
      dateMax: this.getFormattedDateToday(),
      total_result: null,
      companyList: [],
      company: null,
      buList: [],
      business_unit: null,
      deptList: [],
      department: null,
      sectionList: [],
      section: null,
      notFoundItems: 0,
      export: [],
      showRackSetup: false,
      isLoading: false,
      editItem: null,
      countType: null,
      countTypes: ['ANNUAL', 'CYCLICAL'],
      countDateList: [],
      countDate: null,
      type: null,
      loadingDate: false,
      user: []
    }
  },
  components: {
    Modal
  },
  watch: {
    type() {
      this.date = null
      !this.date
        ? (this.getCountDate(), (this.data.data = []))
        : this.business_unit &&
          this.department &&
          this.section &&
          this.type &&
          this.date
        ? this.getResults()
        : null
    },
    countType() {
      this.date = null
      !this.date
        ? (this.getCountDate(), (this.data.data = []))
        : this.business_unit &&
          this.department &&
          this.section &&
          this.type &&
          this.date
        ? this.getResults()
        : null
    },
    showRackSetup() {
      if (this.showRackSetup) $('#rack-setup').modal('show')
    },
    date(val) {
      console.log(val)
      !this.date
        ? (this.getCountDate(), (this.data.data = []))
        : this.business_unit &&
          this.department &&
          this.section &&
          this.type &&
          this.date
        ? this.getResults()
        : null
    },

    business_unit() {
      !this.date
        ? this.getCountDate()
        : this.business_unit &&
          this.department &&
          this.section &&
          this.type &&
          this.date
        ? this.getResults()
        : null

      // this.getResults()
    },
    department() {
      !this.date
        ? this.getCountDate()
        : this.business_unit &&
          this.department &&
          this.section &&
          this.type &&
          this.date
        ? this.getResults()
        : null
    },
    section() {
      this.date = null
      !this.date2
        ? this.getCountDate()
        : this.business_unit &&
          this.department &&
          this.section &&
          this.type &&
          this.date
        ? this.getResults()
        : null
    }
  },
  methods: {
    async removeBtn(data) {
      const xdata = btoa(JSON.stringify(data))
      const result = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
      })

      if (result.isConfirmed) {
        try {
          const response = await axios.post(
            `/setup/countBackendSetup/removeItem?item=${xdata}`
          )
          // Handle the response from the server
          console.log(response.data)
          this.getResults()
          Swal.fire({
            title: 'Deleted!',
            text: 'Your file has been deleted.',
            icon: 'success',
            timer: 2000,
            timerProgressBar: true
          })
        } catch (error) {
          // Handle the error
          console.log(error.response.data)
          Swal.fire({
            title: 'Error!',
            text: 'An error occurred while deleting your file.',
            icon: 'error',
            timer: 2000,
            timerProgressBar: true
          })
        }
      } else {
        // User canceled the alert
      }
    },
    formatDate(value) {
      value ? (this.date = value) : null
      let date = dayjs(value).format('MM/DD/YYYY')
      return date
    },
    editNavQty(data) {
      this.editItem = data
      this.showRackSetup = !this.showRackSetup
    },
    saveSuccess(value) {
      console.log(value)
      this.showRackSetup = value
      if (value == false) this.getResults()
    },
    status(value) {
      // console.log(value)
      this.editItem = null
      this.showRackSetup = value
    },
    async generateBtnEXCEL(e, reportType) {
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

      const countdata = this.data.data
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML

      let pass = null,
        report = null
      if (reportType == 'Excel') {
        report = '&report=Excel'
        pass = '/reports/backend/generateQuestionableItems'
      }

      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'
      const { headers, data } = await axios.post(
        pass +
          `?date=${btoa(this.date)}&bu=${this.business_unit}&dept=${
            this.department
          }&section=${this.section}&countType=${this.countType}` +
          report,
        { data: countdata },
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

      let title = 'Questionable Items from Count',
        fileType = '.xlsx'
      if (reportType == 'NotFound Excel') {
        title = 'Items Not Found from Actual Count (APP)'
      } else if (reportType == 'NotFound PDF') {
        title = 'Items Not Found from Actual Count (APP)'
        fileType = '.pdf'
      }
      link.setAttribute(
        'download',
        `${title} as of ${this.date}  ${this.business_unit} ${this.department}${section}${fileType}`
      )
      // console.log(link)
      document.body.appendChild(link)
      link.click()

      thisButton.disabled = false
      thisButton.innerHTML = oldHTML
      Swal.close()
      $.niftyNoty({
        type: 'success',
        icon: 'pli-cross icon-2x',
        message: '<i class="fa fa-check"></i> Generate successful!',
        container: 'floating',
        timer: 5000
      })
    },
    async generateBtn(e) {
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
      // document.location.href = `/reports/appdata/generate`
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML

      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'

      const { headers, data } = await axios.post(
        `/reports/appdata/generate`,
        {
          export: btoa(JSON.stringify(this.export))
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
      // console.log(fileName)
      link.setAttribute(
        'download',
        `Actual Count (APP) as of ${this.date} ${this.business_unit} ${this.department}${section}.pdf`
      )
      // console.log(link)
      document.body.appendChild(link)
      link.click()

      thisButton.disabled = false
      thisButton.innerHTML = oldHTML
      Swal.close()
      $.niftyNoty({
        type: 'success',
        icon: 'pli-cross icon-2x',
        message: '<i class="fa fa-check"></i> Generate successful!',
        container: 'floating',
        timer: 5000
      })
    },
    departmentSelected(val) {
      const department = this.deptList.filter(sm => sm.dept_name == val)[0]
      const bu = this.buList.filter(
        sm => sm.business_unit == this.business_unit
      )[0]
      const company = this.companyList.find(e => e.acroname == this.company)
      axios
        .get(
          // `/setup/location/getSection/?bu=${bu.bunit_code}&dept=${department.dept_code}`
          `/uploading/nav_upload/getSection/?code=${company.company_code}&bu=${bu.bunit_code}&dept=${department.dept_code}`
        )
        .then(response => {
          this.sectionList = response.data
        })
        .catch(response => {
          console.log('error')
        })
    },
    buSelected(val) {
      this.department = null
      this.section = null
      if (val) {
        const bu = this.buList.filter(sm => sm.business_unit == val)[0]
        const company = this.companyList.find(e => e.acroname == this.company)
        axios
          // .get(`/setup/location/getDept/?bu=${bu.bunit_code}`)
          .get(
            `/setup/location/getDept/?code=${company.company_code}&bu=${bu.bunit_code}`
          )
          .then(response => {
            this.deptList = response.data
          })
          .catch(response => {
            console.log('error')
          })
      }
    },
    companySelected(val) {
      this.business_unit = null
      this.department = null
      this.section = null
      // console.log(val)
      if (val) {
        const comp = this.companyList.filter(sm => sm.acroname == val)[0]
        axios
          .get(`/uploading/nav_upload/getBU/?code=${comp.company_code}`)
          .then(response => {
            this.buList = response.data
          })
          .catch(response => {
            console.log('error')
          })
      }
    },
    async getBU() {
      return await axios.get('/setup/location/getBU')
    },

    async getCompany() {
      return await axios.get('/uploading/nav_upload/getCompany')
    },
    getResults2() {
      Promise.all([this.getCompany()]).then(response => {
        this.companyList = response[0].data
      })
    },
    getFormattedDateToday() {
      return new Date().toJSON().slice(0, 10).replace(/-/g, '-')
    },

    async getCountData(page = 1) {
      let url = `/reports/backend/questionable_items?date=${btoa(
        this.date
      )}&vendors=${btoa(this.forPrintVendor)}&category=${
        this.forPrintCategory
      }&bu=${this.business_unit}&dept=${this.department}&section=${
        this.section
      }&type=${this.type}&countType=${this.countType}&page=`

      return await axios.get(url + page)
    },
    async getExport() {
      let url = `/reports/appdata/getResults/?date=${btoa(
        this.date
      )}&date2=${btoa(this.date2)}&vendors=${btoa(
        this.forPrintVendor
      )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${
        this.department
      }&section=${this.section}&countType=${this.countType}&forExport=true`

      return await axios.get(url)
    },
    async getCountDate() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.type &&
        this.countType
      ) {
        this.loadingDate = true
        return await axios
          .get(
            `/reports/backend/getCountDate?bu=${this.business_unit}&dept=${this.department}&sect=${this.section}&type=${this.type}`
          )
          .then(response => {
            this.countDateList = response.data
            this.loadingDate = false
          })
      }
    },
    getResults() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.date &&
        this.countType &&
        this.type
      ) {
        this.isLoading = true
        Promise.all([this.getCountData()]).then(response => {
          // this.export = []
          this.data.data = response[0].data
          this.total_result = response[0].data.total
          // this.export = response[0].data
          this.isLoading = false
        })
      }
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.name = this.$root.authUser.name
    this.user = this.$root.authUser
    // document.getElementById('maxDate').setAttribute('max', this.dateMax)
    this.getResults2()
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
