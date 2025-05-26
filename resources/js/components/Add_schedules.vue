<template>
  <div id="page-body">
      <div id="page-content">
        <div class="panel">
          <div class="panel-body">
            <div class="row mar-top">
              <div class="col-md-12">
                <h3 class="panel-heading text-thin" style="font-size: 20px">
                  Add Schedules
                </h3>
                <hr class="new-section-xs" />

                  <div class="row">
					    <div class="col-sm-6">
					        <div class="panel">
					            <div>
					                    <div class="row">
					                        <div class="col-sm-6">
					                            <div class="form-group">
					                                <label class="control-label">Type of Events</label>
					                                <input type="text" v-model="firstname" class="form-control">
					                            </div>
                                                <div class="form-group">
					                                <label class="control-label">Time</label>
					                                <input type="text" v-model="company" class="form-control">
					                            </div>
					                        </div>
					                        <div class="col-sm-5">
					                            <div class="form-group">
					                                <label class="control-label">Date of Events</label>
					                                 <input type="text" v-model="company" class="form-control">
					                            </div>
                                              <div class="form-group" style="margin-top: 40px;">
                                                    <button :class="!id ? 'btn btn-success': 'btn btn-primary'" @click="addcadet()">{{!id ? 'Add Events' : 'Update Events' }}</button>
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
        </div>
    </div>
</template>

<script>
export default {
  data () {
    

    return {}
  },
  
}
</script>

<style lang="scss" scoped>

</style>