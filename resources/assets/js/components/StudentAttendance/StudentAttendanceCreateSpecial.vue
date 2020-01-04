<template>
<div class="home" >

    <!-- OVERVIEW -->
<div class="panel panel-headline">
    <div class="panel-heading">
        <h3 class="panel-title">Make Attendance for Class <span v-if="department">for</span> {{department}} {{year}} <span v-if="year">year</span> {{section}} <span v-if="section">Section</span> <span v-if="date">on</span> {{date}} </h3>
    <!-- <h1>{{displayDate()}}</h1> -->
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="form-create" v-if="showForm">
<form @submit.prevent="createDayAttendancePage()">


                        <div class="select-class-only">
                            <ul class="schedule-list">
                            <li @click="setAllDatas(MyClass)" :class="{highlight:MyClass.id == selectedClass}" v-for="MyClass in filterPermissions" v-bind:key="MyClass.id">
                                <span style="text-transform:uppercase;">{{MyClass.degree}}-{{MyClass.department}}</span><br>
                                <span>{{MyClass.year}} year {{MyClass.section}} Section</span>
                            </li>
                            </ul>
                        </div>
                          <div class="selest_department" style="padding-bottom:20px;">
        <label>Select Masters Department</label>
        <select v-model="selestMeDapartment" class="form-control" style="width:150px;">
            <option v-for="usersMasterDept in usersMasterDepts()">{{usersMasterDept}}</option>
        </select>
    </div>
     <div class="select-class-only">
                            <ul class="schedule-list">
                            <li @click="setAllDatas(MyClass)" :class="{highlight:MyClass.id == selectedClass}" v-for="MyClass in filterPermissions2" v-bind:key="MyClass.id">
                                <span style="text-transform:uppercase;">{{MyClass.degree}}-{{MyClass.department}}</span><br>
                                <span>{{MyClass.year}} year {{MyClass.section}} Section</span>
                            </li>
                            </ul>
                        </div>
                        <div class="row">
        <div class="col-sm-2" v-if="AlterStaff === false">
                <label for="day" class="">Day Order</label>
            <select required="true" @change="emptyList()" v-model="day" class="form-control" id="day" name="day">
                <option v-for="day_option in day_options" v-bind:key="day_option.value">{{day_option.text}}</option>
            </select>
            <span class="text-primary">{{PreviousDayOrder}}</span>
            <br>
            </div>
                            <div class="col-sm-2">
                <label for="date">Date </label>
                <input  style="min-width: 151px;" required="true" type="date" id="date"  v-model="date" name="date" :max="displayCurrentDate()" :min="AcademicS.sem_strt" class="form-control">
            <span class="text-primary">{{PreviousDateA}}</span>
            </div>
                        </div>
                        
                        <div style="height:250px;" v-if="filterSchedules.length > 0">
    <label>Select Hours</label><br>
    <vue-multi-select
      v-model="selected_hours_toat"
      search
      historyButton
      :options="Scheduleoptions"
      :filters="Schedulefilters"
      :btnLabel="btnLabel"
      @open="SCopen"
      @close="SCclose"
      :selectOptions="Sdata" />
  </div>
                <div style="height:250px;" v-if="filteredStudents.length > 0">
    <label>Select Hour Students</label><br>
    <vue-multi-select
      v-model="selected_hour_students"
      search
      historyButton
      :options="Studentoptions"
      :filters="Studentfilters"
      :btnLabel="studentbtnLabel"
      @open="STopen"
      @close="STclose"
      :selectOptions="SelectStudentdata" />
  </div>


<div class="select" style="width:120px;margin-bottom:30px;">
    <label>Select Status</label>
  <select v-model="Sstatus" class="form-control">
      <option v-for="atStatuse in atStatuses">{{atStatuse.value}}</option>
  </select>
</div>


  <button class="btn btn-primary" type="submit">Submit</button>
</form>



            </div>  
            <div class="create-attendance" v-if="showForm === false">

