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
            <i class="demo-pli-information icon-lg"></i>
            Justification Reason Masterfile Setup
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
        <div class="modal-body">
          <div class="table-responsive">
            <form class="form-horizontal" @submit.prevent>
              <div class="panel-body">
                <label
                  for="demo-oi-definput"
                  class="control-label text-semibold"
                  >Input Reason</label
                >
                <div class="input-group">
                  <input
                    id="demo-oi-definput"
                    type="text"
                    placeholder="Input Reason"
                    class="form-control text-xl"
                    style="font-style: 14px;"
                    v-model="reason"
                    ref="reason"
                  />
                  <span class="input-group-btn">
                    <button
                      v-if="!form.id"
                      class="btn btn-warning"
                      type="button"
                      @click="submitBtn()"
                    >
                      Add
                    </button>
                    <button
                      v-else
                      class="btn btn-warning"
                      type="button"
                      @click="submitBtn()"
                    >
                      Update
                    </button>
                  </span>
                </div>
                <small
                  class="help-block text-danger text-bold"
                  data-bv-for="demo-oi-definput"
                  style=""
                  v-if="message"
                  >{{ message }}</small
                >
              </div>
            </form>
            <table class="table table-striped table-vcenter table-hover">
              <thead>
                <th class="text-main text-center" style="width: 30%">
                  Reason
                </th>
                <th class="text-main text-center" style="20%">Action</th>
              </thead>
              <tbody>
                <tr v-if="!data.length">
                  <td colspan="2" style="text-align: center;">
                    No data available.
                  </td>
                </tr>
                <tr v-for="(data, index) in data" :key="index">
                  <td class="text-main text-normal" style="width: 30%">
                    {{ data.reason }}
                  </td>
                  <td
                    class="text-main text-normal text-center"
                    style="width: 20%"
                  >
                    <button class="btn btn-info btn-xs" @click="editBtn(data)">
                      <i class="demo-pli-gear icon-lg icon-fw"></i>
                    </button>
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
  //   props: ['company', 'business_unit', 'department', 'section', 'showRackSetup'],
  data() {
    return {
      data: [],
      reason: null,
      form: new Form({
        id: null,
        reason: null,
        added_by: null,
        created_at: null,
        updated_at: null
      }),
      message: null
    }
  },
  props: {
    showModal: Boolean
  },
  watch: {
    showModal(value) {
      if (value) this.getResults()
    }
  },
  methods: {
    editBtn(data) {
      console.log(data)
      this.reason = data.reason
      this.form.id = data.id
      this.form.reason = data.reason
      this.form.added_by = data.added_by
      this.form.created_at = data.created_at
      this.form.updated_at = data.updated_at
      this.$nextTick(() => this.$refs.reason.focus())
    },
    submitBtn() {
      this.form.reason = this.reason

      this.form
        .post('/setup/justification/createJustification')
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
          this.reason = null
          this.message = null
          this.form.reset()
          //   }
        })
        .catch(({ response }) => {
          const { status, data } = response
          //   console.log(status, data)
          this.message = data.message
          this.$nextTick(() => this.$refs.reason.focus())
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
      axios.get(`/reports/justification/getReasons`).then(response => {
        this.data = response.data
      })
    },
    closeBtn() {
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
