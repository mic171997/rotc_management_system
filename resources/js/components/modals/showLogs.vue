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
            Sync Logs
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeLogs()"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <ul id="demo-mail-list" class="file-list" style="padding: 0px;">
              <li v-if="!result.length">
                <div class="media-block">
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
                  <span class="text-thin text-main" v-if="isLoading"
                    >Loading please wait... :)
                  </span>

                  <div class="media-body" v-else>
                    <p class="file-name text-center">
                      No data available.
                    </p>
                  </div>
                </div>
              </li>

              <li v-else v-for="(data, index) in result" :key="index">
                <div class="file-attach-icon"></div>
                <a href="javascript:;" class="file-details">
                  <div class="media-block">
                    <div class="media-left">
                      <i class="demo-psi-folder-zip text-success"></i>
                    </div>
                    <div class="media-body">
                      <p class="file-name">
                        {{ data.items }} Number of Items Synched.
                      </p>
                      <p class="file-name">
                        {{data.nf_items}} Number of Not Found Synched.
                      </p>
                      <small
                        >Created {{ data.datetime_exported | formatDateTime }}
                      </small>
                    </div>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeLogs()"
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
  props: ['location_id', 'testBU', 'countType'],
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
      isLoading: true
    }
  },
  watch: {
    location_id() {
      this.getResults()
    }
  },
  methods: {
    async getResults() {
      this.isLoading = true
      return await axios
        .get(
          `/setup/location/getLocationCount?location_id=${this.location_id}&BU=${this.testBU}&countType=${this.countType}`
        )
        .then(response => {
          this.result = response.data
          console.log(this.result)
          this.isLoading = false
        })
    },
    closeLogs() {
      //   this.result = []
      this.$emit('closeLogs', false)
      this.$emit('resetLocationID', null)
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
