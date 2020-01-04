<template>
<div class="home">
        <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Class Advisors</h3>
	</div>
	<div class="panel-body">
  <div class="bachlers-degree">
      <h3 style="text-transform:uppercase;">{{this.authenticateduser.working_department}} CLASSES</h3>
            <ul>
            <li v-for="permission in filterPermissions" v-bind:key="permission.id" class="class-row">
                {{permission.year}} Year
               <span style="text-transform:uppercase;"> {{permission.section}}</span> Section
                
                    <p v-if="permission.class_advisor !== 0" class="text-info">
                    {{getstaffEid(permission.class_advisor)}}
                    </p>
                    <p v-if="permission.class_advisor === 0" class="text-info">Not Set</p>
                    <p v-if="permission.class_advisor2 !== 0" class="text-info">
                    {{getstaffEid(permission.class_advisor2)}}
                    </p>
                    <p v-if="permission.class_advisor2 === 0" class="text-info">Not Set</p>

         <div id="editPermission" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <form @submit.prevent="updatePermission()">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Class Advisor</h4>
      </div>
      <div class="modal-body">
          

          <table class="table table-striped">
              
              <tr>
                  <td>Class</td>
                  <td class="data-reg-no">{{Edit_year}} Year <span style="text-transform:uppercase;">{{Edit_section}} </span>Section</td>
              </tr>
                <tr>
                    <td>Current Class Advisor</td>
                    <td class="text-info">{{getstaffEid(Edit_Advisor)}}</td>
                </tr>
                <tr>
                    <td>Change Class Advisor</td>
                    <td class="data-checkbox">
                      <input type="text" id="checkbox_student" placeholder="Enter Staff ID" class="form-control" v-model="change_Advisor">
                    </td>
                </tr>
                <tr>
                    <td>Current Class Advisor 2</td>
                    <td class="text-info">{{getstaffEid(Edit_Advisor2)}}</td>
                </tr>
                <tr>
                    <td>Change Class Advisor 2</td>
                    <td class="data-checkbox">
                      <input type="text" id="checkbox_student" placeholder="Enter Staff ID" class="form-control" v-model="change_Advisor2">
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
<div class="masters-degree" v-if="usersMasterDepts().length > 1">
    <div class="selest_department">
        <label>Select Department</label>
        <select v-model="selestMeDapartment">
            <option v-for="usersMasterDept in usersMasterDepts()">{{usersMasterDept}}</option>
        </select>
    </div>
    <h3 style="text-transform:uppercase;" v-if="selestMeDapartment">{{this.selestMeDapartment}} CLASSES</h3>
          <ul v-if="selestMeDapartment">
            <li v-for="permission in filterPermissions2" v-bind:key="permission.id" class="class-row">
                {{permission.year}} Year
               <span style="text-transform:uppercase;"> {{permission.section}}</span> Section
                
                    <p v-if="permission.class_advisor !== 0" class="text-info">
                    {{getstaffEid(permission.class_advisor)}}
                    </p>
                    <p v-if="permission.class_advisor === 0" class="text-info">Not Set</p>
                    <p v-if="permission.class_advisor2 !== 0" class="text-info">
                    {{getstaffEid(permission.class_advisor2)}}
                    </p>
                    <p v-if="permission.class_advisor2 === 0" class="text-info">Not Set</p>

         <div id="editPermission" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <form @submit.prevent="ME_updatePermission()">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Class Advisor</h4>
      </div>
      <div class="modal-body">
          <table class="table table-striped">
              
              <tr>
                  <td>Class</td>
                  <td class="data-reg-no">{{ME_Edit_year}} Year <span style="text-transform:uppercase;">{{ME_Edit_section}} </span>Section</td>
              </tr>
                <tr>
                    <td>Current Class Advisor</td>
                    <td class="text-info">{{getstaffEid(ME_Edit_Advisor)}}</td>
                </tr>
                <tr>
                    <td>Change Class Advisor</td>
                    <td class="data-checkbox">
                      <input type="text" id="checkbox_student" placeholder="Enter Staff ID" class="form-control" v-model="ME_change_Advisor">
                    </td>
                </tr>
                <tr>
                    <td>Current Class Advisor 2</td>
                    <td class="text-info">{{getstaffEid(ME_Edit_Advisor2)}}</td>
                </tr>
                <tr>
                    <td>Change Class Advisor 2</td>
                    <td class="data-checkbox">
                      <input type="text" id="checkbox_student" placeholder="Enter Staff ID" class="form-control" v-model="ME_change_Advisor2">
                    </td>
                </tr>
          </table>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary" >Save</button>
        <button class="btn btn-default" @click="ME_CloseClick()" data-dismiss="modal">Close</button>
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
            selestMeDapartment: "",
            EditATPermissions: [],
            Edit_permission_id:"",
            Edit_year: "",
            Edit_section:"",
            Edit_status:"",
            Edit_Advisor:"",
            change_Advisor: "",
            Edit_Advisor2:"",
            change_Advisor2: "",

            ME_Edit_permission_id:"",
            ME_Edit_year: "",
            ME_Edit_section:"",
            ME_Edit_status:"",
            ME_Edit_Advisor:"",
            ME_change_Advisor: "",
            ME_Edit_Advisor2:"",
            ME_change_Advisor2: "",
           
        }
    },
    mounted(){
        this.EditATpermissions();
        
    },
    created(){
        this.getAllStaffs();
    },
     props:['authenticateduser', 'authrole'],
    methods:{
        usersMasterDepts(){
            if(this.authenticateduser.working_department_me !== null){
                let mArr = this.authenticateduser.working_department_me;

            mArr = mArr.split('&&');
            return mArr;
            }else{
                return [];
            }
            
        },
        EditClick(permission){
            this.Edit_year = permission.year;
            this.Edit_section = permission.section;
            this.Edit_status = permission.status;
            this.Edit_Advisor = permission.class_advisor;
            this.Edit_Advisor2 = permission.class_advisor2;
            this.Edit_permission_id = permission.id;
        },
        ME_EditClick(permission){
            this.ME_Edit_year = permission.year;
            this.ME_Edit_section = permission.section;
            this.ME_Edit_status = permission.status;
            this.ME_Edit_Advisor = permission.class_advisor;
            this.ME_Edit_Advisor2 = permission.class_advisor2;
            this.ME_Edit_permission_id = permission.id;
        },
        CloseClick(){
            this.Edit_year = "";
            this.Edit_section = "";
            this.Edit_status = "";
             this.Edit_Advisor = "";
             this.change_Advisor = "";
              this.Edit_Advisor2 = "";
             this.change_Advisor2 = "";
             this.Edit_permission_id = "";
        },
        ME_CloseClick(){
            this.ME_Edit_year = "";
            this.ME_Edit_section = "";
            this.ME_Edit_status = "";
             this.ME_Edit_Advisor = "";
             this.ME_change_Advisor = "";
              this.ME_Edit_Advisor2 = "";
             this.ME_change_Advisor2 = "";
             this.ME_Edit_permission_id = "";
        },
        EditATpermissions(){
                fetch('/api/studentEditPermissions2')
            .then(res => { return res.json(); })
                .then(data => {
                    this.EditATPermissions = data;
                    // console.log(data);
                }).catch(err => console.log(err));
            },
            getAllStaffs(){
            fetch('/api/staffs')
            .then(res => {
                return res.json();
            })
            .then(data => {
                this.Staffs = data;
            }).catch(err => console.log(err));
        },
        getstaffEid(staffid){
            let stat;

            let aData = this.Staffs;
  
            for (var child in aData) {
        if(aData[child].id === staffid) {
          stat = aData[child].eid;
        }
      }
      if(stat) {
        return stat;
      } else {
        return "null";
      }
              
        },
            updatePermission(){

                let Formdata = {
                   permission_id : this.Edit_permission_id,
                   class_advisor: this.change_Advisor,
                   class_advisor2: this.change_Advisor2
                }

              // console.log(Formdata);

                fetch('/api/staff/updateadvisor', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    console.log(data);
                    if(data['status'] === 'success'){
                        alert('Class Advisor Updated');
                    }else{
                        alert(data['message']);
                    }
                this.EditATpermissions();
                }).catch(err => {
                    console.log(err);
                });
               this.EditATpermissions();
            },
            ME_updatePermission(){

                let Formdata = {
                   permission_id : this.ME_Edit_permission_id,
                   class_advisor: this.ME_change_Advisor,
                   class_advisor2: this.ME_change_Advisor2
                }

              // console.log(Formdata);

                fetch('/api/staff/updateadvisor', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    console.log(data);
                    if(data['status'] === 'success'){
                        alert('Class Advisor Updated');
                    }else{
                        alert(data['message']);
                    }
                this.EditATpermissions();
                }).catch(err => {
                    console.log(err);
                });
               this.EditATpermissions();
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

    },
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
