<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Staff Attendance View Overall</h3>

        <p>These Results are calculated from {{Yrstart}} to today</p>
	</div>
	<div class="panel-body">
       
    <div class="row">
        <div class="table-responsive">
            <table class="table" id="table">
                <tbody>
                    <tr>
                    <th>#Emp ID</th>
                    <th>Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>CL</th>
                    <th>CPL</th>
                    <th>OD</th>
                    <th>SOD</th>
                </tr>
                    <tr v-for="Staff in Staffs" v-bind:key="Staff.id">
                        <td>{{Staff.eid}}</td>
                        <td>{{Staff.name}}</td>
                        <td>{{getStaffAttendance(Staff,'present')}}</td>
                        <td>{{getStaffAttendance(Staff,'absent')}}</td>
                        <td>{{getStaffAttendance(Staff,'cl')}}</td>
                        <td>{{getStaffAttendance(Staff,'cpl')}}</td>
                        <td>{{getStaffAttendance(Staff,'od')}}</td>
                        <td>{{getStaffAttendance(Staff,'sod')}}</td>
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
export default {
    data(){
        return{
            hideForm:true,
            Staffs:[],
            AtRecords:[],
            AtDates:[],
            Exceldata:[],
            Datas:{
                animals: [
                  {"name": "cat", "category": "animal"}
                  ,{"name": "dog", "category": "animal"}
                  ,{"name": "pig", "category": "animal"}
                ],
      pokemons: [
                  {"name": "pikachu", "category": "pokemon"}
                  ,{"name": "Arbok", "category": "pokemon"}
                  ,{"name": "Eevee", "category": "pokemon"}
                ]
            }
           
        }
    },
          props:['authenticateduser', 'authrole'],
    mounted(){
       this.getAllStaffs();
       this.getAllAttendances();
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
        getCsv(){
            var html = document.querySelector("table").outerHTML;
    this.export_table_to_csv(html, `
    Staff-Attendance-overall
    `
    );
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

        getAllAttendances(){
            fetch('/api/staffs/attendances')
            .then(res => {
                return res.json();
            })
            .then(data => {
                console.log(data);
                this.Yrstart = data['year_start'];
                this.AtRecords = data['records'];
                this.AtDates = data['dates'];
            }).catch(err => console.log(err));
        },

                getStaffAttendance(Staff,stat){
            var status = [];
            var aData;
            var child;
      aData = this.AtRecords;
      for (var child in aData) {
        if (aData[child].staff_id === Staff.id && aData[child].status === stat) {
          status.push(aData[child].status);
        }
      }
      if (status) {
        return status.length;
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
