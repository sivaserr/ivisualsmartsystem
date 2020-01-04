<template>
<div class="home">

    <!-- OVERVIEW -->
<div class="panel panel-headline">
	<div class="panel-body">
		<div class="row">
            <div class="create-subject-form">
                 <form @submit.prevent="CreateSubject()">

                    <div class="container" v-if="SelectionBar === true">
 <h3>Assign Schedule</h3>
 <p v-if="showClasse.length === 0 || showClasse === null">No Classes Found</p>
                        <div class="select-class-only">
                            <h4>Select Your Class</h4>
                            <ul class="schedule-list">
                            <li @click="setAllDatas(MyClass)" :class="{highlight:MyClass.id == selectedClass}" v-for="MyClass in showClasse" v-bind:key="MyClass.id">
                                <span style="text-transform:uppercase;">{{MyClass.degree}}-{{MyClass.department}}</span><br>
                                <span>{{MyClass.year}} year {{MyClass.section}} Section</span>
                            </li>
                            </ul>
                        </div>

 <p class="btn btn-primary btn-select-submit" @click="selctionSubmit()">Submit</p>

                        <!-- <div class="slection-automatic">
                        <h3>Assign Schedule</h3>
                        <br>
                    <div class="form-group col-sm-2">
                        <label for="degree" class="">Degree</label>
            <select v-model="degree" class="form-control" id="degree" name="degree">
                <option selected disabled>Choose</option>
				<option v-for="degree_option in degree_options" v-bind:key="degree_option.value">{{degree_option.text}}</option>
			</select>
                   
            <br>
             </div>
                    <div class="form-group col-sm-2"> 
            <label for="department" class="">Department</label>
			<select v-model="department" class="form-control" id="department" name="department">
            <option selected disabled>Choose</option>
				<option v-for="department_option in department_options" v-bind:key="department_option.value">{{department_option.text}}</option>
			</select>
            <br>
             </div>
                    <div class="form-group col-sm-2"> 
                        <label for="year" class="">Year</label>
            <select v-model="year" class="form-control" id="year" name="year">
            <option selected disabled>Choose</option>
				<option v-for="year_option in year_options" v-bind:key="year_option.value">{{year_option.text}}</option>
			</select>
            <br>
             </div>
                    <div class="form-group col-sm-2"> 
            <label for="semester" class="">Semester</label>
			<select v-model="semester" class="form-control" id="semester" name="semester">
            <option selected disabled>Choose</option>
				<option v-for="semester_option in semester_options" v-bind:key="semester_option.value">{{semester_option.value}}</option>
			</select>
            <br>
             </div>
                    <div class="form-group col-sm-2"> 
            <label for="section" class="">Section</label>
			<select v-model="section" class="form-control" id="section" name="section">
            <option selected disabled>Choose</option>
				<option v-for="section_option in section_options" v-bind:key="section_option.value">{{section_option.text}}</option>
			</select>
            <br>
            <p style="color:red;font-weight:bold;">{{formError}}</p>
            <p class="btn btn-primary" @click="selctionSubmit()">Submit</p>
                    </div>
                        </div> -->
                    </div>

                    <div class="selectionBar" v-if="SelectionBar === false">
                        <!-- SUBJECT LISTING -->
                     <h3>Subjects</h3>      
            <div class="subjects-listing table-responsive">
                 <div class="table-subs">
                <table class="table" v-if="filterSubjects.length > 0">
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
												<td>{{getStaffEID(filterSubject.user_id)}}
                                                    <span v-if="getStaffEID(filterSubject.user_id2) !== 'null'">, {{getStaffEID(filterSubject.user_id2)}}</span>
                                                    <span v-if="getStaffEID(filterSubject.user_id3) !== 'null'">, {{getStaffEID(filterSubject.user_id3)}}</span>
                                                </td>
                                                <td>{{filterSubject.staff_name}}</td>
                                                <td>
                                                 <button :disabled="disableEditIf" class="btn btn-primary" type="button" @click="EditSubjectForm(filterSubject)" data-toggle="modal" data-target="#EditSubjectModel">Edit</button>    
                                                </td>
											</tr>
										</tbody>
									</table>
                                    <p class="text-center" v-if="filterSubjects.length === 0">No Subjects are Found</p>
                 </div>
            </div>
            <!-- SUBJECT LISTING -->

                    <h3>Create subject</h3>
                    <label class="fancy-radio">
						<input name="subject_type" id="0" value="0" type="radio"  v-model="subject_type">
						<span><i></i>Theory</span>
					</label>
                    <label class="fancy-radio">
						<input name="subject_type" id="1" value="1" type="radio"  v-model="subject_type">
						<span><i></i>Elective</span>
					</label>
                    <label class="fancy-radio">
						<input name="subject_type" id="2" value="2" type="radio"  v-model="subject_type">
						<span><i></i>Lab</span>
					</label>
                   
                    <br>
                    <label for="subject_name" class="">Subject Name </label>
                    <input required="true" v-model="subject_name" type="text" class="form-control" placeholder="Subject Name" id="subject_name" name="subject_name">
                    <small v-if="subject_type === '2'">Include Batch Name</small>
                    <br>
                    <label for="subject_id" class="">Subject ID</label>
                    <input required="true" v-model="subject_id" type="text" class="form-control" placeholder="Subject ID" id="subject_id" name="subject_id">
                    <small v-if="subject_type === '2'">Differentiate Subject ID's for more then one batch's <strong>EG: 18CS321-batch1</strong></small>
                    <br>
                    <label for="staff_id" class="">Staff ID</label>
                    <input required="true" v-model="staff_id" type="text" class="form-control" placeholder="Staff ID" id="staff_id" name="staff_id">
                    <span class="text-danger" v-if="error_staffid">Staff ID not found Create the Staff first</span>
