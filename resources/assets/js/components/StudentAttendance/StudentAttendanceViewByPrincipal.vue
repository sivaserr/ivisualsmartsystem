<template>
    <div class="student-day-at">
<div class="row">
    <form @submit.prevent="GetAllDepartmentDatas()" v-if="viewDepartData === false">
        <h3>Department wise Day Attendance</h3>
        <div class="col-sm-2" style="width:150px;">
                <label for="date">Select date</label>
                <input required="true" type="date" id="date" v-model="date" name="date" max="3000-12-31" min="1000-01-01" class="form-control">
            </div>
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

            <button type="submit" class="btn btn-primary" style="margin-top:25px;">Submit</button>
    </form>
      
</div>

<div class="view-data" v-if="viewDepartData">
    <h2 style="text-transform:uppercase;">{{degree}}-{{department}} ON {{date}}</h2>
    <p class="btn btn-primary btn-back" @click="viewDepartData = false">Back</p>
    <div class="each-class" v-for="GotData in GotDatas" v-if="GotData.students.length > 0">
        <h3 style="text-transform:uppercase;">{{GotData.year}} Year {{GotData.section}} Section</h3>

        <table class="table">
            <thead>
                <th>
                    Student
                </th>
                <th>
                    1) <ul>
                        <li v-for="sub in getSubject(1,GotData.hours)">{{sub}}</li>
                    </ul>
                </th>
                <th>
                    2) <ul>
                        <li v-for="sub in getSubject(2,GotData.hours)">{{sub}}</li>
                    </ul>
                </th>
                <th>
                    3) <ul>
                        <li v-for="sub in getSubject(3,GotData.hours)">{{sub}}</li>
                    </ul>
                </th>
                <th>
                    4) <ul>
                        <li v-for="sub in getSubject(4,GotData.hours)">{{sub}}</li>
                    </ul>
                </th>
                <th>
                    5) <ul>
                        <li v-for="sub in getSubject(5,GotData.hours)">{{sub}}</li>
                    </ul>
                </th>
                <th>
                    6) <ul>
                        <li v-for="sub in getSubject(6,GotData.hours)">{{sub}}</li>
                    </ul>
                </th>
                <th>
                    7) <ul>
                        <li v-for="sub in getSubject(7,GotData.hours)">{{sub}}</li>
                    </ul>
                </th>
            </thead>
            <tbody>
                <tr v-for="student in GotData.students">
                    <td>
                        ({{student.reg_no}}) {{student.name}}
                    </td>
                    <td>
                       <span :class="DiffClass(getAttendance(student,1,GotData.records))">{{getAttendance(student,1,GotData.records)}}</span> 
                    </td>
                    <td>
                        <span :class="DiffClass(getAttendance(student,2,GotData.records))">{{getAttendance(student,2,GotData.records)}}</span> 
                    </td>
                    <td>
                        <span :class="DiffClass(getAttendance(student,3,GotData.records))">{{getAttendance(student,3,GotData.records)}}</span> 
                    </td>
                    <td>
                        <span :class="DiffClass(getAttendance(student,4,GotData.records))">{{getAttendance(student,4,GotData.records)}}</span> 
                    </td>
                    <td>
                        <span :class="DiffClass(getAttendance(student,5,GotData.records))">{{getAttendance(student,5,GotData.records)}}</span> 
                    </td>
                    <td>
                        <span :class="DiffClass(getAttendance(student,6,GotData.records))">{{getAttendance(student,6,GotData.records)}}</span> 
                    </td>
                    <td>
                        <span :class="DiffClass(getAttendance(student,7,GotData.records))">{{getAttendance(student,7,GotData.records)}}</span> 
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</div>
       
    </div>
</template>

