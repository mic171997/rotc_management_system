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
                                File Absent
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
                                                    class="btn btn-sm btn-primary"
                                                    data-target="#filemodal"
                                                    data-toggle="modal"
                                                    @click="file(trans)"
                                                >
                                                    File Absent
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

            <!-- modal -->
            <div
                class="modal fade"
                id="filemodal"
                role="dialog"
                aria-labelledby="myModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog" style="width: 600px" role="document">
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
                                <i class="demo-pli-male"></i> File Absent
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
                            <div>
                                <div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label"
                                                    >Cadet No.</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="cadetno"
                                                    disabled
                                                />
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"
                                                    >Full Name</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="name"
                                                    disabled
                                                />
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"
                                                    >Company</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="company"
                                                    disabled
                                                />
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"
                                                    >Events</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="event"
                                                    disabled
                                                />
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"
                                                    >Date</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="date"
                                                    disabled
                                                />
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"
                                                    >Time</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="time"
                                                    disabled
                                                />
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label"
                                                    >Reason</label
                                                >
                                                <textarea
                                                    class="form-control"
                                                    placeholder="Reason"
                                                    v-model="reason"
                                                ></textarea>
                                            </div>
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
    </div>
</template>

<script>
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
            users: "",
            cadetno: "",
            name: "",
            company: "",
            date: "",
            time: "",
            event: "",
            reason: "",
            id: "",
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
        file(trans) {
            $("#filemodal").modal("show");
            this.cadetno = this.user.cadetno;
            this.name = this.user.name + " " + this.user.lastname;
            this.company = this.user.company;
            this.date = trans.date;
            this.time = trans.time_from + "-" + trans.time_to;
            this.event = trans.events;
            this.id = trans.id;
        },

        submitfile() {
            Swal.fire({
                title: "Are you sure to Submit this Absent Request?",
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
                            `/cadets/add_absent_request?cadetno=${this.cadetno}&name=${this.name}&company=${this.company}&events=${this.event}&date=${this.date}&time=${this.time}&reason=${this.reason}&id=${this.id}`
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
                                this.reason = "";
                                this.id = "";
                            } else if (response.data.message == "Exists") {
                                Swal.fire({
                                    title: "Warning!",
                                    text: "You Already Filed For this Schedules!",
                                    icon: "warning",
                                    allowOutsideClick: false,
                                });
                                $("#filemodal").modal("hide");
                                this.reason = "";
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
        this.user = this.$root.authUser;
    },
};
</script>

<style lang="scss" scoped></style>
