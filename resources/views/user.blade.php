@include('config')
@include('layouts.header')
@include('layouts.sidebar')
	        <div class="col-sm-10 offset-sm-2 py-4">
                @include('message')
	            <div class="card">
	            	<div class="card-header">
	            		@include('header_card',['element'=>'user','name'=>'Management'])
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="table">
	            				<thead>
	            					<tr>
                                        <th class="id">ID</th>
	            						<th class="profile">Image</th>
	            						<th class="username">User Name</th>
										<th class="contact_no">User Contact No.</th>
										<th class="email">User Email</th>
                                        <th class="created_at">Created On</th>
                                        <th class="user_status" >Status</th>
										<th class="action">Action</th>
	            					</tr>
                                </thead>
	            			</table>
	            		</div>
	            	</div>
	            </div>
	        </div>
</body>
</html>

<div id="Modal" class="modal fade">
  	<div class="modal-dialog">
        <form method="post" id="form" action="<?php echo route('user');?>"enctype="multipart/form-data">
            @csrf
      		<div class="modal-content">
        		<div class="modal-header">
                <span class="text-center position-absolute w-100"id="form_message" style="z-index:50"></span>
          			<h4 class="modal-title" id="modal_title">Add User</h4>
          		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Name <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="text" name="username" id="user_name" class="form-control"  required data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Contact No. <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="text" name="contact_no" id="user_contact_no" class="form-control"  data-parsley-maxlength="12" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Email <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="email" name="email" id="user_email" class="form-control" required data-parsley-type="email"data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>

		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Password <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="password" name="password" id="user_password" class="form-control" data-parsley-maxlength="16" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Type <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
							<select name="user_type" id="user_type" class="form-control" >
								<option value="" selected>Select User type</option>
			            		<option value="user">User</option>
								<option value="admin">Admin</option>
								<option value="doctor">Doctor</option>
                                <option value="master">Master</option>
                                <option value="patient">Patient</option>
								</select>

			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Profile</label>
			            	<div class="col-md-8">
			            		<input type="file" name="image" id="user_image" />
								<span id="user_uploaded_image"></span>
			            	</div>
			            </div>
		          	</div>
        		</div>
        		<div class="modal-footer">
          			<button type="submit"  id="submit_button" class="btn btn-success">Add</button>
          			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

@include('layouts.footer')
<script>

        function update(data){
        $('#user_password').removeAttr('required data-parsley-minlength data-parsley-trigger' );
        $('#user_name').val(data.username);
        $('#user_contact_no').val(data.contact_no);
        $('#user_email').val(data.email);
        $('#user_type').val(data.user_type);
        $('#user_uploaded_image').html('<img src="'+data.profile+'" class="img-fluid img-thumbnail" width="75" height="75" /><input type="hidden" name="hidden_user_image" value="'+data.profile+'" />');
        }
</script>


