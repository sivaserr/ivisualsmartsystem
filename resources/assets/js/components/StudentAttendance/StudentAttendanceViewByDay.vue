<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Student Attendance View for <span style="text-transform:uppercase;"> {{degree}}<span v-if="degree">-</span>{{department}} {{year}} <span v-if="year">Year</span> {{section}} <span v-if="section">Section</span> <span v-if="date">on</span> {{date}} </span></h3>
	</div>
	<div class="panel-body">
        <div class="row" v-if="hideForm">
           
        <form @submit.prevent="getAttendances()">
            <div class="as-a-class-advisor" v-if="authrole !== 1">
                <div class="view-as-hod" v-if="authrole !== 3">
                <div class="not-hod" v-if="ClassAdvisors.length > 0">
                    <h3>View as a class advisor</h3>
                <ul class="schedule-list" >
                    <li @click="setAllDatasAsAdvisor(ClassAdvisor)" :class="{highlight:ClassAdvisor.id == selectedULAdvisor}" v-for="ClassAdvisor in ClassAdvisors" v-bind:key="ClassAdvisor.id">
                        <span class="text-info"><span style="text-transform:uppercase;">{{ClassAdvisor.department}}</span> {{ClassAdvisor.year}} Year <br>
                        <span style="text-transform:uppercase;"> {{ClassAdvisor.section}} </span> Section </span>
                    </li>
                </ul>
                </div>
                </div>
            </div>
            <div class="as-a-subject-handler" v-if="authrole !== 1">
                <div class="view-as-hod" v-if="authrole !== 3">
                <div class="no-the-hod">
                    <h3>View as a Subject Handler</h3>
                 <ul class="schedule-list">
                    <li @click="setAllDatasAsSubjectHandler(SubjectHandler)" :class="{highlight:SubjectHandler.id == selectedULSubHandler}" v-for="SubjectHandler in StaffSubjects" v-bind:key="SubjectHandler.id">
                        {{SubjectHandler.subject_name}} <br>
                        <span class="text-info">{{SubjectHandler.department}} {{SubjectHandler.year}} Year <br>
                        <span style="text-transform:uppercase;">{{SubjectHandler.section}}</span>  Section </span>
                          
                    </li>
                </ul>
                </div>
                </div>
            </div>
            <div class="if-user-is-hod-or-principal" v-if="authrole === 1 || authrole === 3">
          
               <div class="col-sm-2">
                <label for="degree" class="">Degree</label>
            <select required="true" v-model="degree" class="form-control" id="degree" name="degree">
				<option v-for="degree_option in degree_options" v-bind:key="degree_option.value">{{degree_option.text}}</option>
			</select>
            <br>
            </div>
            <div class="col-sm-2">
                <label for="department" class="">Department</label>
			<select required="true" v-model="department" class="form-control" id="department" name="department">
				<option v-for="department_option in filerDapart" v-bind:key="department_option.value">{{department_option.text}}</option>
			</select>
            <br>
            </div>
            <div class="col-sm-2">
                <label for="year" class="">Year</label>
			<select required="true" v-model="year" class="form-control" id="year" name="year">
				<option v-for="year_option in year_options" v-bind:key="year_option.value">{{year_option.text}}</option>
			</select>
            <br>
            </div>
            <div class="col-sm-2">
                <label for="semester" class="">Semester</label>
			<select required="true" v-model="semester" class="form-control" id="semester" name="semester">
				<option v-for="semester_option in semester_options" v-bind:key="semester_option.value">{{semester_option.text}}</option>
			</select>
            <br>
            </div>
            <div class="col-sm-2">
                <label for="section" class="">Section</label>
			<select required="true" v-model="section" class="form-control" id="section" name="section">
				<option v-for="section_option in section_options" v-bind:key="section_option.value">{{section_option.text}}</option>
			</select>
            <br>
            </div> 

            </div>

            <div class="form-group" style="width:150px;">
                <label for="date">Select date</label>
                <input required="true" type="date" id="date"  v-model="date" name="date" max="3000-12-31" min="1000-01-01" class="form-control">
            </div>
        
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
	</div>
    <div class="row" v-if="hideForm === false">
        <div v-if="this.ShowAllSubjects === false">Since you are Not a class adviser you can't view All subject attendances</div>
        <div class="back-div" style="margin-bottom:20px;">
            <a class="btn btn-primary back-btn" href="">Back</a>
            <button class="btn btn-primary" @click="onexport()">download</button>
        </div>
        
        
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <tr>
                        <td>REG No</td>
                        <td>Name</td>
                        <td class="hover-blk">
                            1) <ul><li v-for="schedule in DayAtHour1" >{{schedule}}</li></ul>
                        </td>
                        <td class="hover-blk">
                            2)  <ul><li v-for="schedule in DayAtHour2" >{{schedule}}</li></ul>
                        </td>
                        <td class="hover-blk">
                            3)  <ul><li v-for="schedule in DayAtHour3" >{{schedule}}</li></ul>
                        </td>
                        <td class="hover-blk">
                            4)  <ul><li v-for="schedule in DayAtHour4" >{{schedule}}</li></ul>
                        </td>
                        <td class="hover-blk">
                            5)  <ul><li v-for="schedule in DayAtHour5" >{{schedule}}</li></ul>
                        </td>
                        <td class="hover-blk">
                            6)  <ul><li v-for="schedule in DayAtHour6" >{{schedule}}</li></ul>
                        </td>
                        <td class="hover-blk">
                            7)  <ul><li v-for="schedule in DayAtHour7" >{{schedule}}</li></ul>
                        </td>
                    </tr>
                    <tr v-for="Student in filteredStudents" v-bind:key="Student.id">
                        <td>{{Student.reg_no}}</td>
                        <td>{{Student.name}}</td>
                        <td>
                            <span :class="DiffClass(getAttendance(Student,1))">{{getAttendance(Student,1)}}</span> 
                        </td>
                        <td>
                            <span :class="DiffClass(getAttendance(Student,2))">{{getAttendance(Student,2)}}</span> 
                        </td>
                        <td>
                            <span :class="DiffClass(getAttendance(Student,3))">{{getAttendance(Student,3)}}</span> 
                        </td>
                        <td>
                            <span :class="DiffClass(getAttendance(Student,4))">{{getAttendance(Student,4)}}</span> 
                        </td>
                        <td>
                            <span :class="DiffClass(getAttendance(Student,5))">{{getAttendance(Student,5)}}</span> 
                        </td>
                        <td>
                            <span :class="DiffClass(getAttendance(Student,6))">{{getAttendance(Student,6)}}</span> 
                        </td>
                        <td>
                            <span :class="DiffClass(getAttendance(Student,7))">{{getAttendance(Student,7)}}</span> 
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
import XLSX from 'xlsx'

