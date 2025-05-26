<!-- @format -->
<template>
    <div id="page-body">
      <div id="page-content">
        <div class="panel">
          <div class="panel-body">
            <div class="row mar-top">
              <div class="col-md-12">
                <h3 class="panel-heading text-thin" style="font-size: 20px">
                  Add Cadet
                </h3>
                <hr class="new-section-xs" />
                <div class="row">
					    <div class="col-sm-6">
					        <div class="panel">
					            <div>
					                    <div class="row">
					                        <div class="col-sm-6">
					                            <div class="form-group">
					                                <label class="control-label">Firstname</label>
					                                <input type="text" v-model="firstname" class="form-control">
					                            </div>
                                                <div class="form-group">
					                                <label class="control-label">Company</label>
					                                <input type="text" v-model="company" class="form-control">
					                            </div>
					                        </div>
					                        <div class="col-sm-5">
					                            <div class="form-group">
					                                <label class="control-label">Lastname</label>
					                                <input type="text" v-model="lastname" class="form-control">
					                            </div>
                                                <div class="form-group">
					                                <label class="control-label">Cadet No.</label>
					                                <input type="text" v-model="cadetno" class="form-control" disabled>
					                            </div>
					                        </div>
                                            <div class="col-sm-1">
                                                <div class="form-group" style="margin-top: 23px;">
                                                    <button class="btn btn-success" @click="addcadet()" type="submit">Submit</button>
					                            </div>
					                        </div>
					                    </div>
					            </div>
					        </div>
					    </div>
					    </div>

                        <div class="row" style="text-align: right;">
                    <div class="form-group">
                      <div class="col-md-2" style="float: right;">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Search"
                          v-model="search"
                        />
                      </div>
                    </div>
                  </div>
                  <br>

                        <div class="table-responsive">
          <table
            class="table table-bordered table-hover dt-responsive nowrap table-vcenter"
            id="demo-panel-ref"
          >
            <thead>
              <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Company</th>
                <th>Cadet No</th>
                <th style="text-align: center;">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="!cadets.data.length && !loading">
                <td colspan="4" style="text-align: center">
                  No data available.
                </td>
              </tr>
              <tr v-if="loading">
                <td colspan="4" style="text-align: center">
                  <spinner style="height: 90px;"></spinner>
                  Loading please wait... :)
                </td>
              </tr>
              <tr v-for="(trans, index) in cadets.data" :key="index">
                <td>{{ trans.name }}</td>
                <td>{{ trans.lastname }}</td>
                <td>{{ trans.company }}</td>
                <td>{{ trans.cadetno }}</td>
                <td style="text-align: center;"><button class="btn btn-sm  btn-danger" @click="deletecadets(trans.id)" >Delete</button>
                    <button class="btn btn-sm  btn-primary" >Update</button>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="row">
            <div class="col-md-12">
              <hr style="margin-top: 25px; margin-bottom: 15px;" />

              <div class="col-md-6">
                Showing {{ cadets.from }} to {{ cadets.to }} of
                {{ cadets.total }} entries
              </div>
              <div class="col-md-6">
                <div class="text-right">
                  <pagination
                    style="margin: 0 0 20px 0"
                    :limit="1"
                    :show-disabled="false"
                    :data="cadets"
                    @pagination-change-page="getcadet()"
                  ></pagination>
                </div>
              </div>
            </div>
          </div>
        </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
import { clippingParents } from '@popperjs/core';
import Swal from "sweetalert2";
import Vue from "vue";
import _ from "lodash";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { debounce } from "lodash";
Vue.component("v-select", vSelect);
Vue.component("pagination", require("laravel-vue-pagination"));


  export default {
    data() {
      return {
        cadets: {
          data: [],
          current_page: null,
          to: null,
          from: null,
          total: null,
          per_page: null
        },
        total_result: 0,
        user: [],
        cadetno: '',
        firstname:'',
        lastname:'',
        company:'',
        search: "",
        loading: false,
      }
    },
    watch: {
        search() {
            this.getcadet();
        }
    },

    components: {
     
    },
    methods: {
     
        getCadetCounts() {
      axios
        .get(
          `/cadets/get_cadet_counts?`
        )
        .then((response) => {
          this.cadetno = response.data;
        })
        .catch((response) => {
          console.log("error");
        });
    },

    addcadet() {
        Swal.fire({
        title: "Are you sure to Add this Cadet?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, I am sure!",
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post(`/cadets/add_cadets?firstname=${this.firstname}&lastname=${this.lastname}&company=${this.company}&cadetno=${this.cadetno}`).then((response) => {
            if (response.data.message == "Success") {
              Swal.fire({
                title: "Success!",
                text: "Successfully Added!",
                icon: "success",
                allowOutsideClick: false,
              });
         this.clear();
         this.getCadetCounts();
         this.getcadet();
            }
          });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          Swal.fire({
            title: "Cancelled",
            text: "Action has been cancelled!",
            icon: "info",
            allowOutsideClick: false,
          });
        }
      });
    },
    deletecadets(id) {
        Swal.fire({
        title: "Are you sure to Delete this Cadet?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, I am sure!",
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post(`/cadets/delete_cadets?id=${id}`).then((response) => {
            if (response.data.message == "Success") {
              Swal.fire({
                title: "Success!",
                text: "Successfully Deleted!",
                icon: "success",
                allowOutsideClick: false,
              });
         this.clear();
         this.getCadetCounts();
         this.getcadet();
            }
          });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          Swal.fire({
            title: "Cancelled",
            text: "Action has been cancelled!",
            icon: "info",
            allowOutsideClick: false,
          });
        }
      });
    },
    clear() {
        this.firstname = '';
        this.lastname = '';
        this.company = '';
        this.getCadetCounts();
    },

    getcadet: _.debounce(function(page = 1) {
        this.loading = true;
      axios
        .get(
          `/cadets/get_cadets?search=${this.search}&page=` +
            page
        )
        .then((res) => {
          this.cadets = res.data;
          this.loading = false;
        });
    }, 350),
    },
    mounted() {
        this.getCadetCounts();
        this.getcadet();
      this.$root.currentPage = this.$route.meta.name
      this.user = this.$root.authUser
      console.log(this.user)
    }
  }
  </script>
  
  <style lang="scss" scoped></style>
  