<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Staff Profiles</h3>

        <!-- <a type="button" :disabled="disableEditIf && authrole >= 3" @click="atStaffCreateClick()" class="btn btn-primary float-right">Create Staff</a> -->
        <a type="button" :disabled="true"  class="btn btn-primary float-right">Create Staff</a>
	</div>
	<div class="panel-body">
		<div class="row">
			<!-- BASIC TABLE -->
            
            <div class="panel table-responsive" v-if="edit === false">

                <!-- <div class="form-group department-select">
                <label for="department" class="">Working Department</label>
			<select :disabled="disAccess" v-model="w_department" class="form-control" id="department" name="department" style="width:250px;">
            <option selected >Choose</option>
				<option v-for="department_option in department_options" v-bind:key="department_option.value">{{department_option.text}}</option>
			</select>
            <br>
            </div> -->
									<table class="table">
										<thead>
											<tr>
												<th>#Reg No</th>
												<th>Name</th>   
												<th>Edit</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="Staff in filteredStaffs" v-bind:key="Staff.id">
                                                <td>{{Staff.eid}}</td>
												<td>{{Staff.name}}</td>
                                                <td>
                                                    <!-- <button :disabled="disableEditIf && authrole >= 3" class="btn btn-primary" @click="EditClick(Staff)">
                                                        Edit
                                                    </button> -->
                                                    <button disabled  class="btn btn-primary">
                                                        Edit
                                                    </button>
                                                </td>
											</tr>
										</tbody>
									</table>
								
							
		            </div>
                    <!-- END BASIC TABLE -->
            <div class="row form"  v-if="edit === true">
                <button class="btn btn-primary" @click="ClickBack()">Back</button>
        <div class="container">
            <div class="col-sm-6">
            <form @submit.prevent="EditStudent()">
            <label for="name" class="">Name</label>
            <input v-model="name" type="text" class="form-control" placeholder="Name" id="name" name="name">
            <br>
            <label for="email" class="">Email</label>
            <input v-model="email" type="email" class="form-control" placeholder="Email" id="email" name="email">
            <br>
            <label for="eid" class="">Employee ID Number</label>
            <input disabled="true" v-model="eid" type="text" class="form-control" placeholder="Register Number" id="eid" name="eid">
            <br>
            <label for="department" class="">Department</label>
			<select disabled="true" v-model="working_department" class="form-control" id="department" name="department">
            <option selected >Choose</option>
				<option v-for="department_option in department_options" v-bind:key="department_option.value">{{department_option.text}}</option>
			</select>
            <br>
            <label for="role" class="">Role</label>
			<select disabled="true" v-model="dashboard_role" class="form-control" id="role" name="role">
            <option selected >Choose</option>
				<option v-for="dashboard_role_option in dashboard_role_options" v-bind:key="dashboard_role_option.value">{{dashboard_role_option.text}}</option>
			</select>
            <br>

            <label for="occupation" class="">occupation</label>
			<select disabled="true" v-model="occupation" class="form-control" id="occupation" name="occupation">
            <option selected >Choose</option>
				<option v-for="occupation_option in occupation_options" v-bind:key="occupation_option.value">{{occupation_option.text}}</option>
			</select>
            <br>
            <label for="status" class="">status</label>
			<select disabled="true" v-model="status" class="form-control" id="status" name="status">
            <option selected >Choose</option>
				<option v-for="status_option in status_options" v-bind:key="status_option.value">{{status_option.text}}</option>
			</select>
            <br>
            <label for="password" class="">password</label>
            <input v-model="password" type="password" class="form-control" placeholder="password" id="password" name="password">
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
            <p v-text="eid"></p>
			<br>
            <label  class="">Department</label>
			<p v-text="working_department"></p>
            <br>
            <label  class="">Database Role</label>
            <p v-text="dashboard_role"></p>
            <br>
            <label  class="">occupation</label>
			<p v-text="occupation"></p>
            <br>
            <label  class="">status</label>
            <p v-text="status"></p>
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
            Staffs:[],
            edit:false,

            AcademiC:[],
            disAccess: false,
            w_department:'',
             name:'',
                email:'',
                eid:'',
                password:'',
                staff_id:'',
                status:'',
                status_options:[
                    {text:'Current', value:'current'},
                    {text:'Resigned', value:'resigned'}
                ],
                working_department:'',
                department_options:[
                    {text:'ECE', value:'ece'},
                    {text:'EEE', value:'eee'},
                    {text:'CSE', value:'cse'},
                    {text:'MECH', value:'mech'},
                    {text:'IT', value:'it'},
                    {text:'CIVIL', value:'cicil'},
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
                dashboard_role:'',
                dashboard_role_options:
                [
                    {text:'Super admin',value:'superadmin'},
                    {text:'Admin',value:'admin'},
                    {text:'Sub Admin',value:'subadmin'},
                    {text:'Staff',value:''},
                    {text:'Sub Staff',value:'substaff'}
                ],
                occupation:'',
                occupation_options:[
                    {text:'Hod', value:'hod'},
                    {text:'Professor', value:'professor'},
                    {text:'Associate Professor', value:'associateprofessor'},
                    {text:'Lab Incharge', value:'labincharge'},
                    {text:'Non Teaching', value:'nonteaching'}
                ],
                disableAuth:''
        }
    },
          props:['authenticateduser', 'authrole'],
    mounted() {
        this.getAllStaffs();
        this.fetchSem();
    },
    methods:{
        atStaffCreateClick(){
            if(this.disableEditIf === false){
                window.location.href = "http://172.16.1.61/staff/create";   
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
        EditClick(Staff){
            this.edit = true;
            this.name = Staff.name;
            this.email = Staff.email;
            this.eid = Staff.eid;
            this.working_department = Staff.working_department;
            this.dashboard_role = Staff.dashboard_role;
            this.occupation = Staff.occupation;
            this.status = Staff.status;
            this.password = '';
            this.staff_id = Staff.id;
        },

        EditStudent(){
                let Formdata = {
                    name:this.name,
                    email:this.email,
                    eid:this.eid,
                    working_department:this.working_department,
                    dashboard_role:this.dashboard_role,
                    occupation:this.occupation,
                    semester:this.semester,
                    password:this.password,
                    status:this.status,
                    staff_id:this.staff_id,
                }

                fetch('/api/staff/update', {
                    method: "put",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    const dt = data;
                    // console.log(data);
                }).catch(err => {
                    console.log(err);
                });
            },

            ClickBack(){
                this.edit = false;
            },
             //Fetch sem
        fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                // console.log(data);
                this.AcademiC = data;
            })
            .catch(err => console.log(err));
        },

    },
    computed:{
        disableEditIf(){
            let studPermission = this.AcademiC.staff_profile_edit;
            if(studPermission === 1){
                return false;
            }else{
                return true;
            }
        },
        filteredStaffs(){
            let depart = this.w_department.toLowerCase();

            let w_dapart = this.authenticateduser.working_department;


             if(this.authrole === 3 || this.authrole === 4){ //if user is hod or professor show their staffs
                    depart = w_dapart;
                    this.disAccess = true;
                }
                    
                    if(w_dapart === ""){
                return this.Staffs;
            }else{
                return this.Staffs.filter(function(staff){
                        return staff.working_department.toLowerCase() === w_dapart.toLowerCase();
                    });
            }
        }
    }
}
</script>

<style scoped>
.panel-heading::after
{
    content: "";
    clear: both;
    display: block;
}
.department-select{
    padding-left: 30px;
}
</style>
