<template>
<div class="home">

 <div class="vld-parent">
        <loading :active.sync="isLoading" 
        :can-cancel="true" 
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>
        
        <!-- <label><input type="checkbox" v-model="fullPage">Full page?</label>
        <button @click.prevent="doAjax">fetch Data</button> -->
    </div>
<div class="print-month" >
    <div class="print-buttons" style="padding-bottom:25px;">
   <button class="btn btn-primary " @click="onexport()" v-if="hideForm === false">download</button>

<button class="btn btn-primary" style="float:right;" @click="printMonth()"  v-if="hideForm === false">Print</button>

</div>
    <!-- OVERVIEW -->
<div class="panel panel-headline" id="printMonth">
	<div class="panel-heading">
                    <div class="print-header">
                      <h3 class="Main-Head" style="text-align:center;font-weight:bold;">K. S. R College of Engineering, Tiruchengode - 637215</h3>
                    <h4 class="Main-Head2" style="text-align:center;padding-bottom:10px;">( An Autonomous Intitution, Affiliated to Anna University, Chennai )</h4>
                      <h4 class="Main-Head2" style="text-align:center;padding-bottom:20px;">Attendance Report <strong><span v-if="fromdate">{{displayDateFormat(fromdate)}}</span> <span v-if="todate">-</span> <span v-if="todate">{{displayDateFormat(todate)}}</span></strong></h4>
 <h3 class="main-heading">
    <h3>Department: <span style="text-transform:uppercase;font-weight:bold;">{{degree}} {{department}} </span></h3>
    <h3>Year: <span style="text-transform:uppercase;font-weight:bold;">{{year}}</span></h3>
    <h3>Section: <span style="text-transform:uppercase;font-weight:bold;">{{section}}</span></h3>
    <h3>Academic year: <span style="text-transform:uppercase;font-weight:bold;">{{PrintAcademicYear()}}</span></h3>
 </h3>
      </div>
		
	</div>
	<div class="panel-body">
        <div class="row" v-if="hideForm">
            <a class="btn btn-primary back-btn" href="">Back</a>
        <form @submit.prevent="getAttendances()">
		<div class="row">
               <div class="col-sm-2">
                <label for="degree" class="">Degree</label>
            <select required="true" v-model="degree" class="form-control" id="degree" name="degree">
                <option selected >Choose</option>
				<option v-for="degree_option in degree_options" v-bind:key="degree_option.value">{{degree_option.text}}</option>
			</select>
            <br>
            </div>
            
            <div class="col-sm-2">
                <label for="department" class="">Department</label>
			<select required="true" v-model="department" class="form-control" id="department" name="department">
            <option selected >Choose</option>
				<option v-for="department_option in filerDapart" v-bind:key="department_option.value">{{department_option.text}}</option>
			</select>
            <br>
            </div>
            
            <div class="col-sm-2">
                <label for="year" class="">Year</label>
			<select required="true" v-model="year" class="form-control" id="year" name="year">
            <option selected >Choose</option>
				<option v-for="year_option in year_options" v-bind:key="year_option.value">{{year_option.text}}</option>
			</select>
            <br>
            </div>
            
            <div class="col-sm-2">
                <label for="semester" class="">Semester</label>
			<select required="true" v-model="semester" class="form-control" id="semester" name="semester">
            <option selected >Choose</option>
				<option v-for="semester_option in semester_options" v-bind:key="semester_option.value">{{semester_option.text}}</option>
			</select>
            <br>
            </div>
            
            <div class="col-sm-2">
                <label for="section" class="">Section</label>
			<select required="true" v-model="section" class="form-control" id="section" name="section">
            <option selected >Choose</option>
				<option v-for="section_option in section_options" v-bind:key="section_option.value">{{section_option.text}}</option>
			</select>
            <br>
            </div>
            <div class="col-sm-2">
                <label for="fromdate">From date</label>
                <input required="true" type="date" id="fromdate" v-model="fromdate" name="fromdate" max="3000-12-31" min="1000-01-01" class="form-control">
            </div>
            <div class="col-sm-2">
                <label for="todate">To date</label>
                <input required="true" type="date" id="todate" v-model="todate" name="todate" max="3000-12-31" min="1000-01-01" class="form-control">
            </div>
        </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
	</div>
    <div class="row" v-if="hideForm === false">
        <div class="not-classadvisor">
            <h2 class="text-center" v-if="ImtheClassAdvisor === false">You are not class Advisor for this class</h2>
        </div>
        <div class="class-advisor"  v-if="ImtheClassAdvisor" >
        <!-- <div class="back-btn-div">
                <a class="btn btn-primary back-btn" href="">Back</a>
            </div>
        <button class="btn btn-primary download-btn" @click="onexport()">download</button> -->
        <div class="table-responsive print-month-view 
        table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>S.No</th>
                        <th>REG No</th>
                        <th>Name</th>
                        <th v-for="AtDate in AtDates" v-bind:key="AtDate.id">
                            {{displayDateFormat(AtDate.date)}}
                        </th>
                        <th>Percentage</th>
                        <th>Overall Percentage</th>
                        <th>Student Sign</th>
                    </tr>
                    <tr v-for="(Student,sl_no) in filteredStudents" v-bind:key="Student.id">
                        <td>{{IncrementBy1(sl_no)}}</td>
                        <td>{{Student.reg_no}}</td>
                        <td>{{Student.name}}</td>
                        <td v-for="AtDate in AtDates" v-bind:key="AtDate.id">
                            <!-- {{getAttendance(Student,AtDate)}} / {{AtDate.total_hours}} -->
                            <ul class="day-attendances-ul" v-if="dateMatch(Student,AtDate)">
                                <li v-if="getAttendance(Student,AtDate,1)">1)<span v-html="getAttendance(Student,AtDate,1)"></span></li>
                                <li v-if="getAttendance(Student,AtDate,2)">2)<span v-html="getAttendance(Student,AtDate,2)"></span></li>
                                <li v-if="getAttendance(Student,AtDate,3)">3)<span v-html="getAttendance(Student,AtDate,3)"></span></li>
                                <li v-if="getAttendance(Student,AtDate,4)">4)<span v-html="getAttendance(Student,AtDate,4)"></span></li>
                                <li v-if="getAttendance(Student,AtDate,5)">5)<span v-html="getAttendance(Student,AtDate,5)"></span></li>
                                <li v-if="getAttendance(Student,AtDate,6)">6)<span v-html="getAttendance(Student,AtDate,6)"></span></li>
                                <li v-if="getAttendance(Student,AtDate,7)">7)<span v-html="getAttendance(Student,AtDate,7)"></span></li>
                            </ul>
                        </td>
                        <td>
                            <span>
                                ({{getStudentTotalsHours(Student)}} /
                                {{getTotalsHours(Student)}})  = 
                                <span v-if="getTotalsHours(Student) > 0" style="font-weight:bolds;">
                                    {{(Math.round(getStudentTotalsHours(Student) / getTotalsHours(Student) * 100)).toFixed(1)}}%
                                </span>
                            </span>
                            <span>

                            </span>
                        </td>
                        <td>{{attendanceHours(Student).overall}} / {{attendanceHours(Student).total_hours}} =
                             <span v-if="attendanceHours(Student).total_hours > 0" style="font-weight:bolds;">
                                    {{(Math.round(attendanceHours(Student).overall / attendanceHours(Student).total_hours * 100)).toFixed(1)}}%
                                </span>
                        </td>
                        <td>

                        </td>
                    </tr>
                </tbody>
            </table>
            
            <p class="bottom-textx" style="margin-top:30px;">
                <span class="left-text" style="float:left;font-size:18px;margin-left:50px;">Class Advisor</span>
                <span class="center-text" style="position: absolute;font-size:18px;
    left: 50%;
    transform: translateX(-50%);">HoD</span>
                <span class="right-text" style="float:right;font-size:18px;margin-right:50px;">Principal</span>
            </p>

        </div>
        </div>
        </div>
    </div>
