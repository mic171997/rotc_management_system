/** @format */

require("./bootstrap");

require("alpinejs");
var dayjs = require("dayjs");

import Vue from "vue";
import router from "./router";
import Breadcrumb from "./components/Breadcrumb";
import Spinner from "./components/global/Spinner";
import Swal from "sweetalert2";
import Modal from "./components/modals/ChangePassword.vue";
import "bootstrap";

const originalPopover = $.fn.popover;

$.fn.popover = function (config, ...args) {
    if (config === "destroy") {
        config = "dispose";
    }
    return originalPopover.call(this, config, ...args);
};

const authUser = JSON.parse(
    document.head.querySelector('meta[name="auth-user"]').content
);

Vue.filter("numberFormat", (number) => {
    if (number == "-") return "-";

    return new Intl.NumberFormat().format(number);
});
Vue.filter("formatDate", (date) => dayjs(date).format("MM/DD/YYYY"));
Vue.filter("formatDateTime", (date) => dayjs(date).format("MM/DD/YYYY h:mm A"));
Vue.filter("DashboardTime", (date) => dayjs(date).format("MMMM D, YYYY"));

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 4500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

window.Toast = Toast;
window.Swal = Swal;
window.authUser = authUser;
Vue.component("breadcrumb", Breadcrumb);
Vue.component("spinner", Spinner);
const app = new Vue({
    el: "#app",
    router,
    data() {
        return {
            authUser: null,
            currentPage: null,
            showChangePassMdl: false,
        };
    },
    components: { Modal },
    watch: {
        showChangePassMdl(val) {
            val == true
                ? $("#passwordMdl").modal("show")
                : $("#passwordMdl").modal("hide");
        },
    },
    methods: {
        togglechangePassMdl(val) {
            this.showChangePassMdl = val;
        },
    },
    created() {
        this.authUser = window.authUser;
    },
});
