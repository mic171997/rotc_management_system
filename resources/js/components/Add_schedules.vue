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
                                Schedules
                            </h3>
                            <hr class="new-section-xs" />

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="panel">
                                        <div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Type of
                                                            Events</label
                                                        >
                                                        <input
                                                            type="text"
                                                            v-model="event"
                                                            class="form-control"
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Time From</label
                                                        >
                                                        <br />
                                                        <date-picker
                                                            type="time"
                                                            format="HH:mm"
                                                            value-type="HH:mm"
                                                            v-model="time_from"
                                                            placeholder="Select Time"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Date of
                                                            Events</label
                                                        >
                                                        <br />
                                                        <date-picker
                                                            format="YYYY-MM-DD"
                                                            value-type="YYYY-MM-DD"
                                                            v-model="date"
                                                            placeholder="Select count date"
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Time To</label
                                                        >
                                                        <br />
                                                        <date-picker
                                                            type="time"
                                                            format="HH:mm"
                                                            value-type="HH:mm"
                                                            v-model="time_to"
                                                            placeholder="Select Time"
                                                        />
                                                    </div>
                                                </div>

                                                <div class="col-sm-1">
                                                    <div
                                                        class="form-group"
                                                        style="margin-top: 25px"
                                                    >
                                                        <button
                                                            :class="
                                                                !id
                                                                    ? 'btn btn-success'
                                                                    : 'btn btn-primary'
                                                            "
                                                            @click="addevents()"
                                                        >
                                                            {{
                                                                !id
                                                                    ? "Add Events"
                                                                    : "Update Events"
                                                            }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="text-align: right">
                                <div class="form-group">
                                    <div class="col-md-2" style="float: right">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Search"
                                            v-model="search"
                                        />
                                    </div>
                                </div>
                            </div>
                            <br />

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
                                            <th style="text-align: center">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-if="
                                                !events.data.length && !loading
                                            "
                                        >
                                            <td
                                                colspan="4"
                                                style="text-align: center"
                                            >
                                                No data available.
                                            </td>
                                        </tr>
                                        <tr v-if="loading">
                                            <td
                                                colspan="4"
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
                                            ) in events.data"
                                            :key="index"
                                        >
                                            <td>{{ trans.events }}</td>
                                            <td>{{ trans.date }}</td>
                                            <td>
                                                {{ trans.time_from }} -
                                                {{ trans.time_to }}
                                            </td>
                                            <td style="text-align: center">
                                                <button
                                                    class="btn btn-sm btn-info"
                                                    data-target="#filemodal"
                                                    data-toggle="modal"
                                                    @click="
                                                        showattendance(trans)
                                                    "
                                                >
                                                    Show Attendance
                                                </button>
                                                <button
                                                    class="btn btn-sm btn-danger"
                                                    @click="
                                                        deletesched(trans.id)
                                                    "
                                                >
                                                    Delete
                                                </button>
                                                <button
                                                    class="btn btn-sm btn-primary"
                                                    @click="updatesched(trans)"
                                                >
                                                    Update
                                                </button>
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
                                            Showing {{ events.from }} to
                                            {{ events.to }} of
                                            {{ events.total }} entries
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <pagination
                                                    style="margin: 0 0 20px 0"
                                                    :limit="1"
                                                    :show-disabled="false"
                                                    :data="events"
                                                    @pagination-change-page="
                                                        getevents()
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

            <div
                class="modal fade"
                id="filemodal"
                role="dialog"
                aria-labelledby="myModalLabel"
                aria-hidden="true"
            >
                <div
                    class="modal-dialog modal-lg"
                    style="width: 1000px"
                    role="document"
                >
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5
                                class="modal-title"
                                id="mdlTitle"
                                style="
                                    padding: 5px 15px 15px 15px;
                                    font-size: 12px;
                                "
                            >
                                <i class="demo-pli-male"></i> Attendance
                                Information
                            </h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                                @click="closemodal()"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"
                                            >Events</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="events_info"
                                            disabled
                                        />
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"
                                            >Total Cadets:
                                            {{ cadetcounts }}</label
                                        >
                                        <br />
                                        <label class="control-label"
                                            >Present:
                                            {{ cadetattendance }}</label
                                        >
                                        <br />
                                        <label class="control-label"
                                            >Absent:
                                            {{
                                                cadetcounts - cadetattendance
                                            }}</label
                                        >
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"
                                            >Date</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="date_info"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="text-align: right">
                                <div class="form-group">
                                    <div class="col-md-2" style="float: right">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Search"
                                            v-model="searchlist"
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
                                            <th>Cadet No.</th>
                                            <th>Cadet</th>
                                            <th>Company</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-if="
                                                !cadetattendancelist.data
                                                    .length && !loading
                                            "
                                        >
                                            <td
                                                colspan="5"
                                                style="text-align: center"
                                            >
                                                No data available.
                                            </td>
                                        </tr>
                                        <tr v-if="loading">
                                            <td
                                                colspan="5"
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
                                            ) in cadetattendancelist.data"
                                            :key="index"
                                        >
                                            <td>
                                                {{ trans.cadetno }}
                                            </td>
                                            <td>
                                                {{ trans.name }}
                                                {{ trans.lastname }}
                                            </td>
                                            <td>{{ trans.company }}</td>
                                            <td>
                                                {{
                                                    trans.time_in == null
                                                        ? "No Time In"
                                                        : trans.time_in
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    trans.time_out == null
                                                        ? "No Time Out"
                                                        : trans.time_out
                                                }}
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
                                            {{ cadetattendancelist.from }} to
                                            {{ cadetattendancelist.to }} of
                                            {{ cadetattendancelist.total }}
                                            entries
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <pagination
                                                    style="margin: 0 0 20px 0"
                                                    :limit="1"
                                                    :show-disabled="false"
                                                    :data="cadetattendancelist"
                                                    @pagination-change-page="
                                                        getattendancecadet()
                                                    "
                                                ></pagination>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                                aria-label="Close"
                                @click="closemodal()"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { debounce } from "lodash";
