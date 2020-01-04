<template>
<div class="home">
        <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Student Attendance and Schedule Edit Permissions</h3>
	</div>
	<div class="panel-body">
        <div class="bachelers-department">
<h4  style="text-transform:uppercase;">{{authenticateduser.working_department}}</h4>
        <ul>
            <li v-for="permission in filterPermissions" v-bind:key="permission.id" class="class-row">
                {{permission.year}} Year
               <span style="text-transform:uppercase;"> {{permission.section}} </span> Section
                
                    <!-- <p v-if="permission.status === 1" class="text-info">
                    Enabled
                    </p>
                    <p v-if="permission.status !== 1" class="text-info">Disabled</p> -->

         <div id="editPermission" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <form @submit.prevent="updatePermission()">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> Edit Attendance Permission</h4>
      </div>
      <div class="modal-body">
          

          <table class="table table-striped">
              
              <tr>
                  <td>Class</td>
                  <td class="data-reg-no">{{Edit_year}} Year <span style="text-transform:uppercase;">{{Edit_section}} </span>Section</td>
              </tr>
                <tr>
                    <td>Attendance Edit Permission</td>
                    <td class="data-checkbox">
                        <label class="fancy-checkbox" for="checkbox_student">
						<input type="checkbox" id="checkbox_student" v-model="Edit_status">
                           <span v-if="Edit_status === 1">Enabled</span><span v-if="Edit_status !== 1">Disabled</span>
                        </label>
                      
                    </td>
                </tr>
                 <tr>
                    <td>Schedule Edit Permission</td>
                    <td class="data-checkbox">
                        <label class="fancy-checkbox" for="checkbox_student2">
						<input type="checkbox" id="checkbox_student2" v-model="Schedule_Edit_status">
                           <span v-if="Schedule_Edit_status === 1">Enabled</span><span v-if="Schedule_Edit_status !== 1">Disabled</span>
                        </label>
                      
                    </td>
                </tr>
          </table>
        
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary" >Save</button>
        <button class="btn btn-default" @click="CloseClick()" data-dismiss="modal">Close</button>
      </div>
    </div>
 </form>
  </div>
</div>
    <button  class="btn btn-primary edit-button" type="button" data-toggle="modal" data-target="#editPermission" @click="EditClick(permission)">Edit</button></td>
</li>
</ul>

        </div>
    <div class="masters-department">
        
        <div class="selest_department">
        <label>Select Department</label>
        <select v-model="selestMeDapartment">
            <option v-for="usersMasterDept in usersMasterDepts()">{{usersMasterDept}}</option>
        </select>
    </div>
    <h4 v-if="selestMeDapartment" style="text-transform:uppercase;">{{selestMeDapartment}}</h4>
        <ul v-if="selestMeDapartment">
            <li v-for="permission in filterPermissions2" v-bind:key="permission.id" class="class-row">
                {{permission.year}} Year
               <span style="text-transform:uppercase;"> {{permission.section}} </span> Section
                
                    <!-- <p v-if="permission.status === 1" class="text-info">
                    Enabled
                    </p>
                    <p v-if="permission.status !== 1" class="text-info">Disabled</p> -->

         <div id="editPermission" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <form @submit.prevent="updatePermission()">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> Edit Attendance Permission</h4>
      </div>
      <div class="modal-body">
          

          <table class="table table-striped">
              
              <tr>
                  <td>Class</td>
                  <td class="data-reg-no">{{Edit_year}} Year <span style="text-transform:uppercase;">{{Edit_section}} </span>Section</td>
              </tr>
                <tr>
                    <td>Attendance Edit Permission</td>
                    <td class="data-checkbox">
                        <label class="fancy-checkbox" for="checkbox_student">
						<input type="checkbox" id="checkbox_student" v-model="Edit_status">
                           <span v-if="Edit_status === 1">Enabled</span><span v-if="Edit_status !== 1">Disabled</span>
                        </label>
                      
                    </td>
                </tr>
                 <tr>
                    <td>Schedule Edit Permission</td>
                    <td class="data-checkbox">
                        <label class="fancy-checkbox" for="checkbox_student2">
						<input type="checkbox" id="checkbox_student2" v-model="Schedule_Edit_status">
                           <span v-if="Schedule_Edit_status === 1">Enabled</span><span v-if="Schedule_Edit_status !== 1">Disabled</span>
                        </label>
                      
                    </td>
                </tr>
          </table>
        
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary" >Save</button>
        <button class="btn btn-default" @click="CloseClick()" data-dismiss="modal">Close</button>
      </div>
    </div>
 </form>
  </div>
