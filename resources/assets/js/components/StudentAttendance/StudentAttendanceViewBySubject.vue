<template>
<div class="home">

<!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Student Attendance For Subject {{Subject_selected}}</h3>
	</div>
	<div class="panel-body">
        <div class="row" v-if="hideForm">
            
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

            <div class="form-group col-sm-2"> 
                <label for="Subject">Subject</label>
                <select class="form-control" name="Subject" id="Subject" @change="getSubjectId()" v-model="Subject_selected">
                    <option v-for="Subject in filterSubjects">{{Subject.subject_name}}</option>
                </select><br>
            </div>

        </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
	</div>
    <div class="row" v-if="hideForm === false">
         <div class="not-classadvisor">
            <h2 class="text-center" v-if="ImtheClassAdvisor === false">You are not class Advisor for this class</h2>
        </div>
        <div class="class-advisor">
        <div class="back-btn-div">
                <a class="btn btn-primary back-btn" href="">Back</a>
                <button class="btn btn-primary download-btn" @click="onexport()">download</button>
            </div>
            
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <tr>
                    <th>S.No</th>
                    <th>REG No</th>
                    <th>Name</th>
                    <th>Hours</th>
                    <th>Percentage</th>
                </tr>
                    <tr v-for="(Student,sl_no) in filteredStudents" v-bind:key="Student.id">
                        <td>{{1+sl_no}}</td>
                        <td>{{Student.reg_no}}</td>
                        <td>{{Student.name}}</td>
                        <td>{{getStudSubOverall(Student)}} / {{Totalhours()}}</td>
                        <td>{{attendancePercentage(Student)}}</td>
                    </tr>
                </tbody>
            </table>
        
        </div>
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
            Studs:[],
            subject_id:'',

            ELStudents: [],
