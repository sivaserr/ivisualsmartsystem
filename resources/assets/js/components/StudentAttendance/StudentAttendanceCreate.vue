<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
    <div class="panel-heading">
        <h3 class="panel-title">Student Attendance <span v-if="department">for</span> {{department}} {{year}} <span v-if="year">year</span> {{section}} <span v-if="section">Section</span> <span v-if="date">on</span> {{date}} For {{hour}} Hour</h3>
    <!-- <h1>{{displayDate()}}</h1> -->
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="form-create" v-if="showForm">
            <form @submit.prevent="createDateandHour()">
                 <div class="row">
                    <div class="alter-selector">
                        <label class="fancy-checkbox">
                        <input type="checkbox" id="checkbox" v-model="AlterStaff">
                        <span>Altered Staff ?</span>
                    </label>
                        <!-- <input type="checkbox" id="checkbox" v-model="AlterStaff"> -->
                        <!-- <label for="checkbox">{{ AlterStaff }}</label> -->
                    </div>
                    <!-- <div class="alter-selector">
                        Elective OR Lab ?
                        <input type="checkbox" id="checkbox_Elective_Lab" v-model="checkbox_Elective_Lab">
                    </div> -->
                <div class="select-hour" v-if="AlterStaff === false">
                    <h4 v-if="hideHours" style="margin-left: 20px;">Select your Subject to make Attendances</h4>
                    <ul class="schedule-list" v-if="hideHours">
                    <li @click="setAllDatas(MySchedule)" :class="[{highlight:MySchedule.id == selected}, {done:MySchedule.attendance_made == true}, {notdone:MySchedule.attendance_made != true}]" v-for="MySchedule in showHours" v-bind:key="MySchedule.id">
                        <span style="text-transform:uppercase;">{{MySchedule.day}} - 
                        {{MySchedule.hour}}</span><br>
                        {{MySchedule.subject_name}}<br>
                        <span style="text-transform:uppercase;">{{MySchedule.degree}}-{{MySchedule.department}}-{{MySchedule.year}}-{{MySchedule.section}}</span><br>
                    </li>
                    </ul>
                </div>

                </div>
                <div class="row" v-if="AlterStaff === true">
                <h4 style="margin-left: 20px;">Your todays Alternatives</h4>
                <p v-if="myalternatives.length <= 0"  style="margin-left: 20px;">No Alternates found or approved</p>
                
                <ul class="schedule-list" v-if="hideHours == true">
                    <li @click="setAllDatas(MySchedule)" :class="{highlight:MySchedule.id == selected}" v-for="MySchedule in myalternatives" v-bind:key="MySchedule.id">
                        <span style="text-transform:uppercase;">{{MySchedule.day}} - 
                        {{MySchedule.hour}}</span><br>
                        {{MySchedule.subject_name}}<br>
                        <span style="text-transform:uppercase;">{{MySchedule.degree}}-{{MySchedule.department}}-{{MySchedule.year}}-{{MySchedule.section}}</span><br>
                    </li>
                </ul>
   
            
            </div>
            <div class="row">
                <div class="col-sm-2" v-if="AlterStaff === false">
                <label for="day" class="">Day Order</label>
            <select  disabled required="true" v-model="day" class="form-control" id="day" name="day">
                <option v-for="day_option in day_options" v-bind:key="day_option.value">{{day_option.text}}</option>
            </select>
            <span class="text-primary">{{PreviousDayOrder}}</span>
            <br>
            </div>
        
            
            <div class="col-sm-2">
                <label for="date">Date </label>
                <input @change="ChangeDate()" style="min-width: 151px;" required="true" type="date" id="date" v-model="date" name="date" :max="displayCurrentDate()" :min="AcademicS.sem_strt" class="form-control">
            <span class="text-primary">{{PreviousDateA}}</span>
            </div>
            <div class="col-sm-6">

            </div>
            </div>
            <div class="row">
                <br>
                <button :disabled="disableAttendance()" type="submit" class="btn btn-primary" style="margin-left: 17px;">Submit</button>
                <br><br>
                <p class="text-muted" style="margin-left: 15px;">By clicking submit this hour is added as working hours</p>
            </div>
            </form> 
            </div>  
            <div class="create-attendance" v-if="showForm === false">

