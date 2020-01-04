<template>
    <div class="home">

<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">My Profile</h3>

	</div>
<div class="panel-body">
    
    <div class="row form">
        <p>
            <button class="btn btn-primary edit-button" @click="ClickEdit()" v-if="edit === false">Edit Profile</button>
        </p>
        <div class="container">
        <div class="row">
             <div class="col-sm-6" v-if="edit === false">
                 <table class="table">
                     <tr>
                         <td><label class="">Name</label></td>
                         <td><p v-text="name"></p></td>
                     </tr>
                    <tr>
                        <td><label  class="">Email</label></td>
                        <td><p v-text="email"></p></td>
                    </tr>
                    <tr>
                        <td><label  class="">Register Number</label></td>
                        <td><p v-text="eid"></p></td>
                    </tr>
                    <tr>
                        <td><label  class="">Department</label></td>
                        <td><p v-text="working_department"></p></td>
                    </tr>
                    <tr>
                        <td><label  class="">occupation</label></td>
                        <td><p v-text="occupation"></p></td>
                    </tr>
                 </table>
            </div>
            
            <div class="col-sm-6" v-if="edit === true">
            <form @submit.prevent="SaveEditited()">
            

            <table class="table edit-tbl">
                <tr>
                    <td><label for="name" class="">Name</label></td>
                    <td><input required="true" disabled="true" v-model="name" type="text" class="form-control" placeholder="Name" id="name" name="name"></td>
                </tr>
                <tr>
                    <td><label for="email" class="">Email</label></td>
                    <td><input v-model="email" type="email" class="form-control" placeholder="Email" id="email" name="email"></td>
                </tr>
                <tr>
                    <td><label for="eid" class="">Employee ID Number</label></td>
                    <td><input disabled="true" v-model="eid" type="text" class="form-control" placeholder="Register Number" id="eid" name="eid"></td>
                </tr>
                <tr>
                    <td><label for="department" class="">Department</label></td>
                    <td>
                        <select disabled="true" v-model="working_department" class="form-control" id="department" name="department">
				<option v-for="department_option in department_options" v-bind:key="department_option.value">{{department_option.text}}</option>
			</select>
                    </td>
                </tr>
                <tr>
                    <td><label for="occupation" class="">occupation</label></td>
                    <td>
                        <select disabled="true" v-model="occupation" class="form-control" id="occupation" name="occupation">
				<option v-for="occupation_option in occupation_options" v-bind:key="occupation_option.value">{{occupation_option.text}}</option>
			</select>
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <p class="btn btn-primary" style="float:right;" @click="resetPwd = true" v-if="resetPwd === false">Reset Password</p>
                    </td>
                </tr>
                <tr v-if="resetPwd">
                       <td><label for="password" class="">password</label></td>
                <td>
                    <input v-model="password" type="password" class="form-control" placeholder="password" id="password" name="password">
                </td>
            
            
           
                </tr>
                <tr v-if="resetPwd">
                    <td>
                        <label for="cnfmpassword" class="">Confirm password</label>
                    </td>
                    <td>
 <input  type="password" class="form-control" placeholder="Confirm password" id="cnfmpassword" name="cnfmpassword">
           
                    </td>
                </tr>
            </table>
            
            <button type="submit" class="btn btn-primary">Save</button>
            </form>		
            </div>
        </div>	
    </div>		
	</div>
    <p v-if="SavedMsg" style="color:green;">Your profile saved</p>
</div>
</div>
</div>
</template>

<script>
export default {
    data(){
        return{
            SavedMsg: false,
            edit:false,
            resetPwd:false,
            name:'',
            email:'',
            eid:'',
            password:'',
            working_department:'',
            occupation:'',

                department_options:[
                    {text:'ECE', value:'ece'},
                    {text:'EEE', value:'eee'},
                    {text:'CSE', value:'cse'},
                    {text:'MECH', value:'mech'},
                    {text:'IT', value:'it'},
                    {text:'CIVIL', value:'cicil'},
                    {text:'AUTO', value:'auto'},
                ],

                occupation_options:[
                    {text:'Hod', value:'hod'},
                    {text:'Professor', value:'professor'},
                    {text:'Associate Professor', value:'associateprofessor'},
                    {text:'Lab Incharge', value:'labincharge'},
                    {text:'Non Teaching', value:'nonteaching'}

                ]

        }
    },
    mounted(){
        this.getAuthuserData();
    },
         props:['authenticateduser', 'authrole'],

    methods:{

        getAuthuserData(){
            let vm = this;
            vm.name = vm.authenticateduser.name;
            vm.email = vm.authenticateduser.email;
            vm.name = vm.authenticateduser.name;
            vm.eid = vm.authenticateduser.eid;
            vm.working_department = vm.authenticateduser.working_department;
            vm.password = vm.authenticateduser.password;
            vm.occupation = vm.authenticateduser.occupation;
        },

        ClickEdit(){
            this.edit = true;
        },
        SaveEditited(){
            let Formdata = {
                user_id:this.authenticateduser.id,
                password:this.password,
                email:this.email
            }
            fetch('/api/staff/profile/update', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
            })
            .then(res => {
                return res.json();
            }).then(data => {
                this.SavedMsg = true;
                this.edit = false;
            }).catch(err => console.log(err));
        }
    },

    computed:{
        
    }
}
</script>


<style scoped>
.edit-button{
    float: right;
    margin: 10px;
}
.edit-tbl td
{
    padding-bottom: 20px;
}
</style>
