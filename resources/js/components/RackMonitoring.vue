<!-- @format -->

<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel" id="demo-panel-collapse-default">
        <div class="panel-body">
          <div class="panel-heading pad-all">
            <div class="panel-control">
              <button
                class="demo-panel-ref-btn btn btn-default"
                data-toggle="panel-overlay"
                data-target="#demo-panel-collapse-default"
                id="demo-state-btn"
                @click="refresh($event)"
                data-loading-text="Loading..."
              >
                <i class="demo-psi-repeat-2 icon-fw"></i> Refresh Table
              </button>
            </div>
            <h3
              class="text-thin bord-btm"
              style="font-size: 20px; padding: 10px"
            >
              <i class="demo-pli-map-2 icon-lg"></i> Rack Area Monitoring
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <div class="row pad-top">
                <div class="col-md-6 table-toolbar-left form-horizontal">
                  <div class="row pad-all" style="padding-left: 10px">
                    <label class="col-lg-3 control-label text-thin">
                      <h5>
                        <i class="icon-lg demo-pli-calendar-4 icon-fw"></i>
                        Count Date :
                      </h5>
                    </label>
                    <div class="col-lg-6">
                      <!-- <input
                        class="form-control"
                        v-model="date"
                        type="date"
                        style="border-radius: 4px"
                        min="dateToday"
                        name="dateFrom"
                        id="dateFrom"
                      /> -->
                      <date-picker
                        v-model="date"
                        :disabled-date="
                          date =>
                            !availableDates.includes(
                              new Date(
                                Date.UTC(
                                  date.getFullYear(),
                                  date.getMonth(),
                                  date.getDate()
                                )
                              )
                                .toISOString()
                                .substr(0, 10)
                            )
                        "
                        format="YYYY-MM-DD"
                        value-type="YYYY-MM-DD"
                        placeholder="Select count date"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 table-toolbar-right form-horizontal">
                  <div class="row pad-all" v-if="user.usertype_id == 1">
                    <router-link
                      to="/location"
                      class="btn btn-info btn-rounded mar-lft"
                    >
                      <i class="fa fa-arrow-circle-left icon-lg"></i> Location
                      Setup
                    </router-link>
                  </div>
                  <div class="row">
                    <code>
                      <small class="text-bold">
                        Legend:
                        <span class="text-main"> Completed</span>

                        <span class="fa fa-check" style="color: green"></span>

                        <span class="text-main"> On-going</span>

                        <span
                          class="fa fa-spinner fa-pulse fa-1x fa-fw"
                          style="color: orange"
                        ></span>
                      </small>
                    </code>
                  </div>
                </div>
                <table
                  class="table table-condensed table-vcenter table-hover"
                  id="datatable"
                >
                  <thead>
                    <tr>
                      <th class="text-center text-main">Location</th>
                      <th class="text-center text-main">Status</th>
                    </tr>
                  </thead>
                  <tbody v-if="!data.data.length">
                    <tr>
                      <td
                        colspan="6"
                        style="text-align: center"
                        class="mar-top"
                      >
                        No data available.
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-for="(data, index) in data.data" :key="index">
                    <tr>
                      <td
                        colspan="2"
                        class="text-main"
                        style="background-color: rgba(0, 0, 0, 0.09)"
                      >
                        <i class="fa fa fa-map-marker fa-1x fa-fw"></i>
                        {{ data.company }} {{ data.business_unit }}
                        {{ data.department }}
                      </td>
                    </tr>
                    <template v-for="section in data.sectionList">
                      <tr v-if="section.section != 'null'">
                        <td
                          colspan="2"
                          class="text-main"
                          style="background-color: rgba(0, 0, 0, 0.09)"
                        >
                          <i class="fa fa-arrow-circle-o-right fa-1x fa-fw"></i>
                          {{
                            section.section != 'null'
                              ? section.section
                              : 'No Section'
                          }}
                        </td>
                      </tr>
                      <template v-for="racks in section.racks">
                        <tr>
                          <td class="text-thin text-center">
                            <a href="javascript:;" class="btn-link text-thin">
                              <span
                                @click="
                                  ;(showModal = !showModal),
                                    (test = racks),
                                    (testBU = data.business_unit),
                                    (testSection = section.section)
                                "
                              >
                                {{ racks.rack_desc ? racks.rack_desc : 'Rack' }}
                              </span>
                            </a>
                          </td>
                          <td class="text-center text-thin">
                            <span
                              class="fa fa-check fa-1x fa-fw"
                              :class="{
                                'fa-check text-success': racks.rackGroup.every(
                                  rack => rack.done == 'true'
                                ),
                                'fa-spinner fa-pulse text-warning':
                                  !racks.rackGroup.every(
                                    rack => rack.done == 'true'
                                  )
                              }"
                              :style="{
                                'color: green;': racks.rackGroup.every(
                                  rack => rack.done == 'true'
                                ),
                                'color: orange;': !racks.rackGroup.every(
                                  rack => rack.done == 'true'
                                )
                              }"
                            ></span>
                          </td>
                        </tr>
                      </template>
                    </template>
                  </tbody>
                </table>
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-6">
                      <small>Showing {{ data.data.length }} Result(s) </small>
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
    <Modal
      :test="test"
      :testBU="testBU"
      :testSection="testSection"
      class="modal fade"
      id="rack-setup"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
      data-keyboard="false"
      data-backdrop="static"
      :showModal="showModal"
      :ywa="showLogs"
      @closeMdl="status"
      @passLocation="locationData"
    >
      <!-- @closeMdlForLogs="Logs" -->
      <!-- @passLocationBU="testBU = testBU" -->
      <!-- @passLocationID="location_id = $event"-->
      <!-- @passCountType="countType = $event" -->
    </Modal>
    <ModalLogs
      :location_id="location_id"
      :testBU="testBU"
      :countType="countType"
      class="modal fade"
      id="sync_logs"
      role="dialog"
      aria-labelledby="sync_logs"
      aria-hidden="true"
      data-keyboard="false"
      data-backdrop="static"
      @closeLogs="yw"
      @resetLocationID="location_id = $event"
    >
    </ModalLogs>
  </div>
