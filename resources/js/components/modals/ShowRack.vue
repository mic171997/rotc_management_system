<!-- @format -->

<template>
  <div>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5
            class="modal-title bord-btm text-thin"
            id="mdlTitle"
            style="padding: 5px 15px 15px 15px; font-size: 20px;"
          >
            <i class="demo-pli-map-2 icon-lg"></i>
            {{ data == null ? 'Rack' : test.rack_desc }}
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeBtn()"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div
          class="modal-body"
          style="height: 50vh;
          overflow-y: auto;"
        >
          <div class="table-responsive">
            <table class="table table-striped table-vcenter table-hover">
              <thead>
                <th class="text-main text-center">
                  Inventory Clerk
                </th>
                <th class="text-main text-center">Audit</th>
                <th class="text-main text-center">Count Type</th>
                <th class="text-main text-center" style="width: 20%">Status</th>
                <th class="text-main text-center">Logs</th>
              </thead>
              <tbody>
                <tr v-if="!showModal">
                  <td colspan="3" style="text-align: center;">
                    No data available.
                  </td>
                </tr>
                <tr v-else v-for="(rack, index) in test.rackGroup" :key="index">
                  <td class="text-main text-normal">
                    {{ rack.app_user }}
                  </td>
                  <td class="text-main text-normal">
                    {{ rack.audit_user }}
                  </td>
                  <td class="text-main text-normal">
                    {{ rack.countType }}
                  </td>
                  <td class="text-center">
                    <span
                      class="fa fa-check fa-1x fa-fw"
                      :class="{
                        'fa-check text-success': rack.done == 'true',
                        'fa-spinner fa-pulse text-warning': rack.done == 'false'
                      }"
                      :style="{
                        'color: green;': rack.done == 'true',
                        'color: orange;': rack.done == 'false'
                      }"
                    ></span>
                  </td>
                  <td class="text-center">
                    <a
                      v-if="rack.done == 'true'"
                      href="javascript:;"
                      @click="
                        ;(showLogs = !showLogs),
                          (location_id = rack.location_id),
                          (countType = rack.countType)
                      "
                      class="fa fa-check fa-1x fa-fw"
                      :class="{
                        'fa-bars text-success': rack.done == 'true',
                        'fa-bars text-muted': rack.done == 'false'
                      }"
                      :style="{
                        'color: green;': rack.done == 'true',
                        'color: gray;': rack.done == 'false'
                      }"
                    ></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeBtn()"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  props: ['showModal', 'test', 'testBU', 'testSection', 'ywa'],
  data() {
    return {
      data: null,
      rack_name: null,
      rack: new Form({
        id: null,
        name: null
      }),
      message: null,
      result: [],
      showLogs: false,
      location_id: null,
      countType: null
    }
  },
  components: {},
  watch: {
    showModal() {
      this.data = true
      this.showModal == true ? (this.showLogs = false) : null
    },
    showLogs() {
      // this.showLogs == true ? this.$emit('closeMdlForLogs', false) : null
      // this.showLogs == true
      //   ? this.$emit('passLocationID', this.location_id)
      //   : null
      // this.showLogs == true ? this.$emit('passLocationBU', this.testBU) : null
      // this.showLogs == true ? this.$emit('passCountType', this.countType) : null
      this.showLogs == true
        ? this.$emit('passLocation', {
            bu: this.testBU,
            section: this.testSection,
            location_id: this.location_id,
            countType: this.countType
          })
        : null
    },
    ywa(val) {
      this.showLogs = val
      this.location_id = null
    }
  },
  methods: {
    editBtn(data) {
      this.rack_name = data.rack_name
      this.rack.id = data.id
      this.rack.name = data.rack_name
      this.rack.company = data.company
      this.rack.business_unit = data.business_unit
      this.rack.department = data.department
      this.rack.section = data.section
      this.$nextTick(() => this.$refs.rackname.focus())
    },
    submitBtn() {
      this.rack.name = this.rack_name
      this.rack.company = this.company
      this.rack.business_unit = this.business_unit
      this.rack.department = this.department
      this.rack.section = this.section

      this.rack
        .post('/setup/location/createRack')
        .then(({ data, status, text }) => {
          //   if ((status = 200)) {
          $.niftyNoty({
            type: 'success',
            icon: 'fa fa-check icon-2x',
            message: data.message,
            container: 'floating',
            timer: 5000
          })
          this.getResults()
          this.rack_name = null
          this.message = null
          this.rack.reset()
          //   }
        })
        .catch(({ response }) => {
          const { status, data } = response
          //   console.log(status, data)
          this.message = data.message
          this.$nextTick(() => this.$refs.rackname.focus())
          $.niftyNoty({
            type: 'danger',
            icon: 'fa fa-exclamation-triangle',
            message: data.message,
            container: 'floating',
            timer: 5000
          })
        })
    },
    getResults() {
      axios
        .get(
          `/setup/location/getRacks?company=${this.company}&business_unit=${this.business_unit}&department=${this.department}&section=${this.section}`
        )
        .then(response => {
          this.data = response.data
          // console.log(response.data)
        })
    },
    closeBtn() {
      this.rack_name = null
      this.message = null
      this.rack.reset()
      this.data = null
      this.$emit('closeMdl', false)
    }
  },
  mounted() {}
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
