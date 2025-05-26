/** @format */

import Vue from 'vue'
import VueRouter from 'vue-router'

import Dashboard from './components/Dashboard.vue'
import Cadets from './components/Add_cadets.vue'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      name: 'Dashboard',
      path: '/dashboard',
      component: Dashboard,
      meta: {
        name: 'Dashboard'
      }
    },
    {
      name: 'Add Cadets',
      path: '/cadets',
      component: Cadets,
      meta: {
        name: 'Add Cadets'
      }
    },


  ]
})

export default router
