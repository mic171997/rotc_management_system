<!-- @format -->
<template>
    <div id="page-body">
        <div id="page-content">
            <div class="panel">
                <div class="panel-body">
                    <div class="panel-heading pad-all">
                        <h3
                            class="panel-heading bord-btm text-thin"
                            style="
                                font-size: 20px; /* padding: 15px 0px 0px 25px; */
                            "
                        >
                            <i class="demo-pli-printer icon-lg"></i>
                            {{ $route.meta.name }}
                        </h3>
                    </div>
                    <div class="row">
                        <div class="table-responsive panel-body">
                            <div class="row pad-top">
                                <div
                                    class="col-md-6 table-toolbar-left form-horizontal"
                                >
                                    <div
                                        class="row"
                                        style="padding: 10px 15px 15px 10px"
                                    >
                                        <label
                                            class="col-md-3 control-label text-thin"
                                            style="text-align: right"
                                        >
                                            <h5>Company :</h5>
                                        </label>
                                        <div class="col-md-6">
                                            <v-select
                                                :options="companyList"
                                                :reduce="
                                                    (companyList) =>
                                                        companyList.acroname
                                                "
                                                label="acroname"
                                                v-model="company"
                                                placeholder="Company"
                                                @input="companySelected($event)"
                                                :disabled="isLoading"
                                            ></v-select>
                                        </div>
                                    </div>
                                    <div
                                        class="row"
                                        style="padding: 10px 15px 15px 10px"
                                    >
                                        <label
                                            class="col-md-3 control-label text-thin"
                                            style="text-align: right"
                                        >
                                            <h5>Business Unit yw :</h5>
                                        </label>
                                        <div class="col-md-6">
                                            <v-select
                                                v-model="business_unit"
                                                label="business_unit"
                                                :options="buList"
                                                placeholder="Search for Business Unit"
                                                :reduce="
                                                    (buList) =>
                                                        buList.business_unit
                                                "
                                                @input="buSelected($event)"
                                                :disabled="
                                                    !company || isLoading
                                                "
                                            >
                                            </v-select>
                                        </div>
                                    </div>
                                    <div
                                        class="row"
                                        style="padding: 10px 15px 15px 10px"
                                    >
                                        <label
                                            class="col-md-3 control-label text-thin"
                                            style="text-align: right"
                                        >
                                            <h5>Department :</h5>
                                        </label>
                                        <div class="col-md-6">
                                            <v-select
                                                :options="deptList"
                                                :reduce="
                                                    (deptList) =>
                                                        deptList.dept_name
                                                "
                                                label="dept_name"
                                                v-model="department"
                                                placeholder="Department"
                                                :disabled="
                                                    !business_unit || isLoading
                                                "
                                                @input="
                                                    departmentSelected($event)
                                                "
                                            >
                                            </v-select>
                                        </div>
                                    </div>
                                    <div
                                        class="row pad-all"
                                        style="padding-left: 10px"
                                    >
                                        <label
                                            class="col-lg-3 control-label text-thin"
                                        >
                                            <h5>
                                                <i
                                                    class="icon-lg demo-pli-file-edit icon-fw"
                                                ></i>
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
                                                    !business_unit ||
                                                    !department ||
                                                    isLoading ||
                                                    (![
                                                        'PLAZA MARCELA',
                                                        'ALTA CITTA',
                                                    ].includes(business_unit) &&
                                                        !section)
                                                "
                                            ></v-select>
                                        </div>
                                    </div>
                                    <div
                                        class="row pad-all"
                                        style="padding-left: 10px"
                                    >
                                        <label
                                            class="col-lg-3 control-label text-thin"
                                        >
                                            <h5>
                                                <i
                                                    class="icon-lg demo-pli-calendar-4 icon-fw"
                                                ></i>
                                                Advance Count Date :
                                            </h5>
                                        </label>
                                        <div class="col-lg-6">
                                            <date-picker
                                                v-model="date2"
                                                type="date"
                                                format="MM/DD/YYYY"
                                                value-type="YYYY-MM-DD"
                                                range
                                                :disabled="
                                                    !business_unit ||
                                                    !department ||
                                                    checkDateAdvance ||
                                                    loadingDate ||
                                                    isLoading ||
                                                    !advanceCountAvailableDates.length
                                                "
                                                :disabled-date="
                                                    (date) =>
                                                        !advanceCountAvailableDates.includes(
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
                                                "
                                                :placeholder="
                                                    loadingDate
                                                        ? 'RETRIEVING DATE PLS WAIT...'
                                                        : !advanceCountAvailableDates.length
                                                        ? 'No dates available'
                                                        : 'Select date range'
                                                "
                                            ></date-picker>
                                        </div>
                                    </div>
                                    <div
                                        class="row pad-all"
                                        style="padding-left: 10px"
                                    >
                                        <label
                                            class="col-lg-3 control-label text-thin"
                                        >
                                            <h5>
                                                <i
                                                    class="icon-lg demo-pli-calendar-4 icon-fw"
                                                ></i>
                                                Actual Count Date :
                                            </h5>
                                        </label>
                                        <div class="col-lg-6">
                                            <date-picker
                                                v-model="date"
                                                type="date"
                                                format="MM/DD/YYYY"
                                                value-type="YYYY-MM-DD"
                                                range
                                                :disabled="
                                                    !business_unit ||
                                                    !department ||
                                                    checkDateActual ||
                                                    loadingDate ||
                                                    isLoading ||
                                                    !actualCountAvailableDates.length
                                                "
                                                :disabled-date="
                                                    (date) =>
                                                        !actualCountAvailableDates.includes(
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
                                                "
                                                :placeholder="
                                                    loadingDate
                                                        ? 'RETRIEVING DATE PLS WAIT...'
                                                        : !actualCountAvailableDates.length
                                                        ? 'No dates available'
                                                        : 'Select date range'
                                                "
                                            ></date-picker>
                                        </div>
                                    </div>
                                    <div
                                        class="row pad-all"
                                        style="padding-left: 10px"
                                    >
                                        <label
                                            class="col-lg-3 control-label text-thin"
                                        >
                                            <h5>
                                                <i
                                                    class="icon-lg demo-pli-calendar-4 icon-fw"
                                                ></i>
                                                Questionable Items Count Date :
                                            </h5>
                                        </label>
                                        <div class="col-lg-6">
                                            <date-picker
                                                v-model="date3"
                                                type="date"
                                                format="MM/DD/YYYY"
                                                value-type="YYYY-MM-DD"
                                                range
                                                :disabled="
                                                    !business_unit ||
                                                    !department ||
                                                    checkForSection ||
                                                    isDateDisabled ||
                                                    // !date ||
                                                    loadingDate ||
                                                    isLoading ||
                                                    !questionableAvailableDates.length
                                                "
                                                :disabled-date="
                                                    (date) =>
                                                        !questionableAvailableDates.includes(
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
                                                "
                                                :placeholder="
                                                    loadingDate
                                                        ? 'RETRIEVING DATE PLS WAIT...'
                                                        : !questionableAvailableDates.length
                                                        ? 'No dates available'
                                                        : 'Select date range'
                                                "
                                            ></date-picker>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-md-6 table-toolbar-right form-horizontal"
                                >
                                    <div
                                        class="row"
                                        style="padding: 10px 15px 15px 10px"
                                    >
                                        <label
                                            class="col-md-3 control-label text-thin"
                                        >
                                            <h5></h5>
                                        </label>
                                        <div class="col-md-6 pad-all"></div>
                                    </div>
                                    <div
                                        class="row"
                                        style="padding: 10px 15px 15px 10px"
                                    >
                                        <label
                                            class="col-md-3 control-label text-thin"
                                        >
                                            <h5></h5>
                                        </label>
                                        <div class="col-md-6 pad-all"></div>
                                    </div>
                                    <div
                                        class="row"
                                        style="padding: 10px 15px 15px 10px"
                                    >
                                        <label
                                            class="col-md-3 control-label text-thin"
                                            style="text-align: right"
                                        >
                                            <h5>Section :</h5>
                                        </label>
                                        <div
                                            v-if="
                                                business_unit ==
                                                'ALTURAS TALIBON'
                                            "
                                        >
                                            <div class="col-md-6">
                                                <!-- <v-select :options="countTALIBON" label="section_name" v-model="section" placeholder="Section"
                          :disabled="!department || isLoading || checkSection"></v-select> -->
                                                <v-select
                                                    v-model="section"
                                                    :options="countTALIBON"
                                                    placeholder="Section"
                                                    :disabled="
                                                        !department ||
                                                        isLoading ||
                                                        checkSection
                                                    "
                                                ></v-select>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="col-md-6">
                                                <v-select
                                                    :options="sectionList"
                                                    :reduce="
                                                        (sectionList) =>
                                                            sectionList.section_name
                                                    "
                                                    label="section_name"
                                                    v-model="section"
                                                    placeholder="Section"
                                                    :disabled="
                                                        !department ||
                                                        isLoading ||
                                                        checkSection
                                                    "
                                                ></v-select>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="row"
                                        style="padding: 10px 15px 15px 10px"
                                    >
                                        <label
                                            class="col-md-3 control-label text-thin"
                                        >
                                            <h5></h5>
                                        </label>
                                        <div class="col-md-6 pad-all"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pad-all">
                                <div class="btn-group pull-left">
                                    <button
                                        class="btn btn-success btn-rounded text-thin mar-lft"
                                        type="button"
                                        aria-expanded="false"
                                        @click="getResults()"
                                        :disabled="
                                            !department ||
                                            !business_unit ||
                                            !department ||
                                            !countType ||
                                            (!date && !date2 && !date3)
                                        "
                                    >
                                        Consolidate Count
                                    </button>
                                </div>
                                <div class="btn-group pull-right">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-info btn-rounded text-thin mar-lft dropdown-toggle"
                                            data-toggle="dropdown"
                                            type="button"
                                            aria-expanded="false"
                                            :disabled="
                                                !data.data.length || isLoading
                                            "
                                        >
                                            <i
                                                class="demo-pli-printer icon-lg"
                                            ></i
                                            >&nbsp; Generate Report
                                            <i class="dropdown-caret"></i>
                                        </button>
                                        <ul
                                            class="dropdown-menu dropdown-menu-right"
                                            style=""
                                        >
                                            <li class="dropdown-header">
                                                Actual Count (APP)
                                            </li>
                                            <li>
                                                <a
                                                    href="javscript:;"
                                                    @click="
                                                        generateBtn(
                                                            $event,
                                                            'Excel'
                                                        )
                                                    "
                                                    v-if="
                                                        !data.data.length ||
                                                        (data.hasOwnProperty(
                                                            'hasData'
                                                        ) &&
                                                            data.hasData ==
                                                                false)
                                                    "
                                                >
                                                    Generate Excel
                                                </a>
                                            </li>
                                            <!-- <li>
                        <a
                          href="javscript:;"
                          @click="generateBtn($event, 'PDF')"
                        >
                          Generate PDF
                        </a>
                      </li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <table
                                class="table table-striped table-vcenter"
                                id="data-table"
                            >
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Variant Code</th>
                                        <th>Description</th>
                                        <th>UOM</th>
                                        <!-- <th class="text-center" v-if="business_unit == 'DISTRIBUTION'">Lot #</th>
                    <th v-if="business_unit == 'DISTRIBUTION'">Date Expiry</th> -->
                                        <th>Actual Count Qty</th>
                                        <th>Advance Count Qty</th>
                                        <th>Questionable Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="!returnData.length && isLoading">
                                        <td
                                            colspan="8"
                                            style="text-align: center"
                                        >
                                            <div
                                                class="sk-cube-grid"
                                                v-if="isLoading"
                                            >
                                                <div
                                                    class="sk-cube sk-cube1"
                                                ></div>
                                                <div
                                                    class="sk-cube sk-cube2"
                                                ></div>
                                                <div
                                                    class="sk-cube sk-cube3"
                                                ></div>
                                                <div
                                                    class="sk-cube sk-cube4"
                                                ></div>
                                                <div
                                                    class="sk-cube sk-cube5"
                                                ></div>
                                                <div
                                                    class="sk-cube sk-cube6"
                                                ></div>
                                                <div
                                                    class="sk-cube sk-cube7"
                                                ></div>
                                                <div
                                                    class="sk-cube sk-cube8"
                                                ></div>
                                                <div
                                                    class="sk-cube sk-cube9"
                                                ></div>
                                            </div>
                                            <span
                                                class="text-thin text-main"
                                                v-if="isLoading"
                                                >Loading please wait... :)
                                            </span>

                                            <span v-else
                                                >No data available.</span
                                            >
                                        </td>
                                    </tr>
                                    <tr
                                        v-if="
                                            !returnData.length &&
                                            !isLoading &&
                                            !data.hasData
                                        "
                                    >
                                        <td
                                            colspan="8"
                                            style="text-align: center"
                                        >
                                            <span>No data available.</span>
                                        </td>
                                    </tr>
                                    <tr
                                        v-if="
                                            data.hasOwnProperty('hasData') &&
                                            data.hasData == true &&
                                            !isLoading
                                        "
                                    >
                                        <td
                                            class="text-main text-center"
                                            colspan="8"
                                        >
                                            <div class="bord-btm pad-btm">
                                                <button
                                                    class="btn btn-block btn-rounded btn-lg btn-success v-middle text-thin"
                                                    :disabled="isLoading"
                                                    @click="
                                                        generateBtn(
                                                            $event,
                                                            'Excel'
                                                        )
                                                    "
                                                >
                                                    Download file
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr
                                        v-else
                                        v-for="(data, index) in returnData"
                                        :key="index"
                                    >
                                        <td class="text-main text-thin">
                                            {{ data.itemcode }}
                                        </td>
                                        <td class="text-main text-thin">
                                            {{ data.variant_code }}
                                        </td>
                                        <td class="text-main text-thin">
                                            {{ data.description }}
                                        </td>
                                        <td class="text-main text-thin">
                                            {{ data.nav_uom }}
                                        </td>
                                        <!-- <td
                                            v-if="
                                                business_unit == 'DISTRIBUTION'
                                            "
                                            class="text-main text-thin text-center"
                                        >
                                            {{ data.lot_number }}
                                        </td>
                                        <td
                                            v-if="
                                                business_unit == 'DISTRIBUTION'
                                            "
                                            class="text-main text-thin"
                                        >
                                            {{ data.expiry }}
                                        </td> -->
                                        <td
                                            class="text-main text-normal text-center"
                                        >
                                            {{
                                                data.ActualCountQty
                                                    | numberFormat
                                            }}
                                        </td>
                                        <td
                                            class="text-main text-normal text-center"
                                        >
                                            {{
                                                data.AdvCountQty
                                                    ? data.AdvCountQty
                                                    : 0 | numberFormat
                                            }}
                                        </td>
                                        <td
                                            class="text-main text-normal text-center"
                                        >
                                            {{
                                                data.QuestCountQty
                                                    | numberFormat
                                            }}
                                        </td>
                                        <td
                                            class="text-main text-normal text-center"
                                        >
                                            {{ data.total | numberFormat }}
                                        </td>
                                    </tr>
                                    <tr
                                        v-if="
                                            returnData.length &&
                                            returnData.length !=
                                                data.data.length &&
                                            data.hasData == false
                                        "
                                    >
                                        <td
                                            class="text-main text-center"
                                            colspan="8"
                                        >
                                            <div class="bord-btm pad-btm">
                                                <button
                                                    class="btn btn-block btn-rounded btn-default v-middle text-thin"
                                                    :disabled="isLoading"
                                                    @click="limit += 50"
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
                                        Showing {{ returnData.length }} entries
                                        out of
                                        <strong
                                            >{{ data.data.length }} entries
                                        </strong>
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
    </div>
</template>

<script>
import Vue from "vue";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { debounce } from "lodash";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
Vue.component("pagination", require("laravel-vue-pagination"));
Vue.component("v-select", vSelect);
export default {
    data() {
        return {
            data: {
                data: [],
                current_page: null,
                from: null,
                to: null,
                total: null,
                per_page: null,
                hasData: false,
            },
            searchProducts: null,
            buttonconso: false,
            name: null,
            date: null,
            date2: null,
            date3: null,
            total_result: null,
            companyList: [],
            company: null,
            buList: [],
            business_unit: null,
            deptList: [],
            department: null,
            sectionList: [],
            section: null,
            vendorList: [],
            filteredvendorList: [],
            vendor: null,
            categoryList: [],
            filteredCategoryList: [],
            category: null,
            forPrintVendor: [],
            forPrintCategory: [],
            countType: null,
            countTypes: ["ANNUAL", "CYCLICAL"],
            countTALIBON: ["CENTRALIZE", "WAREHOUSE"],
            notFoundItems: 0,
            export: [],
            isLoading: false,
            limit: 100,
            actualCountAvailableDates: [],
            advanceCountAvailableDates: [],
            questionableAvailableDates: [],
            loadingDate: false,
        };
    },
    components: {
        DatePicker,
    },
    computed: {
        checkSection() {
            return this.business_unit.includes("PLAZA MARCELA") ||
                this.business_unit.includes("ALTA CITTA")
                ? true
                : false;
        },
        checkForSection() {},
        checkDateAdvance() {
            // return this.advanceCountAvailableDates.length < 0
        },
        checkDateActual() {
            // return this.advanceCountAvailableDates.length > 0 && this.date2 === null
            // return this.advanceCountAvailableDates.length > 0 && this.date2 === null
        },
        returnData() {
            return this.data.data.filter((el, index) => index < this.limit);
        },
        isDateDisabled() {
            // return (
            //   (this.advanceCountAvailableDates.length > 0 && this.date2 === null) ||
            //   (this.actualCountAvailableDates.length > 0 && this.date === null)
            // )
        },
    },
    watch: {
        // date() {
        //   if (
        //     this.business_unit &&
        //     this.department
        //     // && this.date2
        //   ) {
        //     this.getResults()
        //   }
        // },
        // date2() {
        //   if (this.business_unit && this.department) {
        //     this.getResults()
        //   }
        // },
        // date3() {
        //   if (
        //     this.business_unit &&
        //     this.department
        //     // this.date
        //     //  &&
        //     // this.date2
        //   ) {
        //     this.getResults()
        //   }
        // },
        business_unit() {
            this.clear();

            this.business_unit && this.department && this.date && this.date2
                ? this.getResults()
                : null;
        },
        department() {
            this.clear();
            this.countType = null;
            this.business_unit && this.department && this.date && this.date2
                ? this.getResults()
                : null;
        },
        section() {
            this.clear();
            if (
                (this.business_unit != "PLAZA MARCELA" &&
                    !this.section &&
                    !this.countType) ||
                (this.business_unit != "ALTA CITTA" && !this.section)
            ) {
            } else {
                this.getDates();
            }

            this.business_unit &&
            this.department &&
            this.section &&
            this.date &&
            this.date2
                ? this.getResults()
                : null;
        },
        countType() {
            if (
                (this.business_unit != "PLAZA MARCELA" &&
                    !this.section &&
                    !this.countType) ||
                (this.business_unit != "ALTA CITTA" &&
                    !this.section &&
                    !this.countType)
            ) {
            } else {
                this.getDates();
            }
        },
    },
    methods: {
        clear() {
            this.date = null;
            this.date2 = null;
            this.date3 = null;
            this.data.data = [];
            this.data.hasData = false;
            this.data.current_page = null;
            this.data.from = null;
            this.data.to = null;
            this.data.total = null;
            this.data.per_page = null;
            this.actualCountAvailableDates = [];
            this.advanceCountAvailableDates = [];
            this.questionableAvailableDates = [];
        },
        notAfterToday(date) {
            return date > new Date(new Date().setHours(0, 0, 0, 0));
        },
        async generateBtn(e, reportType) {
            Swal.fire({
                html: "Please wait, don't close the browser.",
                title: "Generating report in progress",
                timerProgressBar: true,
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {},
            }).then((result) => {
                if (result.isConfirmed) {
                }
            });
            const countdata = this.data;
            const thisButton = e.target;
            const oldHTML = thisButton.innerHTML;

            thisButton.disabled = true;
            thisButton.innerHTML =
                '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...';

            let WebRoute = null;

            reportType == "PDF"
                ? (WebRoute = "/reports/consolidate_adv_actual/generate")
                : (WebRoute = "/reports/consolidate_adv_actual/generateExcel");

            const { headers, data } = await axios.post(
                WebRoute + `?`,
                {
                    data: countdata,
                    company: this.company,
                    bu: this.business_unit,
                    dept: this.department,
                    section: this.section,
                    date: btoa(this.date),
                    date2: btoa(this.date2),
                },
                {
                    responseType: "blob",
                }
            );
            // return console.log(headers)
            const { "content-disposition": contentDisposition } = headers;
            const [attachment, file] = contentDisposition.split(" ");
            const [key, fileName] = file.split("=");

            const url = window.URL.createObjectURL(new Blob([data]));
            const link = document.createElement("a");
            link.href = url;
            let section = null;
            // console.log(fileName)
            this.section ? (section = "-" + this.section) : (section = "");

            let fileTypeExt = null;
            reportType == "PDF"
                ? (fileTypeExt = ".pdf")
                : (fileTypeExt = ".xlsx");
            let date = this.date != null ? this.date : this.date2;
            link.setAttribute(
                "download",
                `Consolidated Advance & Actual Count (APP) as of ${date} ${this.business_unit} ${this.department} ${section}${fileTypeExt}`
            );
            // console.log(link)
            document.body.appendChild(link);
            link.click();

            thisButton.disabled = false;
            thisButton.innerHTML = oldHTML;
            Swal.close();
            // this.getResults()
            $.niftyNoty({
                type: "success",
                icon: "pli-cross icon-2x",
                message: '<i class="fa fa-check"></i> Generate successful!',
                container: "floating",
                timer: 5000,
            });
        },
        departmentSelected(val) {
            const department = this.deptList.filter(
                (sm) => sm.dept_name == val
            )[0];
            const bu = this.buList.filter(
                (sm) => sm.business_unit == this.business_unit
            )[0];
            const company = this.companyList.find(
                (e) => e.acroname == this.company
            );
            axios
                .get(
                    `/uploading/nav_upload/getSection/?code=${company.company_code}&bu=${bu.bunit_code}&dept=${department.dept_code}`
                )
                .then((response) => {
                    this.sectionList = response.data;
                })
                .catch((response) => {
                    console.log("error");
                });
        },
        buSelected(val) {
            this.department = null;
            this.section = null;
            if (val) {
                const bu = this.buList.filter(
                    (sm) => sm.business_unit == val
                )[0];
                const company = this.companyList.find(
                    (e) => e.acroname == this.company
                );
                axios
                    .get(
                        `/setup/location/getDept/?code=${company.company_code}&bu=${bu.bunit_code}`
                    )
                    .then((response) => {
                        this.deptList = response.data;
                    })
                    .catch((response) => {
                        console.log("error");
                    });
            }
        },
        companySelected(val) {
            this.business_unit = null;
            this.department = null;
            this.section = null;
            // console.log(val)
            if (val) {
                const comp = this.companyList.filter(
                    (sm) => sm.acroname == val
                )[0];
                axios
                    .get(
                        `/uploading/nav_upload/getBU/?code=${comp.company_code}`
                    )
                    .then((response) => {
                        this.buList = response.data;
                    })
                    .catch((response) => {
                        console.log("error");
                    });
            }
        },
        retrieveCategory(search, loading) {
            if (search) {
                loading(true);
                this.search2(search, loading, this);
            }
        },
        search2: debounce((search, loading, vm) => {
            if (search.trim().length > 0) {
                axios
                    .get(`/uploading/nav_upload/getCategory?category=${search}`)
                    .then(({ data }) => {
                        vm.filteredCategoryList = data;
                        loading(false);
                    })
                    .catch((error) => {
                        vm.filteredCategoryList = [];
                        loading(false);
                    });
            } else {
                vm.filteredCategoryList = [];
                loading(false);
            }
        }, 1000),
        retrieveVendor(search, loading) {
            loading(true);
            this.search(search, loading, this);
        },
        search: debounce((search, loading, vm) => {
            if (search.trim().length > 0) {
                axios
                    .get(`/uploading/nav_upload/getVendor?vendor=${search}`)
                    .then(({ data }) => {
                        vm.filteredvendorList = data;
                        loading(false);
                    })
                    .catch((error) => {
                        vm.filteredvendorList = [];
                        loading(false);
                    });
            } else {
                vm.filteredvendorList = [];
                loading(false);
            }
        }, 1000),
        async getBU() {
            return await axios.get("/setup/location/getBU");
        },
        async getCategory() {
            return await axios.get("/uploading/nav_upload/getCategory");
        },
        async getVendor() {
            return await axios.get("/uploading/nav_upload/getVendor");
        },
        async getCompany() {
            return await axios.get("/uploading/nav_upload/getCompany");
        },
        getResults2() {
            Promise.all([
                this.getVendor(),
                this.getCategory(),
                // this.getBU(),
                this.getCompany(),
            ]).then((response) => {
                this.vendorList = response[0].data;
                this.filteredvendorList = response[0].data;
                this.categoryList = response[1].data;
                this.filteredCategoryList = response[1].data;
                this.companyList = response[2].data;
            });
        },
        getFormattedDateToday() {
            return new Date().toJSON().slice(0, 10).replace(/-/g, "-");
        },
        async getNotFound() {
            return await axios.get(
                `/reports/advance_count/getNotFound/?date=${btoa(
                    this.date
                )}&date2=${btoa(this.date2)}&vendors=${btoa(
                    this.forPrintVendor
                )}&category=${this.forPrintCategory}&bu=${
                    this.business_unit
                }&dept=${this.department}&section=${this.section}`
            );
        },
        async getCountData(page = 1) {
            let url = `/reports/consolidate_adv_actual/getResults/?date=${btoa(
                this.date
            )}&date2=${btoa(this.date2)}&date3=${btoa(
                this.date3
            )}&vendors=${btoa(this.forPrintVendor)}&category=${
                this.forPrintCategory
            }&comp=${this.company}&bu=${this.business_unit}&dept=${
                this.department
            }&section=${this.section}&countType=${this.countType}&page=`;

            return await axios.get(url + page);
        },
        getResults() {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });
            swalWithBootstrapButtons
                .fire({
                    title:
                        "Are you sure to consolidate without" +
                        (!this.date2 ? " Advanced ," : "") +
                        (!this.date ? " Actual ," : "") +
                        (!this.date3 ? " Questionable" : "") +
                        " Count ?",
                    icon: "warning",

                    showCancelButton: true,
                    cancelButtonText: "No, cancel!",
                    confirmButtonText: "Consolidate count",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        this.isLoading = true;
                        // this.buttonconso = true
                        this.data.data = [];

                        Promise.all([this.getCountData()]).then((response) => {
                            this.data = response[0].data;
                            this.total_result = response[0].data.total;
                            this.isLoading = false;
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire("Cancelled", "error");
                        this.reason = null;
                    }
                });
        },

        // getResults() {
        //   if (this.questionableAvailableDates.length > 0 && !this.date3) {
        //   } else {
        //     this.isLoading = true
        //     this.data.data = []
        //     if (
        //       this.business_unit &&
        //       this.department &&
        //       ((this.advanceCountAvailableDates.length > 0 && this.date2) ||
        //         (this.actualCountAvailableDates.length > 0 && this.date))
        //     ) {
        //       Promise.all([this.getCountData()]).then(response => {
        //         this.data = response[0].data
        //         this.total_result = response[0].data.total
        //         this.isLoading = false
        //       })
        //     } else {
        //       this.isLoading = false
        //     }
        //   }
        // },
        async getDates() {
            this.loadingDate = true;
            Promise.all([
                this.actualCountDates(),
                this.advanceCountDates(),
                this.questionableCountDates(),
            ]).then((response) => {
                this.actualCountAvailableDates = response[0].data.map(
                    (obj) => obj.batchDate
                );
                this.advanceCountAvailableDates = response[1].data.map(
                    (obj) => obj.batchDate
                );
                this.questionableAvailableDates = response[2].data.map(
                    (obj) => obj.batchDate
                );
                this.loadingDate = false;
            });
        },
        async actualCountDates() {
            return await axios.get(
                `/reports/consolidate_adv_actual/getDates?comp=${this.company}&bu=${this.business_unit}&dept=${this.department}&sect=${this.section}&countType=ACTUAL`
            );
        },
        async advanceCountDates() {
            return await axios.get(
                `/reports/consolidate_adv_actual/getDates?comp=${this.company}&bu=${this.business_unit}&dept=${this.department}&sect=${this.section}&countType=ADVANCE`
            );
        },
        async questionableCountDates() {
            return await axios.get(
                `/reports/backend/questionableItemsDateSetup?bu=${this.business_unit}&dept=${this.department}&sect=${this.section}&report=Consolidated`
            );
        },
    },
    mounted() {
        this.$root.currentPage = this.$route.meta.name;
        this.name = this.$root.authUser.name;
        // this.getResults()
        this.getResults2();
    },
};
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
