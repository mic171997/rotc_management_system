<template>
    <div>
        <div id="page-content">
            <div class="panel">
                <div class="panel-body">
                    <div class="row mar-top">
                        <div class="col-md-12">
                            <h3
                                class="panel-heading text-thin"
                                style="font-size: 20px"
                            >
                                Attendance
                            </h3>
                            <hr class="new-section-xs" />
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
                                            <th>Time In</th>
                                            <th>Time Out</th>
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
                                                colspan="6"
                                                style="text-align: center"
                                            >
                                                No data available.
                                            </td>
                                        </tr>
                                        <tr v-if="loading">
                                            <td
                                                colspan="6"
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
                                            <td>
                                                {{
                                                    getTimeIn(
                                                        trans.time_in,
                                                        trans.date,
                                                        trans.time_from
                                                    )
                                                }}
                                            </td>

                                            <td>
                                                {{
                                                    getTimeout(
                                                        trans.time_out,
                                                        trans.date,
                                                        trans.time_to
                                                    )
                                                }}
                                            </td>
                                            <td style="text-align: center">
                                                <button
                                                    class="btn btn-sm btn-success"
                                                    data-target="#filemodal"
                                                    data-toggle="modal"
                                                    :disabled="
                                                        isPastDate(trans.date)
                                                    "
                                                    @click="attend(trans.id)"
                                                >
                                                    Time in/Time out
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
        </div>

        <!-- modal -->
        <div
            class="modal fade"
            id="filemodal"
            role="dialog"
            aria-labelledby="myModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" style="width: 300px" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5
                            class="modal-title"
                            id="mdlTitle"
                            style="padding: 5px 15px 15px 15px; font-size: 12px"
                        >
                            <i class="demo-pli-male"></i> Time In / Time Out
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group pad-ver">
                            <label class="col-md-3 control-label"
                                >Select Options</label
                            >
                            <div class="col-md-9">
                                <div class="radio">
                                    <input
                                        id="demo-form-radio-2"
                                        class="magic-radio"
                                        type="radio"
                                        name="form-radio-button"
                                        value="Time In"
                                        v-model="attendance"
                                    />
                                    <label for="demo-form-radio-2"
                                        >Time In</label
                                    >
                                </div>
                                <div class="radio">
                                    <input
                                        id="demo-form-radio-3"
                                        class="magic-radio"
                                        type="radio"
                                        name="form-radio-button"
                                        value="Time Out"
                                        v-model="attendance"
                                    />
                                    <label for="demo-form-radio-3"
                                        >Time Out</label
                                    >
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <button
                                        class="btn btn-sm btn-success"
                                        style="float: right"
                                        data-target="#filemodal"
                                        data-toggle="modal"
                                        @click="submitattendance()"
                                    >
                                        Submit
                                    </button>
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
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal -->
    </div>
</template>

<script>
import Vue from "vue";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { debounce } from "lodash";
import _ from "lodash";
import { clippingParents } from "@popperjs/core";
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
            search: "",
            id: "",
            attendance: "",
        };
    },
    watch: {
        search() {
            this.getevents();
        },
    },
    methods: {
        isPastDate(dateStr) {
            if (!dateStr) return false;
            const inputDate = new Date(dateStr);
            const today = new Date();
            inputDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);
            return inputDate < today;
        },
        getTimeIn(timeIn, date, time) {
            if (!timeIn) return "No Time In";
            const timeInDate = new Date(timeIn);
            const datetimeString = `${date}T${time}`;
            const datetime = new Date(datetimeString);

            if (timeInDate > datetime) {
                return (
                    this.$options.filters.formatDateTime(timeIn) +
                    "    ( Late )"
                );
            }
            return this.$options.filters.formatDateTime(timeIn);
        },

        getTimeout(timeIn, date, time) {
            if (!timeIn) return "No Time Out";
            const timeInDate = new Date(timeIn);
            const datetimeString = `${date}T${time}`;
            const datetime = new Date(datetimeString);
            if (timeInDate < datetime) {
                return (
                    this.$options.filters.formatDateTime(timeIn) +
                    "    ( Undertime )"
                );
            }
            return this.$options.filters.formatDateTime(timeIn);
        },
        getevents: _.debounce(function (page = 1) {
            this.loading = true;
            axios
                .get(
                    `/cadets/get_events_attendance?search=${this.search}&page=` +
                        page
                )
                .then((res) => {
                    this.events = res.data;
                    this.loading = false;
                    console.log(this.loading);
                });
        }, 350),
        attend(id) {
            this.id = id;
            $("#filemodal").modal("show");
        },

        submitattendance() {
            Swal.fire({
                title: "Are you sure to Submit this Attendace?",
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
                            `/cadets/add_attendance?attendance=${this.attendance}&id=${this.id}`
                        )
                        .then((response) => {
                            if (response.data.message == "Success") {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Successfully Submitted!",
                                    icon: "success",
                                    allowOutsideClick: false,
                                });
                                $("#filemodal").modal("hide");
                                this.getevents();
                                this.attendance = "";
                                this.id = "";
                            } else {
                                Swal.fire({
                                    title: "Warning!",
                                    text:
                                        "You Already" +
                                        " " +
                                        response.data.message +
                                        "!",
                                    icon: "warning",
                                    allowOutsideClick: false,
                                });
                                $("#filemodal").modal("hide");
                                this.getevents();
                                this.attendance = "";
                                this.id = "";
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
    },
    mounted() {
        this.getevents();
    },
};
</script>

<style lang="scss" scoped></style>
