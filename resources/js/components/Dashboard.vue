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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
            total_result: 0,
            user: [],
            cadets: 0,
            schedules: 0,
            request: 0,
        };
    },
    components: {},
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
    },
    mounted() {
        this.$root.currentPage = this.$route.meta.name;
        this.user = this.$root.authUser;
        this.getcountcadets();
        this.getcountschedules();
        this.getcountrequest();
        console.log(this.user);
    },
};
</script>

<style lang="scss" scoped></style>