</div>
    <button  class="btn btn-primary edit-button" type="button" data-toggle="modal" data-target="#editPermission" @click="EditClick(permission)">Edit</button></td>
</li>
</ul>
    
    </div>    

	</div>
</div>
</div>
</template>

<script>
export default {
    data(){
        return {
            EditATPermissions: [],
            Edit_year: "",
            Edit_section:"",
            Edit_status:"",
            Edit_permission_id:"",
            Schedule_Edit_status:"",
            selestMeDapartment:""
        }
    },
    mounted(){
        this.EditATpermissions();
    },
     props:['authenticateduser', 'authrole'],
    methods:{
        EditClick(permission){
            this.Edit_year = permission.year;
            this.Edit_section = permission.section;
            this.Edit_status = permission.status;
            this.Edit_permission_id = permission.id;
            this.Schedule_Edit_status = permission.schedule_edit_status;
        },
        CloseClick(){
            this.Edit_year = "";
            this.Edit_section = "";
            this.Edit_status = "";
             this.Edit_permission_id = "";
             this.Schedule_Edit_status = "";
        },
        EditATpermissions(){
                fetch('/api/studentEditPermissions2')
            .then(res => { return res.json(); })
                .then(data => {
                    this.EditATPermissions = data;
                    // console.log(data);
                }).catch(err => console.log(err));
            },
            updatePermission(){

                let Formdata = {
                   permission_id : this.Edit_permission_id,
                   status: this.Edit_status,
                   Schestatus: this.Schedule_Edit_status
                }

                  fetch('/api/studentEditPermissions/update', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {

                    if(data['status'] === "success"){
                        alert('Your Permission Updated!');
                    }else{
                        alert('Some thing went Wrong try later');
                    }
                this.EditATpermissions();
                }).catch(err => {
                    console.log(err);
                });
               this.EditATpermissions();
            },
             usersMasterDepts(){
            if(this.authenticateduser.working_department_me !== null){
                let mArr = this.authenticateduser.working_department_me;

            mArr = mArr.split('&&');
            return mArr;
            }else{
                return [];
            }
            
        }
    },
    computed:{
         filterPermissions(){
        let EditATPermissions = this.EditATPermissions;

        let department = this.authenticateduser.working_department.toLowerCase();

        if (department === "") {
            return this.EditATPermissions;
        }else{
            return this.EditATPermissions.filter(function(EditATPermission){

                let stdepartment = EditATPermission.department.toLowerCase();
                return (
                    (department === "" || stdepartment === department)
                );
            })
        }

    },
      filterPermissions2(){
        let EditATPermissions = this.EditATPermissions;

        let department = this.selestMeDapartment.toLowerCase();

        if (department === "") {
            return this.EditATPermissions;
        }else{
            return this.EditATPermissions.filter(function(EditATPermission){

                let stdepartment = EditATPermission.department.toLowerCase();
                return (
                    (department === "" || stdepartment === department)
                );
            })
        }

    }
    }
}
</script>

<style scoped>
    .class-row {
        display: inline-block;
        width: 150px;
        padding: 10px;
        margin: 10px;
        box-shadow: 0px 1px 3px 4px #f2f2f2;
    }
    .class-row tr{
        height: 50px;
    }
    .class-row .edit-button{
        margin-top: 10px;
    }
    .table.table-striped tr td:first-child{
        font-weight: 700;
    }
</style>
