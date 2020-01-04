<template>
    <div class="home">
        <div class="panel panel-headine">
            <div class="panel-heading">
                <h3 class="panel-title">My Approvals and Limits</h3>
            </div>
        <div class="panel-body">
            <div class="row">
								<div class="col-md-3">
									<div class="metric">
										<span class="icon"><i class="fa fa-calendar"></i></span>
										<p>
											<span class="number text-info"><strong>{{MyLeaveCounts.cl_count}}</strong> / {{academicCL}}</span>
											<span class="title">CL</span>
										</p>
									</div>
								</div>
                                <div class="col-md-3">
									<div class="metric">
										<span class="icon"><i class="lnr lnr-map"></i></span>
										<p>
											<span class="number text-info"> <strong>{{MyLeaveCounts.od_count}}</strong> / {{academicOD}}</span>
											<span class="title">OD</span>
										</p>
									</div>
								</div>
								<div class="col-md-3">
									<div class="metric">
										<span class="icon"><i class="fa fa-calendar"></i></span>
										<p>
											<span class="number text-info"><strong>{{MyLeaveCounts.cpl_count}} & {{MyLeaveCounts.sod_count}}</strong> </span>
											<span class="title">CPL <strong>{{MyLeaveCounts.cpl_count}}</strong></span>
                                            <span class="title">SOD <strong>{{MyLeaveCounts.sod_count}}</strong></span>
										</p>
									</div>
								</div>
								<div class="col-md-3">
									<!-- <div class="metric">
										<span class="icon"><i class="fa fa-map"></i></span>
										<p>
											<span class="number text-info"><strong>{{MyLeaveCounts.cpl_count}} & {{MyLeaveCounts.sod_count}}</strong> </span>
											<span class="title">CPL <strong>{{MyLeaveCounts.cpl_count}}</strong></span>
                                            <span class="title">SOD <strong>{{MyLeaveCounts.sod_count}}</strong></span>
										</p>
									</div> -->
								</div>
							</div>
            <div class="my-approvals">
                <table class="table">
                <thead>
                    <th>Date</th>
                    <th>Approval For</th>
                    <th>Alternatives</th>
                    <th>Status</th>
                    <th>Remove</th>
                </thead>
                <tbody>
                    <tr v-for="mApproval in mApprovals" v-bind:key="mApproval.id">
                        <td>{{mApproval.date}}</td>
                        <td><span style="text-transform:uppercase;">{{mApproval.approval_for}}</span></td>
                        <td><span style="text-transform:uppercase;">{{mApproval.staff_alternatives}}</span></td>
                        <td>{{mApproval.status}}</td>
                        <td>
                            <button class="btn-round btn-danger" @click="removeApproval(mApproval)">X</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <h3 style="text-align:center;" v-if="mApprovals.length === 0">No approval found</h3>
            </div>

<!-- Large modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".createApproval">Create Approval</button>

