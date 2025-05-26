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
              <i class="demo-pli-printer icon-lg"></i> {{ $root.currentPage }}
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
                        <i class="icon-lg demo-pli-calendar-4 icon-fw"></i> Date
                        as of :
                      </h5>
                    </label>
                    <div class="col-lg-6">
                      <button
                        class="btn btn-default btn-rounded mar-lft text-thin text-main"
                        @click="
                          ;(showModal = !showModal),
                            (title = 'Select Nav Uploaded Date')
                        "
                        style="font-weight: 500"
                      >
                        <i
                          class="demo-pli-information icon-lg"
                          style="line-height: 0em"
                        ></i>
                        {{
                          !nav_id
                            ? ' Select Nav Uploaded Date'
                            : formatDate(selectedData.date, 'NavDate')
                        }}
                      </button>
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
              <div class="row" style="padding: 10px 15px 15px 10px">
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
                      Excel Report
                      <i class="dropdown-caret"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" style="">
                      <li class="dropdown-header">Generate Excel Report</li>
                      <li>
                        <a
                          href="javascript:void(0);"
                          @click="generateBtn($event, 'All')"
                        >
                          All Entries
                        </a>
                      </li>
                      <li>
                        <a
                          href="javascript:void(0);"
                          @click="generateBtn($event, 'Negative')"
                        >
                          Negative Entries
                        </a>
                      </li>
                      <li>
                        <a
                          href="javascript:void(0);"
                          @click="generateBtn($event, 'Positive')"
                        >
                          Positive Entries
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>

                <!-- <button
                  class="btn btn-danger btn-rounded pull-right text-thin"
                  :disabled="!data.data.length"
                  @click="exportBtn($event, 'Variance')"
                >
                  <i class="demo-pli-printer icon-lg"></i>&nbsp; Export Negative
                  Inventory Balance
                </button> -->
              </div>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center" style="vertical-align: middle">
                      Item Code
                    </th>
                    <th class="text-center" style="vertical-align: middle">
                      Description
                    </th>
                    <th class="text-center" style="vertical-align: middle">
                      UOM
                    </th>
                    <th class="text-center" style="vertical-align: middle">
                      Nav Sys Count
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-if="!data.data.length">
                    <td colspan="4" style="text-align: center">
                      <div
                        class="sk-wave"
                        v-if="isLoading"
                        style="
                          width: 100%;
                          height: 50px;
                          font-size: 30px;
                          margin: 30px auto;
                        "
                      >
                        <div class="sk-rect sk-rect1"></div>
                        <div class="sk-rect sk-rect2"></div>
                        <div class="sk-rect sk-rect3"></div>
                        <div class="sk-rect sk-rect4"></div>
                        <div class="sk-rect sk-rect5"></div>
                      </div>
                      <div v-else>No data available.</div>
                    </td>
                  </tr>
                  <tr v-for="(data, index) in returnData" :key="index">
                    <td class="text-main text-normal" style="font-size: 1.1em">
                      {{ data.itemcode }}
                    </td>
                    <td class="text-main text-normal" style="font-size: 1.1em">
                      {{ data.extended_desc }}
                    </td>
                    <td
                      class="text-main text-normal text-center"
                      style="font-size: 1.1em"
                    >
                      {{ data.uom }}
                    </td>
                    <td
                      class="text-main text-normal text-center"
                      style="font-size: 1.1em"
                    >
                      {{ data.nav_qty | numberFormat }}
                    </td>
                  </tr>
                  <tr
                    v-if="
                      returnData.length && returnData.length != data.data.length
                    "
                  >
                    <td class="text-main text-center" colspan="6">
                      <div class="bord-btm pad-btm">
                        <button
                          class="btn btn-block btn-rounded btn-default v-middle text-thin"
                          :disabled="isLoading"
                          @click="limit += 15"
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
                    Showing {{ returnData.length }} entries out of
                    <strong>{{ data.data.length }} entries </strong>
                  </div>
                  <div class="col-md-6"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Modal
      class="modal fade"
      id="rack-setup"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
      data-keyboard="false"
      data-backdrop="static"
      :showModal="showModal"
      :title="title"
      :business_unit="business_unit"
      :department="department"
      :section="section"
      @closeMdl="showModal = false"
      @passData="getData"
    ></Modal>
  </div>
</template>

