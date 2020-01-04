<template>
    <div class="home-page">
        <!-- OVERVIEW -->
<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Welcome {{authenticateduser.name}}</h3>
							<!-- <h3 v-if="authenticateduser.dashboard_role === 'superadmin'">Principal for KSR College of Engineering</h3> -->
							<!-- <h4 v-if="authenticateduser.dashboard_role === 'subadmin'">Head of {{authenticateduser.working_department}} Department for KSR College of Engineering</h4> -->
							<p class="panel-subtitle font-16px"><i class="fa fa-clock-o"></i> {{displayDate()}} {{currentTime}}</p>
						</div>
						<div class="panel-body">
							<div class="if-principal" >
							<div class="row">
								<div class="col-md-3">
									<div class="metric">
										<span class="icon"><i class="lnr lnr-users"></i></span>
										<p>
											<span class="number">{{Studs}}</span>
											<span class="title">Students</span>
										</p>
									</div>
								</div>
								<div class="col-md-3">
									<div class="metric">
										<span class="icon"><i class="lnr lnr-user"></i></span>
										<p>
											<span class="number">{{Staffs}}</span>
											<span class="title">Staff</span>
										</p>
									</div>
								</div>
							</div>
							</div>
					
						</div>
					</div>

<div class="row">
						<div class="col-md-6" v-if="authenticateduser.dashboard_role !== 'superadmin'">
							<!-- RECENT PURCHASES -->
							<div class="panel" >
								<div class="panel-heading">
									<h3 class="panel-title">Your Todays Hours</h3>
									<div class="right">
										<button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
										<button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
									</div>
								</div>
								<div class="panel-body no-padding">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Hour</th>
												<th>Subject</th>
												<th>Class</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="it in 7" v-if="getscheduleSubject(CurrentDay,it) !== '--'">
												<td><a href="#">{{it}}</a></td>
												<td>{{getscheduleSubject(CurrentDay,it)[0].subject_name}}</td>
												<td>{{getscheduleSubject(CurrentDay,it)[1]}}</td>
												<td>
													<span v-if="getscheduleSubject(CurrentDay,it)[0].attendance_made !== true" class="label label-danger">Not Done</span>
													<span v-if="getscheduleSubject(CurrentDay,it)[0].attendance_made === true" class="label label-success">Done</span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="panel-footer">
									<div class="row">
										<div class="col-md-6"><span class="panel-note"><i class="fa fa-clock-o"></i>{{displayDate()}} {{currentTime}}</span></div>
										<div class="col-md-6 text-right"><a  v-bind:href="basepath + '/student/attendance/make'" class="btn btn-primary">Make Attendance</a></div>
									</div>
								</div>
							</div>
							<!-- END RECENT PURCHASES -->
						</div>
						<div class="col-md-6">
						
							<div class="panel" v-if="authenticateduser.dashboard_role === 'superadmin'">
								<div class="panel-heading">
									<h3 class="panel-title">Update Saturday Day Orders</h3>
									<div class="right">
										<button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
										<button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
									</div>
								</div>
								<div class="panel-body">
									<table class="table table-bordered">
						<thead>
							<tr><th>Date</th><th>Day Order</th></tr>
						</thead>
						<tbody>
							<tr v-for="SemSaturday in SemSaturdays"><td>{{SemSaturday.date}}</td><td>{{SemSaturday.day}}</td></tr>
						</tbody>
					</table>
								
									<form @submit.prevent="updateSaturdayDayorder()">
										
											<div class="satday-date">
												<label for="day" class="">Select Date</label>
												<input required="true"  style="width:150px;" data-date-format="DD/MM/YYYY" v-model="satudrDaydate" type="date" max="3000-12-31" min="1000-01-01" class="form-control">
											</div>
											<div class="satday-day" style="margin-top:20px;">
												<label for="day" class="">Select Day Order</label>
											<select required="true" v-model="satudrDayOrder" class="form-control" id="day" name="day" style="width:150px;">
												<option v-for="day_option in day_options" v-bind:key="day_option.text">{{day_option.text}}</option>
											</select>
											</div>
										
										<button class="btn btn-primary" type="submit" style="margin-top:20px;">Save</button>
									</form>

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
			Studs:'',
			Staffs:'',
			CurrentDay:'',
			MySchedules:[],
			currentTime: null,
			satudrDaydate: "",
			satudrDayOrder: "",
			day_options:[
                {text:'Mon',value:'mon'},
                {text:'Tue',value:'tue'},
                {text:'Wed',value:'wed'},
                {text:'Thu',value:'thu'},
                {text:'Fri',value:'fri'}
			],
			SemSaturdays:[],
        }
    },
	 props:['authenticateduser','basepath'],
    mounted(){
		this.getAllStudents();
		this.getAllStaffs();
		this.getStaffSchedules();
		this.fetchSemSaturDays();
		this.setSatDate();
    },
