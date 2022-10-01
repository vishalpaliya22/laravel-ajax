@extends('layouts.app')

@section('content')
 
    
<div class="container">
    <div id="message"></div>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewEmployee"> Create New Employee</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Employee Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Gender</th>
                <th width="300px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
   
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="employeeForm" name="employeeForm" class="form-horizontal" enctype="multipart/form-data">
                   <input type="hidden" name="employee_id" id="employee_id">
                    
                    <div class="form-group">
                        <label>Role:</label>
                        <select class="form-control" name="role_id" id="role_id">
                            <option value="{{old('role_id')}}" disabled selected>{{old('role_id') ?? 'Choose your role'}}</option>
                            @foreach ($userRole as $role)
                            <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="name" class="col-sm-2 control-label">Name</label>   
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="images" class="col-sm-3 control-label">Upload Picture</label>
                                <input type="file" class="form-control" name="images" id="upload_file" accept="image/*" multiple/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email </label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value=""required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone_no" class="col-sm-4 control-label">Phone Number </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Enter phone number" value=""required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gender" class="col-sm-4 control-label">Gender </label>
                        <div class="col-md-6">
                            <input type="radio" name="gender" value="male" id="male"  required=""> Male<br>
                            <input type="radio" name="gender" value="female" id="female" required=""> Female            
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="col-sm-2 control-label">Address </label>
                        <div class="col-sm-12">
                            <textarea id="address" name="address" required="" placeholder="Enter address" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-sm-2 control-label">Status </label>
                        <select class="form-control" name="status" id="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    </br>
     
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="showModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelshowHeading"></h4>
            </div>
            <div class="modal-body">
                <!-- <form id="employeeForm" name="employeeForm" class="form-horizontal"> -->
                   <!-- <input type="hidden" name="employee_id" id="employee_id"> -->
                    <div class="form-group">
                        <label for="name" class="col-sm-12 control-label">Name : &nbsp;&nbsp;&nbsp; 
                            <div id="employeeProfilePic"  width="150" name="image" height="150" ></div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-12 control-label">Name : &nbsp;&nbsp;&nbsp; <span id="employeeName"></span></label>
                    </div>
     
                    <div class="form-group">
                        <label class="col-sm-12 control-label">Email : &nbsp;&nbsp;&nbsp; <span id="employeeEmail"></span></label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label">Phone Number : &nbsp;&nbsp;&nbsp; <span id="employeePhoneNo"></span></label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label">Gender : &nbsp;&nbsp;&nbsp; <span id="employeeGender"></span></label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label">Address : &nbsp;&nbsp;&nbsp; <span id="employeeAddress"></h4></label>
                    </div></br>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>


@endsection
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>  
<script type="text/javascript">
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('employee.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'name', name: 'roleName'},
            {data: 'email', name: 'email'},
            {data: 'phone_number', name: 'phone_number'},
            {data: 'gender', name: 'gender'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    $('#createNewEmployee').click(function () {
        $('#saveBtn').val("create-employee");
        $('#employee_id').val('');
        $('#employeeForm').trigger("reset");
        $('#modelHeading').html("Create New Employee");
        $('#ajaxModel').modal('show');
    });
    $('body').on('click', '.editEmployee', function () {
      var employee_id = $(this).data('id');
      $.get("{{ route('employee.index') }}" +'/' + employee_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Employee");
          $('#saveBtn').val("edit-employee");
          $('#ajaxModel').modal('show');
          $('#employee_id').val(data.id);
          $('#name').val(data.name);
          $('#email').val(data.email);
          $('#phone_no').val(data.phone_number);
          $('#address').val(data.address);
          if(data.gender === "male"){
            $('#male').attr('checked', true);
          }else{
            $('#female').attr('checked', true);
          }
          if(data.role_id){
            $('#role_id').attr('selected', true);
            // $('#role_id :selected').text();
          }

      })
   });
    $('body').on('click', '.showEmployee', function () {
      var employee_id = $(this).data('id');
      $.get("{{ route('employee.index') }}" +'/' + employee_id, function (data) {
          $('#modelHeading').html("Show Employee");
          $('#saveBtn').val("edit-employee");
          $('#showModel').modal('show');
          // $('#employee_id').val(data.id);
          $('#employeeName').text(data.name);
          $('#employeeEmail').text(data.email);
          $('#employeePhoneNo').text(data.phone_number);
          $('#employeeAddress').text(data.address);
          $('#employeeGender').text(data.gender);
          $('#employeeAddress').text(data.address);
          var path = "{!! asset('"+  data.profile_pic + "') !!}";
          $("#employeeProfilePic").html('<img src="'+path+'" />');
      })
   });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');

         var formData = new FormData();

        let name = $("input[name=name]").val();
        var photo = $('#upload_file').prop('files')[0];   
        let email = $("input[name=email]").val();
        let phone_no = $("input[name=phone_no]").val();
        let gender = $("input[name=gender]").val();
        let address = $("#address").val();
        let role_id = $("#role_id").val();
        let status = $("#status").val();
        let _token = $('meta[name="csrf-token"]').attr('content');

        formData.append('name', name);
        formData.append('photo', photo);
        formData.append('email', email);
        formData.append('phone_no', phone_no);
        formData.append('gender', gender);
        formData.append('address', address);
        formData.append('role_id', role_id);
        formData.append('status', status);
        $.ajax({
          // data: $('#employeeForm').serialize(),
          /*data: formData,
          url: "{{ route('employee.store') }}",
          type: "POST",
          dataType: 'json',*/
          url: "{{ route('employee.store') }}",
            type: 'POST',
            contentType: 'multipart/form-data',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
          success: function (data) {
     
              $('#employeeForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              
              if(data.success){
                $('#message').html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+data.success+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }                                                 
              table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteEmployee', function () {
     
        var employee_id = $(this).data("id");
        confirm("Are You sure want to delete !");
      
        $.ajax({
            type: "DELETE",
            url: "{{ route('employee.store') }}"+'/'+employee_id,
            success: function (data) {
                
                if(data.success){
                    $('#message').html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+data.success+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }  
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>
</body>
</html>