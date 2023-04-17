@include('config')
@include('layouts.header')
@include('layouts.sidebar')

	        <div class="col-sm-10 offset-sm-2 ">
                @include('message')
	            <div class="card">
	            	<div class="card-header">
                        @include('header_card',['element'=>'Profile','name'=>'','noreport'=>true,'nobutton'=>true])
	            	</div>
	            	<div class="card-body p-0">
	            		<div class="col-md-6">
	            			<form method="post" id="form" class="no-reset"enctype="multipart/form-data" action="<?=route('user')?>">
                                @csrf
                                <div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right"></label>
						            	<div class="col-md-8">
											<span id="user_uploaded_image" class="mt-2">
                                                <img src="<?php echo $user->profile;  ?>" class="img-fluid img-thumbnail rounded-circle" width="200" height="200"/>

											</span>
						            	</div>
						            </div>
					          	</div>
	            				<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">User Name <span class="text-danger">*</span></label>
						            	<div class="col-md-8">
						            		<input type="text" name="username" id="user_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" value="<?php echo $user->username; ?>" />
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">User Contact No. <span class="text-danger"></span></label>
						            	<div class="col-md-8">
						            		<input type="text" name="contact_no" id="user_contact_no" class="form-control" data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="16" data-parsley-trigger="keyup" value="<?php echo $user->contact_no; ?>" />
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">User Email <span class="text-danger">*</span></label>
						            	<div class="col-md-8">
						            		<input type="text" name="email" id="user_email" class="form-control" required data-parsley-type="email" data-parsley-maxlength="150" data-parsley-trigger="keyup" value="<?php echo $user->email; ?>" />
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">User Profile</label>
						            	<div class="col-md-8">
						            		<input type="file" name="image" class="file_upload" id="user_image" data-allowed_file='[<?php echo '"' . implode('","', ALLOWED_IMAGES) . '"'?>]' data-upload_time="later" accept="<?php echo "image/" . implode(", image/", ALLOWED_IMAGES);?>"/>
											<div class="text-muted">Only <?php  echo join(' and ', array_filter(array_merge(array(join(', ', array_slice(ALLOWED_IMAGES, 0, -1))), array_slice(ALLOWED_IMAGES, -1)), 'strlen'));?> extensions are supported</div>
												<input type="hidden" name="hidden_user_image" value="<?php echo $user->profile; ?>" />
												<input type="hidden" id="hidden_id" value=" {{$user->id}}" />
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group text-center">
					          		<button type="submit"  id="submit_button" class="btn btn-success"><i class="far fa-save"></i> Save</button>
					          	</div>
	            			</form>
	            		</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>

</body>
</html>
@include('layouts.footer')
<script>
    url=url+"/"+$('#hidden_id').val()+'/update';

</script>

