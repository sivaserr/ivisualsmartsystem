<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Student Profile</h3>

        <a type="button" :disabled="disableEditIf" href="student/create" class="btn btn-primary float-right">Create Student</a>
	</div>
	<div class="panel-body">
		<div class="row">
			<!-- BASIC TABLE -->
            <div class="" v-if="edit === false">
                <div class="form-group d-select">
                    <label for="department" class="">Department</label>
			        <select v-model="Fdepartment" class="form-control" id="department" name="department">
                        <option selected disabled>Choose</option>
				        <option v-for="Fdepartment_option in Fdepartment_options" v-bind:key="Fdepartment_option.value">{{Fdepartment_option.text}}</option>
			        </select>
                </div>  
                <div class="form-group d-select">
                    <label for="year" class="">Year</label>
			        <select v-model="Fyear" class="form-control" id="year" name="year">
                        <option selected disabled>Choose</option>
				        <option v-for="Fyear_option in Fyear_options" v-bind:key="Fyear_option.value">{{Fyear_option.text}}</option>
			        </select>
                </div> 
                <div class="form-group d-select">
                    <label for="section" class="">Section</label>
			        <select v-model="Fsection" class="form-control" id="section" name="section">
                        <option selected disabled>Choose</option>
				        <option v-for="Fsection_option in Fsection_options" v-bind:key="Fsection_option.value">{{Fsection_option.text}}</option>
			        </select>
                </div> 
                <div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>S.No</th>
												<th>#Register No</th>
												<th>Name</th>
												<th>Degree</th>
												<th>Department</th>
                                                <th>Year</th>
                                                <th>Section</th>
                                                <th>Edit</th>
                                                <th>View</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(Stud,sl_no) in filteredStudents" v-bind:key="Stud.id">
                                                <td>{{1+sl_no}}</td>
                                                <td>{{Stud.reg_no}}</td>
												<td>{{Stud.name}}</td>
												<td>{{Stud.degree}}</td>
												<td>{{Stud.department}}</td>
												<td>{{Stud.year}}</td>
												<td>{{Stud.section}}</td>
                                                <td>
                                                    <!-- <button :disabled="disableEditIf" class="btn btn-primary" @click="EditClick(Stud)">
                                                        Edit
                                                    </button> -->
                                                    <button :disabled="disableEditIf" class="btn btn-primary" >
                                                        Edit
                                                    </button>
                                                </td>
                                                <td>
                                                    <button @click="viewStud(Stud)" class="btn btn-primary" type="button" data-toggle="modal" data-target="#myModal">View</button>
                                                <!-- Trigger the modal with a button -->


                                                </td>
											</tr>
										</tbody>
									</table>
					</div>
							
		            </div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{this.name}} Profile</h4>
      </div>
      <div class="modal-body">
          <table class="profile-table">
          <tr>
            <td><strong>Name</strong></td>
            <td>{{this.name}}</td>
          </tr>
          <tr>
            <td><strong>Register No</strong></td>
            <td>{{this.reg_no}}</td>
          </tr>
          <tr>
            <td><strong>Department</strong></td>
            <td>{{this.department}}</td>
          </tr>
          <tr>
            <td><strong>Year</strong></td>
            <td>{{this.year}}</td>
          </tr>
          <tr>
            <td><strong>Section</strong></td>
            <td>{{this.section}}</td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


                    <!-- END BASIC TABLE -->
            <div class="row form"  v-if="edit === true">
                <button class="btn btn-primary" @click="ClickBack()">Back</button>
        <div class="container">
            <div class="col-sm-6">
            <form @submit.prevent="EditStudent()">
            <label for="name" class="">Name</label>
            <input required="true" disabled="true" v-model="name" type="text" class="form-control" placeholder="Name" id="name" name="name">
            <br>
            <label for="email" class="">Email</label>
            <input required="true" v-model="email" type="email" class="form-control" placeholder="Email" id="email" name="email">
            <br>
            <label for="reg_no" class="">Register Number</label>
            <input required="true" disabled="true" v-model="reg_no" type="text" class="form-control" placeholder="Register Number" id="reg_no" name="reg_no">
			<br>
            <label for="degree" class="">Degree</label>
            <select required="true" disabled="true" v-model="degree" class="form-control" id="degree" name="degree">
                <option selected >Choose</option>
				<option v-for="degree_option in degree_options" v-bind:key="degree_option.value">{{degree_option.text}}</option>
			</select>
            <br>
            <label for="department" class="">Department</label>
			<select required="true" v-model="department" class="form-control" id="department" name="department">
            <option selected >Choose</option>
				<option v-for="department_option in Fdepartment_options" v-bind:key="department_option.value">{{department_option.text}}</option>
			</select>
            <br>
            <label for="year" class="">Year</label>
			<select required="true" v-model="year" class="form-control" id="year" name="year">
            <option selected >Choose</option>
				<option v-for="year_option in Fyear_options" v-bind:key="year_option.value">{{year_option.text}}</option>
			</select>
            <br>
            <label for="semester" class="">Semester</label>
			<select required="true" v-model="semester" class="form-control" id="semester" name="semester">
            <option selected >Choose</option>
				<option v-for="semester_option in Fsemester_options" v-bind:key="semester_option.value">{{semester_option.text}}</option>
			</select>
            <br>
            <label for="section" class="">Section</label>
			<select required="true" v-model="section" class="form-control" id="section" name="section">
            <option selected >Choose</option>
				<option v-for="section_option in Fsection_options" v-bind:key="section_option.value">{{section_option.text}}</option>
			</select>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
            </form>		
            </div>
            <div class="col-sm-6">
                <label class="">Name</label>
            <p v-text="name"></p>
            <br>
            <label  class="">Email</label>
            <p  v-text="email"></p>
            <br>
            <label  class="">Register Number</label>
            <p v-text="reg_no"></p>
			<br>
            <label  class="">Degree</label>
            <p v-text="degree"></p>
            <br>
            <label  class="">Department</label>
			<p v-text="department"></p>
            <br>
            <label class="">Year</label>
			<p v-text="year"></p>
            <br>
            <label  class="">Semester</label>
			<p v-text="semester"></p>
            <br>
            <label  class="">Section</label>
            <p v-text="section"></p>
            <br>
            </div>
        </div>			
	</div>
    <!-- gfd -->

	</div>