<a class="btn btn-primary back-btn" href="">Back</a>

                <button @click="createAllStudentHour()" class="btn btn-primary btn-allpresent" >Save</button>
<button class="btn btn-primary" @click="placeAllpresent()">All Present</button>

<div class="over-all-count">
    <ul>
        <li ><strong class="text-success">{{presentCounts[0]}}</strong> No of Present </li>
        <li ><strong class="text-danger">{{presentCounts[1]}}</strong> No of Absent </li>
        <!-- <li ><strong class="text-warning">{{NoLeave.length}}</strong> No of Leave </li> -->
        <li ><strong class="text-info">{{presentCounts[3]}}</strong> No of On Duty </li>
    </ul>
</div>

                <table class="table table-hover">
                    <thead>
                        <th>S.No</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Change Status</th>
                    </thead>
                    <tbody>
<tr v-for="(student,sl_no) in filteredStudents" v-bind:key="student.id" >
                            <td>{{1+sl_no}}</td>
                            <td>{{student.reg_no}}</td>
                            <td>{{student.name}}</td>
                            <td>
                                <span v-if="student.attendance_status === 'present'" class="label label-success">Present</span>
                                <span v-if="student.attendance_status === 'absent'" class="label label-danger">Absent</span>
                                <span v-if="student.attendance_status === 'leave'" class="label label-warning">Leave</span>
                                <span v-if="student.attendance_status === 'od'" class="label label-info">OD</span>
                            </td>
                            <td>
<select class="form-control" v-model="student.attendance_status"> 
  <option  v-for="atStatus in atStatuses">
    {{ atStatus.text }}
  </option>