<br>
                    <label for="staff_id" class="" v-if="subject_type === '2'">Staff ID 2</label>
                    <input required="true" v-model="staff_id2" type="text" v-if="subject_type === '2'" class="form-control" placeholder="Staff ID" id="staff_id" name="staff_id">
                    <span class="text-danger" v-if="error_staffid2 && subject_type === '2'">Staff ID not found Create the Staff first</span>
                    <br>
                    <label for="staff_id" class="" v-if="subject_type === '2'">Staff ID 3</label>
                    <input v-model="staff_id3" type="text" v-if="subject_type === '2'" class="form-control" placeholder="Staff ID" id="staff_id" name="staff_id">
                    <span class="text-danger" v-if="error_staffid3 && subject_type === '2'">Staff ID not found Create the Staff first</span>
                    <br>
                   
                    <br>
                
                   <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#myModal"  v-if="subject_type === '1' || subject_type === '2'">Select Students</button>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> Select Students</h4>
      </div>
      <div class="modal-body">
          <input type="text" class="form-control" v-model="SelectSearch" placeholder="Search with Register Number"><br>
          <table class="table table-striped">
              <thead>
                <th class="head-select">Select Student</th>
                <th class="head-regno">S.No</th>
                <th class="head-regno">Register No</th>
                <th class="head-name">Name</th>
              </thead>
              <tr v-for="(student,sl_no) in filteredStudents" v-bind:key="student.id" class="row-striped">
                  <td class="data-checkbox">
                      <input type="checkbox" id="checkbox_student" v-model="student.selected">
                  </td>
                  <td class="data-sl_no">{{1+sl_no}}</td>
                  <td class="data-reg-no">{{student.reg_no}}</td>
                  <td class="data-name">{{student.name}}</td>
              </tr>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<button :disabled="disableEditIf" type="submit" class="btn btn-primary">Create</button>
</div>
                </form>
            </div>
            <br>
       
            <div class="assignn-subb" v-if="SelectionBar === false">