</div>
</div>
</div>
</template>


<script>
window.moment = require('moment');
import VueHtmlToPaper from 'vue-html-to-paper';
import Loading from 'vue-loading-overlay';
 import 'vue-loading-overlay/dist/vue-loading.css';
import XLSX from 'xlsx'
export default {
    data(){
        return{

            Studs:[],
            isLoading: false,
            fullPage: true,
            AtRecords:[],
            AtDates:[],
            hideForm:true,
            fromdate:'',
            todate:'',
            degree:'',
            EditATPermissions: [],
            ImtheClassAdvisor:"",
                degree_options:[
                    {text:'BE', value:'be'},
                    {text:'ME', value:'me'},
                    {text:'BTECH', value:'btech'},
                    {text:'MTECH', value:'mtech'},
                    {text:'MBA', value:'mba'},
                    {text:'MCA', value:'mca'}
                ],
                bachlers_departments:["ece","eee","mech","cse","it","civil","automobile"],
            masters_departments:["me-cse","me-cem","me-est","me-ise","me-ped","me-se","me-vlsi","mtech-it","mba"],
                department:'',
                department_options:[
                    {degree:'be', text:'ECE', value:'ece'},
                    {degree:'be', text:'EEE', value:'eee'},
                    {degree:'be', text:'CSE', value:'cse'},
                    {degree:'be', text:'MECH', value:'mech'},
                    {degree:'be', text:'CIVIL', value:'civil'},
                    {degree:'be', text:'AUTOMOBILE', value:'automobile'},
                    {degree:'me', text:'ME-CSE', value:'me-cse'},
                    {degree:'me', text:'ME-CS', value:'me-cs'},
                    {degree:'me', text:'ME-Cem', value:'me-cem'},
                    {degree:'me', text:'ME-EST', value:'me-est'},
                    {degree:'me', text:'ME-ISE', value:'me-ise'},
                    {degree:'me', text:'ME-PED', value:'me-ped'},
                    {degree:'me', text:'ME-SE', value:'me-se'},
                    {degree:'mtech', text:'MTECH-IT', value:'mtech-it'},
                    {degree:'btech', text:'BTECH-IT', value:'btech-it'},
                    {degree:'mba', text:'MBA', value:'mba'},
                    {degree:'mca', text:'MCA', value:'mca'}
                ],
                year:'',
                year_options:[
                    {text:'1', value:'1'},
                    {text:'2', value:'2'},
                    {text:'3', value:'3'},
                    {text:'4', value:'4'}
                ],
                semester:'',
                semester_options:[
                    {text:'1', value:'1'},
                    {text:'2', value:'2'},
                    {text:'3', value:'3'},
                    {text:'4', value:'4'},
                    {text:'5', value:'5'},
                    {text:'6', value:'6'},
                    {text:'7', value:'7'},
                    {text:'8', value:'8'},
                ],
                section:'',
                section_options:[
                    {text:'A', value:'A'},
                    {text:'B', value:'B'},
                    {text:'C', value:'C'}
                ],
                AcademicS:"",
                isLoading: false,
                fullPage: false,
                attendanceOverall:[]
        }
    },
     props:['authenticateduser', 'authrole'],
     components: {
            Loading
        },
    mounted(){
        this.getAllStudents();
        this.hodandStaffRole();
         this.EditATpermissions();
         this.fetchSem();
    },
    created(){
         this.EditATpermissions();
    },
    methods:{
         attendanceHours(Student){
             var status = [];
            var aData;
            var child;
            let Fletters;
      aData = this.attendanceOverall.studentrecords;
      for (var child in aData) {
        if (aData[child].student_id === Student.id) {
            // if(aData[child].status === "present"){
                status = aData[child];
            // }
        }
      }
      if (status) {
         return status;
      } else {
        return "null";
      }
        },
        dateMatch(student,date){
        var a = new Date(date.date);
        var b = new Date(student.joined_date);
            if(a >= b){
                return true;
            }else{
                return false;
            }
        },
        doAjax() {
                this.isLoading = true;
                // simulate AJAX
                setTimeout(() => {
                  this.isLoading = false
                },5000)
            },
          fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                this.AcademicS = data;
            })
            .catch(err => console.log(err));
        },
         PrintAcademicYear(){
            var d = new Date();
            var c = d.getFullYear();
           
           
            if(this.AcademicS.academic_semester === 'odd'){
                 d.setFullYear(d.getFullYear() + 1);
                var n = d.getFullYear();
                return c+' - '+ n;
            }else{
                 d.setFullYear(d.getFullYear() - 1);
                var n = d.getFullYear();
                return c+' - '+ n;
            }
        },
         printMonth() {
      
const modal = document.getElementById("modalInvoice")
  const cloned = modal.cloneNode(true)
  let section = document.getElementById("print")

  if (!section) {
     section  = document.createElement("div")
     section.id = "print"
     document.body.appendChild(section)
  }

  section.innerHTML = "";
  section.appendChild(cloned);
  window.print();

     
    },
        IncrementBy1(i){
           let a = i;
           return a + 1;
       },
        displayDateFormat(DateA){
            return moment(DateA).format('DD-MM-YYYY');
        },
        getStudentTotalsHours(Student){
        var Sdate = moment(Student.joined_date).format('YYYY-MM-DD');
               var status = [];
            var aData;
            var child;
      aData = this.AtRecords;
      var a = new Date(Sdate);
var b = new Date(aData[0].date);

                if(a > b){
      for (var child in aData) {
      var c = new Date(Sdate);
                        var d = new Date(aData[child].date);
        if (aData[child].student_id === Student.id && aData[child].is_present === 1 && c <= d) {
          status.push(aData[child]);
        }
      }
      if (status) {
          return status.length;
      }else{
          return 0;
      }
                }else{
                     for (var child in aData) {
      var c = new Date(Sdate);
                        var d = new Date(aData[child].date);
        if (aData[child].student_id === Student.id && aData[child].is_present === 1) {
          status.push(aData[child]);
        }
      }
      if (status) {
          return status.length;
      }else{
          return 0;
      }
                }
        },
         getTotalsHours(student){
            if(student.joined_date !== null){
                var Sdate = moment(student.joined_date).format('YYYY-MM-DD');
            }
            let Dates = this.AtDates;
            let T = 0;
var a = new Date(Sdate);
var b = new Date(Dates[0].date);

                if(a > b){
                    for(let index = 0; index < Dates.length; index++) {
                        const element = Dates[index];
                        var c = new Date(Sdate);
                        var d = new Date(element.date);
                            if(c <= d){
                                T = T + element.total_hours;
                            }
                    }
                    if(T !== NaN && T !== Infinity){
                        return T;
                    }
                                   
                }else{
                    for(let index = 0; index < Dates.length; index++) {
                        const element = Dates[index];
                        T = T + element.total_hours;
                    }
                    if(T !== NaN && T !== Infinity){
                        return T;
                    }   
                }
            
         },
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
let filenme = 'Student-Attendance-Month.xlsx';
XLSX.writeFile(wb, filenme) // name of the file 

},
        
        getAttendance(Student,AtDate,hour){
            var status = [];
            var aData;
            var child;
      aData = this.AtRecords;
      for (var child in aData) {
        if (aData[child].student_id === Student.id && aData[child].date === AtDate.date && aData[child].hour === hour) {
          status.push(aData[child]);
        }
      }
      if (status) {
          if(status.length > 0){
              switch (status[0].status) {
                  case 'present':
                      return "<span style='background-color:rgba(85, 205, 45, 0.56);color:#000;padding: 2px 3px;font-weight: 500;'>P</span>";
                      break;
                case 'absent':
                      return "<span style='background-color:rgba(205, 45, 45, 0.68);color:#000;padding: 2px 3px;font-weight: 500;'>A</span>";
                      break;
                      case 'leave':
                      return "<span style='background-color:rgba(205, 157, 45, 0.56);color:#000;padding: 2px 3px;font-weight: 500;'>L</span>";
                      break;
                      case 'od':
                      return "<span style='background-color:rgba(45, 117, 205, 0.56);color:#000;padding: 2px 3px;font-weight: 500;'>O</span>";
                      break;
                  default:
                      return 'N';
                      break;
              }
          }
        
      } else {
        return null;
      }
  
            
        },
