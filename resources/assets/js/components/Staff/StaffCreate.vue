<template>
<div class="home">
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Create Student Profile</h3>

	</div>
<div class="panel-body">
    <a href="/students" class="btn btn-primary float-right">Back</a>
	<div class="row">
        <div class="container">
            <div class="col-sm-6">
            <form @submit.prevent="CreateStaff()">
            <label for="name" class="">Name</label>
            <input required="true" v-model="name" type="text" class="form-control" placeholder="Name" id="name" name="name">
            <br>
            <label for="email" class="">Email</label><small class="text-muted ml-3">Should be unique</small>
            <input required="true" v-model="email" type="email" class="form-control" placeholder="Email" id="email" name="email">
            <br>
            <label for="eid" class="">Employee ID</label><small class="text-muted ml-3">Should be unique</small>
            <input required="true" v-model="eid" type="text" class="form-control" placeholder="Register Number" id="eid" name="eid">
            <br>
            <label for="department" class="">Department</label>
			<select required="true" v-model="working_department" class="form-control" id="department" name="department">
            <option selected >Choose</option>
				<option v-for="department_option in department_options" v-bind:value="department_option.value">{{department_option.text}}</option>
			</select>
            <br>
            <label for="role" class="">Role</label>
			<select required="true" v-model="dashboard_role" class="form-control" id="role" name="role">
            <option selected >Choose</option>
				<option v-for="dashboard_role_option in dashboard_role_options" v-bind:value="dashboard_role_option.value">{{dashboard_role_option.text}}</option>
			</select>
            <br>

            <label for="occupation" class="">occupation</label>
			<select required="true" v-model="occupation" class="form-control" id="occupation" name="occupation">
            <option selected >Choose</option>
				<option v-for="occupation_option in occupation_options" v-bind:value="occupation_option.value">{{occupation_option.text}}</option>
			</select>
            <br>
            <label for="password" class="">password</label>
            <input required="true" v-model="password" type="password" class="form-control" placeholder="password" id="password" name="password">
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
            <label  class="">Role</label>
            <p v-text="dashboard_role"></p>
            <br>
            <label  class="">occupation</label>
			<p v-text="occupation"></p>
            <br>
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
                Staffs:[],
            edit:false,

            name:'',
                email:'',
                eid:'',
                password:'',

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
                    {text:'Principal',value:'superadmin'},
                    {text:'Attendance manager',value:'admin'},
                    {text:'Hod',value:'subadmin'},
                    {text:'Teaching staff',value:'staff'},
                    {text:'Non teaching staff',value:'substaff'}
                ],
                occupation:'',
                occupation_options:[
                    {text:'Hod', value:'hod'},
                    {text:'Professor', value:'professor'},
                    {text:'Associate Professor', value:'associateprofessor'},
                    {text:'Lab Incharge', value:'labincharge'},
                    {text:'Non Teaching', value:'nonteaching'}

                ]

            }
        },
              props:['authenticateduser', 'authrole'],
        methods:{
            CreateStaff(){
                let Formdata = {
                    name:this.name,
                    email:this.email,
                    eid:this.eid,
                    working_department:this.working_department,
                    dashboard_role:this.dashboard_role,
                    occupation:this.occupation,
                    password:this.password
                }

                console.log(Formdata);

                fetch('/api/staff/store', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    // Work with JSON data here
                    this.name = "";
                    this.email = "";
                    this.eid = "";
                    this.working_department = "";
                    this.dashboard_role = "";
                    this.occupation = "";
                    this.semester = "";
                    this.password = "";

                    if(data !== null){
                        alert(''+data.name+' created Successfully');
                    }else{
                        alert('Somthing went wrong try again later!');
                    }
                    
                    
                }).catch(err => {
                    // Do something for an error here
                });

            }


            
        }
    }
</script>


<style scoped>
    .ml-3{
        margin-left: 10px;
    }
</style>