</select>
<span></span>
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
// for date format
window.moment = require('moment');
export default {
    data(){
        return {
            disableAllPresent:false,

            Studs:[],
            ext_hour:'',
            subject_id:'',
            Subject_selected:'',
            Subjects:[],

            ELStudents: [],
            EditATPermissions: [],

            checkbox_Elective_Lab:false,

            showForm:true,
            Schedules:[],
            schedule_id:'',
            date:'',
            student_attendance_id:'',
            is_sheduled_staff:'',
            alternate_staff:'',
            alternate_subject:'',
            disAccess:'',
            Attendances:[],
            MySchedules:[],
            selected: undefined,
            AlterStaff:false,
selectedSubjectStaff:'',
            NoPresent : [],
    NoAbsent : [],
    NoLeave : [],
    NoOd : [],
            
            bachlers_departments:["ece","eee","mech","cse","it","civil","automobile"],
            masters_departments:["me-cse","me-cem","me-est","me-ise","me-ped","me-se","me-vlsi","mtech-it","mba"],
            Sstatus:'',
            atStatuses:[
                {text:'present', value:'present'},
                {text:'absent', value:'absent'},
                {text:'od', value:'od'}
            ],
            hour:'',
            hour_options:[
                {text:'1',value:'1'},
                {text:'2',value:'2'},
                {text:'3',value:'3'},
                {text:'4',value:'4'},
                {text:'5',value:'5'},
                {text:'6',value:'6'},
                {text:'7',value:'7'}
            ],
            day:'',
            day_options:[
                {text:'Mon',value:'mon'},
                {text:'Tue',value:'tue'},
                {text:'Wed',value:'wed'},
                {text:'Thu',value:'thu'},
                {text:'Fri',value:'fri'}
            ],
            degree:'',
                degree_options:[
                    {text:'BE', value:'be'},
                    {text:'ME', value:'me'}
                ],
                department:'',
                department_options:[
                    {text:'ECE', value:'ece'},
                    {text:'EEE', value:'eee'},
                    {text:'CSE', value:'cse'},
                    {text:'MECH', value:'mech'},
                    {text:'IT', value:'it'},
                    {text:'CIVIL', value:'civil'},
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
                hideHours:'',
                SubStudents:'',
                myalternatives:[],
                processing:false,
                PreviousDayOrder:"",
                PreviousDateA:"",
                AcademicS:"",
                SubjectName:"",
                SemSaturdays:[],
                disButn:true,
        }
    },
    created(){
        this.hodandStaffRole();
        this.getAllShedules();
        this.getAllStudents();
        this.getAllSubjects();
        this.getStaffSchedules();
        this.AlterStaffRun();
        this.EditATpermissions();
        this.getOverallATT();
        this.getMyAlternativeHours();
        this.fetchSem();
        this.fetchSemSaturDays();
        this.setDefaults();
    },
    mounted(){
         //this.setDefaults();
    },
    
    props:['authenticateduser', 'authrole'],
   
    methods:{
        displayStudentifJoineddate(student){
            if(student.joined_date === null){
                return true;
            }else if(student.joined_date >= this.date){
                return true;
            }else{
                return false;
            }
        },
        fetchSemSaturDays(){
            fetch('/api/fetchsemsaturdays')
            .then(res => res.json())
            .then(data => {
                this.SemSaturdays = data;
            })
            .catch(err => console.log(err));
        },
        fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                this.AcademicS = data;
            })
            .catch(err => console.log(err));
        },
        ChangeDate(){
             var date = new Date();
            var D = moment(date).format('YYYY-MM-DD');
            var day = new Date(this.date).getUTCDay();
            
            if([0].includes(day)){
                 this.hideHours = false;
                this.date = '';
                this.disButn = false;
                alert('You are not allowed to make attendance for sunday');
            }else if([6].includes(day) !== true){
                switch (day) {
                case 1: this.day = 'Mon'; break;
                case 2: this.day = 'Tue'; break;
                case 3: this.day = 'Wed'; break;
                case 4: this.day = 'Thu'; break;
                case 5: this.day = 'Fri'; break;
            }
             this.hideHours = true;
            }else if([6].includes(day)){
                let able = false;
                let ADay = '';
                let SemSaturdays = this.SemSaturdays;
                SemSaturdays.forEach(element => {
                    if(element.date === this.date){
                        able = true;
                        ADay = element.day;
                    }
                });
                if(able === false){
                   alert('Day order Not Assigned!'); 
                   this.day = '';
                   this.hideHours = false;
                }else{
                    this.day = ADay;
                    this.hideHours = true;
                }
            }else{
               switch (day) {
                case 1: this.day = 'Mon'; break;
                case 2: this.day = 'Tue'; break;
                case 3: this.day = 'Wed'; break;
                case 4: this.day = 'Thu'; break;
                case 5: this.day = 'Fri'; break;
            } 
             this.hideHours = true;
               
            }

                if(D > this.date){
                    this.PreviousDateA = "Not Current Date";
                }else if(D < this.date){
                    this.PreviousDateA = "You cannot make attendance for future";
                }else{
                    this.PreviousDateA = "";
                }
        },
        setDefaults(){
            console.log('setDefaults');
            var date = new Date();
            var D = moment(date).format('YYYY-MM-DD');
            this.date = D;
            var day = date.getDay();
            
            // switch (day) {
            //     case 1: this.day = 'Mon'; break;
            //     case 2: this.day = 'Tue'; break;
            //     case 3: this.day = 'Wed'; break;
            //     case 4: this.day = 'Thu'; break;
            //     case 5: this.day = 'Fri'; break;
            // }
            // this.date = D;
            if([0].includes(day)){
                this.hideHours = false;
                this.date = '';
                this.disButn = false;
                alert('You are not allowed to make attendance for sunday');
            }else if([6].includes(day) !== true){
                switch (day) {
                case 1: this.day = 'Mon'; break;
                case 2: this.day = 'Tue'; break;
                case 3: this.day = 'Wed'; break;
                case 4: this.day = 'Thu'; break;
                case 5: this.day = 'Fri'; break;
            }
             this.hideHours = true;
            }else if([6].includes(day)){
                this.date = '';
                let able = false;
                let ADay = '';
                let SemSaturdays = this.SemSaturdays;
                SemSaturdays.forEach(element => {
                    if(element.date === this.date){
                        able = true;
                        ADay = element.day;
                    }
                });
                if(able === false){
                //    alert('Day order Not Assigned!'); 
                   this.day = '';
                   this.hideHours = false;
                }else{
                    this.day = ADay;
                    this.hideHours = true;
                }
            }else{
               switch (day) {
                case 1: this.day = 'Mon'; break;
                case 2: this.day = 'Tue'; break;
                case 3: this.day = 'Wed'; break;
                case 4: this.day = 'Thu'; break;
                case 5: this.day = 'Fri'; break;
            } 
             this.hideHours = true;
               
            }
            // this.shwHours();
        },
        giveAlert(){
            let confirmm = confirm('your Attendance for the hour is saved! No of Present:' + this.NoPresent.length + ' No of Absent:' + this.NoAbsent.length + ' No of Leave:' + this.NoLeave.length + ' No of OD:' + this.NoOd.length + '');
            if(confirmm){
                window.location = "http://172.16.1.61/student/attendance/make";
            }
        },
         displayCurrentDate(){
            return moment(Date()).format('YYYY-MM-DD');
        },
        displayDate(){
            return moment(Date()).format('DD-MM-YYYY');
        },

        disableAttendance(){
            let CDate = moment(Date()).format('YYYY-MM-DD');

            if(this.date === CDate){
                return false;
            }else if(this.degree !== "" && this.department !== "" && this.year !== "" && this.semester !== "" && this.section !== ""){
                if(this.filterPermissions[0].status === 1){
                    return false;
                }else{
                     return true;
                }
            }else{
                return true;
            }
            },
        getMyAlternativeHours(){
             console.log('test');
            fetch('/api/staff/myalternatives/' + this.authenticateduser.id + '')
            .then(res => {
                return res.json();
            })
            .then(data => {
                this.myalternatives = data;
                // console.log('alter', data);
            }).catch(err => console.log(err));
        },

        AlterStaffRun(){
            // this.alternate_subject = this.AlterStaff;
            
if(this.AlterStaff === true){
    this.alternate_staff = this.authenticateduser.id
}else{
    this.alternate_staff = false;
}
        },


        shwHours(){
            
            var date = new Date();
            var D = moment(date).format('YYYY-MM-DD');
            var day = new Date(this.date).getUTCDay();
            
            if([0].includes(day)){
                this.date = '';
                this.disButn = false;
                alert('You are not allowed to make attendance for sunday');
            }else if([6].includes(day) !== true){
                switch (day) {
                case 1: this.day = 'Mon'; break;
                case 2: this.day = 'Tue'; break;
                case 3: this.day = 'Wed'; break;
                case 4: this.day = 'Thu'; break;
                case 5: this.day = 'Fri'; break;
            }
            }else if([6].includes(day)){
                let able = false;
                let ADay = '';
                let SemSaturdays = this.SemSaturdays;
                SemSaturdays.forEach(element => {
                    if(element.date === this.date){
                        able = true;
                        ADay = element.day;
                    }
                });
                if(able === false){
                //    alert('Day order Not Assigned!'); 
                   this.day = '';
                   this.date = '';
                   this.hideHours = false;
                }else{
                    this.day = ADay;
                    this.hideHours = true;
                }
            }else{
               switch (day) {
                case 1: this.day = 'Mon'; break;
                case 2: this.day = 'Tue'; break;
                case 3: this.day = 'Wed'; break;
                case 4: this.day = 'Thu'; break;
                case 5: this.day = 'Fri'; break;
            } 
            
               
            }

                if(D > this.date){
                    this.PreviousDateA = "Not Current Date";
                }else if(D < this.date){
                    this.PreviousDateA = "You cannot make attendance for future";
                }else{
                    this.PreviousDateA = "";
                }

        },

        setAllDatas(MySchedule){

                       
                this.selected = MySchedule.id;
                this.schedule_id = MySchedule.id;
                this.subject_id = MySchedule.student_subject_id;

            this.year = MySchedule.year;
            this.degree = MySchedule.degree.toUpperCase();
            this.department = MySchedule.department.toUpperCase();
            this.semester = MySchedule.semester;
            this.section = MySchedule.section;
            let daOrder = jsUcfirst(MySchedule.day);
            let converted = daOrder;
            this.day = converted;
            this.hour = MySchedule.hour;
            this.SubjectName = MySchedule.subject_name;
            
            if(this.authenticateduser.id !== this.filterSchedules[0].staff_id){
                    this.is_sheduled_staff = false;
                    this.alternate_staff = this.authenticateduser.id;
                }else{
                    this.is_sheduled_staff = true;
                    this.alternate_staff = null;
                }


function jsUcfirst(string) 
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
        },
    hodandStaffRole(){
        let w_dapart = this.authenticateduser.working_department;
        if(this.authrole === 3 || this.authrole === 4){ //if user is hod or professor show their staffs
                    // this.department = w_dapart.toUpperCase();
                    this.disAccess = true;

                    // if(this.bachlers_departments.includes(w_dapart)){
                    //     this.degree = 'BE';
                    // }else if(this.masters_departments.includes(w_dapart)){
                    //     this.degree = 'ME';
                    // }
                }
    },

