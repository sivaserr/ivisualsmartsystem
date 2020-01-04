<template>
<div class="home">
    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">View Schedule</h3>
	</div>
	<div class="panel-body">
		<div class="row">
            <div class="select-class"  v-if="SelectionBar === true">
                <div class="row">
            <form @submit.prevent="selctionSubmit()">
                    <div class="form-group col-sm-2">
                        <label for="degree" class="">Degree</label>
            <select required="true" v-model="degree" class="form-control" id="degree" name="degree">
                <option selected disabled>Choose</option>
				<option v-for="degree_option in degree_options" v-bind:key="degree_option.value">{{degree_option.text}}</option>
			</select>
            <br>
             </div>
                    <div class="form-group col-sm-2"> 
            <label for="department" class="">Department</label>
			<select required="true" v-model="department" class="form-control" id="department" name="department">
            <option selected disabled>Choose</option>
				<option v-for="department_option in filerDapart" v-bind:key="department_option.value">{{department_option.text}}</option>
			</select>
            <br>
             </div>
                    <div class="form-group col-sm-2"> 
                        <label for="year" class="">Year</label>
            <select required="true" v-model="year" class="form-control" id="year" name="year">
            <option selected disabled>Choose</option>
				<option v-for="year_option in year_options" v-bind:key="year_option.value">{{year_option.text}}</option>
			</select>
            <br>
             </div>
                    <div class="form-group col-sm-2"> 
            <label for="semester" class="">Semester</label>
			<select required="true" v-model="semester" class="form-control" id="semester" name="semester">
            <option selected disabled>Choose</option>
				<option v-for="semester_option in semester_options" v-bind:key="semester_option.value">{{semester_option.value}}</option>
			</select>
            <br>
             </div>
                    <div class="form-group col-sm-2"> 
            <label for="section" class="">Section</label>
			<select required="true" v-model="section" class="form-control" id="section" name="section">
            <option selected disabled>Choose</option>
				<option v-for="section_option in section_options" v-bind:key="section_option.value">{{section_option.text}}</option>
			</select>
            <br>
            <button class="btn btn-primary" >Submit</button>
                    </div>
                    
                    </form>
                    </div>
            </div>
            <div class="assign-subject" v-if="SelectionBar === false">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <th>Day/Hour</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>6</th>
                            <th>7</th>
                        </thead>
                        <tbody>
                            <!-- <tr v-for="Day in Days"> -->
                                <!-- <td  v-for="Hour in Hours">{{getSchedule(Day.value,Hour.value)}}</td> -->
                            <tr>
                                <td>Monday</td>
                                 <td><ul><li v-for="schedule in getSchedule('mon',1)" >{{schedule}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('mon',2)" >{{schedule}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('mon',3)" >{{schedule}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('mon',4)" >{{schedule}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('mon',5)" >{{schedule}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('mon',6)" >{{schedule}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('mon',7)" >{{schedule}}</li></ul></td>
                            </tr>
                            <tr>
                                <td>Tuesday</td>
                                <td><ul><li v-for="schedule in getSchedule('tue',1)" >{{schedule}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('tue',2)" >{{schedule}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('tue',3)" >{{schedule}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('tue',4)" >{{schedule}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('tue',5)" >{{schedule}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('tue',6)" >{{schedule}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('tue',7)" >{{schedule}}</li></ul></td>
                            </tr>
                            <tr>
                                <td>Wednesday</td>
                               <td><ul><li v-for="schedule in getSchedule('wed',1)" >{{schedule}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('wed',2)" >{{schedule}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('wed',3)" >{{schedule}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('wed',4)" >{{schedule}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('wed',5)" >{{schedule}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('wed',6)" >{{schedule}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('wed',7)" >{{schedule}}</li></ul></td>
                            </tr>
                            <tr>
                                <td>Thursday</td>
                                <td><ul><li v-for="schedule in getSchedule('thu',1)" >{{schedule}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('thu',2)" >{{schedule}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('thu',3)" >{{schedule}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('thu',4)" >{{schedule}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('thu',5)" >{{schedule}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('thu',6)" >{{schedule}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('thu',7)" >{{schedule}}</li></ul></td>
                            </tr>
                            <tr>
                                <td>Friday</td>
                                <td><ul><li v-for="schedule in getSchedule('fri',1)" >{{schedule}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('fri',2)" >{{schedule}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('fri',3)" >{{schedule}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('fri',4)" >{{schedule}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('fri',5)" >{{schedule}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('fri',6)" >{{schedule}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('fri',7)" >{{schedule}}</li></ul></td>
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
export default {
    data(){
        return {
            SelectionBar:true,


            Subject_selected:'',
            Subjects:[],
            Schedules:[],

            Hour_selected:'',
            Hours:[
                {text:'1', value:'1'},
                {text:'2', value:'2'},
                {text:'3', value:'3'},
                {text:'4', value:'4'},
                {text:'5', value:'5'},
                {text:'6', value:'6'},
                {text:'7', value:'7'}
            ],
            Day_selected:'',
            Days:[
                {text:'Mon', value:'mon'},
                {text:'Tue', value:'tue'},
                {text:'Wed', value:'wed'},
                {text:'Thu', value:'thu'},
                {text:'Fri', value:'fri'}
            ],
bachlers_departments:["ece","eee","mech","cse","it","civil","automobile"],
            masters_departments:["me-cse","me-cem","me-est","me-ise","me-ped","me-se","me-vlsi","mtech-it","mba"],
            degree:'',
                degree_options:[
                   {text:'BE', value:'be'},
                    {text:'ME', value:'me'},
                    {text:'B-TECH', value:'b-tech'},
                    {text:'M-TECH', value:'m-tech'},
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
                    {degree:'me', text:'ME-Cem', value:'me-cem'},
                    {degree:'me', text:'ME-EST', value:'me-est'},
                    {degree:'me', text:'ME-ISE', value:'me-ise'},
                    {degree:'me', text:'ME-PED', value:'me-ped'},
                    {degree:'me', text:'ME-SE', value:'me-se'},
                    {degree:'me', text:'ME-VLSI', value:'me-vlsi'},
                    {degree:'m-tech', text:'MTECH-IT', value:'mtech-it'},
                    {degree:'b-tech', text:'BTECH-IT', value:'btech-it'},
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
        }
    },
          props:['authenticateduser', 'authrole'],
    mounted() {
        this.getAllShedules();
        this.getAllSubjects();
        this.hodandStaffRole();
    },
    methods:{
        selctionSubmit(){
            this.SelectionBar=false;

        },


        assignSchedule(){

 var subb;
                var Subs;
     
                Subs = this.Subjects;

                for(var child in Subs){
                    if (Subs[child].subject_name === this.Subject_selected){
                        subb = Subs[child].id;
                    }
                }

                let Formdata = {
                    Subject_selected:subb,
                    Hour_selected:this.Hour_selected,
                    Day_selected:this.Day_selected.toLowerCase(),

                    degree:this.degree,
                    department:this.department,
                    year:this.year,
                    semester:this.semester,
                    section:this.section,
                }

                fetch('/api/schedule/store', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                   
                    // console.log(data);
                }).catch(err => {
                    console.log(err);
                });
                this.getAllSubjects();
                this.getAllShedules();
            },
        getAllShedules(){
            fetch('/api/schedule/show')
            .then(res => { return res.json(); })
                .then(data => {
                    this.Schedules = data;
                    // console.log(data);
                }).catch(err => console.log(err));
        },

    getAllSubjects(){
                fetch('/api/schedule/subject/show')
                .then(res => { return res.json(); })
                .then(data => {
                    this.Subjects = data;
                    // console.log(data);
                }).catch(err => console.log(err));
            },

            getSchedule(day,hour){
                var sche = [];
                var Schedules;
     
                Schedules = this.filterSchedules;

                for(var child in Schedules){
                    if (Schedules[child].day === day && Schedules[child].hour === hour){
                        sche.push(Schedules[child].subject_name);
                    }
                }

                if(sche){
                    return sche;
                }else{
                    return '__';
                }
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

},

computed:{
    filterSubjects(){
        let Subjects = this.Subjects;
        let degree = this.degree;
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;

        if (degree === "" && department === "" && year === "" && semester === "" && section === "") {
            return Subjects;
        }else{
            return Subjects.filter(function(subject){
                return (
                    (department === "" || department === subject.department)
                    && (department === "" || department === subject.department)
                    && (year === "" || Number(year) === subject.year)
                    && (semester === "" || Number(semester) === subject.semester)
                    && (section === "" || section === subject.section)
                    );
            })
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
    filterSchedules(){
        let Schedules = this.Schedules;
        let degree = this.degree;
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;

        if (degree === "" && department === "" && year === "" && semester === "" && section === "") {
            return Schedules;
        }else{
            return Schedules.filter(function(Schedule){
                return (
                    (department === "" || department === Schedule.department)
                    && (department === "" || department === Schedule.department)
                    && (year === "" || Number(year) === Schedule.year)
                    && (semester === "" || Number(semester) === Schedule.semester)
                    && (section === "" || section === Schedule.section)
                    );
            })
        }

    }
    
}


}
</script>