EditATPermissions: [],
            ImtheClassAdvisor:"",
            Subject_selected:'',
            Subjects:[],
            Schedules:[],
            AtHours:[],
            AtRecords:[],
            hideForm:true,
            date:'',
            degree:'',
                degree_options:[
                    {text:'BE', value:'be'},
                    {text:'ME', value:'me'},
                    {text:'BTECH', value:'btech'},
                    {text:'MTECH', value:'mtech'},
                    {text:'MBA', value:'mba'},
                    {text:'MCA', value:'mca'},
                ],
                bachlers_departments:["ece","eee","mech","cse","it","civil","automobile"],
            masters_departments:["me-cse","me-cem","me-est","me-ise","me-ped","me-se","me-vlsi","mtech-it","mba"],
                department:'',
                department_options:[
                  {degree:'be', text:'ECE', value:'ece'},
                    {degree:'be', text:'EEE', value:'eee'},
                    {degree:'be', text:'CSE', value:'cse'},
                    {degree:'be', text:'MECH', value:'mech'},
                    {degree:'be', text:'IT', value:'it'},
                    {degree:'be', text:'CIVIL', value:'civil'},
                    {degree:'be', text:'AUTO', value:'auto'},
                    {degree:'me', text:'ME-CSE', value:'me-cse'},
                    {degree:'me', text:'ME-CS', value:'me-cs'},
                    {degree:'me', text:'ME-Cem', value:'me-cem'},
                    {degree:'me', text:'ME-EST', value:'me-est'},
                    {degree:'me', text:'ME-ISE', value:'me-ise'},
                    {degree:'me', text:'ME-PED', value:'me-ped'},
                    {degree:'me', text:'ME-SE', value:'me-se'},
                    {degree:'me', text:'ME-VLSI', value:'me-vlsi'},
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
                ]
        }
    },
     props:['authenticateduser', 'authrole'],
    mounted(){
        this.getAllStudents();
        this.getAllSubjects();
        this.hodandStaffRole();
        this.EditATpermissions();
    },
    created(){
        this.EditATpermissions();
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
        Totalhours(){
            return this.AtHours.length;
        },
        attendancePercentage(Student){

 this.AtHours;
 var status = [];
 var stat;
            var aData;
            var child;
            let Fletters;
      aData = this.AtRecords;
      for (var child in aData) {
        if (aData[child].student_id === Student.id) {
          status.push(aData[child]);
        }
      }
      if (status) { 
          stat = (status.length / this.AtHours.length) * 100;
          return Math.round(stat);
      } else {
        return "null";
      }
    },


        getSubjectId(){
            var subb;
            var staff;
                var Subs;
     
                Subs = this.filterSubjects;
                for(var child in Subs){
                    if (Subs[child].subject_name === this.Subject_selected){
                        subb = Subs[child].id;
                        staff = Subs[child].staff_id;

                        this.subject_id = subb;
                    }
                }
               
        },

  getAllSubjects(){
                fetch('/api/schedule/subject/show')
                .then(res => { return res.json(); })
                .then(data => {
                    this.Subjects = data;
                    console.log(data);
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

 getStudSubOverall(Student){
            var status = [];
            var aData;
            var child;
      aData = this.AtRecords;
      for (var child in aData) {
        if (aData[child].student_id === Student.id) {
          status.push(aData[child].status);
        }
      }
      if (status) {

        return status.length;
      } else {
        return "null";
      }
},
       
        getAttendances(){
            console.log(1);
            let Formdata = {
                    subject_id:this.subject_id,
                    degree:this.degree.toLowerCase(),
                    department:this.department.toLowerCase(),
                    year:this.year,
                    semester:this.semester,
                    section:this.section
                }
console.log(Formdata);
            fetch('/api/student/attendance/view/bysubject', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {

                    this.hideForm = false;

                    console.log(data);
                    this.AtHours = data['total_hours'];
                    this.AtRecords = data['records'];   
                    
                    this.ELStudents = data['electivestudents']
             
                    
                }).catch(err => {
                    
                });
        },
         hodandStaffRole(){
        let w_dapart = this.authenticateduser.working_department;
        if(this.authrole === 3 || this.authrole === 4){ //if user is hod or professor show their staffs
                    this.department = w_dapart.toUpperCase();
                    // this.disAccess = true;

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
ClassAdvisors(){
            let sche = [];

             let degree = this.degree;
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;

           let Permissions = this.EditATPermissions;
           let authuser = this.authenticateduser.id;

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

                if(authuser !== ""){
                    let Somthing = Permissions.filter(function(Permission){

let stdepartment = Permission.department.toLowerCase();
              let styear = Number(Permission.year);
              let stsection = Permission.section.toLowerCase();
              let stdegree = Permission.degree.toLowerCase();
              let stclassadvisor = Permission.class_advisor;
              let stclassadvisor2 = Permission.class_advisor2;

                        return (department === "" || stdepartment === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection === section.toLowerCase()) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (stclassadvisor === authuser || stclassadvisor2 === authuser);
                        
                        ;
                    });
             if(Somthing.length === 1 || this.authrole <= 3)
            {
                this.ImtheClassAdvisor = true;
            }else{
                this.ImtheClassAdvisor = false;
            }
                
if(this.authrole === 1){
    return 1;
}else if(hodDeparts.indexOf(department)){
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

            if(this.ELStudents.length > 0){
                return this.ELStudents.sort(function(a, b) {
    return a.reg_no - b.reg_no;
});;;
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
});;;
            }

          
          }
        },
         filterSubjects(){
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;
        let degree = this.degree;

        let Subjects = this.Subjects;

        let authuser = this.authenticateduser.id;
        
        if(department === "" && year === "" && section === "" && degree === "" && semester === ""){
          return this.Subjects;
        }else if(this.ImtheClassAdvisor === true
        ){
            return this.Subjects.filter(function(student){

              let stdepartment = student.department.toLowerCase();
              let styear = Number(student.year);
              let stsemester = Number(student.semester);
              let stsection = student.section.toLowerCase();
              let stdegree = student.degree.toLowerCase();
              let ststaff = student.user_id;

            return (department === "" || stdepartment === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection === section.toLowerCase()) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (semester === "" || stsemester === Number(semester));
           

          });
        }
        else{

              return this.Subjects.filter(function(student){

              let stdepartment = student.department.toLowerCase();
              let styear = Number(student.year);
              let stsemester = Number(student.semester);
              let stsection = student.section.toLowerCase();
              let stdegree = student.degree.toLowerCase();
              let ststaff = student.user_id;

            return (department === "" || stdepartment === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection === section.toLowerCase()) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (semester === "" || stsemester === Number(semester))    
              });
          }
        },

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
    // filterSubjects(){
    //     let Subjects = this.Subjects;
    //     let degree = this.degree.toLowerCase();
    //     let department = this.department.toLowerCase();
    //     let year = this.year;
    //     let semester = this.semester;
    //     let section = this.section.toLowerCase();

    //     if (degree === "" && department === "" && year === "" && semester === "" && section === "") {
    //         return Subjects;
    //     }else{
    //         return Subjects.filter(function(subject){
    //             return (
    //                 (degree === "" || degree === subject.degree)
    //                 && (department === "" || department === subject.department)
    //                 && (year === "" || Number(year) === subject.year)
    //                 && (semester === "" || Number(semester) === subject.semester)
    //                 && (section === "" || section === subject.section)
    //                 );
    //         })
    //     }

    // }
    }
}
</script>


<style scoped>
#myModal{
    font-size: 20px;
}
.hover-text{
    display:none;
    transition: all ease-in .2s;
    position: absolute;
}

.hover-blk:hover .hover-text{
    display:block;
}
.back-btn
{
    margin-bottom: 30px;
}
.download-btn{
    float: right;
}
</style>