</div>
</div>
</div>
</template>


<script>
export default {
    data(){
        return{
            Studs:[],
            edit:false,
            AcademiC:'',
            disAccess:'',
            accessIfstdEdit:'',
                Fdepartment:'',
                Fdepartment_options:[
                    {text:'ECE', value:'ece'},
                    {text:'EEE', value:'eee'},
                    {text:'CSE', value:'cse'},
                    {text:'MECH', value:'mech'},
                    {text:'BTECH-IT', value:'btech-it'},
                    {text:'CIVIL', value:'civil'},
                    {text:'AUTOMOBILE', value:'automobile'},
                    {text:'ME-CSE', value:'me-cse'},
                    {text:'ME-Cem', value:'me-cem'},
                    {text:'ME-EST', value:'me-est'},
                    {text:'ME-ISE', value:'me-ise'},
                    {text:'ME-PED', value:'me-ped'},
                    {text:'ME-SE', value:'me -se'},
                    {text:'ME-CS', value:'me-cs'},
                    {text:'ME-VLSI', value:'me-vlsi'},
                    {text:'MTECH-IT', value:'mtech-it'},
                    {text:'MCA', value:'mca'},
                    {text:'MBA', value:'mba'}
                ],
                Fyear:'',
                Fyear_options:[
                    {text:'1', value:'1'},
                    {text:'2', value:'2'},
                    {text:'3', value:'3'},
                    {text:'4', value:'4'}
                ],
                Fsemester_options:[
                    {text:'1', value:'1'},
                    {text:'2', value:'2'},
                    {text:'3', value:'3'},
                    {text:'4', value:'4'},
                    {text:'5', value:'5'},
                    {text:'6', value:'6'},
                    {text:'7', value:'7'},
                    {text:'8', value:'8'}
                ],
                Fsection:'',
                Fsection_options:[
                    {text:'A', value:'a'},
                    {text:'B', value:'b'},
                    {text:'C', value:'c'},
                    {text:'D', value:'d'}
                ],
            name:'',
                email:'',
                reg_no:'',
                degree:'',
                degree_options:'',
                department:'',
                department_options:'',
                year:'',
                year_options:'',
                semester:'',
                semester_options:'',
                section:'',
                section_options:'',
                status:'',
                student_id:''

        }
    },

          props:['authenticateduser', 'authrole'],
          
    mounted() {
        this.getAllStudents();
        this.fetchSem();
    },
    methods:{
        
        //Fetch sem
        fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                console.log(data);
                this.AcademiC = data;
            })
            .catch(err => console.log(err));
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
        EditClick(Stud){
            this.edit = true;
            this.name = Stud.name;
            this.email = Stud.email;
            this.reg_no = Stud.reg_no;
            this.department = Stud.department;
            this.degree = Stud.degree;
            this.year = Stud.year;
            this.semester = Stud.semester;
            this.section = Stud.section;
            this.status = Stud.status;
            this.student_id = Stud.id;
        },
        viewStud(Stud){
            this.name = Stud.name;
            this.email = Stud.email;
            this.reg_no = Stud.reg_no;
            this.department = Stud.department;
            this.degree = Stud.degree;
            this.year = Stud.year;
            this.semester = Stud.semester;
            this.section = Stud.section;
            this.status = Stud.status;
            this.student_id = Stud.id;
        },
        
        EditStudent(){
                let Formdata = {
                    name:this.name,
                    email:this.email,
                    reg_no:this.reg_no,
                    degree:this.degree,
                    department:this.department,
                    year:this.year,
                    semester:this.semester,
                    section:this.section,
                    status:this.status,
                    student_id:this.student_id
                }

                fetch('/api/student/update', {
                    method: "put",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    const dt = data;
                    if(dt === 'success'){
                        alert('Successfully Updated');
                    }else{
                        alert('Something went wrong!');
                    }
                }).catch(err => {
                    console.log(err);
                });
            },

            ClickBack(){
                this.edit = false;
            }

    },
    computed:{
        disableEditIf(){
            let studPermission = this.AcademiC.student_profile_edit;
            if(studPermission === 1){
                return false;
            }else{
                return true;
            }
        },
        filteredStudents(){
            
            let Students = this.Studs;

            let w_dapart = this.authenticateduser.working_department;
                let depart = this.Fdepartment.toLowerCase();
                let year = this.Fyear;
                let section = this.Fsection;

            if(depart === ""){
                return Students;
            }else{
                return Students.filter(function(stud){
                    return (
                        (depart === '' || stud.department.toLowerCase() === depart.toLowerCase()) 
                    && (year === '' || Number(stud.year) === Number(year)) 
                    && (section === '' || stud.section.toLowerCase() === section.toLowerCase()) 
                        );

                }).sort(function(a, b) {
                    return a.reg_no - b.reg_no;
                });
            }
        }
    }
}




</script>

<style scoped>
.profile-table{
    margin-left: 50%;
    transform: translateX(-50%);
}
.profile-table td:not(:first-child){
    padding-left: 40px;
}
.panel-heading::after
{
    content: "";
    clear: both;
    display: block;
}
.d-select
{
    margin-bottom: 30px;
    width: 250px;
    display: inline-block;
}
</style>