<tabs>
    <tab name="Schedules" :selected="true">
      <form @submit.prevent="assignSchedule()">
                   
            <div class="assign-subject" >
                <h3>Assign Schedule</h3>
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
                                 <td><ul><li v-for="schedule in getSchedule('mon',1)" >{{schedule.subject_name}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('mon',2)" >{{schedule.subject_name}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('mon',3)" >{{schedule.subject_name}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('mon',4)" >{{schedule.subject_name}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('mon',5)" >{{schedule.subject_name}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('mon',6)" >{{schedule.subject_name}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('mon',7)" >{{schedule.subject_name}}</li></ul></td>
                            </tr>
                            <tr>
                                <td>Tuesday</td>
                                <td><ul><li v-for="schedule in getSchedule('tue',1)" >{{schedule.subject_name}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('tue',2)" >{{schedule.subject_name}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('tue',3)" >{{schedule.subject_name}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('tue',4)" >{{schedule.subject_name}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('tue',5)" >{{schedule.subject_name}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('tue',6)" >{{schedule.subject_name}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('tue',7)" >{{schedule.subject_name}}</li></ul></td>
                            </tr>
                            <tr>
                                <td>Wednesday</td>
                               <td><ul><li v-for="schedule in getSchedule('wed',1)" >{{schedule.subject_name}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('wed',2)" >{{schedule.subject_name}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('wed',3)" >{{schedule.subject_name}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('wed',4)" >{{schedule.subject_name}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('wed',5)" >{{schedule.subject_name}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('wed',6)" >{{schedule.subject_name}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('wed',7)" >{{schedule.subject_name}}</li></ul></td>
                            </tr>
                            <tr>
                                <td>Thursday</td>
                                <td><ul><li v-for="schedule in getSchedule('thu',1)" >{{schedule.subject_name}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('thu',2)" >{{schedule.subject_name}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('thu',3)" >{{schedule.subject_name}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('thu',4)" >{{schedule.subject_name}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('thu',5)" >{{schedule.subject_name}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('thu',6)" >{{schedule.subject_name}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('thu',7)" >{{schedule.subject_name}}</li></ul></td>
                            </tr>
                            <tr>
                                <td>Friday</td>
                                <td><ul><li v-for="schedule in getSchedule('fri',1)" >{{schedule.subject_name}}</li></ul></td>
                                 <td><ul><li v-for="schedule in getSchedule('fri',2)" >{{schedule.subject_name}}</li></ul></td>
                                  <td><ul><li v-for="schedule in getSchedule('fri',3)" >{{schedule.subject_name}}</li></ul></td>
                                   <td><ul><li v-for="schedule in getSchedule('fri',4)" >{{schedule.subject_name}}</li></ul></td>
                                    <td><ul><li v-for="schedule in getSchedule('fri',5)" >{{schedule.subject_name}}</li></ul></td>
                                     <td><ul><li v-for="schedule in getSchedule('fri',6)" >{{schedule.subject_name}}</li></ul></td>
                                <td><ul><li v-for="schedule in getSchedule('fri',7)" >{{schedule.subject_name}}</li></ul></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- <h4>Assign Elective Subject <input type="checkbox" id="checkbox_Elect" v-model="checkbox_Elect"></h4> -->
<div class="options-bar">
<div class="row">
    <div class="col-sm-3">
        <h4>Remove Schedules ? <input type="checkbox" id="checkbox_Elect" v-model="Correction_check"></h4>
    </div>
    <div class="col-sm-3">
        <h4>Add More then One <input type="checkbox" id="checkbox_Elect" v-model="add_morethen_one"></h4>
    </div>
    <div class="col-sm-3">
        <h4>Update One <input type="checkbox" id="checkbox_Elect" v-model="update_one"></h4>
    </div>
</div>
</div>
                <!-- <div class="frm" v-if="checkbox_Elect === false"> -->
                <div class="frm">
                    <div class="row">
                    <div class="form-group col-sm-2"> 
                        <label for="day">Day</label>
                    <select required="true" class="form-control" name="day" id="day" v-model="Day_selected">
                        <option v-for="Day in Days">{{Day.text}}</option>
                    </select><br>
                    </div>
                    <div class="form-group col-sm-2"> 
                    <label for="hour">Hour</label>
                    <select required="true" class="form-control" name="hour" id="hour" v-model="Hour_selected">
                        <option v-for="Hour in Hours">{{Hour.text}}</option>
                    </select><br>
                    </div>

                    <button  v-if="Correction_check === true" :disabled="disableEditIf" @click="RemoveSchedules()" class="btn btn-primary remove-schedules">Remove Schedules</button>

                    <div class="form-group col-sm-2" v-if="Correction_check === false"> 
                        <div class="update" v-if="update_one">
                            <label for="Subject">Select Old Subject</label>
                    <select required="true" class="form-control" name="Subject" id="Subject" v-model="OldSubject_selected">
                        <option v-for="Subject in filterSubjects">{{Subject.subject_name}}</option>
                    </select><br>
                        </div>
                        
                    <label for="Subject">Select Subject</label>
                    <select required="true" class="form-control" name="Subject" id="Subject" v-model="Subject_selected">
                        <option v-for="Subject in filterSubjects">{{Subject.subject_name}}</option>
                    </select><br>
                    </div>
                    <br>
                    <button  v-if="Correction_check === false" :disabled="disableEditIf" class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </div>
                
            </form>
    </tab>
    <tab name="Reorder Schedules">
      <div class="update-schedules-form">
          <form @submit.prevent="ReOrderSchedules()">
              <table class="table">
                  <thead>
                      <th>Day</th>
                      <th>Hour</th>
                      <th>Subject</th>
                      <th>Change To Day</th>
                      <th>Change To Hour</th>
                      <th></th>
                  </thead>
                  <tr v-for="Schedule in filterSchedules">
                      <td><span style="text-transform:uppercase;">{{Schedule.day}}</span></td>
                      <td>{{Schedule.hour}}</td>
                      <td>{{Schedule.subject_name}}</td>
                      <td>
                    <select class="form-control" v-model="Schedule.dayChange">
                        <option></option>
                        <option v-for="Day in Days">{{Day.value}}</option>
                    </select>
                      </td>
                      <td>
                          <select class="form-control" v-model="Schedule.hourChange">
                              <option></option>
                              <option v-for="Hour in Hours">{{Hour.value}}</option>
                          </select>
                      </td>
                  </tr>
              </table>
              <button class="btn btn-primary" type="submit">Save</button>
          </form>
      </div>
    </tab>
  </tabs>
                
            


            <!-- <form @submit.prevent="assignSchedule()" v-if="checkbox_Elect"> -->

            </div>
		</div>
	</div>
</div>


<div id="EditSubjectModel" class="modal fade" role="dialog">
  <div class="modal-dialog">
<form @submit.prevent="UpdateSubject()">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Subject</h4>
      </div>
      <div class="modal-body">
          <!-- <input type="text" v-model="SelectSearch" class="form-control"> -->
          <table class="table">
              <tr>
                <td>Subject Name</td>
                <td>Subject ID</td>
                <td>Staff ID</td>
                <td v-if="Edit_subject_type === '2'">Staff ID 2</td>
                <td v-if="Edit_subject_type === '2'">Staff ID 3</td>
                <!-- <td>Elective Subject Or Lab</td> -->
              </tr>
              <tr>
                  <td><input required="true" v-model="Edit_subject_name" type="text" class="form-control" placeholder="Subject Name" id="Edit_subject_name" name="Edit_subject_name"></td>
                  <td><input required="true" v-model="Edit_subject_id" type="text" class="form-control" placeholder="Subject ID" id="Edit_subject_id" name="Edit_subject_id"></td>
                  <td><input required="true" v-model="Edit_staff_id" type="text" class="form-control" placeholder="Staff ID" id="Edit_staff_id" name="Edit_staff_id"></td>
                  <td>
                    <input v-model="Edit_staff_id2" type="text" v-if="Edit_subject_type === '2'" class="form-control" placeholder="Staff ID" id="staff_id" name="staff_id">                    
                    </td>
                    <td>
                        <input v-model="Edit_staff_id3" type="text" v-if="Edit_subject_type === '2'" class="form-control" placeholder="Staff ID" id="staff_id" name="staff_id">
                    </td>
                  <!-- <td>

                      <input type="checkbox" id="checkbox_student" v-model="Edit_subject_type">
                  </td> -->
              </tr>
          </table>
<div class="select-student" v-if="Edit_subject_type === '2' || Edit_subject_type === '1'">
<h4>Subject Students</h4>
  <table class="table table-striped">
              <thead>
                <th class="head-regno">Register No</th>
                <th class="head-name">Name</th>
                <th>
                    Action
                </th>
              </thead>
              <tr v-for="student in filteredStudents" v-bind:key="student.id" class="row-striped">
                  <!-- <td class="data-checkbox">
                    {{isStudentOnSub(student.id)}}
                      <input type="checkbox" id="checkbox_student" v-model="student.selected_before" :checked="isStudentOnSub(student.id)" v-bind:id="isStudentOnSub(student.id)">
                  </td> -->
                  <td class="data-reg-no">{{student.reg_no}}</td>
                  <td class="data-name">{{student.name}}</td>
                  <td>
                      <button type="button" class="btn btn-danger" v-if="isStudentOnSub(student.id) === true" @click="removeAStudent(student.id)">
                          Remove
                      </button>
                      <button type="button" class="btn btn-success" v-if="isStudentOnSub(student.id) === false" @click="addAStudent(student.id)">
                          Add
                      </button>
                  </td>
              </tr>
          </table>
</div>


      </div>
      <div class="modal-footer">
          <button :disabled="disableEditIf" type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
</form>





  </div>
</div>
</div>
</template>


<script>
export default {
    data(){
        return {
            error_staffid:false,
            error_staffid2:false,
            error_staffid3:false,
            Staffs: [],
            SelectionBar:true,
            Studs: [],
            subject_name:'',
            subject_id:'',
            staff_id:'',
            staff_id2:'',
            staff_id3:'',
            ElectiveSub:'',
            Subject_selected:'',
            Subjects:[],
            Schedules:[],
            subject_type: "0",
            subject_Students: [],
            
            SelectSearch: '',
            selectedClass:undefined,
            showClasse:[],
            add_morethen_one:false,
            update_one:false,
            OldSubject_selected:"",

            Correction_check: false,

            Edit_subject_name: "",
            Edit_sub_id:"",
            Edit_subject_id:"",
            Edit_staff_id: "",
            Edit_subject_type: "0",
            Edit_students_removed: [],
            Edit_students_added: [],
            // checkbox_Elect:false,

            AcademiC:[],
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

            degree:'',
                degree_options:[
                    {text:'BE', value:'be'},
                    {text:'ME', value:'me'}
                ],
                bachlers_departments:["ece","eee","mech","cse","it","civil","automobile"],
            masters_departments:["me-cse","me-cem","me-est","me-ise","me-ped","me-se","me-vlsi","mtech-it","mba"],
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
                formError:'',
                EditATPermissions:''
        }
    },
          props:['authenticateduser', 'authrole'],
    mounted() {
        this.getAllShedules();
        this.getAllSubjects();
        this.getAllStudents();
        this.fetchSem();
        this.hodandStaffRole();
        this.getAllStaffs();
        this.showClasses();
        this.EditATpermissions();
    },
    methods:{
        ReOrderSchedules(){
            let formData = {
                schedules: this.filterSchedules,
                changesby:this.authenticateduser.id
            }
            console.log(formData);
            fetch('/api/schedule/reorderschedules',{
                    method: 'POST',
                    body: JSON.stringify(formData),
                    headers:{
                        'content-type': 'application/json'
                    }
                }).then(res => { return res.json(); })
                .then(data => {
                    console.log(data);
                    if(data['status'] === 'success'){
                        alert(data['message']);
                        this.getAllSubjects();
                this.getAllShedules();
                    }else{
                        alert('Something Went Wrong try later');
                    }
                }).catch(err => console.log(err));
                  this.getAllSubjects();
                this.getAllShedules();
        },
        removeAStudent(student_id){
            let remove = true;

            if(confirm('Are you sure. You want to remove this student from this subject completely ?') === true){
                let formData = {
                    remove : remove,
                    student_id : student_id,
                    subject_id : this.Edit_sub_id
                }

                fetch('/api/updatesubjectstudent',{
                    method: 'POST',
                    body: JSON.stringify(formData),
                    headers:{
                        'content-type': 'application/json'
                    }
                }).then(res => { return res.json(); })
                .then(data => {
                    console.log(data);
                    if(data['status'] === 'success'){
                        alert(data['message']);
                        this.getAllSubjects();
                    }else{
                        alert('Something Went Wrong try later');
                    }
                }).catch(err => console.log(err));
            }
        },
        addAStudent(student_id){
            let remove = false;
            if(confirm('Are you sure. You want to add this student to the subject ?') === true){
                let formData = {
                    remove : remove,
                    student_id : student_id,
                    subject_id : this.Edit_sub_id
                }

                fetch('/api/updatesubjectstudent',{
                    method: 'POST',
                    body: JSON.stringify(formData),
                    headers:{
                        'content-type': 'application/json'
                    }
                }).then(res => { return res.json(); })
                .then(data => {
                    console.log(data);
                    if(data['status'] === 'success'){
                        alert(data['message']);
                        this.getAllSubjects();
                    }else{
                        alert('Something Went Wrong try later');
                    }
                }).catch(err => console.log(err));
            }
        },
        isStudentOnSub(stu_id){
            let studs = this.subject_Students;
            // return stu_id;
            if(studs.length > 0){
                 let stdd = "";
              
               for (let index = 0; index < studs.length; index++) {
                   const element = studs[index];
                   if(element.student_id === stu_id){
                       stdd = element;
                   }
               }
               if(stdd !== ""){
                   return true;
               }
            }
            return false;
        },
     setAllDatas(MyClass){
            this.selectedClass = MyClass.id;
            this.year = MyClass.year;
            this.degree = MyClass.degree.toUpperCase();
            this.department = MyClass.department.toUpperCase();
	    this.semester = MyClass.semester;
            this.section = MyClass.section;       
        },
        getStaffEID(user_id){

        var SID = Number(user_id);
                 var status = "";
            var aData;
            var child;
      aData = this.Staffs;
      for (var child in aData) {
        if(aData[child].id === SID) {
          status = aData[child].eid;
        }
      }
      if(status) {
        return status;
      } else {
        return "null";
      }
},

     getStaffEID_withSub(user_id){

        var SID = Number(user_id);
                 var status = "";
            var aData;
            var child;
      aData = this.Staffs;
      for (var child in aData) {
        if(aData[child].id === SID) {
          status = aData[child].eid;
        }
      }
      if(status) {
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
        selctionSubmit(){
            if(this.degree === "" && this.department === "" && this.year === "" && this.semester === "" && this.section === ""){
                this.formError = "Please fill all fields in the form!";
            }else{
                this.SelectionBar=false;
            }
        },

// ElectiveForm(sub_type){
//     if(sub_type === 1){
//         this.checkbox_Elect = true;
//     }
// },


CreateSubject(){

// if(Number(this.subject_type) === 1){
//     let CNFM = confirm('Make Sure you have selected all students dor this class this action can\'t be Edited');
// }else{
//     let CNFM = confirm('Make Sure you have Entered Correct Details');
// }    
  let CNFM = confirm('Make sure you have selected correct values. with this action The students can\'t be changed for the semester ')

if(CNFM === true){

this.SelectSearch = "";

    var status = "";
    var status2 = "";
    var status3 = "";
            var aData;
            var child;
      aData = this.Staffs;
      for (var child in aData) {
        if(aData[child].eid === this.staff_id) {
          status = aData[child].id;
        }
        // if(this.staff_id2 !== "" && aData[child].id === this.staff_id2) {
        //   status2 = aData[child].eid;
        // }
        // if(this.staff_id3 !== "" && aData[child].id === this.staff_id3) {
        //   status3 = aData[child].eid;
        // }
      }
// let stat2 = 'nodata';
// let stat3 = 'nodata';

if((status !== "")){
    this.error_staffid = false;
    // this.error_staffid2 = false;
    // this.error_staffid3 = false;


    var Selected_Studs = [];

if(this.subject_type !== 0){
    var std = this.filteredStudents;
    var studentsSelect = [];
for(var child in std){
    if (std[child].selected === true){
        studentsSelect.push(std[child].id);
    }
}
Selected_Studs = studentsSelect;
}else{
    Selected_Studs = [];
}

                let Formdata = {
                    subject_name:this.subject_name.replace(/\s+/g,' ').trim(),
                    subject_id:this.subject_id.trim(),
                    staff_id:this.staff_id.trim(),
                    subject_type:this.subject_type,
                    degree:this.degree,
                    department:this.department,
                    year:this.year,
                    semester:this.semester,
                    section:this.section,
                    selected_stds: Selected_Studs
                }

                // console.log(Formdata);

                if(this.staff_id2 !== ""){Formdata.staff_id2 = this.staff_id2}
                if(this.staff_id3 !== ""){Formdata.staff_id3 = this.staff_id3}

                console.log(Formdata);

                fetch('/api/schedule/subject/store', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    if(data['status'] === 'success'){
                        alert('Your subject is created.');
                    }else{
                        alert('Somthing went wrong. try later');
                    }
                    // console.log(data);
                    this.getAllShedules();
        this.getAllSubjects();
                }).catch(err => {
                    console.log(err);
                });
                this.getAllShedules();
        this.getAllSubjects();
                this.subject_name = "";
                this.subject_id = "";
                this.staff_id = "";
                this.subject_type = 0; 
                this.Edit_staff_id2 = "";
                this.Edit_staff_id3 = "";
    }else{
        this.error_staffid = true;
    }
      this.subject_name = "";
                this.subject_id = "";
                this.staff_id = "";
                this.subject_type = 0; 
                this.Edit_staff_id2 = "";
                this.Edit_staff_id3 = "";
this.getAllShedules();
this.getAllSubjects();

this.filteredStudents.forEach(element => {
    element.selected = "";
});
}
},




EditSubjectForm(Subject){
    this.Edit_subject_type = Subject.subject_type;
    this.Edit_sub_id = Subject.id;
    this.Edit_subject_name = Subject.subject_name;
    this.Edit_subject_id = Subject.subject_id;
    this.Edit_staff_id = this.getStaffEID_withSub(Subject.user_id);
    this.Edit_staff_id2 = this.getStaffEID_withSub(Subject.user_id2);
    this.Edit_staff_id3 = this.getStaffEID_withSub(Subject.user_id3);
    this.Edit_subject_type = Subject.subject_type;
    this.subject_Students = Subject.students;

    for (let index = 0; index < this.subject_Students.length; index++) {
        const element = this.subject_Students[index];
        element.added_now = false;
        element.removed_now = false;
    }

},
UpdateSubject(){


    var status = "";
            var aData;
            var child;
      aData = this.Staffs;
      for (var child in aData) {
        if(aData[child].id === this.Edit_staff_id) {
          status = aData[child].eid;
        }
      }

if(status !== null){
    this.error_staffid = false;
    

                let Formdata = {
                    Edit_sub_id:this.Edit_sub_id,
                    Edit_subject_name:this.Edit_subject_name.replace(/\s+/g,' ').trim(),
                    Edit_subject_id:this.Edit_subject_id.trim(),
                    Edit_staff_id:this.Edit_staff_id.trim(),
                    Edit_staff_id2:this.Edit_staff_id2.trim(),
                    Edit_staff_id3:this.Edit_staff_id3.trim(),
                    Edit_subject_type:this.Edit_subject_type,
                    degree:this.degree,
                    department:this.department,
                    year:this.year,
                    semester:this.semester,
                    section:this.section,
                }

                console.log(Formdata);

                fetch('/api/schedule/subject/update', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    // console.log(data);
                    if(data['status'] === 'success'){
                        alert('Subject Updated Successfully');
                    }
                    this.getAllShedules();
        this.getAllSubjects();
                }).catch(err => {
                    console.log(err);
                });
                this.getAllShedules();
        this.getAllSubjects();

                this.subject_name = "";
                this.subject_id = "";
                this.staff_id = "";
                 this.staff_id2 = "";
                  this.staff_id3 = "";
                this.subject_type = "";
    }else{
        this.error_staffid = true;
    }
this.getAllShedules();
        this.getAllSubjects();
         this.subject_name = "";
                this.subject_id = "";
                this.staff_id = "";
                 this.staff_id2 = "";
                  this.staff_id3 = "";
                this.subject_type = "";
            },

RemoveSchedules(){
    let Formdata = {
                    Hour_selected:this.Hour_selected,
                    Day_selected:this.Day_selected.toLowerCase(),
                    degree:this.degree,
                    department:this.department,
                    year:this.year,
                    semester:this.semester,
                    section:this.section,
                }
                //console.log(Formdata);
                fetch('/api/schedule/remove', {
                    method: "post",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                   
                   this.getAllSubjects();
                this.getAllShedules();
                    //console.log(data);
                }).catch(err => {
                    console.log(err);
                });
                this.getAllSubjects();
                this.getAllShedules();
},

            // assignElectiveSchedule(){
            //     var subb;
            //     var staff;
            //     var Subs;
            //     Subs = this.filterSubjects;
            //     for(var child in Subs){
            //         if (Subs[child].subject_name === this.Subject_selected){
            //             subb = Subs[child].idconsole.log(data);;
            //             staff = Subs[child].staff_id;
            //         }
            //     }
            //     let Formdata = {
            //         Subject_selected:subb,
            //         Hour_selected:this.Hour_selected,
            //         Day_selected:this.Day_selected.toLowerCase(),
            //         staff_id: staff,
            //         degree:this.degree,
            //         department:this.department,
            //         year:this.year,
            //         semester:this.semester,
            //     }

            //     fetch('/api/electiveschedule/store', {
            //         method: "post",
            //         body: JSON.stringify(Formdata),
            //         headers:{
            //             "content-type":"application/json"
            //         }
            //     }).then(response => {
            //         return response.json();
            //     }).then(data => {
                   
            //         console.log(data);
            //     }).catch(err => {
            //         console.log(err);
            //     });
            //     this.getAllSubjects();
            //     this.getAllShedules();
            // },
        assignSchedule(){

 var subb;
 var staff;
 var is_elect;
                var Subs;
     
                Subs = this.filterSubjects;

                for(var child in Subs){
                    if (String(Subs[child].subject_name) == String(this.Subject_selected)){
                        subb = Subs[child].id;                        
                        staff = Subs[child].user_id;
                        is_elect = Subs[child].subject_type;
                    }
                }
                var OSub = "";
                for(var child in Subs){
                    if (String(Subs[child].subject_name) == String(this.OldSubject_selected)){
                        OSub = Subs[child].id;
                    }
                }

                // console.log(subb);

                var Selected_Studs = [];

                // if(Number(is_elect) === 1){

                 
                //     var std = this.filteredStudents;

                //     var studentsSelect = [];

                //     for(var child in std){
                //     if (std[child].selected === true){
                //         studentsSelect.push(std[child]);
                //     }
                //     }
                //     Selected_Studs = studentsSelect;
                // }else{
                //     Selected_Studs = [];
                //     is_elect = 0;
                // }

                let Formdata = {
                    Subject_selected:subb,
                    Hour_selected:this.Hour_selected,
                    Day_selected:this.Day_selected.toLowerCase(),
                    // is_elective: this.checkbox_Elect,
                    staff_id: staff,
                    degree:this.degree,
                    department:this.department,
                    year:this.year,
                    semester:this.semester,
                    section:this.section,
                    Selected_Studs: Selected_Studs,
                    delete_all:this.Correction_check,
                    addmore:this.add_morethen_one,
                    update_one:this.update_one,
                    oldsubject:OSub
                }
                
                console.log(Formdata);
                fetch('/api/schedule/store', {
                    method: "POST",
                    body: JSON.stringify(Formdata),
                    headers:{
                        "content-type":"application/json"
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                   
                   this.getAllSubjects();
                this.getAllShedules();
                    //console.log(data);
                }).catch(err => {
                    console.log(err);
                });
                this.getAllSubjects();
                this.getAllShedules();
            },
            getAllStudents(){
            fetch('/api/students')
            .then(res => {
                return res.json();
            })
            .then(data => {
                this.Studs = data;
                // console.log('studs', data);
            }).catch(err => console.log(err));
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
                    if (Schedules[child].day === day && Schedules[child].hour === hour && Schedules[child].is_changed_inbetween !== 1){
                        sche.push(Schedules[child]);
                    }
                }

                if(sche){
                    return sche;
                }else{
                    return '__';
                }
            },
              //Fetch sem
        fetchSem(){
            fetch('/api/fetchsemester')
            .then(res => res.json())
            .then(data => {
                //console.log(data);
                this.AcademiC = data;
            })
            .catch(err => console.log(err));
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
     showClasses(){
                fetch('/api/studentEditPermissions/staff/'+this.authenticateduser.id+'')
            .then(res => { return res.json(); })
                .then(data => {
                    this.showClasse = data;
                    console.log(data);
                }).catch(err => console.log(err));
    },

     EditATpermissions(){
                fetch('/api/studentEditPermissions')
            .then(res => { return res.json(); })
                .then(data => {
                    this.EditATPermissions = data;
                   
                }).catch(err => console.log(err));
            }

},

computed:{
   
    disableEditIf(){
        if(this.section !== ""){
            if(this.filterPermissions.length === 1 && this.filterPermissions[0].schedule_edit_status === 1){
            return false;
        }else{
            return true;
            }
        }
        
            // let studPermission = this.filterPermissions[0];
            // if(studPermission === 1){
            //     return false;
            // }else{
            //     return true;
            // }
        },
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
    filteredStudents(){
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let degree = this.degree;
         let section = this.section;
         let SelectSearch = this.SelectSearch;
        
        if(department === "" && year === "" && degree === "" && semester === "" && section === ""){
          return this.Studs;
        }else{
          return this.Studs.filter(function(student){

              let stdepartment = student.department.toLowerCase();
              let styear = Number(student.year);
              let stsemester = Number(student.semester);
              let stdegree = student.degree.toLowerCase();
              let stsection = student.section.toLowerCase();
              let stregno = student.reg_no.toLowerCase();

            return (department === "" || stdepartment === department.toLowerCase()) 
            && (year === "" || styear === Number(year)) 
            && (degree === "" || stdegree === degree.toLowerCase())
            && (semester === "" || stsemester === Number(semester))
            && (section === "" || stsection === section.toLowerCase())
            && (SelectSearch === "" || stregno.match(SelectSearch));
          });
          }
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
                    (degree === "" || degree === Schedule.degree)
                    && (department === "" || department === Schedule.department)
                    && (year === "" || Number(year) === Schedule.year)
                    && (semester === "" || Number(semester) === Schedule.semester)
                    && (section === "" || section === Schedule.section)
                    );
            })
        }

    },

    filterPermissions(){
         let degree = this.degree;
        let department = this.department;
        let year = this.year;
        let semester = this.semester;
        let section = this.section;
        let permissions = this.EditATPermissions;
        let authenticateduser = this.authenticateduser;
         if (degree === "" && year === "" && semester === "" && section === "") {
            return null;
        }else{
            return permissions.filter(function(perm){
                return (
                (perm.class_advisor === authenticateduser.id || perm.class_advisor2 === authenticateduser.id)
                && (degree === "" || degree.toLowerCase() === perm.degree.toLowerCase())
                    && (year === "" || Number(year) === perm.year)
                    && (section === "" || section.toLowerCase() === perm.section.toLowerCase())
                );
            });
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
    
}


}
</script>


<style scoped>
.options-bar h4{
    padding: 10px;
}
.tabs li{
    display: inline;
}
.tabs li a{
    color:#232627;
}
.tabs li.active a{
    color:#00AAFF;
}
.btn-select-submit{
    /* float: right; */
}
.update-schedules-form table td{
    padding: 15px;
}
.update-schedules-form table tr{
    border-bottom: 1px solid #e8e8e8;
}
.schedule-list li{
    list-style: none;
    border: 1px solid #f2f2f2;
    width: 150px;
    padding: 10px;
    box-shadow: 1px 1px 2px;
    display: inline-block;
    cursor: pointer;
}
.schedule-list li:not(:last-child){
    margin-right: 20px;
}
.schedule-list li.highlight{
    border: 2px solid #00AAFF;
}
    .remove-schedules{
        margin-top: 25px;
    }
    .table-striped .head-select, .table-striped .head-regno{
        text-align: center;
    }
    .table-striped .data-checkbox, .table-striped .data-reg-no{
        text-align: center;
    }
    .table-striped tr{
        height: 50px;
    }
</style>

