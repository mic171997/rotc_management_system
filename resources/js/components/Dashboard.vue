<!-- @format -->
<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel">
        <div class="panel-body">
          <div class="row mar-top">
            <div class="col-md-12">
              <h3 class="panel-heading text-thin" style="font-size: 20px">
                ROTC MANAGEMENT SYSTEM
              </h3>
              <h3 class="text-main text-normal text-2x mar-no">
                Welcome {{ user.name }}!
              </h3>
              <!-- <h5 class="text-uppercase text-muted text-normal">
                {{ date | DashboardTime }}
              </h5> -->
              <hr class="new-section-xs" />
              <div class="row mar-top">
              
                <div class="col-sm-4">
                
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
export default {
  data() {
    return {
      data: {
        data: [],
        current_page: null,
        to: null,
        from: null,
        total: null,
        per_page: null
      },
      total_result: 0,
      user: []
    }
  },
  components: {
   
  },
  methods: {
   
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.user = this.$root.authUser
    console.log(this.user)
  }
}
</script>

<style lang="scss" scoped></style>
