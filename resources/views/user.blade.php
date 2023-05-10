@include('layouts.new_header')
        @include('layouts.new_sidebar')
        <div class="page-wrapper">
			@include('message')
            <div class="content">
                <div class="row">
                    @include('page_header',['element'=>'user'])
                </div>
				<div class="row">
					<div class="col-md-12">
	            		<div class="table-responsive">
	            			<table class="table table-striped" id="table">
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
			
		</div>
		@include('delete_modal',['element'=>'user'])

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
			            		<input type="text" name="username" id="username" class="form-control"  required data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Contact No. <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="text" name="contact_no" id="contact_no" class="form-control"  data-parsley-maxlength="12" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Email <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="email" name="email" id="email" class="form-control" required data-parsley-type="email"data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>

		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Password <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="password" name="password" id="password" class="form-control" data-parsley-maxlength="16" data-parsley-trigger="keyup" />
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
			            		<input type="file" name="image" id="user_image" class="file_uploaded"/>
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
@include('layouts.footer_script')

