<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Staff Attendance Make {{makedate}}</h3>
	</div>
	<div class="panel-body">
		<div class="row">
            <div class="showCreateAttendance" v-if="showCreateAttendance">
                            <!-- Form  -->
                            <form @submit.prevent="makeDate()">
                               <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="date">Date </label>
                                            <input  type="date" id="date" v-model="makedate" name="date" max="3000-12-31" min="1000-01-01" class="form-control" style="width:200px;">
                                            <p class="text-muted">Please double Check the Date</p>
                                            <p class="text-danger">{{checkDateMatch}}</p>
                                       </div>
                                       <div class="form-group col-md-4">
                                            <label for="eid">Employee ID</label>
                                            <input disabled="true" type="text" style="width: 200px;" id="eid" v-model="eid" name="eid" class="form-control">
                                            <p class="text-muted">Enter Your Employee Id</p>
                                       </div>
                                       <div class="form-group col-md-4 department-select">
                <label for="department" class="">Department</label>
			<select v-model="department" class="form-control" id="department" name="department" style="width:250px;">
            <option selected >Choose</option>
				<option v-for="department_option in department_options" v-bind:key="department_option.value">{{department_option.text}}</option>
			</select>
            <br>
            </div>
                               </div>
                               <button :disabled="dateMismatch" type="submit" class="btn btn-primary">Submit</button>
                            </form>
                           </div>
 
                           <div class="showNew" v-if="showCreateAttendance === false">

                               <div class="showapprovals" v-if="Approvals.length > 0">
                                   <h3>The following staffs has Approvals </h3> 

                                <table class="table">
                                    <thead>
                                        <th>EID</th>
                                        <th>Name</th>
                                        <th>Approval For</th>
                                        <th>Status</th>
                                    </thead>
                                    <tr v-for="Approval in Approvals" v-bind:key="Approval.id">
                                        <td>{{Approval.staff_eid}}</td>
                                        <td>{{Approval.staff_name}}</td>
                                        <td>{{Approval.approval_for}}</td>
                                        <td>{{Approval.status}}</td>
                                    </tr>
                                </table>
                               </div>
                              
                         

                                <!-- <input type="text" class="form-control col-md-4 m-b-3" style="text-transform:uppercase;margin-botom:20px;" placeholder="Search with Employee Id" v-model="search"> -->
                                <button class="btn btn-primary float-right" @click="setAllStaffAt()">Make all Present</button>
                                <table class="table">
                                   <thead>
                                       <th>#EID</th>
                                       <th>Name</th>
                                       <th>Status</th>
                                       <th>Change Status</th>
                                   </thead>
                                   <tbody>
                                       <tr v-for="Staff in filteredStaffs" v-bind:key="Staff.id">
                                           <td>{{ Staff.eid }}</td>
                                           <td>{{ Staff.name }}</td>
                                           <td>{{getStaffStatus(Staff)}}</td>
                                            <td>
<div class="form-group">
<select class="form-control" @change="makAtforStaff(Staff)" v-model="StatusSelected" id="selectedStatus" style="width:120px;"> 
  <option v-for="setStatuse in setStatuses" v-bind:value="setStatuse.value">
    {{ setStatuse.text }}
  </option>
</select>
</div>
                                            </td>
                                       </tr>
                                   </tbody>
                                </table>
                           </div>
		</div>
	</div>
</div>
</div>
   
</template>

