<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Add Subject</h3>
	</div>
	<div class="panel-body">
		<div class="row">
         
                     <div class="create-subjects">
                <form @submit.prevent="CreateSubject()">
                    <div class="container" v-if="SelectionBar === true">
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
				<option v-for="department_option in department_options" v-bind:key="department_option.value">{{department_option.value}}</option>
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
            <p style="color:red;text-weight:bold;">{{formError}}</p>
            <p class="btn btn-primary" @click="selctionSubmit()">Submit</p>
                    </div>
                    </div>
                    <div class="selectionBar" v-if="SelectionBar === false">
                       

                        <!-- SUBJECT LISTING -->
            <div class="subjects-listing table-responsive">
                 <div class="table-subs">
                <table class="table">
										<thead>
											<tr>
												<th>Subject Name</th>
												<th>Subject Id</th>
												<th>Staff ID</th>
                                                <th>Staff Name</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="filterSubject in filterSubjects" v-bind:key="filterSubject.id">
												<td>{{filterSubject.subject_name}}</td>
												<td>{{filterSubject.subject_id}}</td>
												<td>{{getStaffEID(filterSubject.user_id)}}</td>
                                                <td>{{filterSubject.staff_name}}</td>
											</tr>
										</tbody>
									</table>
                 </div>
            </div>
            <!-- SUBJECT LISTING -->
                    
                        <h3>Create subject</h3>
                    <label for="subject_name" class="">Subject Name</label>
                    <input required="true" v-model="subject_name" type="text" class="form-control" placeholder="Subject Name" id="subject_name" name="subject_name">
                    <br>
                    <label for="subject_id" class="">Subject ID</label>
                    <input required="true" v-model="subject_id" type="text" class="form-control" placeholder="Subject ID" id="subject_id" name="subject_id">
                    <br>
                    <label for="staff_id" class="">Staff ID</label>
                    <input required="true" v-model="staff_id" type="text" class="form-control" placeholder="Staff ID" id="staff_id" name="staff_id">
                    <br>
                    
                    <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                   
                </form>
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
            Staffs:[],
            subject_name:'',
            subject_id:'',
            staff_id:'',
            Subjects:[],
            SelectionBar:true,
            //dropdowns
            degree:'',
                degree_options:[
                    {text:'BE', value:'be'},
                    {text:'ME', value:'me'}
                ],
                 bachlers_departments:["ece","eee","mech","cse","it","civil","automobile"],
            masters_departments:["me-cse","me-cem","me-est","me-ise","me-ped","me-se","me-vlsi","mtech-it","mba"],
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
                formError:''
        }
    },
     props:['authenticateduser', 'authrole'],
    mounted(){
        this.getAllSubjects();
        this.hodandStaffRole();
        this.getAllStaffs();
    },
    methods:{
        getStaffEID(user_id){
            var status ='';
            var aData;
            var child;
      aData = this.Staffs;
      for (var child in aData) {
        if (aData[child].id == user_id) {
          status = aData[child].eid;
        }
      }
      if (status) {
        return status;
      } else {
        return "null";
      }
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
        selctionSubmit: function() {
            if(this.degree === "" && this.department === "" && this.year === "" && this.semester === "" && this.section === ""){
                this.formError = "Please fill all fields in the form!";
            }else{
                this.SelectionBar=false;
            }
            
        },
        CreateSubject(){
                let Formdata = {
                    subject_name:this.subject_name,
                    subject_id:this.subject_id,
                    staff_id:this.staff_id,
                    degree:this.degree,
                    department:this.department,
                    year:this.year,
                    semester:this.semester,
                    section:this.section,
                }

                fetch('/api/schedule/subject/store', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    
                    console.log(data);
                }).catch(err => {
                    console.log(err);
                });
                this.getAllSubjects();

            },

            getAllSubjects(){
                fetch('/api/schedule/subject/show')
                .then(res => { return res.json(); })
                .then(data => {
                    this.Subjects = data;
                    console.log(data);
                }).catch(err => console.log(err));
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
                    (degree === "" || degree === subject.degree)
                    && (department === "" || department === subject.department)
                    && (year === "" || Number(year) === subject.year)
                    && (semester === "" || Number(semester) === subject.semester)
                    && (section === "" || section === subject.section)
                    );
            })
        }

    }
    
}
}
</script>

