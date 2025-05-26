<!-- @format -->

<template>
  <div>
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3
            class="panel-heading bord-btm text-thin animate-pulse"
            id="mdlTitle"
            style="font-size: 20px; padding: 5px 15px 15px 15px"
          >
            <i class="demo-pli-printer icon-lg"></i>
            Item Selection Setup
          </h3>
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
          <div class="col-md-6 table-toolbar-left form-horizontal"></div>
          <div class="col-md-6 table-toolbar-right form-horizontal">
            <div class="row">
              <code>
                <small class="text-bold">
                  Note:
                  <span class="text-main">
                    <i class="fa fa-warning"></i> Take note Data will be
                    overwritten once saved.
                  </span>
                </small>
              </code>
            </div>
          </div>
          <table class="table table-striped table-vcenter table-hover">
            <thead>
              <th class="text-main" style="width: 10%">Item Code</th>
              <th class="text-main" style="width: 10%">Barcode</th>
              <th class="text-main" style="width: 10%">Variant Code</th>
              <th class="text-main" style="width: 45%">Description</th>
              <th class="text-main" style="width: 5%">Uom</th>
              <th class="text-main" style="width: 5%">Action</th>
            </thead>
            <tbody>
              <tr v-if="!itemList.length">
                <td colspan="6" style="text-align: center">
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
                  <div v-else>No data available.</div>
                </td>
              </tr>
              <tr v-for="(data, index) in itemList" :key="index">
                <td class="text-main text-thin" style="width: 10%">
                  {{
                    data.item_code
                      ? data.item_code
                      : data.itemcode
                      ? data.itemcode
                      : '-'
                  }}
                </td>
                <td class="text-main text-thin" style="width: 10%">
                  {{ data.barcode ? data.barcode : '-' }}
                </td>

                <td class="text-main text-normal italic" style="width: 10%">
                  {{ data.variant_code ? data.variant_code : '-' }}
                </td>
                <td class="text-main text-normal italic" style="width: 45%">
                  {{
                    data.extended_desc ? data.extended_desc : data.description
                  }}
                </td>
                <td class="text-main text-normal italic" style="width: 5%">
                  {{ data.uom }}
                </td>
                <td class="text-main text-normal" style="width: 5%">
                  <!-- :class="{
                      'cursor-not-allowed opacity-50': editItem != null
                    }" -->
                  <button
                    class="btn btn-xs btn-danger pull-right text-white font-medium bg-red-500 focus:outline-none border-red-500"
                    @click="saveBtn(data, editItem)"
                  >
                    <span
                      aria-hidden="true"
                      class="fa fa-check fa-1x fa-fw"
                    ></span>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeBtn()"
          >
            CLOSE
          </button>

          <!-- <button
            type="button"
            class="inline-flex justify-center rounded-md border border-transparent p-4 bg-red-600 text-lg leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 focus:ring focus:ring-violet-300"
            @click="saveBtn"
            data-dismiss="modal"
            aria-label="Close"
            :disabled="invalid == false"
            :class="{
              'cursor-not-allowed opacity-50': invalid == false
            }"
          >
            SAVE
          </button> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import Form from 'vform'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'
