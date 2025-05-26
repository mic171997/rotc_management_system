<!-- @format -->
<template>
    <div id="page-body">
        <div id="page-content">
            <div class="panel">
                <div class="panel-body">
                    <div class="panel-heading pad-all">
                        <h3 class="panel-heading bord-btm text-thin"
                            style="font-size: 20px; /* padding: 15px 0px 0px 25px; */">
                            <i class="demo-pli-printer icon-lg"></i> {{ $route.meta.name }}
                        </h3>
                    </div>
                    <div class="row">
                        <div class="table-responsive panel-body">
                            <div class="row pad-top">
                                <div class="col-md-6 table-toolbar-left form-horizontal">
                                    <div class="row" style="padding: 10px 15px 15px 10px">
                                        <label class="col-md-3 control-label text-thin" style="text-align: right">
                                            <h5>Company :</h5>
                                        </label>
                                        <div class="col-md-6">
                                            <v-select :options="companyList" :reduce="companyList => companyList.acroname"
                                                label="acroname" v-model="company" placeholder="Company"
                                                @input="companySelected($event)" style="font-size: 12px"></v-select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding: 10px 15px 15px 10px">
                                        <label class="col-md-3 control-label text-thin" style="text-align: right">
                                            <h5>Business Unit :</h5>
                                        </label>
                                        <div class="col-md-6">
                                            <v-select v-model="business_unit" label="business_unit" :options="buList"
                                                placeholder="Search for Business Unit"
                                                :reduce="buList => buList.business_unit" @input="buSelected($event)"
                                                :disabled="!company">
                                            </v-select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding: 10px 15px 15px 10px">
                                        <label class="col-md-3 control-label text-thin" style="text-align: right">
                                            <h5>Department :</h5>
                                        </label>
                                        <div class="col-md-6">
                                            <v-select :options="deptList" :reduce="deptList => deptList.dept_name"
                                                label="dept_name" v-model="department" placeholder="Department"
                                                :disabled="!company || !business_unit" @input="departmentSelected($event)">
                                            </v-select>
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 10px 15px 15px 10px">
                                        <label class="col-md-3 control-label text-thin" style="text-align: right">
                                            <h5>Section :</h5>
                                        </label>
                                        <div class="col-md-6">
                                            <v-select :options="sectionList"
                                                :reduce="sectionList => sectionList.section_name" label="section_name"
                                                v-model="section" placeholder="Section"
                                                :disabled="!company || !business_unit || !department"></v-select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 table-toolbar-right form-horizontal">

                                    <div class="row pad-all" style="padding-left: 10px">
                                        <label class="col-lg-3 control-label text-thin">
                                            <h5>
                                                <i class="icon-lg demo-pli-file-edit icon-fw"></i>
                                                Count Type :
                                            </h5>
                                        </label>
                                        <div class="col-lg-6">
                                            <v-select :options="countTypes" label="countTypes" v-model="countType"
                                                placeholder="Count Type" :disabled="
                                                    !company ||
                                                    !business_unit ||
                                                    !department ||
                                                    !section
                                                "></v-select>
                                        </div>
                                    </div>

                                    <div class="row pad-all" style="padding-left: 10px;">
                                        <label class="col-lg-3 control-label text-thin">
                                            <h5>
                                                <i class="icon-lg demo-pli-file-edit icon-fw"></i>
                                                Type :
                                            </h5>
                                        </label>
                                        <div class="col-lg-6">
                                            <v-select :options="Types" label="types" v-model="Type" placeholder="Count Type"
                                                :disabled="!company || !business_unit || !department || !section || !countType"></v-select>
                                        </div>
                                    </div>


                                    <div class="row pad-all" style="padding-left: 10px">
                                        <label class="col-lg-3 control-label text-thin">
                                            <h5>
                                                <i class="icon-lg demo-pli-calendar-4 icon-fw"></i> Date
                                                as of :
                                            </h5>
                                        </label>
                                        <div class="col-lg-3">

                                            <date-picker v-model="date" type="date" :disabled-date="
                                                date =>
                                                    !availableDates.includes(
                                                        new Date(
                                                            Date.UTC(
                                                                date.getFullYear(),
                                                                date.getMonth(),
                                                                date.getDate()
                                                            )
                                                        )
                                                            .toISOString()
                                                            .substr(0, 10)
                                                    )
                                            " format="YYYY-MM-DD" value-type="YYYY-MM-DD" :placeholder="
    loadingDate
        ? 'RETRIEVING DATE PLS WAIT...'
        : !availableDates.length
            ? 'No dates available.'
            : 'Select date range'
