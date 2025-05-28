<!-- @format -->
<template>
    <div id="page-body">
        <div id="page-content">
            <div class="panel">
                <div class="panel-body">
                    <div class="row mar-top">
                        <div class="col-md-12">
                            <h3
                                class="panel-heading text-thin"
                                style="font-size: 20px"
                            >
                                ROTC MANAGEMENT SYSTEM
                            </h3>
                            <h3 class="text-main text-normal text-2x mar-no">
                                Welcome {{ user.name }}!
                            </h3>
                            <hr class="new-section-xs" />
                            <div class="row">
                                <div class="col-md-4">
                                    <div
                                        class="panel panel-warning panel-colorful media middle pad-all"
                                    >
                                        <div class="media-left">
                                            <div class="pad-hor">
                                                <i
                                                    class="fa fa-group fa-3x"
                                                ></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p
                                                class="text-2x mar-no text-semibold"
                                            >
                                                {{ cadets }}
                                            </p>
                                            <p class="mar-no">Total Cadets</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div
                                        class="panel panel-info panel-colorful media middle pad-all"
                                    >
                                        <div class="media-left">
                                            <div class="pad-hor">
                                                <i
                                                    class="fa fa-calendar-check-o fa-3x"
                                                ></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p
                                                class="text-2x mar-no text-semibold"
                                            >
                                                {{ schedules }}
                                            </p>
                                            <p class="mar-no">
                                                Total Schedules
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div
                                        class="panel panel-mint panel-colorful media middle pad-all"
                                    >
                                        <div class="media-left">
                                            <div class="pad-hor">
                                                <i class="fa fa-edit fa-3x"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p
                                                class="text-2x mar-no text-semibold"
                                            >
                                                {{ request }}
                                            </p>
                                            <p class="mar-no">
                                                Absence's Request
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <br />
                            <div class="panel">
                                <!--Panel heading-->
                                <div class="panel-heading">
                                    <h3
                                        class="text-main text-normal text-2x mar-no"
                                    >
                                        Upcoming Schedules!
                                    </h3>
                                </div>
                                <div class="row" style="text-align: right">
                                    <div class="form-group">
                                        <div
                                            class="col-md-2"
                                            style="float: right"
                                        >
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Search"
                                                v-model="search"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-hover dt-responsive nowrap table-vcenter"
                                        id="demo-panel-ref"
                                    >
                                        <thead>
                                            <tr>
                                                <th>Events</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-if="
                                                    !upcomingevents.data
                                                        .length && !loading
                                                "
                                            >
                                                <td
                                                    colspan="3"
                                                    style="text-align: center"
                                                >
                                                    No data available.
                                                </td>
                                            </tr>
                                            <tr v-if="loading">
                                                <td
                                                    colspan="3"
                                                    style="text-align: center"
                                                >
                                                    <spinner
                                                        style="height: 90px"
                                                    ></spinner>
                                                    Loading please wait... :)
                                                </td>
                                            </tr>
                                            <tr
                                                v-for="(
                                                    trans, index
                                                ) in upcomingevents.data"
                                                :key="index"
                                            >
                                                <td>{{ trans.events }}</td>
                                                <td>{{ trans.date }}</td>
                                                <td>
                                                    {{ trans.time_from }} -
                                                    {{ trans.time_to }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr
                                                style="
                                                    margin-top: 25px;
                                                    margin-bottom: 15px;
                                                "
                                            />

                                            <div class="col-md-6">
                                                Showing
                                                {{ upcomingevents.from }} to
                                                {{ upcomingevents.to }} of
                                                {{ upcomingevents.total }}
                                                entries
                                            </div>
                                            <div class="col-md-6">
                                                <div class="text-right">
                                                    <pagination
                                                        style="
                                                            margin: 0 0 20px 0;
                                                        "
                                                        :limit="1"
                                                        :show-disabled="false"
                                                        :data="upcomingevents"
                                                        @pagination-change-page="
                                                            getupcomingevents()
                                                        "
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
            </div>
        </div>
    </div>
</template>

<script>
import { debounce } from "lodash";
import _ from "lodash";
export default {
    data() {
        return {
            data: {
                data: [],
                current_page: null,
                to: null,
                from: null,
                total: null,
                per_page: null,
            },
            upcomingevents: {
                data: [],
                current_page: null,
                to: null,
                from: null,
                total: null,
                per_page: null,
            },
            total_result: 0,
            user: [],
            cadets: 0,
            schedules: 0,
            request: 0,
            loading: false,
            search: "",
        };
    },
    watch: {
        search() {
            this.getupcomingevents();
        },
    },
    methods: {
        getcountcadets() {
            axios.get(`/cadets/get_count_cadets?`).then((res) => {
                this.cadets = res.data;
            });
        },
        getcountschedules() {
            axios.get(`/cadets/get_count_schedules?`).then((res) => {
                this.schedules = res.data;
            });
        },
        getcountrequest() {
            axios.get(`/cadets/get_count_request?`).then((res) => {
                this.request = res.data;
            });
        },

        getupcomingevents: _.debounce(function (page = 1) {
            this.loading = true;
            axios
                .get(`/cadets/get_upcomingevents?search=${this.search}`)
                .then((res) => {
                    this.upcomingevents = res.data;
                    this.loading = false;
                });
        }, 350),
    },
    mounted() {
        this.$root.currentPage = this.$route.meta.name;
        this.user = this.$root.authUser;
        this.getcountcadets();
        this.getcountschedules();
        this.getcountrequest();
        this.getupcomingevents();
        console.log(this.user);
    },
};
</script>

<style lang="scss" scoped></style>
