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
                                            <h5>Business Unit :</h5>
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
                                                    isLoading || !company
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
                                                    !company ||
                                                    !business_unit ||
                                                    !department ||
                                                    isLoading ||
                                                    filterBU
                                                "
                                            ></v-select>
                                        </div>
                                    </div>
                                    <!-- <div
                                        class="row pad-all"
                                        style="
                                            padding-left: 10px;
                                            margin-top: 15px;
                                        "
                                        v-if="business_unit == 'DISTRIBUTION'"
                                    >
                                        <label
                                            class="col-lg-3 control-label text-thin"
                                        >
                                            <h5>
                                                <i
                                                    class="icon-lg demo-pli-calendar-4 icon-fw"
                                                ></i>
                                                Consolidated Old Warehouse Date:
                                            </h5>
                                        </label>
                                        <div class="col-lg-6">
                                            <button
                                                class="btn btn-default btn-rounded mar-lft text-thin text-main"
                                                @click="
                                                    (showModal = !showModal),
                                                        (title =
                                                            'Select Consolidated Old Warehouse Date')
                                                "
                                                style="font-weight: 500"
                                                :disabled="
                                                    isLoading || filterBU
                                                "
                                            >
                                                <i
                                                    class="demo-pli-information icon-lg"
                                                    style="line-height: 0em"
                                                ></i>
                                                {{
                                                    !old_batch_id
                                                        ? "Select Consolidated Old Warehouse Date"
                                                        : formatDate(
                                                              oldSelectedData.actual_count_date,
                                                              "AppStart"
                                                          ) +
                                                          " ~ " +
                                                          formatDate(
                                                              oldSelectedData.actual_count_end,
                                                              "AppEnd"
                                                          )
                                                }}
                                            </button>
                                        </div>
                                    </div> -->
                                    <!-- <div
                                        class="row pad-all"
                                        style="
                                            padding-left: 10px;
                                            margin-top: 15px;
                                        "
                                        v-else
                                    > -->
                                    <div
                                        class="row pad-all"
                                        style="
                                            padding-left: 10px;
                                            margin-top: 15px;
                                        "
                                    >
                                        <label
                                            class="col-lg-3 control-label text-thin"
                                        >
                                            <h5>
                                                <i
                                                    class="icon-lg demo-pli-calendar-4 icon-fw"
                                                ></i>
                                                Consolidated Actual & Advance
                                                Date:
                                            </h5>
                                        </label>
                                        <div class="col-lg-6">
                                            <button
                                                class="btn btn-default btn-rounded mar-lft text-thin text-main"
                                                @click="
                                                    (showModal = !showModal),
                                                        (title =
                                                            'Select Consolidated Actual vs Advance Date')
                                                "
                                                style="font-weight: 500"
                                                :disabled="
                                                    isLoading || filterBU
                                                "
                                            >
                                                <i
                                                    class="demo-pli-information icon-lg"
                                                    style="line-height: 0em"
                                                ></i>
                                                {{
                                                    !batch_id
                                                        ? "Select Consolidated Actual vs Advance Date"
                                                        : formatDate(
                                                              selectedData.actual_count_date !=
                                                                  null
                                                                  ? selectedData.actual_count_date
                                                                  : selectedData.advance_count_date,
                                                              "AppStart"
                                                          ) +
                                                          " ~ " +
                                                          formatDate(
                                                              selectedData.actual_count_end !=
                                                                  null
                                                                  ? selectedData.actual_count_end
                                                                  : selectedData.advance_count_end,
                                                              "AppEnd"
                                                          )
                                                }}
                                            </button>
                                        </div>
                                    </div>
                                    <!-- <div
                                        class="row pad-all"
                                        style="
                                            padding-left: 10px;
                                            margin-top: 15px;
                                        "
                                        v-if="business_unit == 'DISTRIBUTION'"
                                    >
                                        <label
                                            class="col-lg-3 control-label text-thin"
                                        >
                                            <h5>
                                                <i
                                                    class="icon-lg demo-pli-calendar-4 icon-fw"
                                                ></i>
                                                Consolidated New Warehouse Date:
                                            </h5>
                                        </label>
                                        <div class="col-lg-6">
                                            <button
                                                class="btn btn-default btn-rounded mar-lft text-thin text-main"
                                                @click="
                                                    (showModal = !showModal),
                                                        (title =
                                                            'Select Consolidated New Warehouse Date')
                                                "
                                                style="font-weight: 500"
                                                :disabled="
                                                    isLoading || filterBU
                                                "
                                            >
                                                <i
                                                    class="demo-pli-information icon-lg"
                                                    style="line-height: 0em"
                                                ></i>
                                                {{
                                                    !new_batch_id
                                                        ? "Select Consolidated New Warehouse Date"
                                                        : formatDate(
                                                              newSelectedData.actual_count_date,
                                                              "NewAppStart"
                                                          ) +
                                                          " ~ " +
                                                          formatDate(
                                                              newSelectedData.actual_count_end,
                                                              "NewAppEnd"
                                                          )
                                                }}
                                            </button>
                                        </div>
                                    </div> -->
                                    <!-- <div
                                        class="row pad-all"
                                        style="padding-left: 10px"
                                        v-else
                                    > -->
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
                                                Nav Uploaded Date :
                                            </h5>
                                        </label>
                                        <div class="col-lg-6">
                                            <button
                                                class="btn btn-default btn-rounded mar-lft text-thin text-main"
                                                @click="
                                                    (showModal = !showModal),
                                                        (title =
                                                            'Select Nav Uploaded Date')
                                                "
                                                style="font-weight: 500"
                                                :disabled="
                                                    isLoading ||
                                                    (selectedData.actual_count_date !=
                                                    null
                                                        ? !selectedData.actual_count_date &&
                                                          !selectedData.actual_count_end
                                                        : !selectedData.advance_count_date &&
                                                          !selectedData.advance_count_end)
                                                "
                                            >
                                                <i
                                                    class="demo-pli-information icon-lg"
                                                    style="line-height: 0em"
                                                ></i>
                                                {{
                                                    !nav_id
                                                        ? " Select Nav Uploaded Date"
                                                        : formatDate(
                                                              navSelectedData.date,
                                                              "NavDate"
                                                          )
                                                }}
                                            </button>
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

                                        <!-- <div class="col-md-6">
                      <v-select :options="sectionList" :reduce="sectionList => sectionList.section_name"
                        label="section_name" v-model="section" placeholder="Section"
                        :disabled="!department || isLoading || checkSection"></v-select>
                    </div> -->
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
                            <div
                                class="row"
                                style="padding: 10px 15px 15px 10px"
                            >
                                <div class="btn-group pull-right">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-info btn-rounded text-thin mar-lft pull-right dropdown-toggle"
                                            data-toggle="dropdown"
                                            type="button"
                                            aria-expanded="false"
                                            :disabled="
                                                !data.data.length || isLoading
                                            "
                                        >
                                            <!-- :disabled="!data.data.length || isLoading" -->
                                            <i
                                                class="demo-pli-printer icon-lg"
                                            ></i
                                            >&nbsp; Generate Variance Report
                                            <i class="dropdown-caret"></i>
                                        </button>
                                        <ul
                                            class="dropdown-menu dropdown-menu-right"
                                            style=""
                                        >
                                            <li class="dropdown-header">
                                                Variance Report
                                            </li>
                                            <li>
                                                <a
                                                    href="javascript:void(0);"
                                                    @click="
                                                        generateBtn(
                                                            $event,
                                                            'Variance'
                                                        )
                                                    "
                                                >
                                                    All Entries
                                                </a>
                                            </li>
                                            <li>
                                                <a
                                                    href="javascript:void(0);"
                                                    @click="
                                                        generateBtn(
                                                            $event,
                                                            'Positive'
                                                        )
                                                    "
                                                >
                                                    Positive Entries
                                                </a>
                                            </li>
                                            <li>
                                                <a
                                                    href="javascript:void(0);"
                                                    @click="
                                                        generateBtn(
                                                            $event,
                                                            'Negative'
                                                        )
                                                    "
                                                >
                                                    Negative Entries
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="btn-group pull-right">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-danger btn-rounded text-thin mar-lft dropdown-toggle"
                                            data-toggle="dropdown"
                                            type="button"
                                            aria-expanded="false"
                                            :disabled="
                                                !data.data.length || isLoading
                                                || userType == 2
                                            "
                                        >
                                            <i
                                                class="demo-pli-printer icon-lg"
                                            ></i
                                            >&nbsp; Export to Navision
                                            <i class="dropdown-caret"></i>
                                        </button>
                                        <ul
                                            class="dropdown-menu dropdown-menu-right"
                                            style=""
                                        >
                                            <li class="dropdown-header">
                                                Export to Navision
                                            </li>
                                            <li>
                                                <a
                                                    href="javascript:void(0);"
                                                    @click="
                                                        exportBtn(
                                                            $event,
                                                            'Variance'
                                                        )
                                                    "
                                                >
                                                    All Entries
                                                </a>
                                            </li>
                                            <!-- <li>
                        <a
                          href="javascript:void(0);"
                          @click="exportBtn($event, 'Positive')"
                        >
                          Positive
                        </a>
                      </li> -->
                                            <!-- <li>
                        <a
                          href="javascript:void(0);"
                          @click="exportBtn($event, 'Negative')"
                        >
                          Negative
                        </a>
                      </li> -->
                                        </ul>
                                    </div>
                                </div>

                                <!-- estongStart -->

                                <div class="btn-group pull-right">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-danger btn-rounded text-thin mar-lft dropdown-toggle"
                                            data-toggle="dropdown"
                                            type="button"
                                            aria-expanded="false"
                                            :disabled="
                                                !data.data.length ||
                                                isLoading ||
                                                data.exist == false || userType == 2
                                            "
                                        >
                                            <i
                                                class="demo-pli-printer icon-lg"
                                            ></i
                                            >&nbsp; Summary
                                            <i class="dropdown-caret"></i>
                                        </button>
                                        <ul
                                            class="dropdown-menu dropdown-menu-right"
                                            style=""
                                        >
                                            <li class="dropdown-header">
                                                Summary
                                            </li>
                                            <li>
                                                <a
                                                    href="javascript:void(0);"
                                                    @click="
                                                        exportBtnSummary(
                                                            $event,
                                                            'Variance Report Summary Unique Itemcode'
                                                        )
                                                    "
                                                >
                                                    SUMMARY (UNIQUE ITEMCODE)
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    href="javascript:void(0);"
                                                    @click="
                                                        exportBtnSummary(
                                                            $event,
                                                            'Variance Report With Cost'
                                                        )
                                                    "
                                                >
                                                    ALL ENTRIES (WITH AMOUNT)
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- estong end -->
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th
                                            rowspan="2"
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            Item Code
                                        </th>
                                        <th
                                            rowspan="2"
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            Variant Code
                                        </th>
                                        <th
                                            rowspan="2"
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            Description
                                        </th>
                                        <th
                                            rowspan="2"
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            UOM
                                        </th>
                                        <th
                                            colspan="2"
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            Quantity
                                        </th>
                                        <th
                                            rowspan="3"
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            Variance
                                        </th>
                                    </tr>
                                    <!-- <tr v-if="business_unit == 'DISTRIBUTION'">
                                        <th
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            Old Warehouse Count
                                        </th>
                                        <th
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            New Warehouse Count
                                        </th>
                                    </tr> -->
                                    <tr>
                                        <th
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            P Count App
                                        </th>
                                        <th
                                            class="text-center"
                                            style="vertical-align: middle"
                                        >
                                            Net Nav Sys Count
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-if="!data.data.length">
                                        <td
                                            colspan="7"
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

                                            <div v-else>No data available.</div>
                                        </td>
                                    </tr>
                                    <tr
                                        v-if="
                                            (data.hasOwnProperty('exist') &&
                                                data.exist == true) ||
                                            showBtn == true
                                        "
                                    >
                                        <td
                                            class="text-main text-center"
                                            colspan="7"
                                        >
                                            <div class="bord-btm pad-btm">
                                                <router-link
                                                    class="btn btn-block btn-rounded btn-lg btn-purple v-middle text-thin"
                                                    :disabled="isLoading"
                                                    :to="{ name: 'Justification', params: { comp: company, b_unit: business_unit, dept: department, sect: section } }"
                                                >
                                                    <i
                                                        class="fa fa-sign-out"
                                                    ></i>
                                                    For Justification
                                                </router-link>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr
                                        v-else
                                        v-for="(data, index) in returnData"
                                        :key="index"
                                    >
                                        <td
                                            class="text-main text-thin"
                                            style="font-size: 1.1em"
                                        >
                                            {{ data.itemcode }}
                                        </td>
                                        <td
                                            class="text-main text-thin"
                                            style="font-size: 1.1em"
                                        >
                                            {{ data.variant_code }}
                                        </td>
                                        <td
                                            class="text-main text-thin"
                                            style="font-size: 1.1em"
                                        >
                                            {{ data.extended_desc }}
                                        </td>
                                        <td
                                            class="text-main text-thin text-center"
                                            style="font-size: 1.1em"
                                        >
                                            {{ data.uom }}
                                        </td>
                                        <td
                                            class="text-main text-thin text-center"
                                            style="font-size: 1.1em"
                                        >
                                            {{
                                                data.conversion_qty
                                                    | numberFormat
                                            }}
                                        </td>
                                        <!-- <td
                                            class="text-main text-thin text-center"
                                            style="font-size: 1.1em"
                                            v-if="
                                                business_unit == 'DISTRIBUTION'
                                            "
                                        >
                                            {{ data.nav_qty | numberFormat }}
                                        </td> -->
                                        <!-- <td
                                            class="text-main text-thin text-center"
                                            style="font-size: 1.1em"
                                            v-else
                                        >
                                            {{
                                                computeNet(
                                                    data.nav_qty,
                                                    data.unposted
                                                ) | numberFormat
                                            }}
                                        </td> -->
                                        <td
                                            class="text-main text-thin text-center"
                                            style="font-size: 1.1em"
                                        >
                                            {{
                                                computeNet(
                                                    data.nav_qty,
                                                    data.unposted
                                                ) | numberFormat
                                            }}
                                        </td>

                                        <td
                                            class="text-main text-thin text-center"
                                            style="font-size: 1.1em"
                                        >
                                            <!-- {{
                        computeVariance(
                          data.nav_qty,
                          data.unposted,
                          data.conversion_qty
                        ) | numberFormat
                      }} -->
                                            {{ data.variance | numberFormat }}
                                        </td>
                                    </tr>
                                    <tr
                                        v-if="
                                            returnData.length &&
                                            returnData.length !=
                                                data.data.length &&
                                            data.exist == false &&
                                            showBtn == false
                                        "
                                    >
                                        <td
                                            class="text-main text-center"
                                            colspan="7"
                                        >
                                            <div class="bord-btm pad-btm">
                                                <button
                                                    class="btn btn-block btn-rounded btn-default v-middle text-thin"
                                                    :disabled="isLoading"
                                                    @click="limit += 25"
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
            :dateStart="date"
            @closeMdl="showModal = false"
            @passData="getData"
        ></Modal>
    </div>
