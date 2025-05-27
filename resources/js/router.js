/** @format */

import Vue from "vue";
import VueRouter from "vue-router";

import Dashboard from "./components/Dashboard.vue";
import Cadets from "./components/Add_cadets.vue";
import Schedules from "./components/Add_schedules.vue";
import Absents from "./components/file_absent.vue";
import Absents_Request from "./components/absent_request.vue";
import Attendance from "./components/schedules.vue";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            name: "Dashboard",
            path: "/dashboard",
            component: Dashboard,
            meta: {
                name: "Dashboard",
            },
        },
        {
            name: "Add Cadets",
            path: "/cadets",
            component: Cadets,
            meta: {
                name: "Add Cadets",
            },
        },
        {
            name: "Add Schedules",
            path: "/schedules",
            component: Schedules,
            meta: {
                name: "Add Schedules",
            },
        },
        {
            name: "File Absent",
            path: "/file_absents",
            component: Absents,
            meta: {
                name: "File Absent",
            },
        },
        {
            name: "Absent Request",
            path: "/absents_request",
            component: Absents_Request,
            meta: {
                name: "Absent Request",
            },
        },
        {
            name: "Attedance",
            path: "/attendance",
            component: Attendance,
            meta: {
                name: "Attendance",
            },
        },
    ],
});

export default router;