" :disabled="
    !company ||
    !business_unit ||
    !department ||
    !section ||

    !countType ||
    loadingDate ||
    !availableDates.length
"></date-picker>
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 10px 15px 15px 10px">
                                        <label class="col-md-3 control-label text-thin" style="text-align: right">
                                            <h5>Search Items :</h5>
                                        </label>
                                        <div class="col-md-6">

                                            <input type="text"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                @keyup="handleSearch" v-model="barcode" placeholder="Search Barcode"
                                                :disabled="!company || !business_unit || !department || !section || !date || !Type">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="flex justify-end ">
                            <div class="text-lg mr-5">
                                <div class="bg-gray-200 ">

                                    <router-link to="/backendadjustment">
                                        <a href="javascript:;" class="list-group-item panel-title" @click=clear1()>
                                            <i class="fa fa-edit"></i>
                                            &nbsp; Back-End Adjustment

                                        </a>
                                    </router-link>

                                </div>
                            </div>
                            <div class="text-lg mr-20">
                                <div class="bg-gray-200 ">

                                    <router-link to="/deletehistory">
                                        <a href="javascript:;" class="list-group-item panel-title" @click=clear1()><i
                                                class="fa fa-trash"></i>
                                            &nbsp; Deleted History

                                        </a>
                                    </router-link>

                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-vcenter" id="data-table">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Barcode</th>
                                    <th>Description</th>
                                    <th>Ex_Description</th>
                                    <th>Uom</th>
                                    <th>Variant Code</th>
                                    <th>Department</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!getdata.data.length">
                                    <td colspan="13" style="text-align: center">
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
                                        <span class="text-thin text-main" v-if="isLoading">Loading please wait... :)
                                        </span>

                                        <div v-else>No data available.</div>
                                    </td>
                                </tr>
                                <tr v-for="(datas, index) in getdata.data" :key="index">
                                    <td class="text-main text-thin">{{ datas.item_code }}</td>
                                    <td class="text-main text-thin">{{ datas.barcode }}</td>
                                    <td class="text-main text-thin">
                                        {{ datas.desc }}
                                    </td>
                                    <td class="text-main text-normal text-left">
                                        {{ datas.extended_desc }}
                                    </td>
                                    <td class="text-main text-normal text-left">
                                        {{ datas.uom }}
                                    </td>
                                    <td class="text-main text-normal text-left">
                                        {{ datas.variant_code }}
                                    </td>
                                    <td class="text-main text-normal text-left">
                                        {{ datas.section }}
                                    </td>

                                    <td>

                                        <button class="btn btn-primary btn-xs  icon-lg add-tooltip" data-container="body"
                                            data-toggle="modal" data-target="#exampleModal" data-original-title="Delete"
                                            @click="editBtn(datas.id)">
                                            <i class="fa fa-save"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    Showing {{ getdata.from }} to {{ getdata.to }} of
                                    {{ data.total }} entries

                                    <span v-if="barcode && date">(Filtered from {{ total_result }} total
                                        entries)</span>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-right">
                                        <pagination style="margin: 0 0 20px 0" :limit="1" :show-disabled="true"
                                            :data="getdata" @pagination-change-page="handleSearch"></pagination>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">For Input Items</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                @click="clearmodal()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="flex flex-wrap -mx-3 mb-3">
                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Item Code:</label>&nbsp;<label>{{ labelitemcode }}</label>
                                </div>
                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Barcode:</label>&nbsp;<label>{{ labelbarcode }}</label>
                                </div>

                            </div>

                            <div class="flex flex-wrap -mx-3 mb-3">
                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Description:</label>&nbsp;<label>{{ labeldescription }}</label>
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Extended Desc:</label>&nbsp;<label>{{ labelquantity }}</label>
                                </div>
                            </div>

                            <div class="flex flex-wrap -mx-3 mb-3">
                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Uom:</label>&nbsp;<label>{{ labeluom }}</label>
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Department:</label>&nbsp;<label>{{ labelbu }}</label>
                                </div>
                            </div>

                            <div class="flex flex-wrap -mx-3 mb-3">
                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Quantity:</label>
                                    <input type="number" v-model="newqty"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Enter Desire Quantity">

                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Rack Location:</label>
                                    <br>
                                    <div class="col-lg-12">
                                        <v-select v-model="racklocation" :options="racklits" :disabled="!newqty"
                                            placeholder="Rack Location"></v-select>

                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-3 mb-3">
                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>IAD:</label>
                                    <br>
                                    <div class="col-lg-12">
                                        <v-select v-model="iad" :options="namelist" placeholder="Select IAD"
                                            :reduce="namelist => namelist.audit" label="audit" :disabled="!racklocation">

                                        </v-select>
                                    </div>

                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Store Rep:</label>
                                    <br>
                                    <div class="col-lg-12">
                                        <v-select v-model="storerep" :options="userlist"
                                            :reduce="userlist => userlist.storerep" label="storerep" :disabled="!iad"
                                            placeholder="Select Store Rep"></v-select>

                                    </div>

                                </div>
                            </div>


                            <div class="flex flex-wrap -mx-3 mb-3">

                                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                                    <label>Actions:</label>
                                    <br>
                                    <button type="submit" class="btn btn-info" @click="saveBtn()">
                                        Save
                                    </button>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                @click="clearmodal()">Close</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