export default {
    data(){
        return{

            Studs:[],
            selectedULAdvisor:undefined,
            selectedULSubHandler:undefined,
            EditATPermissions: [],
            Schedules:[],
            AtRecords:[],
            AtHours:[],
            hideForm:true,
            gotAcademicData:[],
            ShowAllSubjects:"",
            itsMySchedule:[],
            date:'',
            DayAtHour1:[],
            DayAtHour2:[],
            DayAtHour3:[],
            DayAtHour4:[],
            DayAtHour5:[],
            DayAtHour6:[],
            DayAtHour7:[],

            AtHOURS:[],
            StaffSubjects:[],
            bachlers_departments:["ece","eee","mech","cse","it","civil","automobile"],
            masters_departments:["me-cse","me-cem","me-est","me-ise","me-ped","me-se","me-vlsi","mtech-it","mba"],
            degree:'',
                degree_options:[
                    {text:'BE', value:'be'},
                    {text:'ME', value:'me'},
                    {text:'BTECH', value:'btech'},
                    {text:'MTECH', value:'mtech'},
                    {text:'MBA', value:'mba'},
                    {text:'MCA', value:'mca'},
                ],
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
                    {text:'a', value:'a'},
                    {text:'b', value:'b'},
                    {text:'c', value:'c'}
                ],
                MyClasses:[]
        }
    },
          props:['authenticateduser', 'authrole'],
    mounted(){
        this.getAllStudents();
        this.getAllShedules();
        this.hodandStaffRole();
        this.EditATpermissions();
        this.viewSubjectHendler();
        this.getMySchedules();
        this.showStaffSubjects();
        this.fetchSem();
    },
    created(){
         this.EditATpermissions();
         this.viewSubjectHendler();
         this.getMySchedules();
    },
    methods:{
   /* EXCEL EXPORT */
DiffClass(dt){
    switch(dt){
        case "present":
            return "label label-success";
        break;
        case "absent":
            return "label label-danger";
        break;
        case "leave":
            return "label label-warning";
        break;
        case "od":
            return "label label-info";
        break;
    }
},
   viewSubjectHendler(){
   
      
           if(this.ClassAdvisors.length >! 0 || this.authrole === 1 || this.authrole === 3)
            {
                this.ShowAllSubjects = true;
            }else{
                this.ShowAllSubjects = false;
            }
                    
       
   },
     
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
// Student Attendance View for {{degree}}-{{department}} {{year}} Year {{section}}Section on{{date}}
let filenme = `KSRCE ${this.degree}-${this.department} ${this.year} Year ${this.section}Section on ${this.date}.xlsx`;
XLSX.writeFile(wb, filenme) // name of the file 

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
        getAttendance(Student,AtHour){
            var status = [];
            var aData;
            var child;
            let Fletters = [];
            let Msche = this.filteredMySchedules;
            for(var one in Msche){
                Fletters.push(Msche[one].id);
            }
      aData = this.AtRecords;
      for (var child in aData) {
          if(this.ShowAllSubjects === false){
              if (aData[child].student_id === Student.id 
              && aData[child].date === this.date 
              && aData[child].hour === AtHour
              && Fletters.indexOf(aData[child].schedule_id) !== -1) {
          status = aData[child].status;
        }
          }else{
        if (aData[child].student_id === Student.id && aData[child].date === this.date && aData[child].hour === AtHour) {
          status = aData[child].status;
        }
          }
      }
      if (status) {
        return status;
      } else {
        return "null";
      }
},

getMySchedules(){
    var status = [];
            var aData;
            var child;
      aData = this.filterSchedules;
      for (var child in aData) {
          
        if (aData[child].staff_id === this.authenticateduser.id) {
          status.push(aData[child].id);
        }
      }
      this.itsMySchedule = status;
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

        getDAy(){
            var sche = [];
                var Schedules;
     
                Schedules = this.filterSchedules;

                let DDay;

                for(var child in Schedules){
                    if (Schedules[child].id === this.AtRecords[0].schedule_id){
                        DDay = Schedules[child].day;
                    }
                }

                if(DDay){
                    return DDay;
                }else{
                    return '__';
                }
        },

        getSchedule(day,hour){
                var sche = [];
                var Schedules;
     
                Schedules = this.filterSchedules;

                // for(var child in Schedules){
                //     if (Schedules[child].id === this.AtHours[0].schedule_id){
                //         let DDay = Schedules[child].day;
                //     }
                // }

                for(var child in Schedules){
                    if(this.ShowAllSubjects === false){
                        if (Schedules[child].day === day && Schedules[child].hour === hour && Schedules[child].staff_id === this.authenticateduser.id){
                        sche.push(Schedules[child].subject_name);
                    }
                    }else{
                        if (Schedules[child].day === day && Schedules[child].hour === hour){
                        sche.push(Schedules[child].subject_name);
                    }
                    }
                    
                }

                if(sche){
                    return sche;
                }else{
                    return '__';
                }
            },

        // getSchedule(day,hour){
        //         var sche;
        //         var Schedules;
    
        //         Schedules = this.filterSchedules;
        //         for(var child in Schedules){
        //             if (Schedules[child].day === day && Schedules[child].hour === hour){
        //                 sche = Schedules[child].subject_name;
        //             }
        //         }
        //         if(sche){
        //             var abbr = String(sche).split(' ').map(function(item){return item[0]}).join('');
        //             return {'subject':sche, 'cap':abbr};
        //         }else{
        //             return '__';
        //         }
        //     },



        getAttendances(){
            let Formdata = {
                    date:this.date,
                    degree:this.degree.toLowerCase(),
                    department:this.department.toLowerCase(),
                    year:this.year,
                    semester:this.semester,
                    section:this.section
                }
                console.log(Formdata);
                fetch('/api/student/attendance/view/byday', {
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


                    this.DayAtHour1;

                    this.AtHOURS = data['hours'];
                    let aData = data['hours'];
                    
for(var child in aData){
    switch (aData[child].hour) {
        case 1:
            this.DayAtHour1.push(aData[child].subject_name);
            break;
            case 2:
            this.DayAtHour2.push(aData[child].subject_name);
            break;
            case 3:
            this.DayAtHour3.push(aData[child].subject_name);
            break;
            case 4:
            this.DayAtHour4.push(aData[child].subject_name);
            break;
            case 5:
            this.DayAtHour5.push(aData[child].subject_name);
            break;
            case 6:
            this.DayAtHour6.push(aData[child].subject_name);
            break;
            case 7:
            this.DayAtHour7.push(aData[child].subject_name);
            break;
    }
    
}
                   this.AtRecords = data['records'];

                if(this.AtRecords.length === 0){
                    alert('No Attendances were made for this date');
                }

            //             if(this.ClassAdvisors === 1 || this.authrole <= 3)
            // {
            //     this.ShowAllSubjects = true;
            // }else{
            //     this.ShowAllSubjects = false;
            // }
                }).catch(err => {
                    
                });
        },

        getAllShedules(){
            fetch('/api/schedule/show')
            .then(res => { return res.json(); })
                .then(data => {
                    this.Schedules = data;
                    // console.log(data);
                }).catch(err => console.log(err));
        },
        EditATpermissions(){
                fetch('/api/studentEditPermissions')
            .then(res => { return res.json(); })
                .then(data => {
                    this.EditATPermissions = data;
                    // console.log(data);
                }).catch(err => console.log(err));
            },

       showStaffSubjects(){
                fetch('/api/StaffSubjects/staff/'+this.authenticateduser.id+'')
            .then(res => { return res.json(); })
                .then(data => {
                    this.StaffSubjects = data;
                    console.log(data);
                }).catch(err => console.log(err));
    },
    setAllDatasAsAdvisor(ClassAdvisor){

        this.selectedULSubHandler = '';

        this.selectedULAdvisor = ClassAdvisor.id;
        this.ShowAllSubjects = true;
        this.degree = ClassAdvisor.degree;
        this.department = ClassAdvisor.department;
        this.year = ClassAdvisor.year;
        this.section = ClassAdvisor.section;

        switch (this.year) {
            case 1:
                if(this.gotAcademicData.academic_semester === 'odd'){
                    this.semester = 1;
                }else if(this.gotAcademicData.academic_semester === 'even'){
                    this.semester = 2;
                }
                break;
                 case 2:
                if(this.gotAcademicData.academic_semester === 'odd'){
                    this.semester = 3;
                }else if(this.gotAcademicData.academic_semester === 'even'){
                    this.semester = 4;
                }
                break;
                 case 3:
                if(this.gotAcademicData.academic_semester === 'odd'){
                    this.semester = 5;
                }else if(this.gotAcademicData.academic_semester === 'even'){
                    this.semester = 6;
                }
                break;
                 case 4:
                if(this.gotAcademicData.academic_semester === 'odd'){
                    this.semester = 7;
                }else if(this.gotAcademicData.academic_semester === 'even'){
                    this.semester = 8;
                }
                break;
        }
        
    },
    setAllDatasAsSubjectHandler(SubjectHandler){

        this.selectedULAdvisor = '';

        this.selectedULSubHandler = SubjectHandler.id;
        this.ShowAllSubjects = false;
        this.degree = SubjectHandler.degree;
        this.department = SubjectHandler.department;
        this.year = SubjectHandler.year;
        this.section = SubjectHandler.section;

        switch (this.year) {
            case 1:
                if(this.gotAcademicData.academic_semester === 'odd'){
                    this.semester = 1;
                }else if(this.gotAcademicData.academic_semester === 'even'){
                    this.semester = 2;
                }
                break;
                 case 2:
                if(this.gotAcademicData.academic_semester === 'odd'){
                    this.semester = 3;
                }else if(this.gotAcademicData.academic_semester === 'even'){
                    this.semester = 4;
                }
                break;
                 case 3:
                if(this.gotAcademicData.academic_semester === 'odd'){
                    this.semester = 5;
                }else if(this.gotAcademicData.academic_semester === 'even'){
                    this.semester = 6;
                }
                break;
                 case 4:
                if(this.gotAcademicData.academic_semester === 'odd'){
                    this.semester = 7;
                }else if(this.gotAcademicData.academic_semester === 'even'){
                    this.semester = 8;
                }
                break;
        }
    },
    fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                this.gotAcademicData = data;
            })
            .catch(err => console.log(err));
        }

    },

    computed:{
        autoSemester(){
            var year = this.year;
            var academic = this.gotAcademicData;
            if(this.year !== ""){
                switch (Number(year)) {
                    case 1:
                        if(academic.academic_semester === 'odd'){
                            this.semester = 1;
                        }else if(academic.academic_semester === 'even'){
                            this.semester = 2;
                        }
                        break;
                         case 2:
                        if(academic.academic_semester === 'odd'){
                            this.semester = 3;
                        }else if(academic.academic_semester === 'even'){
                            this.semester = 4;
                        }
                        break;
                         case 3:
                        if(academic.academic_semester === 'odd'){
                            this.semester = 5;
                        }else if(academic.academic_semester === 'even'){
                            this.semester = 6;
                        }
                        break;
                         case 4:
                        if(academic.academic_semester === 'odd'){
                            this.semester = 7;
                        }else if(academic.academic_semester === 'even'){
                            this.semester = 8;
                        }
                        break;
                
                    default:
                        break;
                }
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
});
          }
        },
 ClassAdvisors(){
            let sche = [];

           let Permissions = this.EditATPermissions;
           let authuser = this.authenticateduser.id;

                if(authuser !== ""){
                   
                        let Somthing = Permissions.filter(function(Permission){
                        let stclassadvisor = Permission.class_advisor;
                        let stclassadvisor2 = Permission.class_advisor2;
                        return (stclassadvisor === authuser || stclassadvisor2 === authuser);
                    });
                return Somthing;
                    
                }
        },
 PermissionsEnabled(){
            let sche = [];

           let Permissions = this.EditATPermissions;
           let authuser = this.authenticateduser.id;

                if(authuser !== ""){
                    let Somthing = Permissions.filter(function(Permission){
                        let stclassadvisor = Permission.class_advisor;
                        let stclassadvisor2 = Permission.class_advisor2;
                        return (stclassadvisor === authuser || stclassadvisor2 === authuser);
                    });
                return Somthing;
                }

        },
        

        filterSchedules(){

            
        let degree = this.degree;
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;

        if (degree === "" && department === "" && year === "" && semester === "" && section === "") {
            return this.Schedules;
        }else{
            return this.Schedules.filter(function(Schedule){

                let stdepartment = Schedule.department.toLowerCase();
              let styear = Number(Schedule.year);
              let stsemester = Number(Schedule.semester);
              let stsection = Schedule.section.toLowerCase();
              let stdegree = Schedule.degree.toLowerCase();

                return (department === "" || stdepartment === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection === section.toLowerCase()) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (semester === "" || stsemester === Number(semester));
            });
        }
    },
      filteredMySchedules(){
        let degree = this.degree;
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;
        let myID = this.authenticateduser.id;

        if (degree === "" && department === "" && year === "" && semester === "" && section === "") {
            return this.Schedules;
        }else{
            return this.Schedules.filter(function(Schedule){

                let stdepartment = Schedule.department.toLowerCase();
              let styear = Number(Schedule.year);
              let stsemester = Number(Schedule.semester);
              let stsection = Schedule.section.toLowerCase();
              let stdegree = Schedule.degree.toLowerCase();
              let authid = Schedule.staff_id;

                return (department === "" || stdepartment === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection === section.toLowerCase()) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (semester === "" || stsemester === Number(semester))
            && (Number(authid) === myID);
            });
        }
    }

    }
}
</script>

<style scoped>
.hover-text{
    display:none;
    transition: all ease-in .2s;
    position: absolute;
    margin-top: -50px;
}

.hover-blk:hover .hover-text{
    display:block;
}
.schedule-list li{
    list-style: none;
    border: 1px solid #f2f2f2;
    width: 150px;
    padding: 10px;
    box-shadow: 1px 1px 2px;
    display: inline-block;
    cursor: pointer;
}
.schedule-list li:not(:last-child){
    margin-right: 20px;
}
.schedule-list li.highlight{
    border: 2px solid #00AAFF;
}
</style>