getOverallATT(){
    var aData = this.filteredStudents;
    
   
},

getStudentStatus(student) {
      var status;
      var aData;
      var child;
      aData = this.Attendances;
      for (var child in aData) {
        if (aData[child].student_id === student.id) {
          status = aData[child].status;
        }
      }
      if (status) {
        return status;
      } else {
        return null;
      }
    },


// GET ALL STUDENTS
        getAllStudents(){
            fetch('/api/students')
            .then(res => {
                return res.json();
            })
            .then(data => {
                this.Studs = data;
                // console.log('studs', data);
            }).catch(err => console.log(err));
        },

         getAllSubjects(){
                fetch('/api/schedule/subject/show')
                .then(res => { return res.json(); })
                .then(data => {
                    this.Subjects = data;
                }).catch(err => console.log(err));
            },

        getSubjectId(){
            var subb;
            var staff;
                var Subs;
     
                Subs = this.filterSubjects;
                for(var child in Subs){
                    if (Subs[child].subject_name === this.Subject_selected){
                        subb = Subs[child].id;
                        staff = Subs[child].user_id;

                        this.subject_id = subb;

                        this.selectedSubjectStaff = staff;
                    }
                }
               
        },

// DISPLAY SUBJECT IN FRONT
        displaySubject(){
        let degree = this.degree.toLowerCase();
        let department = this.department.toLowerCase();
        let year = this.year;
        let semester = this.semester;
        let section = this.section;
        let day = this.day.toLowerCase();
        let hour = this.hour;
            if(degree !== "" && department !== "" && year !== "" && semester !== "" && section !== "" && day !== "" && hour !== ""){
                this.schedule_id = this.filterSchedules[0].id;
// console.log(this.authenticateduser.id);
// console.log(this.filterSchedules[0].staff_id);

                if(this.authenticateduser.id !== this.filterSchedules[0].staff_id){
                    this.is_sheduled_staff = false;
                    this.alternate_staff = this.authenticateduser.id;
                }else{
                    this.is_sheduled_staff = true;
                    this.alternate_staff = null;
                }

                return `${this.filterSchedules[0].subject_name}`;
            }else{
                return '__';
            }
        },
