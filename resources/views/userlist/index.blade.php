@extends('layout.app')


@section('content')
<style>
.user_class_field, .user_hod_department_be_field, .user_hod_department_me_field{
display:none;
}
</style>
<div class="panel panel-headline">

<!-- Trigger the modal with a button -->
<div class="usercreatebtn">
    <h4>Users Profile</h4>
        <button type="button" class="btn btn-info btn-lg btn-primary" data-toggle="modal" data-target="#myModal">New user</button>

</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create new user</h4>
      </div>
      <div class="modal-body">
            <div class="card border-secondary">
                    <div class="card-body userform">
                        <form class="form" id="user_form">
                            <div class="row">
                                <div class="col-sm-6">
                                        <div class="form-group">
                                                <label for="user_name">Name</label>
                                                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="full name">
                                            </div>
                                </div>
                                <div class="col-sm-6">
                                        <div class="form-group">
                                                <label for="user_loginid">Login ID</label>
                                                <input type="text" class="form-control" id="user_loginid" name="user_loginid" placeholder="full name">
                                            </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="user_email">Email</label>
                                <input type="email" class="form-control" id="user_email" name="user_email" placeholder="email@gmail.com" required="">
                            </div>
                            <div class="form-group">
                                <label for="user_role">Role</label>
                                <select id="user_role" name="user_role" class="form-control">
                                    <option value="">Choose</option>
                                </select>
                            </div>
<div class="form-group">
                <label><input type="checkbox" id="is_advisor" name="is_advisor">Is Class Advisor</label>

          </div>
<div class="form-group">
                <label><input type="checkbox" id="is_hod_be" name="is_hod_be">Is Head of Bachlers Department</label>
          </div>
<div class="form-group">
                <label><input type="checkbox" id="is_hod_me" name="is_hod_me">Is Head of Masters Department</label>
          </div>

<div class="row">
<div class="col-sm-6">
                                        <div class="form-group user_hod_department_be_field">
                                                <label for="user_hod_department_be">Head of Bachlers Department</label>
                                                <select id="user_hod_department_be" name="user_hod_department_be" class="form-control">
                                                        <option value="">Choose</option>
                                                    </select>
                                            </div>
                                </div>
<div class="col-sm-6">
                                        <div class="form-group user_hod_department_me_field">
                                                <label for="user_hod_department_me">Head of Masters Department</label>
                                                <select id="user_hod_department_me" name="user_hod_department_me" class="form-control">
                                                        <option value="">Choose</option>
                                                    </select>
                                            </div>
                                </div>


</div>

                            <div class="row">
                                <div class="col-sm-6">
                                        <div class="form-group">
                                                <label for="user_department">Working Department</label>
                                                <select  id="user_department" name="user_department" class="form-control">
                                                        <option value="">Choose</option>
                                                    </select>
                                            </div>
                                </div>
                                <div class="col-sm-6">
                                        <div class="form-group user_class_field">
                                                <label for="user_classid">Class</label>
                                                <select name="user_classid" id="user_classid" class="form-control">
                                                        <option value="">Choose</option>
                                                    </select>
                                            </div>
                                </div>
                            </div>
          

                            <div class="form-group">
                                <label for="inputPassword3">Joined Date</label>
                                <input type="date" class="form-control" id="date" required="">
                            </div>
                            <div class="form-group">
                                <label for="user_status">Status :</label>
                                <label class="switch">
                                        <input type="checkbox" >
                                        <span class="slider round"></span>
                                      </label>
                            </div>
                            <div class="usersubmitbtn">
                                    <button type="submit" class="btn btn-primary btn-success">Create</button>                            </div>
                        </form>
                    </div>
                </div>
    </div>

    </div>

  </div>
</div>


      
{{-- User list table --}}
<div class="table-responsive">

        <!--Table-->
        <table class="table">
      
          <!--Table head-->
          <thead>
            <tr>
              <th>S.No</th>
              <th class="th-lg">ID</th>
              <th class="th-lg">Name</th>
              <th class="th-lg">Email</th>
              <th class="th-lg">Role</th>
              <th class="th-lg">Working Department</th>
              <th class="th-lg">Class</th>
              <th class="th-lg">Edit</th>
              <th class="th-lg">View</th>
            </tr>
          </thead>
          <!--Table head-->
      
          <!--Table body-->
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>AD12345</td>
              <td>Admin</td>
              <td>admin@gmail.com</td>
              <td>Super admin</td>
              <td>null</td>
              <td>null</td>
              <td><a href="#" class="btn btn-sm btn-primary">Edit</a></td>
              <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
            </tr>

          </tbody>
          <!--Table body-->
      
        </table>
        <!--Table-->
      
      </div>
      
    
 </div>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script>


$(document).ready(function() {
//Is Advisor Change
$('#is_advisor').on('change',function(e){
	e.preventDefault();
if($(this).is(':checked')){
$('.user_class_field').css('display','block');
}else{
$('.user_class_field').css('display','none');
}
});
//Is HOD BE
$('#is_hod_be').on('change',function(e){
	e.preventDefault();
if($(this).is(':checked')){
$('.user_hod_department_be_field').css('display','block');
}else{
$('.user_hod_department_be_field').css('display','none');
}
});
//Is HOD ME
$('#is_hod_me').on('change',function(e){
	e.preventDefault();
if($(this).is(':checked')){
$('.user_hod_department_me_field').css('display','block');
}else{
$('.user_hod_department_me_field').css('display','none');
}
});


// Save User
    $('#user_form').on('submit',function(e){
e.preventDefault();
var formData = $('#user_form').serialize();
console.log(formData);
$.ajax({
      type: 'POST',
      url: "<?php echo url('/');?>/users",
      data: formData ,
dataType: "json",
headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
      success: function(resultData) { console.log(resultData); },
      error: function(error) { console.log(error); }

});

});
});

</script>
@endsection