getStudentOverallHours(){
let Formdata = {
                    fromdate:this.fromdate,
                    todate:this.todate,
                    degree:this.degree.toLowerCase(),
                    department:this.department.toLowerCase(),
                    year:this.year,
                    semester:this.semester,
                    section:this.section
                }
                console.log(Formdata);
                fetch('/api/student/attendance/view/overallstudent', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    console.log(data);
                    this.attendanceOverall = data;
                }).catch(err => console.log(err));

},
        

        getAllStudents(){
            fetch('/api/students')
            .then(res => {
                return res.json();
            })
            .then(data => {
                this.Studs = data;
            }).catch(err => console.log(err));
        },

        getAttendances(){
            // this.fromdate

//             var date = new Date(this.fromdate), y = date.getFullYear(), m = date.getMonth();
// var firstDay = new Date(y, m, 1);
// var lastDay = new Date(y, m + 1, 0);
// console.log(lastDay);
this.getStudentOverallHours();
let loader = this.$loading.show({
                  // Optional parameters
                  container: this.fullPage ? null : this.$refs.formContainer,
                  canCancel: true,
                  onCancel: this.onCancel,
                });

               
                  
               
               
                let Formdata = {
                    fromdate:this.fromdate,
                    todate:this.todate,
                    degree:this.degree.toLowerCase(),
                    department:this.department.toLowerCase(),
                    year:this.year,
                    semester:this.semester,
                    section:this.section
                }
                console.log(Formdata);
                fetch('/api/student/attendance/view/bydate', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
if(data){
    loader.hide();
}
                    this.hideForm = false;
                    // console.log(data);

                //    this.AtDates = data['dates'];

               this.AtDates = data['dates'].sort(function(a, b){
    var keyA = new Date(a.date),
        keyB = new Date(b.date);
    // Compare the 2 dates
    if(keyA < keyB) return -1;
    if(keyA > keyB) return 1;
    return 0;
});

                   this.AtRecords = data['records'];

                   if(this.ClassAdvisors === 1 || this.authrole <= 3)
            {
                this.ImtheClassAdvisor = true;
            }else{
                this.ImtheClassAdvisor = false;
            }
                    
                }).catch(err => {
                    
                });
        },
        onCancel() {
              console.log('User cancelled the loader.')
            },
        hodandStaffRole(){
        let w_dapart = this.authenticateduser.working_department;
        if(this.authrole === 3 || this.authrole === 4){ //if user is hod or professor show their staffs
                    this.department = w_dapart.toUpperCase();
                    this.disAccess = true;

                    if(this.bachlers_departments.includes(w_dapart)){
                        this.degree = 'BE';
                    }else if(this.masters_departments.includes(w_dapart)){
                        this.degree = 'ME';
                    }
                }
    },
      EditATpermissions(){
                fetch('/api/studentEditPermissions')
            .then(res => { return res.json(); })
                .then(data => {
                    this.EditATPermissions = data;
                    // console.log(data);
                }).catch(err => console.log(err));
            },


    },

    computed:{
         filerDapart(){
            var degree = this.degree;
            var departments = this.department_options;
            if(degree !== ""){
                return departments.filter(function(opt){
                    return opt.degree.toLowerCase() === degree.toLowerCase();
                });
            }
            // return 0;
            
        },
       ClassAdvisors(){
            let sche = [];

             let degree = this.degree;
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;

        let hodDeparts = [];

        if(this.authenticateduser.working_department_me !== null){
        hodDeparts.push(this.authenticateduser.working_department);
        let ArSplit = this.authenticateduser.working_department_me.split('&&');
        ArSplit.forEach(eee => {
            if(eee !== ""){
                hodDeparts.push(eee);
            }
        });
        }
// console.log(hodDeparts);
           let Permissions = this.EditATPermissions;
           let authuser = this.authenticateduser.id;

                if(authuser !== ""){
                    let Somthing = Permissions.filter(function(Permission){

            let stdepartment = Permission.department.toLowerCase();
              let styear = Number(Permission.year);
              let stsection = Permission.section.toLowerCase();
              let stdegree = Permission.degree.toLowerCase();
              let stclassadvisor = Permission.class_advisor;
              let stclassadvisor2 = Permission.class_advisor2;

                        return ((department === "" || stdepartment === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection === section.toLowerCase()) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (stclassadvisor === authuser || stclassadvisor2 === authuser));
                        
                        
                    });
if(this.authrole === 1){
    return 1;
}else if(this.authrole === 1 && hodDeparts.indexOf(department)){
    // console.log('h');
    return 1;
}else{
    return Somthing.length;
}
                 

                }

        },
filteredStudents(){
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;
        let degree = this.degree;
        
        if(department === "" && year === "" && section === "" && degree === "" && semester === ""){
          return this.Studs;
        }else{
          return this.Studs.filter(function(student){

              let stdepartment = student.department.toLowerCase();
              let styear = Number(student.year);
              let stsemester = Number(student.semester);
              let stsection = student.section.toLowerCase();
              let stdegree = student.degree.toLowerCase();


            return (department === "" || stdepartment.trim() === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection.trim() === section.toLowerCase()) 
            && (degree === "" || stdegree.trim() === degree.toLowerCase())
            && (semester === "" || stsemester === Number(semester));


          }).sort(function(a, b) {
    return a.reg_no - b.reg_no;
});;

          }
        },
    }
}
</script>


<style scoped>
.main-heading h3{
    font-size: 14px;
}
.Main-Head{
    font-size: 18px;
}
.Main-Head2{
    font-size: 16px;
}
.day-attendances-ul li{
    list-style: none;
    display: inline-block;
}
.right-list{
    display: inline-block;
    float: right;
}
.left-list{
    display: inline-block;
}
.left-list li, .right-list li{
    list-style: none;
}
.table-responsive.print-month-view{
    font-size: 10px;
}
.day-attendances-ul{
    padding: 0;
}
.print-month{

}
</style>

<style type="text/css" media="print">
.back-btn, .download-btn{
    display: none;
}

</style>