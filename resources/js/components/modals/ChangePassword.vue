<!-- @format -->

<template>
  <div>
    <div class="modal-dialog modal">
      <div class="modal-content">
        <div class="modal-header">
          <h5
            class="modal-title bord-btm text-thin"
            id="mdlTitle"
            style="padding: 5px 15px 15px 15px; font-size: 20px"
          >
            <i class="demo-pli-information icon-lg"></i>
            Change Password
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
          <form @submit.prevent="submitform" class="panel-body form-horizontal">
            <div class="form-group">
              <label class="col-md-3 control-label" for="demo-text-input"
                >Old Password</label
              >
              <div class="col-md-8">
                <input
                  type="password"
                  placeholder="Enter old password"
                  class="form-control text-xl"
                  v-model.trim="userForm.old_password"
                />
                <small
                  class="text-danger"
                  v-if="userForm.errors.has('old_password')"
                  v-html="userForm.errors.get('old_password')"
                />
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label" for="demo-text-input"
                >New Password</label
              >
              <div class="col-md-8">
                <input
                  type="password"
                  placeholder="Must be at least 8 characters and contain at least one digit"
                  class="form-control text-xl"
                  v-model.trim="userForm.password"
                />
                <small
                  class="text-danger"
                  v-if="userForm.errors.has('password')"
                  v-html="userForm.errors.get('password')"
                />
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label" for="demo-text-input"
                >Confirm Password</label
              >
              <div class="col-md-8">
                <input
                  type="password"
                  placeholder="Must be at least 8 characters and contain at least one digit"
                  class="form-control text-xl"
                  v-model.trim="userForm.confirm_password"
                />
                <small
                  class="text-danger"
                  v-if="userForm.errors.has('confirm_password')"
                  v-html="userForm.errors.get('confirm_password')"
                />
              </div>
            </div>
          </form>
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

          <button
            type="button"
            @click="submitBtn()"
            class="btn btn-primary"
            :disabled="userForm.busy"
          >
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  data() {
    return {
      data: [],
      date: null,
      message: null,
      isLoading: false,
      old_password: null,
      userForm: new Form({
        id: null,
        old_password: null,
        password: null,
        confirm_password: null
      })
    }
  },
  methods: {
    submitBtn() {
      this.userForm
        .post('/setup/users/updatePassword')
        .then(({ data, status }) => {
          if (status == 200) {
            this.userForm.clear()
            this.userForm.reset()

            this.closeBtn()

            $.niftyNoty({
              type: 'success',
              icon: 'pli-cross icon-2x',
              message: '<i class="fa fa-check"></i> User added successfully!',
              container: 'floating',
              timer: 5000
            })
          }
        })
        .catch(({ response }) => {
          const { status, data } = response
          // console.log(status, data.errors)
          $.niftyNoty({
            type: 'danger',
            icon: 'fa fa-exclamation-triangle',
            message: data.message,
            container: 'floating',
            timer: 5000
          })
        })
    },
    closeBtn() {
      this.userForm.clear()
      this.userForm.reset()
      this.$emit('toggle', false)
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