</template>

<script>
import Vue from "vue";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { debounce } from "lodash";
import Modal from "./modals/DateUploaded.vue";
var dayjs = require("dayjs");
// Vue.component('pagination', require('laravel-vue-pagination'))
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
                exist: false,
            },
            name: null,
            date: null,
            date2: null,
            dateMax: this.getFormattedDateToday(),
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
            countTypes: ["ANNUAL", "CYCLICAL"],
            countTALIBON: ["CENTRALIZE", "WAREHOUSE"],
            finalExport: [],
            export: [],
            isLoading: false,
            filteredData: [],
            limit: 25,
            showModal: false,
            title: null,
            batch_id: null,
            old_batch_id: null,
            new_batch_id: null,
            selectedData: [],
            nav_id: null,
            navSelectedData: [],
            oldSelectedData: [],
            newSelectedData: [],
            forPrintData: [],
            showBtn: false,
            userType: null
        };
    },
    components: { Modal },
    computed: {
        returnData() {
            return this.filteredData.filter((el, index) => index < this.limit);
        },
        checkSection() {
            return ["PLAZA MARCELA", "ALTA CITTA"].includes(this.business_unit);
        },
        filterBU() {
            return (
                !["PLAZA MARCELA", "ALTA CITTA"].includes(this.business_unit) &&
                !this.section
            );
        },
    },
    watch: {
        section() {
            this.batch_id = null;
            this.old_batch_id = null;
            this.new_batch_id = null;
            this.selectedData = [];
            this.nav_id = null;
            this.navSelectedData = [];
            this.oldSelectedData = [];
            this.newSelectedData = [];
            this.data.data = [];
            this.data.current_page = null;
            this.data.from = null;
            this.data.to = null;
            this.data.total = null;
            this.data.per_page = null;
            this.data.exist = false;
            this.filteredData = [];
        },

        nav_id() {
            // this.retrieve(this)
        },
        batch_id() {
            // this.retrieve(this)
        },
        showModal(val) {
            val == true
                ? $("#rack-setup").modal("show")
                : $("#rack-setup").modal("hide");
        },
        date() {
            if (
                this.business_unit &&
                this.department &&
                !this.filterBU &&
                this.date &&
                this.date2
            ) {
                // this.retrieve(this)
                this.getResults();
            }
        },
        date2() {
            if (
                this.business_unit &&
                this.department &&
                !this.filterBU &&
                this.date &&
                this.date2
            ) {
                // this.retrieve(this)
                this.getResults();
            }
        },
        vendor(newValue) {
            // let value = []
            // newValue.forEach((element, index) => {
            //   value.push(element.vendor_name)
            // })
            // this.forPrintVendor = value.join('|')
            // this.getResults()

            if (newValue?.length == 0) this.vendor = null;
            if (newValue) {
                const res = newValue.find(
                    (val) => val.vendor_name === "ALL VENDORS"
                );

                // console.log(res)
                if (res) {
                    this.filteredvendorList = this.vendorList.filter(
                        (categ) => categ.vendor_name === res.vendor_name
                    );

                    if (
                        this.business_unit &&
                        this.department &&
                        this.section &&
                        this.date &&
                        this.date2
                    ) {
                        // this.retrieve(this)
                        this.getResults();
                    }
                } else {
                    this.filteredvendorList = this.vendorList.filter(
                        (categ) => categ.vendor_name !== "ALL VENDORS"
                    );
                    let value = [];

                    newValue.forEach((element, index) => {
                        value.push("'" + element.vendor_name + "'");
                    });
                    this.forPrintVendor = value.join(" , ");
                    if (
                        this.business_unit &&
                        this.department &&
                        !this.filterBU &&
                        this.date &&
                        this.date2
                    ) {
                        // this.retrieve(this)
                        this.getResults();
                    }
                }
            } else {
                this.filteredvendorList = this.vendorList;
            }
        },
        category(newValue) {
            // let value = []
            // newValue.forEach((element, index) => {
            //   value.push(element.category)
            // })
            // this.forPrintCategory = value.join('|')
            // this.getResults()
            if (newValue?.length == 0) this.category = null;
            if (newValue) {
                const res = newValue.find(
                    (val) => val.category === "ALL CATEGORIES"
                );

                if (res) {
                    this.filteredcategoryList = this.categoryList.filter(
                        (categ) => categ.category === res.category
                    );

                    if (
                        this.business_unit &&
                        this.department &&
                        this.section &&
                        this.date &&
                        this.date2
                    ) {
                        // this.retrieve(this)
                        this.getResults();
                    }
                } else {
                    this.filteredcategoryList = this.categoryList.filter(
                        (categ) => categ.category !== "ALL CATEGORIES"
                    );
                    let value = [];

                    newValue.forEach((element, index) => {
                        value.push("'" + element.category + "'");
                    });
                    this.forPrintCategory = value.join(" , ");
                    if (
                        this.business_unit &&
                        this.department &&
                        this.section &&
                        this.date &&
                        this.date2
                    ) {
                        // this.retrieve(this)
                        this.getResults();
                    }
                }
            } else {
                this.filteredcategoryList = this.categoryList;
            }
        },
    },
    methods: {
        formatDate(value, type) {
            let date = dayjs(value).format("MM/DD/YYYY");
            // console.log(value, type, date)
            type == "AppStart"
                ? (this.date = date)
                : type == "NavDate"
                ? (this.date2 = date)
                : null;
            type == "NewAppStart" ? (this.date2 = date) : null;
            return date;
        },
        getData(value) {
            // console.log(value)
            if (value) {
                value.type == "APP"
                    ? (this.batch_id = value.batch_id)
                    : value.type == "OLD"
                    ? (this.old_batch_id = value.batch_id)
                    : value.type == "NEW"
                    ? (this.new_batch_id = value.batch_id)
                    : (this.nav_id = value.batch_id);

                value.type == "APP"
                    ? (this.selectedData = value.selectedData)
                    : value.type == "OLD"
                    ? (this.oldSelectedData = value.selectedData)
                    : value.type == "NEW"
                    ? (this.newSelectedData = value.selectedData)
                    : (this.navSelectedData = value.selectedData);
            }

            $("#rack-setup").modal("hide");
            this.showModal = false;
            this.batch_id && this.nav_id ? this.getResults() : null;
            this.old_batch_id && this.new_batch_id ? this.getResults() : null;
        },
        retrieve: debounce((vm) => {
            vm.isLoading = true;
            vm.getResults();
            vm.isLoading = false;
        }, 1000),
        async exportBtn(event, reportType) {
            Swal.fire({
                html: "Please wait, don't close the browser.",
                title: "Exporting in progress",
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

            // document.location.href = `/reports/appdata/generate`
            const thisButton = event.target;
            const oldHTML = thisButton.innerHTML;
            this.isLoading = true;
            this.exportcsv(reportType);
            let pass = null,
                report = null;
            // if (reportType == 'Variance') {
            pass = "/reports/variance_report/export";
            // }
            // else {
            //   pass = '/reports/variance_report/NavUnposted'
            // }

            thisButton.disabled = true;
            thisButton.innerHTML =
                '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...';
            const { headers, data } = await axios.post(
                pass,
                {
                    // export: btoa(JSON.stringify(this.export)),
                    export: JSON.stringify(this.export),
                    bu: this.business_unit,
                    section: this.section,
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
            let title = `Import to Nav ${reportType}`;
            if (reportType == "Variance w/ Cost") {
                title = "Import to Nav";
            }

            link.setAttribute(
                "download",
                `${title} as of ${dayjs(this.date2).format("MM-DD-YY")} ${
                    this.business_unit
                } ${this.department}${section}` + ".csv"
            );
            // console.log(link)
            document.body.appendChild(link);
            link.click();

            thisButton.disabled = false;
            thisButton.innerHTML = oldHTML;
            this.isLoading = false;
            Swal.close();
            $.niftyNoty({
                type: "success",
                icon: "pli-cross icon-2x",
                message: '<i class="fa fa-check"></i> Generate successful!',
                container: "floating",
                timer: 5000,
            });
        },

        /////// estong start

        async exportBtnSummary(event, reportType) {
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
            const thisButton = event.target;
            const oldHTML = thisButton.innerHTML;

            thisButton.disabled = true;
            thisButton.innerHTML =
                '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...';
            let pass = null,
                title = null,
                navDates = null;
            navDates = `${this.selectedData.advance_count_date},${this.selectedData.advance_count_end},${this.selectedData.actual_count_date},${this.selectedData.actual_count_end}`;
            var encodedNavDates = btoa(navDates);

            const { headers, data } = await axios.post(
                `/reports/variance_report/summary?`,
                {
                    bu: this.business_unit,
                    company: this.company,
                    dept: this.department,
                    section: this.section,
                    date: btoa(navDates),
                    date2: btoa(this.date2),
                    type: reportType,
                    // export: btoa(JSON.stringify(this.export))
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
            // console.log(fileName)
            link.setAttribute(
                "download",
                `${reportType} of ${this.business_unit} ${
                    this.department
                } ${section} as of ${dayjs(this.date2).format("MM-DD-YY")}.xlsx`
            );
            // console.log(link)
            document.body.appendChild(link);
            link.click();

            thisButton.disabled = false;
            thisButton.innerHTML = oldHTML;
            this.isLoading = false;
            Swal.close();
            $.niftyNoty({
                type: "success",
                icon: "pli-cross icon-2x",
                message: '<i class="fa fa-check"></i> Generate successful!',
                container: "floating",
                timer: 5000,
            });
        },

        /////// estong end

        async generateBtn(e, type) {
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
            // document.location.href = `/reports/variance_report/generate`
            const thisButton = e.target;
            const oldHTML = thisButton.innerHTML;
            this.isLoading = true;

            // this.formatData(type)
            let pass = null,
                title = null,
                navDates = null;

            navDates = `${this.selectedData.advance_count_date},${this.selectedData.advance_count_end},${this.selectedData.actual_count_date},${this.selectedData.actual_count_end}`;

            // if (this.old_batch_id && this.new_batch_id) {
            //     navDates = `${this.oldSelectedData.actual_count_date},${this.oldSelectedData.actual_count_end},${this.newSelectedData.actual_count_date},${this.newSelectedData.actual_count_end}`;
            // }

            if (
                type == "Variance" ||
                type == "Negative" ||
                type == "Positive"
            ) {
                title = `Consolidated Variance Report ${type}`;
                pass = `/reports/variance_report/generate?date=${btoa(
                    navDates
                )}&date2=${btoa(this.date2)}&vendors=${btoa(
                    this.forPrintVendor
                )}&category=${this.forPrintCategory}&company=${
                    this.company
                }&bu=${this.business_unit}&dept=${this.department}&section=${
                    this.section
                }&type=${type}&exists=${this.data.exist}`;
            } else if (type == "Summary") {
                title = "Consolidated Variance Report Summary";
                pass = `/reports/variance_report/generate?date=${btoa(
                    this.date
                )}&date2=${btoa(this.date2)}&vendors=${btoa(
                    this.forPrintVendor
                )}&category=${this.forPrintCategory}&bu=${
                    this.business_unit
                }&dept=${this.department}&section=${
                    this.section
                }&type=${type}&exists=${this.data.exist}`;
            } else {
                title = "Net Nav Sys";
                pass = `/reports/variance_report/NavUnposted?date=${btoa(
                    this.date
                )}&date2=${btoa(this.date2)}&vendors=${btoa(
                    this.forPrintVendor
                )}&category=${this.forPrintCategory}&bu=${
                    this.business_unit
                }&dept=${this.department}&section=${this.section}`;
            }

            thisButton.disabled = true;
            thisButton.innerHTML =
                '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...';
            const { headers, data } = await axios.post(
                pass,
                { data: this.filteredData },
                {
                    responseType: "blob",
                }
            );
            // return console.log(headers)
            const { "content-disposition": contentDisposition } = headers;
            const [attachment, file] = contentDisposition.split(" ");
            const [key, fileName] = file.split("=");
            // console.log(fileName)

            const url = window.URL.createObjectURL(new Blob([data]));
            const link = document.createElement("a");
            link.href = url;
            let section = null;
            // console.log('download', `${fileName.replace('"', '')}.pdf`)
            this.section ? (section = "-" + this.section) : (section = "");
            // console.log(section)

            link.setAttribute(
                "download",
                `${title} as of ${dayjs(this.date2).format("MM-DD-YY")} ${
                    this.business_unit
                } ${this.department} ${section}.xlsx`
            );
            // console.log(link)
            document.body.appendChild(link);
            link.click();

            thisButton.disabled = false;
            thisButton.innerHTML = oldHTML;
            this.isLoading = false;
            // this.showBtn = true
            this.getResults();

            Swal.close();
            $.niftyNoty({
                type: "success",
                icon: "pli-cross icon-2x",
                message: '<i class="fa fa-check"></i> Generate successful!',
                container: "floating",
                timer: 5000,
            });
        },
        formatData(reportType) {
            let variance = 0,
                itemcode = null,
                extended_desc = null,
                uom = null,
                conversion_qty = 0,
                nav_qty = 0;

            this.forPrintData = [];
            this.filteredData.forEach((result) => {
                variance = parseInt(result.variance);
                itemcode = result.itemcode;
                extended_desc = result.extended_desc;
                uom = result.uom;
                conversion_qty = result.conversion_qty;
                nav_qty = result.nav_qty;

                if (
                    (reportType == "Negative" && variance < 0) ||
                    (reportType == "Positive" && variance > 0) ||
                    reportType == "Variance"
                ) {
                    this.forPrintData.push({
                        itemcode,
                        extended_desc,
                        uom,
                        conversion_qty,
                        nav_qty,
                        variance,
                    });
                }
            });
        },
        getResults(page = 1) {
            let navDates = `${this.selectedData.advance_count_date},${this.selectedData.advance_count_end},${this.selectedData.actual_count_date},${this.selectedData.actual_count_end}`;
            let url = `/reports/variance_report/getResults?date=${btoa(
                navDates
            )}&date2=${btoa(this.date2)}&vendors=${btoa(
                this.forPrintVendor
            )}&category=${this.forPrintCategory}&company=${this.company}&bu=${
                this.business_unit
            }&dept=${this.department}&section=${this.section}&batch_id=${
                this.batch_id
            }&nav_id=${this.nav_id}&page=`;

            if (this.old_batch_id && this.new_batch_id) {
                navDates = `${this.oldSelectedData.actual_count_date},${this.oldSelectedData.actual_count_end},${this.newSelectedData.actual_count_date},${this.newSelectedData.actual_count_end}`;

                url = `/reports/variance_report/getResults?date=${btoa(
                    navDates
                )}&date2=${btoa(this.date2)}&vendors=${btoa(
                    this.forPrintVendor
                )}&category=${this.forPrintCategory}&company=${
                    this.company
                }&bu=${this.business_unit}&dept=${this.department}&section=${
                    this.section
                }&old_batch_id=${this.old_batch_id}&new_batch_id=${
                    this.new_batch_id
                }&page=`;
            }

            if (
                this.business_unit &&
                this.department &&
                !this.filterBU &&
                // this.vendor &&
                // this.category &&
                this.batch_id &&
                this.nav_id &&
                this.date &&
                this.date2
            ) {
                this.isLoading = true;
                this.data.data = [];
                this.export = [];
                this.finalExport = [];
                this.filteredData = [];

                axios.get(url).then((response) => {
                    this.data = response.data;
                    this.filteredData = this.data.data;
                    this.total_result = response.data.total;
                    this.isLoading = false;
                    if (response.data.exist == false) {
                        this.finalExport = response.data;
                        // this.exportcsv()
                    }
                });
            } else if (
                this.business_unit &&
                this.department &&
                !this.filterBU &&
                // this.vendor &&
                // this.category &&
                this.old_batch_id &&
                this.new_batch_id &&
                this.date &&
                this.date2
            ) {
                this.isLoading = true;
                this.data.data = [];
                this.export = [];
                this.finalExport = [];
                this.filteredData = [];

                axios.get(url).then((response) => {
                    this.data = response.data;
                    this.filteredData = this.data.data;
                    this.total_result = response.data.total;
                    this.isLoading = false;
                    if (response.data.exist == false) {
                        this.finalExport = response.data;
                        // this.exportcsv()
                    }
                });
            }
        },
        exportcsv(reportType) {
            this.export = [];
            // for (const [data, test] of Object.entries(this.filteredData)) {
            const bu = this.buList.filter(
                (ya) => ya.business_unit === this.business_unit
            )[0];
            const dept = this.deptList.filter(
                (sm) => sm.dept_name == this.department
            )[0];

            let variance = 0,
                journalTemplateName = "ITEM",
                journalBatchName =
                    this.business_unit == "ISLAND CITY MALL"
                        ? "ICM-PCOUNT"
                        : this.business_unit == "ASC: MAIN"
                        ? "ASCM-PCOUNT"
                        : this.business_unit == "ALTURAS TALIBON"
                        ? "ASC-TAL-PCOUNT"
                        : this.business_unit == "ALTA CITTA"
                        ? "AC-PCOUNT"
                        : this.business_unit == "DISTRIBUTION"
                        ? "MPDC-PCOUNT"
                        : "PM-PCOUNT",
                lineNo = 0,
                itemCode = 0,
                postingDate = this.batch_id
                    ? dayjs(this.date2).format("MM/DD/YYYY")
                    : null,
                entryType = "",
                docNo = 10000100,
                variantCode = null,
                desc = "",
                locCode = "",
                invtyPostGroup = "RESALE",
                nav_qty = 0,
                app_qty = 0,
                unposted = 0,
                invQty = 0,
                unitAmt = 0,
                unitCost = 0,
                amt = 0,
                sourceCode = "ITEMJNL",
                // companyCode =
                //     this.business_unit == "ALTA CITTA"
                //         ? "08.00"
                //         : bu.acctng_dimension,
                // deptCode =
                //     this.business_unit == "ALTA CITTA"
                //         ? "08.01.1.01"
                //         : dept.acctng_dimension,

                companyCode = bu.acctng_dimension,
                deptCode = dept.acctng_dimension,
                reasonCode = "ADJ_PCOUNT",
                genProdPostGroup = "RETAIL12",
                docDate = dayjs(this.date2).format("MM/DD/YYYY"),
                exDocNo = "",
                qtyPerUom = 0,
                uom = "",
                qtyBase = 0,
                invQtyBase = 0,
                valueEntry = "",
                itemDiv = 0,
                value = 0;

            this.filteredData.forEach((result) => {
                itemCode = result.itemcode;
                variantCode = result.variant_code;
                desc = result.extended_desc;
                app_qty = result.conversion_qty;
                nav_qty = isNaN(result.nav_qty) ? 0 : result.nav_qty;
                nav_qty = nav_qty > 0 ? nav_qty : (nav_qty = 0);
                unposted = isNaN(result.unposted) ? 0 : result.unposted;
                // variance = result.variance
                value = nav_qty + unposted;

                // if (nav_qty < 0) {
                //   value == isNaN(value) ? variance = app_qty + value :
                //     variance = app_qty;
                // } else {
                //   if (nav_qty != '-') value == isNaN(value) ?
                //     variance = app_qty - value : variance = app_qty; //
                // }
                nav_qty > app_qty
                    ? (variance = result.variance)
                    : (variance = app_qty);
                if (nav_qty > 0) {
                    // if (app_qty > nav_qty) {
                    variance = result.variance;
                    // }
                }
                var x = null,
                    y = null;
                this.business_unit == "ISLAND CITY MALL"
                    ? (x = "ICM")
                    : this.business_unit == "ASC: MAIN"
                    ? (x = "ASCM")
                    : this.business_unit == "ALTURAS TALIBON"
                    ? (x = "ASC-TAL")
                    : this.business_unit == "ALTA CITTA"
                    ? (x = "AC001")
                    : this.business_unit == "DISTRIBUTION"
                    ? (x = "MPDC")
                    : (x = "PM");
                this.section == "SELLING AREA"
                    ? (y = "-SA")
                    : this.section == "STOCKROOM"
                    ? (y = "-SR")
                    : this.section == "SNACKBAR"
                    ? (y = "-SNACKBAR")
                    : this.section == "MIAS"
                    ? (y = "-MIAS")
                    : this.section == "WAREHOUSE"
                    ? (y = "-WAREHOUSE")
                    : this.section == "CENTRALIZE"
                    ? (y = "-CENTRALIZE")
                    : this.section == "NEW WAREHOUSE"
                    ? (y = "-WAREHOUSE")
                    : (y = "");
                locCode = x + y;

                uom = result.uom;

                unitAmt = result.cost_no_vat != 0 ? result.cost_no_vat : "0.0";
                unitCost = result.cost_no_vat != 0 ? result.cost_no_vat : "0.0";
                amt = variance * unitAmt;
                // if (
                //   (reportType == 'Negative' && variance < 0) ||
                //   (reportType == 'Positive' && variance > 0)
                // ) {
                variance < 0
                    ? (entryType = "Negative Adjmt.")
                    : (entryType = "Positive Adjmt.");
                amt = Math.abs(amt);
                variance = Math.abs(variance);
                invQty = Math.abs(variance);
                qtyBase = Math.abs(variance);
                invQtyBase = Math.abs(variance);

                // variance = app_qty
                // invQty = app_qty
                // qtyBase = app_qty
                // invQtyBase = app_qty
                if (variance != 0) {
                    if (nav_qty != app_qty) {
                        lineNo += 10000;
                        docNo += 1;

                        if (this.business_unit == "ALTA CITTA") {
                            this.export.push({
                                journalTemplateName,
                                lineNo,
                                itemCode,
                                postingDate,
                                entryType,
                                docNo,
                                desc,
                                locCode,
                                invtyPostGroup,
                                variance,
                                invQty,
                                unitAmt,
                                unitCost,
                                amt,
                                sourceCode,
                                companyCode,
                                deptCode,
                                journalBatchName,
                                reasonCode,
                                genProdPostGroup,
                                docDate,
                                exDocNo,
                                variantCode,
                                qtyPerUom,
                                uom,
                                qtyBase,
                                invQtyBase,
                                valueEntry,
                                itemDiv,
                            });
                        } else {
                            this.export.push({
                                journalTemplateName,
                                journalBatchName,
                                lineNo,
                                itemCode,
                                variantCode,
                                postingDate,
                                entryType,
                                docNo,
                                desc,
                                locCode,
                                invtyPostGroup,
                                variance,
                                invQty,
                                unitAmt,
                                unitCost,
                                amt,
                                sourceCode,
                                companyCode,
                                deptCode,
                                reasonCode,
                                genProdPostGroup,
                                docDate,
                                exDocNo,
                                qtyPerUom,
                                uom,
                                qtyBase,
                                invQtyBase,
                                valueEntry,
                                itemDiv,
                            });
                        }
                    }
                }

                // }
            });
            // }
        },
        computeNet(navQty, Unposted) {
            let net = 0;
            if (Unposted != "-" && navQty != "-") {
                net = parseFloat(navQty) + parseFloat(Unposted);
            } else if (navQty == "-") {
                net = isNaN(Unposted) ? "-" : parseFloat(Unposted);
            } else {
                net = isNaN(navQty) ? "-" : parseFloat(navQty);
            }

            return net;
        },
        computeVariance(a, b, c) {
            let variance = 0,
                value = 0;

            // if (b != '-' && a != '-') {
            //   value = a + b
            // } else if (a == '-' && b != '-') {
            //   value = b
            //   variance = b + c
            // } else if (b == '-' && a != '-') {
            //   value = a
            //   variance = a + c
            // }

            // if (a == '-' && b == '-') {
            //   value = '-'
            //   variance = c
            // }

            //  if (a < 0) {
            //   value == isNaN(value) ? (variance = c + value) : (variance = c)
            // } else {
            //   if (a != '-')
            //     value == isNaN(value) ? (variance = c - value) : (variance = c)
            // }

            // data.nav_qty, data.unposted, data.conversion_qty

            if (b != "-" && a != "-") {
                value = parseFloat(a) + parseFloat(b);
            } else if (a == "-") {
                value = isNaN(b) ? 0 : parseFloat(b);
            } else {
                value = isNaN(a) ? 0 : parseFloat(a);
            }

            value < 0
                ? (variance = parseFloat(c) + value)
                : (variance = value - parseFloat(c));
            return variance;
        },
        getFormattedDateToday() {
            return new Date().toJSON().slice(0, 10).replace(/-/g, "-");
        },
        departmentSelected(val) {
            this.section = null;
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
                    // `/setup/location/getSection/?bu=${bu.bunit_code}&dept=${department.dept_code}`
                    `/uploading/nav_upload/getSection/?code=${company.company_code}&bu=${bu.bunit_code}&dept=${department.dept_code}`
                )
                .then((response) => {
                    if (this.business_unit == "ALTURAS TALIBON") {
                        this.sectionList.push(
                            { section_name: "CENTRALIZE" },
                            { section_name: "WAREHOUSE" }
                        );
                    } else {
                        this.sectionList = response.data;
                    }
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
                        // `/setup/location/getDept/?bu=${bu.bunit_code}`
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
            loading(true);
            this.search2(search, loading, this);
        },
        search2: debounce((search, loading, vm) => {
            if (search.trim().length > 0) {
                axios
                    .get(`/uploading/nav_upload/getCategory?category=${search}`)
                    .then(({ data }) => {
                        vm.filteredcategoryList = data;
                        loading(false);
                    })
                    .catch((error) => {
                        vm.filteredcategoryList = [];
                        loading(false);
                    });
            } else {
                vm.filteredcategoryList = [];
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
        getResults2() {
            Promise.all([this.getCompany()]).then((response) => {
                this.companyList = response[0].data;
            });
        },
        async getCompany() {
            return await axios.get("/uploading/nav_upload/getCompany");
        },
    },
    mounted() {
        this.$root.currentPage = this.$route.meta.name;
        this.name = this.$root.authUser.name;
        this.userType = this.$root.authUser.usertype_id;
        // document.getElementById('maxDate').setAttribute('max', this.dateMax)
        // document.getElementById('maxDate2').setAttribute('max', this.dateMax)
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
