<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Staff Attendance for {{makedate}}</h3>
	</div>
	<div class="panel-body">
		<div class="row">
            <div class="showCreateAttendance" v-if="showCreateAttendance">
                            
                            <!-- Form  -->
                            <form @submit.prevent="makeDate()">
                               <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="date">Date </label>
                                            <input required="true" type="date" id="date" v-model="makedate" name="date" max="3000-12-31" min="1000-01-01" class="form-control" style="width:200px;">
                                            <p class="text-muted">Please double Check the Date</p>
                                            <p class="text-danger">{{checkDateMatch}}</p>
                                       </div>
                                       <div class="form-group col-md-4">
                                            <label for="eid">Employee ID</label>
                                            <input :disabled="true" type="text" style="width: 200px;" id="eid" v-model="eid" name="eid" class="form-control">
                                            <p class="text-muted">Enter Your Employee Id</p>
                                       </div>
                               </div>
                               <button :disabled="dateMismatch" type="submit" class="btn btn-primary">Submit</button>
                            </form>
                           </div>

                           <div class="showNew" v-if="showCreateAttendance === false">

                                <input type="text" class="form-control col-md-4 mb-3 eid-search" style="text-transform:uppercase;margin-botom:20px;" placeholder="Search with Employee Id" v-model="search">
                                <table class="table" id="table">
                                   <tbody>
                                       <tr>
                                            <th>#EID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                        </tr>
                                       <tr v-for="Staff in searchStaffs" v-bind:key="Staff.id">
                                           <td>{{ Staff.eid }}</td>
                                           <td>{{ Staff.name }}</td>
                                           <td>{{getStaffStatus(Staff)}}</td>
                                       </tr>
                                   </tbody>
                                </table>
                                <button class="btn btn-primary" @click="onexport()">download</button>
                           </div>
		</div>
	</div>
</div>
</div>
   
</template>

<script>
import XLSX from 'xlsx'
// for date format
window.moment = require('moment');
export default {
    data(){
        return{
            makedate:'',
            eid:'',
            showCreateAttendance:true,
            Staffs:[],
            search:'',
            Attendances:[],
            errors:'',
            dateMismatch:false,
            
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
        this.setStaffId();
    },
    methods:{
       /* EXCEL EXPORT */
onexport() { 
     
//Get datas from table    
	var csv = [];
    var rows = document.querySelectorAll("table tr");
    var rowo = rows[0].querySelectorAll("td, th");
    for (var i = 1; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 1; j < rowo.length; j++) {
        if(rowo[j]) { row[rowo[j].innerText] = cols[j].innerText; }
        // if(rowo[1]) { row[rowo[1].innerText] = cols[1].innerText; } 
        // if(rowo[2]) { row[rowo[2].innerText] = cols[2].innerText; } 
        // if(rowo[3]) { row[rowo[3].innerText] = cols[3].innerText; } 
        // if(rowo[4]) { row[rowo[4].innerText] = cols[4].innerText; } 
        // if(rowo[5]) { row[rowo[5].innerText] = cols[5].innerText; } 
        // if(rowo[6]) { row[rowo[6].innerText] = cols[6].innerText; } 
        // if(rowo[7]) { row[rowo[7].innerText] = cols[7].innerText; } 
        // if(rowo[8]) { row[rowo[8].innerText] = cols[8].innerText; } 
        // if(rowo[9]) { row[rowo[9].innerText] = cols[9].innerText; } 
        }
        csv.push(row);	
    }
//convert to JSON
function toObject(arr) {
  var rv = {};
  for (var i = 0; i < arr.length; ++i)
    rv[i] = arr[i];
  return rv;
}
console.log(csv)
//Export to Excel
var sheet1 = XLSX.utils.json_to_sheet(csv) 
var wb = XLSX.utils.book_new() // make Workbook of Excel
XLSX.utils.book_append_sheet(wb, sheet1, 'sheet1') 
let filenme = 'Staff-Attendance-overall.xlsx';
XLSX.writeFile(wb, filenme) // name of the file 

},
        setStaffId(){
            this.eid = this.authenticateduser.eid;
        },


        getStaffStatus(staff) {
      var status;
      var aData;
      aData = this.Attendances;
      for (var child in aData) {
        if (aData[child].staff_id === staff.id) {
          status = aData[child].status;
        }
      }
      if (status) {
        return status;
      } else {
        return 'null';
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
                fetch('/api/staff/attendance/date', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    console.log(data);
this.getAllAttendances();
                    this.showCreateAttendance = false;

                }).catch(err => {
                    
                });
                //this.getAllAttendances();
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
                    console.log('attendances',data);
                    this.Attendances = data['records'];

                }).catch(err => {
                    
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
                this.getAllAttendances();
               
        },
         setAllStaffAt(){
            let Formdata = {
                    makedate : this.makedate,
                    eid : this.eid,
                    staffs:this.Staffs,
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
                    console.log(data);
                    this.Sstatus='';
                    
                               
                }).catch(err => {
                    
                });
                this.getAllAttendances();
        }

    },
    computed:{
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
.eid-search{
    margin-bottom: 30px;
}
</style>