<script>
export default {
    data(){
        return{
            makedate:'',
            eid:'',
            department:'',
            showCreateAttendance:true,
            Staffs:[],
            search:'',
            Attendances:[],
            Approvals:[],
            dateMismatch:false,
            department_options:[
                    {text:'ECE', value:'ece'},
                    {text:'EEE', value:'eee'},
                    {text:'CSE', value:'cse'},
                    {text:'MECH', value:'mech'},
                    {text:'IT', value:'it'},
                    {text:'CIVIL', value:'cicil'},
                    {text:'AUTO', value:'auto'},
                    {text:'ME-CSE', value:'me-cse'},
                    {text:'ME-Cem', value:'me-cem'},
                    {text:'ME-EST', value:'me-est'},
                    {text:'ME-ISE', value:'me-ise'},
                    {text:'ME-PED', value:'me-ped'},
                    {text:'ME-SE', value:'me -se'},
                    {text:'ME-VLSI', value:'me-vlsi'},
                    {text:'MTECH-IT', value:'mtech-it'},
                    {text:'MBA', value:'mba'}
                ],
        StatusSelected:'',
            setStatuses: [
        { text: "Not Updated", value: "null" },
        { text: "Present", value: "present" },
        { text: "CL", value: "cl" },
        { text: "CPL", value: "cpl" },
        { text: "Present-Permission", value: "present-permission" },
        { text: "Present-Late", value: "present-late" },
        { text: "OD", value: "od" },
        { text: "SOD", value: "sod" },
        { text: "Absent", value: "absent" }
      ],
        }
    },
          props:['authenticateduser', 'authrole'],
    mounted(){
        this.getAllStaffs();
        this.setEid();
    },
    methods:{
        setEid(){
            this.eid = this.authenticateduser.eid;
        },
        getStaffStatus(staff) {
      var status;
      var aData;
      var child;
      aData = this.Attendances;
      for (var child in aData) {
        if (aData[child].staff_id === staff.id) {
          status = aData[child].status;
        }
      }
      if (status) {
        return status;
      } else {
        return null;
      }
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
        makeDate(){
             let Formdata = {
                    makedate : this.makedate,
                    eid : this.eid,
                }
        console.log(Formdata);
                fetch('/api/staff/attendance/date', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {

                     this.showCreateAttendance = false;
                    console.log(data);

                   

                }).catch(err => {
                     console.log(err);
                });
                 this.showCreateAttendance = false;
                this.getAllAttendances();
        },
        getAllAttendances(){
            let Formdata = {
                    makedate : this.makedate
                }
                 fetch('/api/staff/attendance/getdate', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    //console.log(data);
                    this.Attendances = data['records'];
                    this.Approvals = data['approvals'];
                }).catch(err => {
                    console.log(er);
                });
        },
        makAtforStaff(Staff){
             let Formdata = {
                    makedate : this.makedate,
                    eid : this.eid,
                    staff_id: Staff.id,
                    status:this.StatusSelected,
                }
                 fetch('/api/staff/attendance/individual', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    console.log(data);

                    this.StatusSelected = '';

                }).catch(err => {
                    
                });
                let SendData = {
                    makedate : this.makedate
                }
                fetch('/api/staff/attendance/getdate', {
                    method: "post",
                    body: JSON.stringify(SendData),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    //console.log(data);
                    this.Attendances = data['records'];
                    this.Approvals = data['approvals'];
                }).catch(err => {
                    console.log(er);
                });
               
        },
         setAllStaffAt(){
            let Formdata = {
                    makedate : this.makedate,
                    eid : this.eid,
                    staffs:this.filteredStaffs,
                    status:'present'
                }
                fetch('/api/staff/attendance/allat', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                   this.getAllAttendances();
                this.getAllStaffs(); 
                               
                }).catch(err => {
                    
                });
                this.getAllAttendances();
                this.getAllStaffs();
        }
    },
    computed:{
        filteredStaffs(){
            let depart = this.department.toLowerCase();

            if(depart === ""){
                return this.Staffs;
            }else{
                return this.Staffs.filter(function(staff){
                        return staff.working_department.toLowerCase() === depart;
                });
            }
        },
         checkDateMatch(){
            let date = moment(Date()).format('YYYY-MM-DD');

            let errors;
            let makedate = this.makedate;

            if(makedate === ''){
                return '';
            }else{
                if(makedate > date){
                    this.dateMismatch = true;
                    errors = 'Date not matching';
                    return errors;
                }else{
                    this.dateMismatch = false;
                }
            }
        },
        searchStaffs: function() {
      let Staffs = this.Staffs;
      let search = this.search;
      if(this.search === ""){
        return Staffs;
      }else{
        return Staffs.filter(function(staff) {
        return (staff.name.toLowerCase().indexOf(search.toLowerCase()) && staff.eid.toLowerCase().indexOf(search.toLowerCase())) >= 0;
      });
      }
    }
    }
}
</script>


<style scoped>
.m-b-3{
    margin-bottom: 30px;
    width: 250px;
}

</style>