<a class="btn btn-primary back-btn" href="">Back</a>



            </div>
        </div>
    </div>
</div>

</div>
</template>


<script>
// for date format
window.moment = require('moment');
import vueMultiSelect from 'vue-multi-select';
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
            showClasse:[],
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
            selectedClass:"",
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
                selestMeDapartment:"",
                hideHours:'',
                SubStudents:'',
                myalternatives:[],
                processing:false,
                PreviousDayOrder:"",
                PreviousDateA:"",
                AcademicS:"",
                SubjectName:"",
                selected_hours_toat:[],
                selected_hour_students:[],
                // MULTI SELECT
               
       btnLabel: values => `Selected Hours (${values.length})`,
      name: 'Hours',
      values: [],
      Sdata: [{
        name: `Day Hours`,
        list: [],
      }],
       Schedulefilters: [{
        nameAll: 'Select all',
        nameNotAll: 'Deselect all',
        func() {
          return true;
        },
      }],
      Scheduleoptions: {
        renderTemplate(elem) { return `H:${elem.hour} (${elem.subject_name})`; },
        labelName: 'subject_name',
        // labelList: 'elements',
        multi: true,
        groups: true,
      },
 studentbtnLabel: values => `Selected Students (${values.length})`,
      name: 'Students',
      values: [],
      SelectStudentdata: [{
        name: `Students`,
        list: [],
      }],
       Studentfilters: [{
        nameAll: 'Select all',
        nameNotAll: 'Deselect all',
        func() {
          return true;
        },
      }],
      Studentoptions: {
        renderTemplate(elem) { return `${elem.name} (${elem.reg_no})`; },
        labelName: 'student_regno',
        // labelList: 'elements',
        multi: true,
        groups: true,
      },
      

        }
    },
    created(){
        this.getAllShedules();
        this.getAllStudents();
        this.getAllSubjects();
        this.EditATpermissions();
        this.fetchSem();
    },
    mounted(){
          this.showClasses();
    },
     components: {
    vueMultiSelect,
  },
    props:['authenticateduser', 'authrole'],
   
    methods:{
        displayCurrentDate(){
            return moment(Date()).format('YYYY-MM-DD');
        },
emptyList(){
this.Sdata[0].list = this.filterSchedules;
this.selected_hours_toat = [];
this.selected_hour_students = [];
this.SelectStudentdata[0].list = this.filteredStudents;
},
createDayAttendancePage(){

let formdata = {
    selectedhours:this.selected_hours_toat,
    selectedstudents:this.selected_hour_students,
    day:this.day,
    date:this.date,
    degree:this.degree,
    department:this.department,
    year:this.year,
    semester:this.semester,
    section:this.section,
    attendancetakingstaff:this.authenticateduser.id,
    selectedatstatus:this.Sstatus
}

console.log(formdata);
let loader = this.$loading.show({
                  // Optional parameters
                  container: this.fullPage ? null : this.$refs.formContainer,
                  canCancel: true,
                  onCancel: this.onCancel,
                });
 fetch('/api/student/specialattendance', {
                    method: "post",
                    body: JSON.stringify(formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    console.log(data);

                    if(data){
                         loader.hide();
                         if(data['status'] === 'success'){
                             alert(data['message']);
                         }
                    }

                }).catch(err => console.log(err));




},








        // shwHours(){
        //     if(this.filterSchedules.length > 0){
        //         this.Sdata.list = this.filterSchedules;
        //         this.SelectStudentdata.list
        //     }
            
        // },
        fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                this.AcademicS = data;
            })
            .catch(err => console.log(err));
        },
    
      
      
        displayDate(){
            return moment(Date()).format('DD-MM-YYYY');
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

// GET ALL SCHEDULES
        getAllShedules(){
            fetch('/api/schedule/show')
            .then(res => { return res.json(); })
                .then(data => {
                    this.Schedules = data;
                    //console.log(data);
                }).catch(err => console.log(err));
        },


        placeAllpresent(){
            let Students = this.filteredStudents;

            for (let i = 0; i < Students.length; i++) {
                const element = Students[i];
                element.attendance_status = 'present';
            }
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
            },
             showClasses(){
                fetch('/api/studentEditPermissions/staff/'+this.authenticateduser.id+'')
            .then(res => { return res.json(); })
                .then(data => {
                    this.showClasse = data;
                    console.log(data);
                }).catch(err => console.log(err));
    },
    setAllDatas(MyClass){
            this.selectedClass = MyClass.id;
            this.year = MyClass.year;
            this.degree = MyClass.degree.toUpperCase();
            this.department = MyClass.department.toUpperCase();
switch (Number(MyClass.semester)) {
    case 2:
        this.semester = 1;
        break;
 case 4:
        this.semester = 3;
        break;
         case 6:
        this.semester = 5;
        break;
         case 8:
        this.semester = 7;
        break;
        default:
            this.semester = MyClass.semester;
        break
}

        // this.semester = MyClass.semester;
        this.section = MyClass.section;
        this.selected_hours_toat = [];
        this.selected_hour_students = [];
        this.Sdata[0].list = this.filterSchedules;
        this.SelectStudentdata[0].list = this.filteredStudents;
        },
              STopen() {
                  
    console.log('opened');

    },
    STclose() {
        let studs = this.selected_hour_students;
let stdsarray = [];

studs.forEach(element => {
    stdsarray.push(element.id);
});
this.selected_hour_students = studs;
    console.log(studs);

    },
            SCopen() {
                
    console.log('opened');

    },
    SCclose() {
    console.log('closed');

    },
     usersMasterDepts(){
            if(this.authenticateduser.working_department_me !== null){
                let mArr = this.authenticateduser.working_department_me;

            mArr = mArr.split('&&');
            return mArr;
            }else{
                return [];
            }
            
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
  
       
        if (degree === "" && department === "" && year === "" && semester === "" && section === "" && day === "") {
            return this.Schedules;
        }else{
            let sche = this.Schedules.filter(function(Schedule){

                let stdepartment = Schedule.department.toLowerCase();
                let styear = Number(Schedule.year);
                let stsemester = Number(Schedule.semester);
                let stsection = Schedule.section.toLowerCase();
                let stdegree = Schedule.degree.toLowerCase();
                let stday = Schedule.day.toLowerCase();
    

                return (
                    (degree === "" || stdegree === degree)
                    && (department === "" || stdepartment === department)
                    && (year === "" || styear === year)
                    && (semester === "" || stsemester === semester)
                    && (section === "" || stsection === section)
                    && (day === "" || stday === day)
                    
                    );
            });
            // if(sche.length > 0){
            //     this.Sdata[0].list = sche;
            // }
            return sche;
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
            return this.Subjects;
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


        
        if(department === "" && year === "" && section === "" && degree === "" && semester === ""){
          return this.Studs;
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


            return ((department === "" || stdepartment === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (section === "" || stsection === section.toLowerCase()) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (semester === "" || stsemester === Number(semester)));
          }).sort(function(a, b) {
    return a.reg_no - b.reg_no;
});
            }

          
          }
        },

      filterPermissions(){
        let EditATPermissions = this.EditATPermissions;

        let department = this.authenticateduser.working_department.toLowerCase();

        if (department === "") {
            return [];
        }else{
            return this.EditATPermissions.filter(function(EditATPermission){

                let stdepartment = EditATPermission.department.toLowerCase();
                return (
                    (department === "" || stdepartment === department)
                );
            })
        }

    },
         filterPermissions2(){
        let EditATPermissions = this.EditATPermissions;

        let department = this.selestMeDapartment.toLowerCase();

        if (department === "") {
            return [];
        }else{
            return this.EditATPermissions.filter(function(EditATPermission){

                let stdepartment = EditATPermission.department.toLowerCase();
                return (
                    (department === "" || stdepartment === department)
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