// GET ALL SCHEDULES
        getAllShedules(){
            fetch('/api/schedule/show')
            .then(res => { return res.json(); })
                .then(data => {
                    this.Schedules = data;
                    //console.log(data);
                }).catch(err => console.log(err));
        },

// CREATE DATE AND HOUR FOR GETTING OVERALL
        createDateandHour(){

 var date = new Date();
            var D = moment(date).format('YYYY-MM-DD');
            var day = new Date(this.date).getUTCDay();
             let able = false;
if([6].includes(day)){
               
                let SemSaturdays = this.SemSaturdays;
                SemSaturdays.forEach(element => {
                    if(element.date === this.date){
                        
                        able = true;
                        this.day = element.day;
                    }
                });
}
          if(this.degree === "" && this.day === ""){
    alert('Please Click on Your Subject and Day Order To make attendances for!')
}else{

              var date = new Date();
            var D = moment(date).format('YYYY-MM-DD');
 if(D < this.date){
                    alert('Cannot make attendances for future');
    }else if([6].includes(day) && able === false){
                   alert('Day order Not Assigned!'); 
                            
}else{
       let CNFM = false;
       this.$dialog
  .confirm(`
  <h3>Are you Sure?</h3>
  <table style='width:100%;'>
    <tbody>
        <tr>
            <td style='padding:7px;border:1px solid #333;'>Selected Date:  <br> <small style='color:#0096d9;'>${this.PreviousDateA}</small></td>
            <td style='padding:7px;border:1px solid #333;'><strong>${this.date} </strong></td>
        </tr>
        <tr>
            <td style='padding:7px;border:1px solid #333;'>Selected Hour:</td>
            <td style='padding:7px;border:1px solid #333;'><strong>${this.hour}</strong></td>
        </tr>
        <tr>
            <td style='padding:7px;border:1px solid #333;'>Selected Day Order: <br> <small style='color:#0096d9;'>${this.PreviousDayOrder}</small></td>
            <td style='padding:7px;border:1px solid #333;'><strong>${this.day}</strong></td>
        </tr>
        <tr>
            <td style='padding:7px;border:1px solid #333;'>Selected Subject:</td>
            <td style='padding:7px;border:1px solid #333;'><strong>${this.SubjectName}</strong></td>
        </tr>
    </tbody>
  </table>
  `, {
  })
  .then(dialog => {

     
   CNFM = true;

    
//             let CNFM = confirm('Make sure you have selected correct values. with this action the hour is added to overall count OR you can updated previous Attendances')

if(CNFM === true){
let loader = this.$loading.show({
                  // Optional parameters
                  container: this.fullPage ? null : this.$refs.formContainer,
                  canCancel: true,
                  onCancel: this.onCancel,
                });

               
            let Formdata = {
                    degree : this.degree.toLowerCase(),
                    department : this.department.toLowerCase(),
                    year : Number(this.year),
                    semester : Number(this.semester),
                    section : this.section,
                    subject_id: this.subject_id,
                    alternate_staff : this.authenticateduser.id,
                    alternate_subject: this.AlterStaff,
                    schedule_id:this.schedule_id,
                    date : this.date,
                    hour : this.hour,
                    is_sheduled_staff : this.is_sheduled_staff,
                }

               
    console.log(Formdata);
// if(this.AlterStaff && Number(this.selectedSubjectStaff) !== this.authenticateduser.id){
//     alert('You are not the selected subject staff');
// }else{

                fetch('/api/student/attendance/dateandhour', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    console.log(data);
                    if(data['status'] === 'failed'){
                        alert(data['message']);
                        loader.hide();
                    }else{
  if(data){
                        loader.hide();
                    }
                    this.Attendances = data['CurrentRecords'];

                    // this.ext_hour = data['ext_hour'].updated_at;

                        this.showForm = false;
                  
                    if(data['electivestudents']){

                        this.ELStudents = data['electivestudents'].sort(function(a, b) {
    return a.reg_no - b.reg_no;
});
                        // this.ELStudents = data['electivestudents']
                    }
                        this.getOverallATT();
                    }
                  
                    
                }).catch(err => {
                    
                });

                this.getSubjectStudents();
// }
}
    dialog.close();
       })
  .catch(()=>{ dialog.close();});
}
}

        },
