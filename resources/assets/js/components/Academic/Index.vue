<template>
    <div class="home-page">
        <!-- OVERVIEW -->
<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Academic Updates</h3>
						</div>
	<div class="panel-body">
	<div class="row">
    <div class="bgf">
    <div class="btn-grpyearsem mb-5">

<div class="btn-group" role="group" aria-label="...">
<button type="button" class="btn btn-default" @click="modEditablesCredentials()">Permissions</button>
  <button type="button" class="btn btn-default" @click="modYearCredentials()">Update Year</button>
  <button type="button" class="btn btn-default" @click="modSemesterCredentials()">Update Semester</button>
</div>

</div>

<div class="StaffDatass" v-if="YearDatass">
<h3>For Staffs Academic Year</h3>
<form @submit.prevent="submitAcademic">
<label class="text-dark pt-3">Academic Year Start</label> 
<input :required="true" type="date" v-model="academicYear" id="date" name="date" class="form-control" style="width:200px;">
<em>Current Year Started<font style="color:#3f51b5;"> {{gotData.year_start}}</font></em>
<br>
<br>
<h3 class="pt-3 pb-2">Enter Staff Leave Limits</h3>
<div class="form-group" style="width:300px;">
    <label for="cl">CL</label>
    <input :required="true" type="number" v-model="academicCL" class="form-control" placeholder="Casual Leave Limit Eg:10"  name="cl" id="cl" >
</div>
<div class="form-group" style="width:300px;">
    <label for="od">OD</label>
    <input :required="true" type="number" v-model="academicOD" placeholder="OnDuty Count Eg:10" class="form-control"  name="od" id="od">
</div>
<div class="form-group" style="width:300px;">
    <label for="permission">Permissions</label>
    <input :required="true" type="number" placeholder="Permissions limit Eg:2" v-model="academicPermission" class="form-control"  name="permission" id="permission">
</div>
<div class="form-group" style="width:300px;">
    <label for="late">LateRegister</label>
    <input :required="true" type="number" placeholder="Late Register limit Eg:2" v-model="academicLate" class="form-control"  name="late" id="late">
</div>

<p><strong>CPL</strong> <span class="text-muted pl-3">Compentation Leave's not Restictable</span></p>
<p><strong>SOD</strong> <span class="text-muted pl-3">Special OD's not Restictable</span></p>
<button type="submit" class="btn btn-primary">Submit</button>
</form>
       </div>

       <div class="StudDatas" v-if="SemDatass">
<h3>For Student Academic Semester</h3>
<form @submit.prevent="updateSem()">
<div class="form-group" style="width:300px;">
    <br>
<label class="text-dark">Academic Semester Start</label> 
    <input :required="true" type="date" v-model="semStart" id="datesem" name="datesem" class="form-control" style="width:200px;">
<em><strong>Current Semester Started</strong> {{gotData.semester_start}}</em>
    <br>
<br>
<label class="text-dark">Academic Semester End</label> 
    <input :required="true" type="date" v-model="semEnd" id="datesemend" name="datesemend" class="form-control" style="width:200px;">
    <button class="btn btn-primary m-t-3" type="submit">Update Semester</button>
</div>
</form>
</div>
</div>

<div class="enable-permissions" v-if="editablesDatas">
    <form class="form-permissions" @submit.prevent="updateAcademicPermissions">
        <div class="form-group">
            <label for="staff_profile_edit">Staff profile create &amp edit</label>
            <input v-model="staff_profile_edit" type="checkbox" id="staff_profile_edit">
        </div>
        <div class="form-group">
            <label for="student_profile_edit">Student profile create &amp edit</label>
            <input v-model="student_profile_edit" type="checkbox" id="student_profile_edit">
        </div>
        <div class="form-group">
            <label for="schedule_edit">Update Schedules</label>
            <input v-model="schedule_update" type="checkbox" id="schedule_edit" >
        </div>
        <button class="btn btn-primary">Submit</button>
    </form>
</div>


							</div>
							
						</div>
					</div>
    </div>
</template>


<script>
export default {
    data(){
        return {
            //staff academic
            gotData:[],

            student_profile_edit:'',
            staff_profile_edit:'',
            schedule_update:'',

            academicYear:'',
            academicCL:'',
            academicOD:'',
            academicPermission:'',
            academicLate:'',

            Semester:'',
            
            semStart: '',
            semEnd: '',
            close:'closesem',

            //confirm
            confirm: '',
            YearDatass:false,
            SemDatass:false,
            editablesDatas: true
        }
        
    },
    created(){
        this.fetchSem();
    },
    methods:{
        modYearCredentials(){
            this.YearDatass = true;
            this.SemDatass = false;
            this.editablesDatas = false;

        },
        modSemesterCredentials(){
            this.SemDatass = true;
            this.YearDatass = false;
            this.editablesDatas = false;
        },
        modEditablesCredentials(){
            this.SemDatass = false;
            this.YearDatass = false;
            this.editablesDatas = true;
        },
        //Fetch sem
        fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                this.gotData = data;
                console.log(data);
                this.academicCL = data.staff_cl;
                this.academicOD = data.staff_od;
                this.academicPermission = data.staff_permission;
                this.academicLate = data.staff_lateregister;      
                this.student_profile_edit = data.student_profile_edit;  
                this.staff_profile_edit = data.staff_profile_edit;  
                this.schedule_update = data.schedule_update;  
            })
            .catch(err => console.log(err));
        },
        //submitAcademic
        submitAcademic(){
            let Formdata = {
                academicYear: this.academicYear,
                academicCL: this.academicCL,
                academicOD: this.academicOD,
                academicPermission: this.academicPermission,
                academicLate: this.academicLate
            }
             fetch('/api/academicyear', {
          method: 'post',
          body: JSON.stringify(Formdata),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
              console.log(data);
          })
          .catch(err => console.log(err));
        },
        //Update sem
        updateSem(){
            let Formdata = {
                semStart: this.semStart,
                semEnd: this.semEnd
            }
            this.confirm = confirm('Are You Sure ?');
            if(this.confirm === true){
             fetch('/api/semester', {
          method: 'post',
          body: JSON.stringify(Formdata),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
              console.log(data);
          })
          .catch(err => console.log(err));
         
        }
        this.fetchSem();
        },

        updateAcademicPermissions(){
            let Formdata = {
                student_profile_edit:this.student_profile_edit,
                staff_profile_edit:this.staff_profile_edit,
                schedule_update:this.schedule_update
            }

        fetch('/api/academic_permissions', {
          method: 'post',
          body: JSON.stringify(Formdata),
          headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        }).then(res => res.json())
          .then(data => {
              if(data){
                  alert('Updated Permissions')
              }
          }).catch(err => console.log(err));
        }
       
    }
}
</script>

<style scoped>
    .m-t-3
    {
        margin-top: 30px;
    }
    .form-permissions
    {
        padding-top: 30px;
        padding-bottom: 30px;
    }
</style>