</template>

<script>
import Vue from 'vue'
import Modal from './modals/ShowRack.vue'
import ModalLogs from './modals/showLogs.vue'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
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
      date: this.getFormattedDateToday(),
      dateToday: this.getFormattedDateToday(),
      total_result: null,
      showModal: false,
      test: null,
      location_id: null,
      testBU: null,
      testSection: null,
      showLogs: null,
      countType: null,
      user: [],
      availableDates: []
    }
  },
  components: {
    Modal,
    ModalLogs,
    DatePicker
  },
  watch: {
    date(value) {
      if (value) {
        this.getResults()
      } else {
        this.clearData()
      }
    },
    showModal() {
      this.showModal == true ? $('#rack-setup').modal('show') : null
    }
  },
  methods: {
    clearData: function () {
      for (let prop in this.data) {
        if (Array.isArray(this.data[prop])) {
          this.data[prop] = []
        } else {
          this.data[prop] = null
        }
      }
    },
    locationData(options) {
      if (options.bu && options.bu.length) {
        this.testBU = options.bu
      }
      if (options.section && options.section.length) {
        this.testSection = options.section
      }
      if (options.location_id) {
        this.location_id = options.location_id
      }
      if (options.countType && options.countType.length) {
        this.countType = options.countType
      }

      $('#rack-setup').modal('hide')
      this.showModal = false
      $('#sync_logs').modal('show')
    },
    yw() {
      this.showModal = true
      this.showLogs = false
    },
    Logs(value) {
      $('#rack-setup').modal('hide')
      this.showModal = false
      $('#sync_logs').modal('show')
      // this.showModal = value
    },
    status(value) {
      // console.log(value)
      this.showModal = value
    },
    getFormattedDateToday() {
      return new Date().toJSON().slice(0, 10).replace(/-/g, '-')
    },
    getResults(page = 1) {
      let url = null,
        type = 'LocationMonitoring'

      axios
        .get(
          `/setup/location/getResults/?date=${btoa(
            this.date
          )}&type=${type}&company=${this.company}&bu=${
            this.business_unit
          }&dept=${this.department}&section=${this.section}&page=`
        )
        .then(response => {
          this.data = response.data
          this.total_result = response.data.total
        })
    },
    refresh(e) {
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML
      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'

      setTimeout(() => {
        this.getResults()
        thisButton.disabled = false
        thisButton.innerHTML = oldHTML
      }, 800)
    },
    async getDates() {
      const response = await axios.get(
        `/reports/backend/getCountDate?comp=${null}&bu=${null}&dept=${null}&sect=${null}&type=MONITORING`
      )
      this.availableDates = response.data.map(obj => obj.batchDate)
    }
  },
  mounted() {
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

    this.$root.currentPage = 'this.$route.meta.name'
    this.getResults()
    this.user = this.$root.authUser
    this.getDates()
    // document.getElementById('dateFrom').setAttribute('min', this.dateToday)
  }
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}

#container .table-hover > tbody > tr:hover {
  background-color: rgb(2 2 2 / 5%);
}
</style>