import Vue from 'vue'
// import 'bootstrap/dist/css/bootstrap.css';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import moment from 'moment'
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
            searchProducts: null,
            name: null,
            date: null,
            date2: this.getFormattedDateToday(),
            total_result: null,
            companyList: [],
            racklits: [],
            namelist: [],
            userlist: [],
            userid: null,
            company: null,
            buList: [],
            business_unit: null,
            deptList: [],
            department: null,
            sectionList: [],
            section: null,
            barcode: '',
            Type: null,
            vendorList: [],
            filteredvendorList: [],
            vendor: null,
            categoryList: [],
            filteredCategoryList: [],
            category: null,
            forPrintVendor: [],
            forPrintCategory: [],
            countType: null,
            countTypes: ['ANNUAL', 'CYCLICAL'],
            Types: ['ADVANCE', 'ACTUAL', 'FREE GOODS'],
            notFoundItems: 0,
            export: [],
            isLoading: false,
            availableDates: [],
            loadingDate: false,
            reason: null,
            getdata: {
                data: [],
                current_page: null,
                from: null,
                to: null,
                total: null,
                per_page: null
            },

            labelitemcode: null,
            labelbarcode: null,
            labeldescription: null,
            labeluom: null,
            labelquantity: null,
            labelrackdesc: null,
            labelbu: null,
            labeldepartment: null,
            labelsection: null,
            labelexported: null,
            labelstorerep: null,
            labelauditor: null,
            buttonclick: null,
            newqty: null,
            newuom: null,
            racklocation: null,
            iad: null,
            storerep: null,
            iad_id: '',
            storereptest: [],
            getinfo: {
                data: []
            }
        }
    },
    components: {
        DatePicker,
        moment
    },
    watch: {
        iad() {
            this.storerep = null,
                //     this.storereptest = []
                // this.storereptest.push(value)
                this.getuser();
        },
        racklocation() {
            this.iad = null,
                this.getaudit()
        },
        newqty() {
            this.iad = null
            this.storerep = null
            this.racklocation = null
        },
        Type() {

            this.isLoading = false
            this.date = null
            this.barcode = null
            this.clear()
            this.getDates()
        },

        company() {
            this.business_unit = null
            this.department = null
            this.section = null
            this.countType = null
            this.Type = null
            this.barcode = null
            this.isLoading = false
            this.clear()
        },
        barcode() {

        },

        date2() { },
        business_unit() {
            this.department = null
            this.section = null
            this.countType = null
            this.Type = null
            this.barcode = null
            this.clear()
            this.isLoading = false

        },
        department() {
            this.section = null
            this.countType = null
            this.Type = null
            this.barcode = null
            this.clear()
            this.isLoading = false

        },

        section() {
            this.countType = null
            this.Type = null
            this.barcode = null
            this.clear()
            this.isLoading = false
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
                        this.category &&
                        this.date
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
                this.getDates()

                if (res) {
                    this.filteredCategoryList = this.categoryList.filter(
                        categ => categ.category === res.category
                    )

                    if (
                        this.business_unit &&
                        this.department &&
                        this.section &&
                        this.vendor &&
                        this.category &&
                        this.date
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
                        this.getDates()
                    this.getResults()
                }
            } else {
                this.filteredCategoryList = this.categoryList
            }
        }
    },
    methods: {
        clearData: function () {
            for (let prop in this.data) {
                if (Array.isArray(this.data[prop])) {
                    this.data[prop] = []
                } else {
                    this.data[prop] = null
                }
            }
        },
        editBtn(id) {


            axios.get(`/reports/backendadjustment/getinputedit?id=${id}&bu=${this.business_unit}&section=${this.section}`).then(response => {
                console.log(response.data[0].id)
                this.getinfo = response.data
                this.labelitemcode = response.data[0].item_code
                this.labelbarcode = response.data[0].barcode
                this.labeldescription = response.data[0].desc
                this.labeluom = response.data[0].uom
                this.labelquantity = response.data[0].extended_desc
                this.labelrackdesc = response.data[0].variant_code
                this.labelbu = response.data[0].section
                this.getRacklocation()

            })
        },



        saveBtn() {
            $('#exampleModal').modal('hide')
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',

                showCancelButton: true,
                confirmButtonText: 'Yes, Save it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`/reports/backendadjustment/save?itemcode=${this.labelitemcode}&barcode=${this.labelbarcode}&c_qty=${this.getinfo[0].conversion_qty}&desc=${this.labeldescription}&ex_desc=${this.labelquantity}&uom=${this.labeluom}&qty=${this.newqty}&countType=${this.Type}&bu=${this.business_unit}&dept=${this.department}&sect=${this.section}&location=${this.racklocation}&iad=${this.iad}&storerep=${this.storerep}&userid=${this.userid}&date=${this.date}`).then(response => {
                        swalWithBootstrapButtons.fire(
                            'SAVE!',
                            'Your file has been saved.',
                            'success'

                        )
                        this.clearmodal();
                    })


                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel

                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your file was not saved.',
                        'error'
                    )
                    this.clearmodal();
                }
                // $('#exampleModal').modal('show')
            })
            // this.clear()

        },
        clear() {
            this.getdata.data = ''
            this.getdata.current_page = ''
            this.getdata.from = ''
            this.getdata.to = ''
            this.getdata.total = ''
            this.getdata.per_page = ''
        },

        clear1() {
            this.company = null
            this.business_unit = null
            this.department = null
            this.section = null
            this.countType = null
            this.Type = null
            this.barcode = null
            this.isLoading = false
            this.date = null
            this.clear()
        },
        clearmodal() {

            this.newqty = null
            this.iad = null
            this.storerep = null
            this.racklocation = null
        },
        retrieve: debounce(vm => {
            vm.isLoading = true
            vm.getResults()
            // vm.isLoading = false
        }, 2000),
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
                willClose: () => { }
            }).then(result => {
                if (result.isConfirmed) {
                }
            })
            // document.location.href = `/reports/appdata/generate`
            const thisButton = e.target
            const oldHTML = thisButton.innerHTML

            let pass = null,
                report = null
            if (reportType == 'CountData') {
                report = '&report=Excel'
                pass = '/reports/appdata/generateAppDataExcel'
            } else if (reportType == 'NotFound Excel') {
                pass = '/reports/appdata/generateNotFound'
                report = '&report=Excel'
            } else if (reportType == 'NotFound PDF') {
                pass = '/reports/appdata/generateNotFound'
                report = '&report=PDF'
            }
            thisButton.disabled = true
            thisButton.innerHTML =
                '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'
            const { headers, data } = await axios.get(
                pass +
                `?date=${btoa(this.date)}&date2=${btoa(this.date2)}&vendors=${btoa(
                    this.forPrintVendor
                )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${this.department
                }&section=${this.section}&countType=${this.countType}&type=ACTUAL` +
                report,
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

            let title = 'Actual Count (APP)',
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
                willClose: () => { }
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

            // `/reports/appdata/generate?date=${btoa(this.date)}&date2=${btoa(
            //   this.date2
            // )}&vendors=${btoa(this.forPrintVendor)}&category=${
            //   this.forPrintCategory
            // }&bu=${this.business_unit}&dept=${this.department}&section=${
            //   this.section
            // }&countType=${this.countType}`

            const { headers, data } = await axios.post(
                `/reports/appdata/generate`,
                {
                    // export: btoa(JSON.stringify(this.export)),
                    bu: this.business_unit,
                    dept: this.department,
                    section: this.section,
                    date: btoa(this.date)
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
        retrieveCategory(search, loading) {
            if (search) {
                loading(true)
                this.search2(search, loading, this)
            }
        },
        search2: debounce((search, loading, vm) => {
            if (search.trim().length > 0) {
                axios
                    .get(`/uploading/nav_upload/getCategory?category=${search}`)
                    .then(({ data }) => {
                        vm.filteredCategoryList = data
                        loading(false)
                    })
                    .catch(error => {
                        vm.filteredCategoryList = []
                        loading(false)
                    })
            } else {
                vm.filteredCategoryList = []
                loading(false)
            }
        }, 1000),
        retrieveVendor(search, loading) {
            loading(true)
            this.search(search, loading, this)
        },
        search: debounce((search, loading, vm) => {
            if (search.trim().length > 0) {
                axios
                    .get(`/uploading/nav_upload/getVendor?vendor=${search}`)
                    .then(({ data }) => {
                        vm.filteredvendorList = data
                        loading(false)
                    })
                    .catch(error => {
                        vm.filteredvendorList = []
                        loading(false)
                    })
            } else {
                vm.filteredvendorList = []
                loading(false)
            }
        }, 1000),
        async getBU() {
            return await axios.get('/setup/location/getBU')
        },
        async getCategory() {
            return await axios.get('/uploading/nav_upload/getCategory')
        },
        async getVendor() {
            return await axios.get('/uploading/nav_upload/getVendor')
        },
        // async getCompany() {
        //     return await axios.get('/uploading/nav_upload/getCompany')
        // },

        async getCompany() {
            await axios.get('/uploading/nav_upload/getCompany')
                .then(response => {

                    this.companyList = response.data

                })

        },

        // getResults2() {
        //     Promise.all([
        //         // this.getVendor(),
        //         // this.getCategory(),
        //         // this.getBU(),
        //         this.getCompany()
        //     ]).then(response => {
        //         // this.vendorList = response[0].data
        //         // this.filteredvendorList = response[0].data
        //         // this.categoryList = response[1].data
        //         // this.filteredCategoryList = response[1].data
        //         this.companyList = response[0].data
        //     })
        // },
        getFormattedDateToday() {
            return new Date().toJSON().slice(0, 10).replace(/-/g, '-')
        },

        async searchresult(page = 1) {


            this.isloading = true;

            await axios
                .get(
                    `/reports/backendadjustment/getforinput/?bu=${this.business_unit}&section=${this.section}&barcode=${this.barcode}&page=` + page
                )
                // this.isLoading = true
                .then(response => {

                    this.getdata = response.data
                    this.total_result = response.data.total
                    this.isLoading = false
                })

        },

        handleSearch: debounce(function (page = 1)
        // async getCountData(page = 1)
        {
            if (this.barcode === "") {
                this.barcode = null;
                this.searchresult()
                this.isloading = true;

            } else {
                // vm.isloading = true
                this.isLoading = true;
                axios
                    .get(
                        `/reports/backendadjustment/getforinput/?bu=${this.business_unit}&section=${this.section}&barcode=${this.barcode}&page=` + page
                    )
                    // this.isLoading = true
                    .then(response => {

                        this.getdata = response.data
                        this.total_result = response.data.total
                        this.isLoading = false
                    })
            }

        }),



        // getResults() {
        //     if (
        //         this.business_unit &&
        //         this.department &&
        //         this.section &&
        //         this.vendor &&
        //         this.category &&
        //         this.date
        //     )
        //         this.isLoading = true
        //     this.data.data = []
        //     this.notFoundItems = 0
        //     this.total_result = null
        //     this.export = []
        //     Promise.all([
        //         this.getNotFound(),
        //         this.getExport()
        //     ]).then(response => {
        //         this.data = response[0].data
        //         this.total_result = response[0].data.total
        //         this.notFoundItems = response[1].data.length
        //         this.export = response[2].data
        //         this.isLoading = false
        //     })
        // },
        async getDates() {
            this.loadingDate = true
            const response = await axios.get(
                `/reports/backendadjustment/getDates?comp=${this.company}&bu=${this.business_unit}&dept=${this.department}&sect=${this.section}&countType=${this.Type}`
            )
            this.availableDates = response.data.map(obj => obj.batchDate)
            this.loadingDate = false
        },
        async getRacklocation() {
            // this.loadingDate = true
            await axios.get(
                `/reports/backendadjustment/getracklocation?comp=${this.company}&bu=${this.business_unit}&dept=${this.department}&sect=${this.section}&countType=${this.Type}`
            )
                .then(response => {
                    // console.log(response.data)
                    this.racklits = response.data
                    // this.loadingDate = false
                })
        },

        async getaudit() {
            // this.loadingDate = true
            const response = await axios.get(
                `/reports/backendadjustment/getaudit?comp=${this.company}&bu=${this.business_unit}&dept=${this.department}&sect=${this.section}&countType=${this.Type}&location=${this.racklocation}`
            )
                .then(response => {
                    console.log(response.data)

                    this.namelist = response.data;
                    // this.loadingDate = false
                })


        },
        async getuser() {
            // this.loadingDate = true
            const response = await axios.get(
                `/reports/backendadjustment/getuser?comp=${this.company}&bu=${this.business_unit}&dept=${this.department}&sect=${this.section}&countType=${this.Type}&location=${this.racklocation}&iad=${this.iad}`
            )
                .then(response => {
                    console.log(response.data)
                    this.userid = response.data[0].id
                    this.userlist = response.data;
                    // this.loadingDate = false
                })

        }
    },

    mounted() {
        this.$root.currentPage = this.$route.meta.name
        this.name = this.$root.authUser.name
        this.getCompany()
        // this.getResults()
        // this.getResults2()
        // document.getElementById('dateMax').setAttribute('max', this.date2)
    }
}
</script>
  
<style scoped>
#container .table td {
    font-size: 1.1em;
}

#container .table>tbody>tr:hover {
    background-color: rgb(2 2 2 / 5%);
}

h5 {
    font-size: 15px;
}

input.vs__search {
    font-size: 12px;
}
</style>
  