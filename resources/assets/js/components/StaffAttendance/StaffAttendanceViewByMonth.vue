<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Staff Attendance View By Month</h3>

        
	</div>
	<div class="panel-body">
        <div class="row" v-if="hideForm">
        <form @submit.prevent="getAttendances()">
		<div class="row">
              <div class="form-group col-md-4">
            <label for="fromdate">Fromdate </label>
            <input type="date" id="fromdate" v-model="fromdate" name="fromdate" max="3000-12-31" min="1000-01-01" class="form-control" style="width:200px;">
        </div>
        <div class="form-group col-md-4">
            <label for="todate">todate </label>
            <input type="date" id="todate" v-model="todate" name="todate" max="3000-12-31" min="1000-01-01" class="form-control" style="width:200px;">
        </div>
            <button type="submit" class="btn btn-primary">Submit</button>
		</div>
        </form>
	</div>
    <div class="row" v-if="hideForm === false">
        <div class="table-responsive">
            <table class="table" id="table">
                <tbody>
                    <tr>
                    <th>#Emp ID</th>
                    <th>Name</th>
                    <th v-for="AtDate in AtDates" v-bind:key="AtDate.id">{{AtDate.date}}</th>
                </tr>
                    <tr v-for="Staff in Staffs" v-bind:key="Staff.id">
                        <td>{{Staff.eid}}</td>
                        <td>{{Staff.name}}</td>
                        <td v-for="AtDate in AtDates" v-bind:key="AtDate.id">
                            {{getStaffAttendance(Staff,AtDate)}}
                        </td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-primary" @click="onexport()" style="float:right;">download</button>
        </div>
        <a class="btn btn-primary back-btn" href="">Back</a>
    </div>
</div>
</div>
</div>
</template>


<script>
import XLSX from 'xlsx'
export default {
    data(){
        return{
            hideForm:true,
            Staffs:[],
            fromdate:'',
            todate:'',
            AtRecords:[],
            AtDates:[],
           
        }
    },
          props:['authenticateduser', 'authrole'],
    mounted(){
       this.getAllStaffs();
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
        for (var j = 0; j < rowo.length; j++) {
        if(rowo[j]) { row[rowo[j].innerText] = cols[j].innerText; 
        }
    }
        csv.push(row);	
    }
//Export to Excel
var sheet1 = XLSX.utils.json_to_sheet(csv) 
var wb = XLSX.utils.book_new() // make Workbook of Excel
XLSX.utils.book_append_sheet(wb, sheet1, 'sheet1') 
let filenme = 'Staff-Attendance-overall.xlsx';
XLSX.writeFile(wb, filenme) // name of the file 

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

        getAttendances(){
             let Formdata = {
                    fromdate : this.fromdate,
                    todate : this.todate,
                }
                fetch('/api/staff/attendance/withdates', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    console.log(data);

                    this.AtRecords = data['records'];
                    this.AtDates = data['dates'];
                    this.hideForm = false;

                }).catch(err => {
                    
                });
        },

                getStaffAttendance(Staff,AtDate){
            var status = [];
            var aData;
            var child;
      aData = this.AtRecords;
      for (var child in aData) {
        if (aData[child].staff_id === Staff.id && aData[child].date === AtDate.date) {
          status = aData[child].status;
        }
      }
      if (status) {
        return status;
      } else {
        return null;
      }
            
        },

        
      
    },

    computed:{

      
    }
}
</script>


<style scoped>

</style>
