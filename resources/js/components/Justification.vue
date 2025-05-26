<!-- @format -->
<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel" id="demo-panel-collapse-default">
        <div class="panel-body">
          <div class="panel-heading pad-all ">
            <div class="panel-control">
              <button class="btn btn-default btn-rounded mar-lft text-thin" @click="showModal = !showModal">
                <i class="demo-pli-information icon-lg"></i>
                Justification Reason Setup
              </button>
              <button class="demo-panel-ref-btn btn btn-default btn-rounded mar-lft text-thin" data-toggle="panel-overlay"
                data-target="#demo-panel-collapse-default" id="demo-state-btn" @click="refresh($event)"
                data-loading-text="Loading...">
                <i class="demo-psi-repeat-2 icon-fw"></i> Refresh Table
              </button>
            </div>
            <h3 class="text-thin bord-btm " style="font-size: 20px;padding: 10px;">
              <i class="demo-pli-support icon-lg"></i> {{ $route.meta.name }}
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <div class="row pad-top">
                <div class="col-md-6 table-toolbar-left form-horizontal">
                  <div class="row pad-all" style="padding-left: 10px;"></div>
                </div>
                <div class="col-md-6 table-toolbar-right form-horizontal">
                  <div class="row pad-all"></div>
                </div>
                <table class="table table-condensed table-vcenter table-hover" id="datatable">
                  <thead>
                    <tr>
                      <th class="text-main">Company</th>
                      <th class="text-main">Business Unit</th>
                      <th class="text-main">Department</th>
                      <th class="text-main">Section</th>
                      <th class="text-main">Advance Count Date</th>
                      <th class="text-main">Actual Count Date</th>
                      <th class="text-main">Nav Date</th>
                      <th class="text-main">Created By</th>
                      <th class="text-main">Created At</th>
                      <th class="text-main">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="!data.data.length">
                      <td colspan="10" style="text-align: center;">
                        <div class="sk-cube-grid" v-if="isLoading">
                          <div class="sk-cube sk-cube1"></div>
                          <div class="sk-cube sk-cube2"></div>
                          <div class="sk-cube sk-cube3"></div>
                          <div class="sk-cube sk-cube4"></div>
                          <div class="sk-cube sk-cube5"></div>
                          <div class="sk-cube sk-cube6"></div>
                          <div class="sk-cube sk-cube7"></div>
                          <div class="sk-cube sk-cube8"></div>
                          <div class="sk-cube sk-cube9"></div>
                        </div>
                        <span class="text-thin text-main" v-if="isLoading">Loading please wait... :)
                        </span>

                        <div v-else>No data available.</div>
                      </td>
                    </tr>
                    <tr v-for="(data, index) in data.data" :key="index">
                      <td class="text-main text-thin">
                        {{ data.company }}
                      </td>
                      <td class="text-main text-thin">
                        {{ data.business_unit }}
                      </td>
                      <td class="text-main text-thin">
                        {{ data.department }}
                      </td>
                      <td class="text-main text-thin">
                        {{ data.section != 'null' ? data.section : '' }}
                      </td>
                      <td class="text-main text-thin">
                        {{ data.adv_start_date != "0000-00-00" ?
                          formatDate(data.adv_start_date, 'AppStart') +
                          ' ~ ' +
                          formatDate(data.adv_end_date, 'AppEnd') : 'No date'
                        }}
                      </td>
                      <td class="text-main text-thin">
                        {{ data.actual_start_date != "0000-00-00" ?
                          formatDate(data.actual_start_date, 'ActualStart') +
                          ' ~ ' +
                          formatDate(data.actual_end_date, 'ActualEnd') : 'No date'
                        }}
                      </td>
                      <td class="text-main text-thin">
                        {{ data.nav_date | formatDate }}
                      </td>
                      <td class="text-main text-thin">
                        {{ data.name }}
                      </td>
                      <td class="text-main text-thin">
                        {{ data.created_at | formatDate }}
                      </td>
                      <td class="text-main text-thin">
                        <button class="btn btn-info btn-sm" @click="getHead(data)">
                          <i class="fa fa-download"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-6">
                      <small>Showing {{ data.data.length }} result(s)</small>
                    </div>
                    <div class="col-md-6"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <ReportHead v-if="selectedData" :test="selectedData" />
    <ReasonModal class="modal fade" id="rack-setup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
      data-keyboard="false" data-backdrop="static" :showModal="showModal" @closeMdl="showModal = false"></ReasonModal>
  </div>
</template>

<script>
import Vue from 'vue'
import ReportHead from './VarianceReportHead.vue'
import ReasonModal from './modals/ReasonSetup.vue'
var dayjs = require('dayjs')
export default {
  data() {
    return {
      data: {
        data: [],
        current_page: null,
        from: null,
        to: null,
        total: null,
        per_page: null
      },
      selectedData: null,
      isLoading: false,
      showModal: false
    }
  },
  props: ['comp', 'b_unit', 'dept', 'sect'],
  components: {
    ReportHead,
    ReasonModal
  },
  watch: {
    $route(to, from) {
      this.getResults()
    },
    showModal(val) {
      val == true
        ? $('#rack-setup').modal('show')
        : $('#rack-setup').modal('hide')
    }
  },
  methods: {
    formatDate(value, type) {
      // console.log(value)
      let date = value != '0000-00-00' ? dayjs(value).format('MM/DD/YYYY') : ''
      // type == 'AppStart'
      //   ? (this.date = date)
      //   : type == 'NavDate'
      //   ? (this.date2 = date)
      //   : null
      return date
    },
    getHead(data) {
      this.selectedData = data
    },
    async getResults() {
      this.isLoading = true
      return await axios
        .get(`reports/justification/getResults?comp=${this.comp}&b_unit=${this.b_unit}&dept=${this.dept}&sect=${this.sect}`)
        .then(response => {
          this.data = response.data
          this.isLoading = false
        })
    },
    refresh(e) {
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML
      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'
      this.selectedData = null
      setTimeout(() => {
        this.getResults()
        thisButton.disabled = false
        thisButton.innerHTML = oldHTML
      }, 800)
    }
  },
  mounted() {
    this.$root.currentPage = 'this.$route.meta.name'
    this.getResults()
    $('.demo-panel-ref-btn')
      .niftyOverlay({
        iconClass: 'demo-psi-repeat-2 spin-anim icon-2x'
      })
      .on('click', function () {
        var $el = $(this),
          relTime
        $el.niftyOverlay('show')

        // Do something...

        relTime = setInterval(function () {
          // Hide the screen overlay
          $el.niftyOverlay('hide')

          clearInterval(relTime)
        }, 800)
      })
  }
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}

#container .table-hover>tbody>tr:hover {
  background-color: rgb(2 2 2 / 5%);
}
</style>