<script>
import Vue from 'vue'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'
import Modal from './modals/DateUploaded.vue'
import types from 'vform'
var dayjs = require('dayjs')

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
      name: null,
      date: null,
      total_result: null,
      vendorList: [],
      filteredvendorList: [],
      vendor: null,
      categoryList: [],
      filteredcategoryList: [],
      category: null,
      forPrintVendor: [],
      forPrintCategory: [],
      companyList: [],
      company: null,
      buList: [],
      business_unit: null,
      deptList: [],
      department: null,
      sectionList: [],
      section: null,
      countType: null,
      countTypes: ['ANNUAL', 'CYCLICAL'],
      finalExport: [],
      export: [],
      isLoading: false,
      showModal: false,
      title: null,
      batch_id: null,
      selectedData: [],
      nav_id: null,
      navSelectedData: [],
      filteredData: [],
      limit: 15
    }
  },
  components: {
    Modal
  },
  computed: {
    returnData() {
      return this.filteredData.filter((el, index) => index < this.limit)
    }
  },
  watch: {
    showModal(val) {
      val == true
        ? $('#rack-setup').modal('show')
        : $('#rack-setup').modal('hide')
    },
    date() {
      if (this.business_unit && this.department && this.section && this.date) {
        this.getResults()
      }
    }
  },
  methods: {
    getData(value) {
      // console.log(value)
      if (value) {
        value.type == 'APP'
          ? (this.batch_id = value.batch_id)
          : (this.nav_id = value.batch_id)

        value.type == 'APP'
          ? (this.selectedData = value.selectedData)
          : (this.navSelectedData = value.selectedData)
      }

      $('#rack-setup').modal('hide')
      this.showModal = false
    },
    formatDate(value, type) {
      let date = dayjs(value).format('MM/DD/YYYY')
      type == 'AppStart'
        ? (this.date = date)
        : type == 'NavDate'
        ? (this.date = date)
        : null
      return date
    },
    async exportBtn(event, reportType) {
      Swal.fire({
        html: "Please wait, don't close the browser.",
        title: 'Exporting in progress',
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
      const thisButton = event.target
      const oldHTML = thisButton.innerHTML

      let pass = null,
        report = null
      if (reportType == 'Variance') {
        pass = '/reports/variance_report/export'
      } else {
        pass = '/reports/variance_report/NavUnposted'
      }

      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'
      const { headers, data } = await axios.post(
        pass,
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

      let title = 'Import to Nav'
      if (reportType == 'Variance w/ Cost') {
        title = 'Import to Nav Negative Inventory Balances'
      }

      link.setAttribute(
        'download',
        `${title} as of ${this.date}  ${this.business_unit} ${this.department}${section}` +
          '.csv'
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

      this.isLoading = true
      this.exportcsv(reportType)

      // document.location.href = `/reports/variance_report/generate`
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML

      let pass = null,
        title = null

      title =
        reportType == 'Positive'
          ? 'Positive Entries'
          : reportType == 'Negative'
          ? 'Negative Entries'
          : 'All Entries'

      pass = `/reports/nav_sys/exportResult?date=${btoa(this.date)}&date2=${btoa(
        this.date2
      )}&vendors=${btoa(this.forPrintVendor)}&category=${
        this.forPrintCategory
      }&bu=${this.business_unit}&dept=${this.department}&section=${
        this.section
      }&type=${reportType}`

      // if (type == 'Negative NetNavSys') {
      //   title = 'Sum of Items with Negative Inventory Balance'
      //   pass = `/reports/nav_sys/NetNavSys?date=${btoa(this.date)}&date2=${btoa(
      //     this.date2
      //   )}&vendors=${btoa(this.forPrintVendor)}&category=${
      //     this.forPrintCategory
      //   }&bu=${this.business_unit}&dept=${this.department}&section=${
      //     this.section
      //   }&type=NegativeNetNavSys`
      // } else if (type == 'Positive NetNavSys') {
      //   title = 'Sum of Items with Positive Inventory Balance'
      //   pass = `/reports/nav_sys/NetNavSys?date=${btoa(this.date)}&date2=${btoa(
      //     this.date2
      //   )}&vendors=${btoa(this.forPrintVendor)}&category=${
      //     this.forPrintCategory
      //   }&bu=${this.business_unit}&dept=${this.department}&section=${
      //     this.section
      //   }&type=PositiveNetNavSys`
      // } else if (type == 'NetNavSys') {
      //   title = 'Inventory Balance per Navision'
      //   pass = `/reports/nav_sys/NetNavSys?date=${btoa(this.date)}&date2=${btoa(
      //     this.date2
      //   )}&vendors=${btoa(this.forPrintVendor)}&category=${
      //     this.forPrintCategory
      //   }&bu=${this.business_unit}&dept=${this.department}&section=${
      //     this.section
      //   }&type=NetNavSys`
      // } else if (type == 'No Actual Count') {
      //   title = 'Negative Variance Report'
      //   pass = `/reports/not_in_count/generate?date=${btoa(
      //     this.date
      //   )}&date2=${btoa(this.date2)}&vendors=${btoa(
      //     this.forPrintVendor
      //   )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${
      //     this.department
      //   }&section=${this.section}&type=NotInCount`
      // }

      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'
      const { headers, data } = await axios.post(
        pass,
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
      // console.log(fileName)

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement('a')
      link.href = url
      // console.log('download', `${fileName.replace('"', '')}.pdf`)

      link.setAttribute(
        'download',
        `${title} as of ${this.date} ${this.business_unit} ${this.department} ${this.section}.pdf`
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
    getResults(page = 1) {
      let url = `/reports/nav_sys/getResults/?date=${btoa(this.date)}&bu=${
        this.business_unit
      }&dept=${this.department}&section=${this.section}&nav_id=${
        this.nav_id
      }&type=PositiveAdj&page=`
      if (this.business_unit && this.department && this.section) {
        this.isLoading = true
        this.data.data = []
        this.export = []
        this.finalExport = []
        this.filteredData = []

        axios.get(url).then(response => {
          // console.log(response.data.navData, response.data)
          this.data = response.data
          this.filteredData = this.data.data
          this.total_result = response.data.total
          this.finalExport = response.data
          // this.exportcsv()
          this.isLoading = false
        })
      }
    },
    exportcsv(reportType) {
      this.export = []

      let incode = null,
        variant_code = null,
        desc = null,
        qty = 0
      this.filteredData.forEach(result => {
        incode = result.itemcode
        variant_code = result.variant_code
        desc = result.extended_desc
        qty = result.nav_qty
        // return console.log(result, incode, variant_code, desc, qty, reportType)
        if (
          (reportType == 'Negative' && qty < 0) ||
          (reportType == 'Positive' && qty > 0)
        ) {
          qty = Math.abs(qty)
          this.export.push({
            incode,
            variant_code,
            desc,
            qty
          })
        }
      })
      // for (const [data, test] of Object.entries(this.finalExport)) {
      //   return console.log(data, test)
      //   let variance = 0,
      //     journalTemplateName = 'ITEM',
      //     journalBatchName = 'PCount Adj',
      //     lineNo = 0,
      //     itemCode = 0,
      //     postingDate = new Date().toJSON().slice(0, 10).replace(/-/g, '/'),
      //     entryType = '',
      //     docNo = '',
      //     desc = '',
      //     locCode = '',
      //     invtyPostGroup = '',
      //     nav_qty = 0,
      //     app_qty = 0,
      //     invQty = 0,
      //     unitAmt = 0,
      //     unitCost = 0,
      //     amt = 0,
      //     sourceCode = 'ITEMJNL',
      //     companyCode = 0,
      //     deptCode = 0,
      //     reasonCode = 0,
      //     genProdPostGroup = 0,
      //     docDate = new Date().toJSON().slice(0, 10).replace(/-/g, '/'),
      //     exDocNo = '',
      //     qtyPerUom = 0,
      //     uom = '',
      //     qtyBase = 0,
      //     invQtyBase = 0,
      //     valueEntry = '',
      //     itemDiv = 0,
      //     value = 0,
      //     variance2 = 0

      //   test.forEach(result => {
      //     itemCode = result.itemcode
      //     desc = result.extended_desc
      //     app_qty = parseFloat(result.app_qty)
      //     nav_qty = parseFloat(result.nav_qty)
      //     if (result.unposted != '-') {
      //       value = nav_qty + parseFloat(result.unposted)
      //     } else {
      //       value = nav_qty
      //     }
      //     if (value < 0) {
      //       entryType = 'Positive Adjmt.'
      //     } else {
      //       entryType = 'Negative Adjmt.'
      //     }

      //     uom = result.uom
      //     lineNo += 10000
      //     variance = Math.abs(value)
      //     variance2 = value
      //     invQty = Math.abs(value)
      //     qtyBase = Math.abs(value)
      //     invQtyBase = Math.abs(value)
      //     unitAmt = result.cost_no_vat
      //     unitCost = result.cost_no_vat
      //     amt = result.amt

      //     // console.log(variance2)
      //     if (variance2 < 0) {
      //       this.export.push({
      //         journalTemplateName,
      //         journalBatchName,
      //         lineNo,
      //         itemCode,
      //         postingDate,
      //         entryType,
      //         docNo,
      //         desc,
      //         locCode,
      //         invtyPostGroup,
      //         variance,
      //         invQty,
      //         unitAmt,
      //         unitCost,
      //         amt,
      //         sourceCode,
      //         companyCode,
      //         deptCode,
      //         reasonCode,
      //         genProdPostGroup,
      //         docDate,
      //         exDocNo,
      //         qtyPerUom,
      //         uom,
      //         qtyBase,
      //         invQtyBase,
      //         valueEntry,
      //         itemDiv
      //       })
      //     }
      //   })
      // }
    },
    computeNet(navQty, Unposted) {
      let net = 0
      if (Unposted != '-') {
        net = parseFloat(navQty) + parseFloat(Unposted)
      } else {
        net = parseFloat(navQty)
      }

      return net
    },
    computeVariance(a, b, c) {
      let variance = 0,
        value = 0
      if (b != '-') {
        value = parseFloat(a) + parseFloat(b)
      } else {
        value = parseFloat(a)
      }
      return value
    },
    getFormattedDateToday() {
      return new Date().toJSON().slice(0, 10).replace(/-/g, '-')
    },
    departmentSelected(val) {
      this.section = null
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
          .get(
            // `/setup/location/getDept/?bu=${bu.bunit_code}`
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
    getResults2() {
      Promise.all([this.getCompany()]).then(response => {
        this.companyList = response[0].data
      })
    },
    async getCompany() {
      return await axios.get('/uploading/nav_upload/getCompany')
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.name = this.$root.authUser.name
    // this.getResults()
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