<script>
export default {
    data(){
        return {
            showAllDatas:false,
            date:"",
            degree: "",
            viewDepartData:false,
            degree_options:[
                    {text:'BE', value:'be'},
                    {text:'ME', value:'me'},
                    {text:'B-TECH', value:'btech'},
                    {text:'M-TECH', value:'mtech'},
                    {text:'MBA', value:'mba'},
                    {text:'MCA', value:'mca'},
                ],
                department:"",
                department_options:[
                    {degree:'be', text:'ECE', value:'ece'},
                    {degree:'be', text:'EEE', value:'eee'},
                    {degree:'be', text:'CSE', value:'cse'},
                    {degree:'be', text:'MECH', value:'mech'},
                    {degree:'be', text:'CIVIL', value:'civil'},
                    {degree:'be', text:'AUTOMOBILE', value:'automobile'},
                    {degree:'b-tech', text:'BTECH-IT', value:'btech-it'},
                    {degree:'me', text:'ME-CSE', value:'me-cse'},
                    {degree:'me', text:'ME-CS', value:'me-cs'},
                    {degree:'me', text:'ME-Cem', value:'me-cem'},
                    {degree:'me', text:'ME-EST', value:'me-est'},
                    {degree:'me', text:'ME-ISE', value:'me-ise'},
                    {degree:'me', text:'ME-PED', value:'me-ped'},
                    {degree:'me', text:'ME-SE', value:'me-se'},
                    {degree:'me', text:'ME-VLSI', value:'me-vlsi'},
                    {degree:'m-tech', text:'MTECH-IT', value:'mtech-it'},
                    {degree:'mba', text:'MBA', value:'mba'},
                    {degree:'mca', text:'MCA', value:'mca'}
                ],
                 year_options:[
                    {text:'1', value:'1'},
                    {text:'2', value:'2'},
                    {text:'3', value:'3'},
                    {text:'4', value:'4'}
                ],
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
                section_options:[
                    {text:'a', value:'a'},
                    {text:'b', value:'b'},
                    {text:'c', value:'c'}
                ],
                gotAcademicData:[],
                gettingData:[],
                GotDatas:[],
        }
    },
    props:['authenticateduser','authrole'],
    created(){
        this.fetchSem();
    },
    methods:{
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
    getAttendance(Student,AtHour,ATDatas){
            var status = [];
            var aData;
            var child;
         
      aData = ATDatas;
      for (var child in aData) {
        //   if(this.ShowAllSubjects === false){
        //       if (aData[child].student_id === Student.id 
        //       && aData[child].date === this.date 
        //       && aData[child].hour === AtHour
        //       && Fletters.indexOf(aData[child].schedule_id) !== -1) {
        //   status = aData[child].status;
        // }
        //   }else{
        if (aData[child].student_id === Student.id && aData[child].date === this.date && aData[child].hour === AtHour) {
          status = aData[child].status;
        }
          }
    //   }
      if (status) {
        return status;
      } else {
        return "null";
      }
},
        getSubject(hr,hours){
            let SUBS = [];
            for (let index = 0; index < hours.length; index++) {
                const element = hours[index];
                if(Number(element.hour) == Number(hr)){
                    SUBS.push(element.subject_identity);
                }
            }
            if(SUBS.length > 0){
                return SUBS;
            }else{
                return 'null';
            }
        },
         fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                this.gotAcademicData = data;
            })
            .catch(err => console.log(err));
        },
       GetAllDepartmentDatas(){
           let Years = this.year_options;
           let Sections = this.section_options;
           let getDatas = [];
           for (let index = 0; index < Years.length; index++) {
               const elementY = Years[index];
               for (let i = 0; i < Sections.length; i++) {
                   const elementS = Sections[i];
                   getDatas.push({year:elementY.value,section:elementS.value});
               }
           }
           this.gettingData = getDatas;

           let formData = {
                date: this.date,
                degree: this.degree.toLowerCase(),
                department: this.department.toLowerCase(),
                getdatas:getDatas
           }
    console.log(formData);
           fetch('/api/getalldepartmentdatas',{
               method: 'POST',
               body: JSON.stringify(formData),
               headers:{
                   'content-type': 'application/json'
               }
           }).then(res => { return res.json(); })
           .then(data => {
               console.log(data);
               for (let index = 0; index < data.length; index++) {
                   const element = data[index].students.sort(function(a, b) {
    return a.reg_no - b.reg_no;
});
                   
               }
               this.GotDatas = data;
               this.viewDepartData =true;
           }).catch(err => console.log(err))

           
       }
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
    }
}
</script>