// CREATE HOUR ATTENDANCE 
        createStudentHour(student){
            let Formdata = {
                    degree : this.degree.toLowerCase(),
                    department : this.department.toLowerCase(),
                    year : Number(this.year),
                    semester : Number(this.semester),
                    section : this.section,
                    student_id:student.id,

                    subject_id: this.subject_id,
                    alternate_staff : this.authenticateduser.id,

                    alternate_subject: this.AlterStaff,
                    
                    schedule_id:this.schedule_id,
                    date : this.date,
                    hour : this.hour,
                    status : this.Sstatus.toLowerCase()
                    
                }
                console.log(Formdata);
                fetch('/api/student/attendance/hour', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    //console.log(data);

                    this.Sstatus='';
                    
                    this.Attendances = data;
                    this.getOverallATT();
                    
                }).catch(err => {
                    
                });
                this.getOverallATT();
        },
        placeAllpresent(){
            let Students = this.filteredStudents;

            for (let i = 0; i < Students.length; i++) {
                const element = Students[i];
                element.attendance_status = 'present';
            }
        },
         createAllStudentHour(){


this.$dialog
  .confirm(`
<h2>Are you Sure?</h2>
<h4>You are making attendances on <strong>${this.date}</strong> for <strong>${this.hour}</strong> Hour</h4>
<h4>Subject:  <strong>${this.SubjectName}</strong></h4>
<p>No of Present: <strong>${this.presentCounts[0]}</strong></p>
<p>No of Absent: <strong>${this.presentCounts[1]}</strong></p>
<p>No of OD: <strong>${this.presentCounts[3]}</strong></p>
  `, {
    loader: true, // default: false - when set to true, the proceed button shows a loader when clicked.
    // And a dialog object will be passed to the then() callback
  })
  .then(dialog => {
   // Request

 let Formdata = {
                    degree : this.degree.toLowerCase(),
                    department : this.department.toLowerCase(),
                    year : Number(this.year),
                    semester : Number(this.semester),
                    section : this.section,
                    students : this.filteredStudents,
                    is_sheduled_staff : this.is_sheduled_staff,
                    subject_id: this.subject_id,
                    alternate_staff : this.alternate_staff,

                    alternate_subject: this.AlterStaff,
                    
                    schedule_id:this.schedule_id,
                    date : this.date,
                    hour : this.hour,
                    status : 'present'
                }
                console.log(Formdata);
                fetch('/api/student/attendance/hour/all', {
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
                       alert(data['message']);
                        dialog.close();

                    }else{
                         alert(data['message']);
                         dialog.close();
                    }


                    
                }).catch(err => {
                    console.log(err);
                });
                this.getAllStudents();
                this.getOverallATT();

  })
  .catch(() => {
    // Triggered when cancel button is clicked
 
    console.log('Delete aborted');
  });

           
           
        },

        getStaffSchedules(){
            fetch('/api/staff/myschedules/' +  this.authenticateduser.id + '')
            .then(res => { return res.json(); })
                .then(data => {
                    this.MySchedules = data;
                    //console.log(data);
                }).catch(err => console.log(err));
            
        },

        getSubjectStudents(){
                fetch('/api/student/subjectstudents/' +  this.selected + '')
            .then(res => { return res.json(); })
                .then(data => {
                    this.SubStudents = data;
                    console.log(data);
                }).catch(err => console.log(err));
            },

            EditATpermissions(){
                fetch('/api/studentEditPermissions')
            .then(res => { return res.json(); })
                .then(data => {
                    this.EditATPermissions = data;
                   
                }).catch(err => console.log(err));
            }

    },

    computed:{
presentCounts(){
  
 let NoPresent = [];
    let NoAbsent = [];
    let NoLeave = [];
    let NoOd = [];
var aData = this.filteredStudents;
if(aData.length > 0){
    for(var child in aData){
        switch (aData[child].attendance_status) {
            case 'present':
                NoPresent.push(aData[child]);
                break;
            case 'absent':
                NoAbsent.push(aData[child]);
                break;
            case 'leave':
                NoLeave.push(aData[child]);
                break;
            case 'od':
                NoOd.push(aData[child]);
                break;
        }
    }
    return [NoPresent.length,NoAbsent.length,NoLeave.length,NoOd.length];
}
},
        showHours(){
            let day = this.day.toLowerCase();
            let MySchedules = this.MySchedules;

            if(day === ""){
                return MySchedules;
            }else{
                return MySchedules.filter(function(sche){
                    return day === "" || day === sche.day; 
                });
            }

        },
       
        filterSchedules(){
        let Schedules = this.Schedules;
        let degree = this.degree.toLowerCase();
        let department = this.department.toLowerCase();
        let year = Number(this.year);
        let semester = Number(this.semester);
        let section = this.section.toLowerCase();
        let day = this.day.toLowerCase();
        let hour = Number(this.hour);
       
        if (degree === "" && department === "" && year === "" && semester === "" && section === "" && day === "" && hour === "") {
            return this.Schedules;
            return [];
        }else{
            return this.Schedules.filter(function(Schedule){
                let stdepartment = Schedule.department.toLowerCase();
                let styear = Number(Schedule.year);
                let stsemester = Number(Schedule.semester);
                let stsection = Schedule.section.toLowerCase();
                let stdegree = Schedule.degree.toLowerCase();
                let stday = Schedule.day.toLowerCase();
                let sthour = Number(Schedule.hour);
                return (
                    (degree === "" || stdegree === degree)
                    && (department === "" || stdepartment === department)
                    && (year === "" || styear === year)
                    && (semester === "" || stsemester === semester)
                    && (section === "" || stsection === section)
                    && (day === "" || stday.toLowerCase() === day)
                    && (hour === "" || sthour === hour)
                    );
            })
        }

    },

    filterSubjects(){
        let Subjects = this.Subjects;
        let degree = this.degree.toLowerCase();
        let department = this.department.toLowerCase();
        let year = Number(this.year);
        let semester = Number(this.semester);
        let section = this.section.toLowerCase();

        if (degree === "" && department === "" && year === "" && semester === "" && section === "") {
            // return this.Subjects;
            return [];
        }else{
            return this.Subjects.filter(function(subject){
                let stdepartment = subject.department.toLowerCase();
                let styear = Number(subject.year);
                let stsemester = Number(subject.semester);
                let stsection = subject.section.toLowerCase();
                let stdegree = subject.degree.toLowerCase();
                return (
                    (degree === "" || stdegree === degree)
                    && (department === "" || stdepartment === department)
                    && (year === "" || styear === year)
                    && (semester === "" || stsemester === semester)
                    && (section === "" || stsection === section)
                );
            })
        }

    },


    filteredStudents(){
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;
        let degree = this.degree;
        let date = this.date;


        
        if(department === "" && year === "" && section === "" && degree === "" && semester === ""){
        //   return this.Studs;
        return [];
        }else{

            if(this.ELStudents.length > 0){
                return this.ELStudents.sort(function(a, b) {
    return a.reg_no - b.reg_no;
});
                
            }else{
                return this.Studs.filter(function(student){

              let stdepartment = student.department.toLowerCase().trim();
              let styear = Number(student.year);
              let stsemester = Number(student.semester);
              let stsection = student.section.toLowerCase().trim();
              let stdegree = student.degree.toLowerCase().trim();
              let stjdate = student.joined_date;


            return ((department === "" || stdepartment === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection === section.toLowerCase()) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (semester === "" || stsemester === Number(semester))
            && (student.joined_date === null || Date(stjdate) <= Date(date)));
          }).sort(function(a, b) {
    return a.reg_no - b.reg_no;
});
            }

          
          }
        },

        filterPermissions(){
        let EditATPermissions = this.EditATPermissions;
        let degree = this.degree.toLowerCase();
        let department = this.department.toLowerCase();
        let year = Number(this.year);
        let semester = Number(this.semester);
        let section = this.section.toLowerCase();

        if (degree === "" && department === "" && year === "" && semester === "" && section === "") {
            return this.EditATPermissions;
        }else{
            return this.EditATPermissions.filter(function(EditATPermission){

                let stdepartment = EditATPermission.department.toLowerCase();
                let styear = Number(EditATPermission.year);
                let stsemester = Number(EditATPermission.semester);
                let stsection = EditATPermission.section.toLowerCase();
                let stdegree = EditATPermission.degree.toLowerCase();

                return (
                    (degree === "" || stdegree === degree)
                    && (department === "" || stdepartment === department)
                    && (year === "" || styear === year)
                    // && (semester === "" || stsemester === semester)
                    && (section === "" || stsection === section)
                );
            })
        }

    },
    }
}
</script>

<style scoped>
.over-all-count
{
    padding: 10px 0px;
}
.back-btn
{
    margin-bottom: 30px;
    float: right;
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
.schedule-list li.highlight.done{
    border: 2px solid #00AAFF;
}
.schedule-list li.highlight.notdone{
    border: 2px solid #00AAFF;
}
.disable-select
{
    display: none;
}
.alter-selector{
    margin-left: 15px;
    font-size: 20px;
    margin-bottom: 10px;
}
.over-all-count ul li{
list-style: none;
    display: inline-block;
    padding: 5px 15px;
    margin: 5px 10px;
   box-shadow: 1px 1px 2px 2px #c9e3f1;
    }
.over-all-count ul li strong{
        display: block;
    font-size: 25px;
    text-align: right;
    }
    .schedule-list li.done{
        border: 2px solid #4caf508c;
    }
    .schedule-list li.notdone{
        border: 2px solid #f4433673;
    }
    .alert-table{
        border: 1px solid #333;
    }
    .alert-table td{
        border: 1px solid #333;
    }
</style>