Vue.component('v-select', vSelect)
export default {
  props: [
    'company',
    'business_unit',
    'department',
    'section',
    'date',
    'showRackSetup',
    'editItem',
    'type',
    'countType'
  ],
  data() {
    return {
      data: [],
      rack_name: null,
      rack: new Form({
        id: null,
        name: null,
        company: null,
        business_unit: null,
        department: null,
        section: null
      }),
      categoryList: [],
      itemList: [],
      forPrintItems: [],
      item: null,
      message: null,
      qty: [],
      navQty: [],
      isLoading: false
    }
  },
  created() {},
  watch: {
    // item(newValue) {
    //   if (this.showRackSetup == true) {
    //     if (newValue != null) {
    //       var exists = this.itemList.find(function (value) {
    //         return value.barcode == newValue.barcode
    //       })

    //       if (!exists) {
    //         this.itemList.push(newValue)
    //         this.qty.push(newValue)
    //         if (this.type != 'QuestionableItems') this.navQty.push(newValue)
    //       }

    //       this.item = null
    //       this.categoryList = []
    //     }
    //   }
    // },
    showRackSetup() {
      if (this.showRackSetup == true) {
        // console.log(this.editItem)
        // this.$nextTick(() => this.$refs.rackname.focus())
      }
    },
    editItem(value) {
      //   this.editItem ? this.itemList.push(this.editItem) : null

      value ? this.getResults() : null
    }
  },
  computed: {
    invalid() {
      // console.log(this.date)
      if (this.qty.length != 0 && this.editItem == null) {
        let result = this.qty.every(function (e) {
            return e > 0
          }),
          result2 = this.navQty.every(function (e) {
            return e > 0
          })
        console.log(result, result2)
        if (result && result2 && this.type != 'QuestionableItems') return result
        if (result && this.type == 'QuestionableItems') return result
      }
      if (this.navQty != 0 && this.editItem != null) {
        let result = this.navQty.every(function (e) {
          return e > 0
        })
        if (result) return result
      }
      return false
    }
  },
  methods: {
    selectItem(data, editItem) {
      console.log(data, editItem)
    },
    saveBtn(data, editItem) {
      // console.log(this.date)

      editItem.barcode = data.barcode
      editItem.itemcode = data.item_code
      editItem.description = data.extended_desc
      //   editItem.uom = data.uom

      const xdata = btoa(JSON.stringify([editItem]))
      //   console.log(xdata)
      axios
        .get(
          `/setup/countBackendSetup/postCount/?date=${btoa(
            this.date
          )}&data=${xdata}&bu=${this.business_unit}&dept=${
            this.department
          }&section=${this.section}&type=${this.type}&countType=${
            this.countType
          }`
        )
        .then((response, status) => {
          if (response.status == 200) {
            this.$emit('saveSuccess', false)
            this.itemList = []
            this.forPrintItems = []
            this.item = null
            this.qty = []
            this.navQty = []
          } else {
            console.log('wtf')
          }
        })
    },
    removeBtn: function (index) {
      this.itemList.splice(index, 1)
      this.qty.splice(index, 1)
      this.navQty.splice(index, 1)
    },
    isNumber: function (evt) {
      evt = evt ? evt : window.event
      var charCode = evt.which ? evt.which : evt.keyCode
      console.log(parseFloat(charCode))
      if (
        charCode > 31 &&
        (charCode < 46 || charCode > 57)
        // &&
        // charCode !== 46
      ) {
        evt.preventDefault()
      } else {
        return true
      }
    },
    closeBtn() {
      this.rack_name = null
      this.message = null
      this.rack.reset()
      this.itemList = []
      this.forPrintItems = []
      this.item = null
      this.qty = []
      this.navQty = []
      this.$emit('closeMdl', false)
    },
    retrieveCategory(search, loading) {
      if (search) {
        loading(true)
        this.search2(search, loading, this)
      }
    },
    search2: debounce((search, loading, vm) => {
      if (search.trim().length > 0) {
        axios
          .get(`/setup/masterfiles/getItems?item=${search}`)
          .then(({ data }) => {
            vm.categoryList = data
            loading(false)
          })
          .catch(error => {
            vm.categoryList = []
            loading(false)
          })
      } else {
        vm.categoryList = []
        loading(false)
      }
    }, 1000),
    async getItems() {
      const xdata = btoa(JSON.stringify(this.editItem))
      return await axios.get(
        `/setup/masterfiles/getItems?item=${xdata}&type=${this.type}&bu=${this.business_unit}`
      )
    },
    getResults() {
      this.isLoading = true
      Promise.all([this.getItems()])
        .then(response => {
          this.itemList = response[0].data
          this.isLoading = false
        })
        .catch(error => {
          this.itemList = []
        })
    }
  },
  mounted() {
    // console.log(this.editItem)
  }
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}

#container .table > tbody > tr:hover {
  background-color: rgb(2 2 2 / 5%);
}

h5 {
  font-size: 15px;
}
.bg-red-500 {
  --tw-bg-opacity: 1;
  background-color: rgba(239, 68, 68, var(--tw-bg-opacity));
}
.bg-red-500 {
  --tw-bg-opacity: 1;
  background-color: rgba(239, 68, 68, var(--tw-bg-opacity));
}

.btn.disabled,
.btn[disabled],
fieldset[disabled] .btn {
  cursor: not-allowed;
  filter: alpha(opacity=65);
  -webkit-box-shadow: none;
  box-shadow: none;
  opacity: 0.65;
}

button[disabled],
html input[disabled] {
  cursor: default;
}
</style>