created() {
    this.currentTime = moment().format('LTS');
    setInterval(() => this.updateCurrentTime(), 1 * 1000);
  },
    methods:{
		setSatDate(){
			this.satudrDaydate = this.getSaturdays();
		},
		fetchSemSaturDays(){
            fetch('/api/fetchsemsaturdaysFive')
            .then(res => res.json())
            .then(data => {
                this.SemSaturdays = data;
            })
            .catch(err => console.log(err));
        },
		updateSaturdayDayorder(){
			let formData = {
				satudrDaydate:this.satudrDaydate,
				satudrDayOrder:this.satudrDayOrder
			}
				// console.log(formData);
			fetch('/api/updatesaturdayorder', {
                    method: "post",
                    body: JSON.stringify(formData),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
					console.log(data);
					if(data){
						alert(data['message']);
						this.fetchSemSaturDays();
					}
                }).catch(err => console.log(err));
		},
		getSaturdays(){
			let date = new Date();
			 var ret = new Date(date||new Date());
  ret.setDate(ret.getDate() + (6 - 1 - ret.getDay() + 7) % 7 + 1);
  return moment(ret).format('YYYY-MM-DD');
		},
	updateCurrentTime() {
      	this.currentTime = moment().format('LTS');
    },
getscheduleSubject(day,hour){
            var sche;
            var HourClass;
                var Schedules;
                var staff_ID = this.authenticateduser.id;
     
                Schedules = this.MySchedules;

                for(var child in Schedules){
                    if ((Schedules[child].day === day && Schedules[child].hour === hour) && (Schedules[child].staff_id === staff_ID || Schedules[child].staff_id2 === staff_ID || Schedules[child].staff_id3 === staff_ID)){
                        sche = Schedules[child];
                        HourClass = `${Schedules[child].department}-${Schedules[child].year}-${Schedules[child].section}`;
                    }
                }

                if(sche){
                    var abbr = String(sche).split(' ').map(function(item){return item[0]}).join('');
					// return `${abbr}/${HourClass.toUpperCase()}`;
					return [sche,HourClass.toUpperCase(),];
                }else{
                    return `--`;
                }
        },
         
        getStaffSchedules(){
            fetch('/api/staff/myschedules/' +  this.authenticateduser.id + '')
            .then(res => { return res.json(); })
                .then(data => {
                    this.MySchedules = data;
                    console.log(data);
                }).catch(err => console.log(err));
        },


        displayDate(){
			var d = new Date();

			switch (d.getDay()) {
				case 1:
					this.CurrentDay = 'mon';
					break;
					case 2:
					this.CurrentDay = 'tue';
					break;
					case 3:
					this.CurrentDay = 'wed';
					break;
					case 4:
					this.CurrentDay = 'thu';
					break;
					case 5:
					this.CurrentDay = 'fri';
					break;
					case 6:
					this.CurrentDay = 'sat';
					break;
					case 7:
					this.CurrentDay = 'sun';
					break;
			}
			

			return moment(Date()).format('dddd DD-MM-YYYY');
		},
		 getAllStudents(){
            fetch('/api/students')
            .then(res => {
                return res.json();
            })
            .then(data => {
                this.Studs = data.length;
            }).catch(err => console.log(err));
        },
		 getAllStaffs(){
            fetch('/api/staffs')
            .then(res => {
                return res.json();
            })
            .then(data => {
                this.Staffs = data.length;
            }).catch(err => console.log(err));
        },
    }
}
</script>

<style scoped>
.dashboard-schedule li{
	display: inline-block;
}
.font-16px{
	font-size: 16px;
}
</style>
