@extends('layouts.app')

@section('content')
 
    
<div class="container">
    <div id="message"></div>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewRole"> Create New Roles</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Description</th>
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
                <form id="roleForm" name="roleForm" class="form-horizontal">
                   <input type="hidden" name="role_id" id="role_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Role Name <span style="color:red;">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Role Name" value="" maxlength="50" required="">
                            <span class="error-text" id="error_name" style="color:red;"></span>
                        </div>
                    </div>
     
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Details <span style="color:red;">*</span></label>
                        <div class="col-sm-12">
                            <textarea id="description" name="description" required="" placeholder="Enter description" class="form-control"></textarea>
                            <span class="error-text" id="error_description" style="color:red;"></span>
                        </div>
                    </div></br>
      
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
                <!-- <form id="roleForm" name="roleForm" class="form-horizontal"> -->
                   <!-- <input type="hidden" name="role_id" id="role_id"> -->
                    <div class="form-group">
                        <label for="name" class="col-sm-6 control-label">Role Name : &nbsp;&nbsp;&nbsp; <span id="roleName"></span></label>
                    </div>
     
                    <div class="form-group">
                        <label class="col-sm-12 control-label">Details : &nbsp;&nbsp;&nbsp; <h4 id="roleDescription"></h4></label>
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
        ajax: "{{ route('role.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    $('#createNewRole').click(function () {
        $('#saveBtn').val("create-role");
        $('#role_id').val('');
        $('#roleForm').trigger("reset");
        $('#modelHeading').html("Create New Role");
        $('#ajaxModel').modal('show');
    });
    $('body').on('click', '.showRole', function () {
      var role_id = $(this).data('id');
      $.get("{{ route('role.index') }}" +'/' + role_id, function (data) {
          $('#modelshowHeading').html("Show Role");
          $('#showModel').modal('show');
          // $('#role_id').val(data.id);
          $('#roleName').text(data.name);
          $('#roleDescription').text(data.description);
      })
    });
    $('body').on('click', '.editRole', function () {
      var role_id = $(this).data('id');
      $.get("{{ route('role.index') }}" +'/' + role_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Role");
          $('#saveBtn').val("edit-role");
          $('#ajaxModel').modal('show');
          $('#role_id').val(data.id);
          $('#name').val(data.name);
          $('#description').val(data.description);
      })
   });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');
        let name = $('#name').val();
        let description = $('#description').val();
        if(name == "" || name == null){
            $('#error_name').text('Role Name is required');
            return false;
        }else{
            $('#error_name').text('');
        }
        if(description == "" || description == null){
            $('#error_description').text('Description is required');
            return false;
        }else{
            $('#error_description').text('');
        }
    
        $.ajax({
          data: $('#roleForm').serialize(),
          url: "{{ route('role.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#roleForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              
              if(data.success){
                $('#message').html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+data.success+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }                                                 
              table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
               // $('#message_').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Please enter a name<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            
              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteRole', function () {
     
        var role_id = $(this).data("id");
        confirm("Are You sure want to delete !");
      
        $.ajax({
            type: "DELETE",
            url: "{{ route('role.store') }}"+'/'+role_id,
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