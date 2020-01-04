<template>
<div class="myschedule" v-if="authenticateduser.dashboard_role !== 'superadmin'">
 <div class="panel panel-headline">
     <div class="panel-heading">
        <h3 class="panel-title">Your Schedule</h3>
     </div>
     <div class="panel-body">
         <div class="row">
        <div class="table-staff-schedule">
            <div class="panel panel-headline">
                <h3 class="text-center" v-if="MySchedules.length === 0">No Schedules Found</h3>
            <table class="table" v-if="MySchedules.length > 0">
                <thead>
                    <th>Day / Hour</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                </thead>
                <tbody>
                    <tr v-if="hidescheduleRow('mon')">
                        <td>Mon</td>
                        <td>{{getscheduleSubject('mon',1)}}</td>
                        <td>{{getscheduleSubject('mon',2)}}</td>
                        <td>{{getscheduleSubject('mon',3)}}</td>
                        <td>{{getscheduleSubject('mon',4)}}</td>
                        <td>{{getscheduleSubject('mon',5)}}</td>
                        <td>{{getscheduleSubject('mon',6)}}</td>
                        <td>{{getscheduleSubject('mon',7)}}</td>
                    </tr>
                    <tr v-if="hidescheduleRow('tue')">
                        <td>Tue</td>
                        <td>{{getscheduleSubject('tue',1)}}</td>
                        <td>{{getscheduleSubject('tue',2)}}</td>
                        <td>{{getscheduleSubject('tue',3)}}</td>
                        <td>{{getscheduleSubject('tue',4)}}</td>
                        <td>{{getscheduleSubject('tue',5)}}</td>
                        <td>{{getscheduleSubject('tue',6)}}</td>
                        <td>{{getscheduleSubject('tue',7)}}</td>
                    </tr>
                    <tr v-if="hidescheduleRow('wed')">
                        <td>Wed</td>
                        <td>{{getscheduleSubject('wed',1)}}</td>
                        <td>{{getscheduleSubject('wed',2)}}</td>
                        <td>{{getscheduleSubject('wed',3)}}</td>
                        <td>{{getscheduleSubject('wed',4)}}</td>
                        <td>{{getscheduleSubject('wed',5)}}</td>
                        <td>{{getscheduleSubject('wed',6)}}</td>
                        <td>{{getscheduleSubject('wed',7)}}</td>
                    </tr>
                    <tr v-if="hidescheduleRow('thu')">
                        <td>Thu</td>
                        <td>{{getscheduleSubject('thu',1)}}</td>
                        <td>{{getscheduleSubject('thu',2)}}</td>
                        <td>{{getscheduleSubject('thu',3)}}</td>
                        <td>{{getscheduleSubject('thu',4)}}</td>
                        <td>{{getscheduleSubject('thu',5)}}</td>
                        <td>{{getscheduleSubject('thu',6)}}</td>
                        <td>{{getscheduleSubject('thu',7)}}</td>
                    </tr>
                    <tr v-if="hidescheduleRow('fri')">
                        <td>Fri</td>
                        <td>{{getscheduleSubject('fri',1)}}</td>
                        <td>{{getscheduleSubject('fri',2)}}</td>
                        <td>{{getscheduleSubject('fri',3)}}</td>
                        <td>{{getscheduleSubject('fri',4)}}</td>
                        <td>{{getscheduleSubject('fri',5)}}</td>
                        <td>{{getscheduleSubject('fri',6)}}</td>
                        <td>{{getscheduleSubject('fri',7)}}</td>
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
            MySchedules:[],
        }
    },
    props:['authenticateduser', 'authrole'],
    created(){
        this.getStaffSchedules();
    },
    methods:{

        hidescheduleRow(day){
            var dayHours = [];
                var Schedules;
     
                Schedules = this.MySchedules;

                for(var child in Schedules){
                    if (Schedules[child].day === day){
                        dayHours.push(Schedules[child]);
                    }
                }

                if(dayHours.length < 1){
                    return false;
                }else{
                    return true;
                }
                
        },


        getscheduleSubject(day,hour){
            var sche;
            var HourClass;
                var Schedules;
                var staff_ID = this.authenticateduser.id;
     
                Schedules = this.MySchedules;

                for(var child in Schedules){
                    if ((Schedules[child].day === day && Schedules[child].hour === hour) && (Schedules[child].staff_id === staff_ID || Schedules[child].staff_id2 === staff_ID || Schedules[child].staff_id3 === staff_ID)){
                        sche = Schedules[child].subject_name;
                        HourClass = `${Schedules[child].department}-${Schedules[child].year}-${Schedules[child].section}`;
                    }
                }

                if(sche){
                    var abbr = String(sche).split(' ').map(function(item){return item[0]}).join('');
                    return `${abbr}/${HourClass.toUpperCase()}`;
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
        }
    }
}
</script>
