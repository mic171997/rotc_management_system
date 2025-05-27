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
                                Absent Request
                            </h3>
                            <hr class="new-section-xs" />
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <v-select
                                                placeholder="Select Status"
                                                :options="optionlist"
                                                v-model="status"
                                            >
                                            </v-select>
                                        </div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div
                                            class="form-group"
                                            style="float: right"
                                        >
                                            <input
                                                style="width: 250px"
                                                type="text"
                                                v-model="search"
                                                class="form-control"
                                                placeholder="search"
                                            />
                                        </div>
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
                                            <th>Cadet No</th>
                                            <th>Name</th>
                                            <th>Company</th>
                                            <th>Events</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Approved By</th>
                                            <th
                                                style="text-align: center"
                                                v-if="user.type == 'Admin'"
                                            >
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-if="
                                                !request.data.length && !loading
                                            "
                                        >
                                            <td
                                                colspan="9"
                                                style="text-align: center"
                                            >
                                                No data available.
                                            </td>
                                        </tr>
                                        <tr v-if="loading">
                                            <td
                                                colspan="9"
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
                                            ) in request.data"
                                            :key="index"
                                        >
                                            <td>{{ trans.cadetno }}</td>
                                            <td>{{ trans.name }}</td>
                                            <td>{{ trans.company }}</td>
                                            <td>{{ trans.events }}</td>
                                            <td>{{ trans.date }}</td>
                                            <td>{{ trans.time }}</td>
                                            <td>{{ trans.reason }}</td>
                                            <td
                                                style="text-align: center"
                                                v-if="trans.status == 0"
                                            >
                                                <button
                                                    class="btn btn-sm btn-warning"
                                                >
                                                    Pending
                                                </button>
                                            </td>
                                            <td
                                                style="text-align: center"
                                                v-if="trans.status == 1"
                                            >
                                                <button
                                                    class="btn btn-sm btn-success"
                                                >
                                                    Approved
                                                </button>
                                            </td>
                                            <td>{{ trans.approved_by }}</td>

                                            <td
                                                style="text-align: center"
                                                v-if="user.type == 'Admin'"
                                            >
                                                <button
                                                    @click="
                                                        approvebtn(trans.id)
                                                    "
                                                    :disabled="
                                                        trans.status == 1
                                                    "
                                                    :class="
                                                        trans.status == 0
                                                            ? 'btn btn-sm btn-primary'
                                                            : 'btn btn-sm btn-success'
                                                    "
                                                >
                                                    {{
                                                        trans.status == 0
                                                            ? "For Approval"
                                                            : "Approved"
                                                    }}
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
                                            Showing {{ request.from }} to
                                            {{ request.to }} of
                                            {{ request.total }} entries
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <pagination
                                                    style="margin: 0 0 20px 0"
                                                    :limit="1"
                                                    :show-disabled="false"
                                                    :data="request"
                                                    @pagination-change-page="
                                                        getrequest()
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
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    data() {
        return {
            optionlist: ["Pending", "Approved"],
            request: {
                data: [],
                current_page: null,
                to: null,
                from: null,
                total: null,
                per_page: null,
            },
            status: "",
            search: "",
            loading: false,
            user: "",
        };
    },
    watch: {
        search() {
            this.getrequest();
        },
        status() {
            this.getrequest();
        },
    },

    methods: {
        getrequest: _.debounce(function (page = 1) {
            this.loading = true;
            axios
                .get(
                    `/cadets/get_request?search=${this.search}&status=${this.status}&page=` +
                        page
                )
                .then((res) => {
                    this.request = res.data;
                    this.loading = false;
                });
        }, 350),

        approvebtn(id) {
            Swal.fire({
                title: "Are you sure to Approved this Absent Request?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, I am sure!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post(`/cadets/approved_request?id=${id}`)
                        .then((response) => {
                            if (response.data.message == "Success") {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Approved Successfully!",
                                    icon: "success",
                                    allowOutsideClick: false,
                                });
                                this.getrequest();
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
        this.getrequest();
        this.user = this.$root.authUser;
    },
};
</script>

<style lang="scss" scoped></style>