<div class="modal fade createApproval" tabindex="-1" role="dialog" aria-labelledby="createApproval" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="create-approval">
                <h3>Create Approval</h3>
                <form @submit.prevent="createApproval()">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="date">Date</label>
                            <input required="true" type="date" id="date" v-model="date" name="date" class="form-control" max="3000-12-1" min="2000-12-1">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="selectedStatus">Approval for</label>
                                <select required="true" class="form-control" v-model="StatusSelected" id="selectedStatus" style="width:120px;"> 
                                    <option v-for="setStatuse in setStatuses" v-bind:value="setStatuse.value">
                                        {{ setStatuse.text }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <label for="description">Description</label>
                            <textarea required="true" type="text" v-model="description" id="description" name="description" class="form-control" placeholder="Description"></textarea>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-sm-2">
                             <br>
                <label for="day" class="">Day Order</label>
			<select required="true" v-model="day" class="form-control" id="day" name="day">
				<option v-for="day_option in day_options" v-bind:key="day_option.value">{{day_option.text}}</option>
			</select>
            <br>
            </div>
                    </div>
                    <div class="filtered-schedules">
                        <div class="row" v-if="showHours.length !== 0">
                            <div class="col-md-6">
                                <div class="at_container">
                                    <!-- <h3>Leave Staff alternatives blank if you don't have classes on this day order</h3> -->
                            <h4>Select Alternative Staffs</h4>
                        <ul>
                            <li v-for="hour in showHours" v-bind:key="hour.id">{{hour.hour}}-Hour-#{{hour.alternative_staff}}</li>
                        </ul>
                        <table class="dynamic-form table">
                            <thead>
                                <th>For Hour</th>
                                <th>Staff Id</th>
                            </thead>
                            <tbody>
                                <tr class="dynamic-form-item" v-for="hour in showHours" v-bind:key="hour.id">
                                    <td>{{hour.day}}-{{hour.hour}}</td>
                                    <td>
                                        <input v-model="hour.alternative_staff" type="text" class="form-control" placeholder="Staff ID" style="width:200px;margin-left:20px;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="at_suggestions">
                                    <h4>Staff ID Help</h4>
                                    <input type="text" v-model="suggestSearch" class="form-control" placeholder="Search staffs"/>
                                    <div class="scroll-table">
                                        <table class="table">
                                <tbody class="scroll-table table-responsive table-hover">
                                    <tr>
                                        <td>#ID</td>
                                        <td>Name</td>
                                        <td>Department</td>
                                    </tr>
                                    <tr v-for="sStaff in suggestStaffs" v-bind:key="sStaff.id">
                                        <td>{{sStaff.eid}}</td>
                                        <td>{{sStaff.name}}</td>
                                        <td>{{sStaff.working_department}}</td>
                                    </tr>
                                </tbody>
                            </table>
                                    </div>
                            
                        </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-submit-under-approval-form">Submit</button>
                </form>
            </div>
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
            mApprovals:[],
            AllStaffs:[],
            MySchedules:[],
            suggestSearch:'',
            Academics:[],
            academicCL:'',
            academicOD:'',
            academicPermission:'',
            academicLate:'',
            MyLeaveCounts:[],
            date:'',
            StatusSelected:'',
            description:'',
            setStatuses: [
                { text: "CL", value: "cl" },
                { text: "CPL", value: "cpl" },
                { text: "OD", value: "od" },
                { text: "SOD", value: "sod" }
            ],
            day:'',
            day_options:[
                {text:'Mon',value:'mon'},
                {text:'Tue',value:'tue'},
                {text:'Wed',value:'wed'},
                {text:'Thu',value:'thu'},
                {text:'Fri',value:'fri'}
            ],
        }
    },

    mounted(){
        this.getmyapprovals();
        this.getStaffSchedules();
        this.getAllStaffs();
        this.getoverallApproval();
        this.fetchSem();
        this.getStaffLeaveCounts();
    },

         props:['authenticateduser', 'authrole'],

    methods:{
        getoverallApproval(){
            fetch('/api/fetchsemester')
            .then(res => {
                return res.json();
            })
            .then(data => {
                this.Academics = data;
            }).catch(err => console.log(err));
        },
        getAllStaffs(){
            fetch('/api/staffs')
            .then(res => {
                return res.json();
            })
            .then(data => {
                this.AllStaffs = data;
            }).catch(err => console.log(err));
        },
        
        getmyapprovals(){
            let Formdata = {
                staff_id:this.authenticateduser.id,
            }
            fetch('/api/staff/approval/getmyapprovals', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
            }).then(res => {return res.json()})
            .then(data => {
                console.log(data);
                this.mApprovals = data;

            }).then(err => console.log(err));
        },
        createApproval(){

            let alters = [];
    let Hours = this.showHours;
for (var child in Hours) {
        if (Hours[child].alternative_staff) {
          alters.push(`${Hours[child].day}-${Hours[child].hour}-${Hours[child].alternative_staff}`);
        }
      }

            let Formdata = {
                date:this.date,
                approval_for:this.StatusSelected,
                description:this.description,
                staff_id:this.authenticateduser.id,
                staff_alternatives: alters
            }
        let disableask = true;
            switch (this.StatusSelected) {
                case 'cl':
                    if(this.MyLeaveCounts.cl_count >= this.academicCL){
                        disableask = false;
                    }
                    break;
                    case 'od':
                    if(this.MyLeaveCounts.od_count >= this.academicOD){
                        disableask = false;
                    }
                    break;
            }
            if(disableask === false){
                alert('You have exhausted leave limits contact HOD');
            }else{
            fetch('/api/staff/approval/create', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
            }).then(res => {return res.json()})
            .then(data => {
                // console.log(data);
                this.getmyapprovals();

                this.StatusSelected = "";
                this.date = "";
                this.description = "";
                
                if(data['status'] === 'success'){
                    alert('Approval Created!');
                }else{
                    alert('Somthing went wrong try later');
                }

            }).then(err => console.log(err));
            }
        },
        removeApproval(mApproval){
            let Formdata = {
                approval_id:mApproval.id,
            }
            fetch('/api/staff/approval/removemyapprovals', {
                    method: "delete",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
            }).then(res => {return res.json()})
            .then(data => {
                // console.log(data);
                this.getmyapprovals();

                if(data['status'] === 'success'){
                    alert('Approval Removed!');
                }else{
                    alert('Somthing went wrong try later');
                }
                
            }).then(err => console.log(err));
        },

        getStaffSchedules(){
            fetch('/api/staff/myschedules/' +  this.authenticateduser.id + '')
            .then(res => { return res.json(); })
                .then(data => {
                    this.MySchedules = data;
                    console.log(data);
                }).catch(err => console.log(err));
        },
fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                
                console.log(data);
                this.academicCL = data.staff_cl;
                this.academicOD = data.staff_od;
                this.academicPermission = data.staff_permission;
                this.academicLate = data.staff_lateregister;      
            })
            .catch(err => console.log(err));
        },
         getStaffLeaveCounts(){
            fetch('/api/staff/limitscount/' +  this.authenticateduser.id + '')
            .then(res => { return res.json(); })
                .then(data => {
                    this.MyLeaveCounts = data;
                    console.log(data);
                }).catch(err => console.log(err));  
        },

    },
    computed:{
        suggestStaffs(){
            let search = this.suggestSearch;
            if(search === ""){
                return this.AllStaffs;
            }else{
                return this.AllStaffs.filter(function(staff){
                    return staff.name.toLowerCase().indexOf(search.toLowerCase()); 
                });
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
    }
}
</script>


<style scoped>
.btn-submit-under-approval-form
{
    margin-top: 30px;
}
.scroll-table
{
    height: 300px;
    overflow: scroll;
}
.modal-content .create-approval{
    padding: 20px;
}
.btn-round{
    border-radius: 50px;
    border: none;
    padding: 3px 10px;
}
</style>
