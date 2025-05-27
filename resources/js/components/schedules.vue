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
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: center">
                                                <button
                                                    class="btn btn-sm btn-success"
                                                    data-target="#filemodal"
                                                    data-toggle="modal"
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
                                        @click="submitfile()"
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
        };
    },
    watch: {
        search() {
            this.getevents();
        },
    },
    methods: {
        getevents: _.debounce(function (page = 1) {
            this.loading = true;
            axios
                .get(`/cadets/get_events?search=${this.search}&page=` + page)
                .then((res) => {
                    this.events = res.data;
                    this.loading = false;
                });
        }, 350),
        attend(id) {
            $("#filemodal").modal("show");
        },
    },
    mounted() {
        this.getevents();
    },
};
</script>

<style lang="scss" scoped></style>