import _ from "lodash";
export default {
    data() {
        return {
            loading: false,
            events: {
                data: [],
                current_page: null,
                to: null,
                from: null,
                total: null,
                per_page: null,
            },
            cadetattendancelist: {
                data: [],
                current_page: null,
                to: null,
                from: null,
                total: null,
                per_page: null,
            },
            id: "",
            time_from: null,
            time_to: null,
            date: null,
            event: "",
            search: "",
            searchlist: "",
            events_info: "",
            date_info: "",
            id_info: "",
            cadetcounts: 0,
            cadetattendance: 0,
        };
    },
    components: {
        DatePicker,
    },
    watch: {
        search() {
            this.getevents();
        },
        searchlist() {
            this.getattendancecadet();
        },
    },

    methods: {
        getattendancecadet: _.debounce(function (page = 1) {
            this.loading = true;
            axios
                .get(
                    `/cadets/get_cadets_attendancelist?search=${this.searchlist}&id=${this.id_info}&page=` +
                        page
                )
                .then((res) => {
                    this.cadetattendancelist = res.data;
                    this.loading = false;
                });
        }, 350),

        showattendance(trans) {
            axios
                .get(`/cadets/get_count_attendance?id=${trans.id}`)
                .then((res) => {
                    this.cadetattendance = res.data;
                });
            this.events_info = trans.events;
            this.date_info =
                trans.date + " " + trans.time_from + "-" + trans.time_to;
            this.id_info = trans.id;
            this.getattendancecadet();
            $("#filemodal").modal("show");
        },
        closemodal() {
            $("#filemodal").modal("hide");
        },
        addevents() {
            Swal.fire({
                title: !this.id
                    ? "Are you sure to Add this Schedules?"
                    : "Are you sure to Update this Schedules?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, I am sure!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post(
                            `/cadets/add_schedules?date=${this.date}&time_from=${this.time_from}&time_to=${this.time_to}&events=${this.event}&id=${this.id}`
                        )
                        .then((response) => {
                            if (response.data.message == "Success") {
                                Swal.fire({
                                    title: "Success!",
                                    text: !this.id
                                        ? "Successfully Added!"
                                        : "Successfully Updated!",
                                    icon: "success",
                                    allowOutsideClick: false,
                                });
                                this.clear();
                                this.getevents();
                            }
                        });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Cancelled",
                        text: "Action has been cancelled!",
                        icon: "info",
                        allowOutsideClick: false,
                    });
                }
            });
        },

        clear() {
            this.event = "";
            this.date = null;
            this.time_from = null;
            this.time_to = null;
            this.id = "";
        },
        deletesched(id) {
            Swal.fire({
                title: "Are you sure to Delete this Schedules?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, I am sure!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post(`/cadets/delete_schedules?id=${id}`)
                        .then((response) => {
                            if (response.data.message == "Success") {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Successfully Deleted!",
                                    icon: "success",
                                    allowOutsideClick: false,
                                });
                                this.clear();
                                this.getevents();
                            }
                        });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Cancelled",
                        text: "Action has been cancelled!",
                        icon: "info",
                        allowOutsideClick: false,
                    });
                }
            });
        },
        getevents: _.debounce(function (page = 1) {
            this.loading = true;
            axios
                .get(`/cadets/get_events?search=${this.search}&page=` + page)
                .then((res) => {
                    this.events = res.data;
                    this.loading = false;
                });
        }, 350),
        updatesched(trans) {
            this.event = trans.events;
            this.date = trans.date;
            this.time_from = trans.time_from;
            this.time_to = trans.time_to;
            this.id = trans.id;
        },
        getcountcadets() {
            axios.get(`/cadets/get_count_cadets?`).then((res) => {
                this.cadetcounts = res.data;
            });
        },
    },
    mounted() {
        this.getevents();
        this.getcountcadets();
    },
};
</script>

<style lang="scss" scoped></style>
