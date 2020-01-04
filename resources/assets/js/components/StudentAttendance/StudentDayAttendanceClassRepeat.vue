<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline" v-if="filterSchedules.length > 0">
	<div class="panel-heading">
		<h3 class="panel-title">Student Attendance View for <span style="text-transform:uppercase;"> {{degree}}<span v-if="degree">-</span>{{department}} {{year}} <span v-if="year">Year</span> {{section}} <span v-if="section">Section</span> <span v-if="date">on</span> {{date}} </span></h3>
	</div>
	<div class="panel-body">
       
    <div class="row">
        <!-- <div class="back-div" style="margin-bottom:20px;">
            <a class="btn btn-primary back-btn" href="">Back</a>
            <button class="btn btn-primary" @click="onexport()">download</button>
        </div>
         -->
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
          
            gotAcademicData:[],
            ShowAllSubjects:true,
            itsMySchedule:[],
            // date:'',
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
            // degree:'',
                degree_options:[
                    {text:'BE', value:'be'},
                    {text:'ME', value:'me'},
                    {text:'B-TECH', value:'btech'},
                    {text:'M-TECH', value:'mtech'},
                    {text:'MCA', value:'mca'},
                ],
                // department:'',
                department_options:[
                    {text:'ECE', value:'ece'},
                    {text:'EEE', value:'eee'},
                    {text:'CSE', value:'cse'},
                    {text:'MECH', value:'mech'},
                    {text:'IT', value:'it'},
                    {text:'CIVIL', value:'civil'},
                    {text:'AUTO', value:'auto'},
                    {text:'ME-CSE', value:'me-cse'},
                    {text:'ME-CS', value:'me-cs'},
                    {text:'ME-Cem', value:'me-cem'},
                    {text:'ME-EST', value:'me-est'},
                    {text:'ME-ISE', value:'me-ise'},
                    {text:'ME-PED', value:'me-ped'},
                    {text:'ME-SE', value:'me-se'},
                    {text:'ME-VLSI', value:'me-vlsi'},
                    {text:'BTECH-IT', value:'btech-it'},
                    {text:'MTECH-IT', value:'mtech-it'},
                    {text:'MBA', value:'mba'}
                ],
                // year:'',
                year_options:[
                    {text:'1', value:'1'},
                    {text:'2', value:'2'},
                    {text:'3', value:'3'},
                    {text:'4', value:'4'}
                ],
                // semester:'',
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
                // section:'',
                section_options:[
                    {text:'a', value:'a'},
                    {text:'b', value:'b'},
                    {text:'c', value:'c'}
                ],
                MyClasses:[]
        }
    },
          props:['authenticateduser',
           'authrole',
           'degree',
            'department',
            'year',
            'semester',
            'section',
            'date',
           ],
    mounted(){
        this.getAllStudents();
        this.getAllShedules();
        this.hodandStaffRole();
        this.EditATpermissions();
       
        this.getMySchedules();
        this.showStaffSubjects();
        this.fetchSem();
    },
    created(){
         this.EditATpermissions();
        
         this.getMySchedules();
         this.getAttendances();
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
     
      
           if(this.ClassAdvisors.length >! 0 || this.authrole === 1)
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
let filenme = 'Staff-Attendance-overall.xlsx';
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
                    degree:this.degree,
                    department:this.department,
                    year:this.year,
                    semester:this.semester,
                    section:this.section
                }
                
                fetch('/api/student/attendance/view/byday', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {

                   
                    // console.log(data);
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

                // if(this.AtRecords.length === 0){
                //     alert('No Attendances were made for this date');
                // }

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

              let stdepartment = student.department.toString().toLowerCase();
              let styear = Number(student.year);
              let stsemester = Number(student.semester);
              let stsection = student.section.toLowerCase();
              let stdegree = student.degree.toLowerCase();


            return (department === "" || stdepartment === department) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection === section.toLowerCase()) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (semester === "" || stsemester === Number(semester));


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

                let stdepartment = Schedule.department.toString().toLowerCase();
              let styear = Number(Schedule.year);
              let stsemester = Number(Schedule.semester);
              let stsection = Schedule.section.toLowerCase();
              let stdegree = Schedule.degree.toLowerCase();

                return (department === "" || stdepartment === department.toString().toLowerCase()) 
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

                let stdepartment = Schedule.department.toString().toLowerCase();
              let styear = Number(Schedule.year);
              let stsemester = Number(Schedule.semester);
              let stsection = Schedule.section.toLowerCase();
              let stdegree = Schedule.degree.toLowerCase();
              let authid = Schedule.staff_id;

                return (department === "" || stdepartment === department.toString().toLowerCase()) 
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
