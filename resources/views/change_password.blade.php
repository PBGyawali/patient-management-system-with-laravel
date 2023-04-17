@include('config')
@include('layouts.header')
@include('layouts.sidebar')


	        <div class="col-sm-10 offset-sm-2 py-4">
	        	@include('message')
	            <div class="card">
	            	<div class="card-header">
	            		@include('header_card',['element'=>'Change password','name'=>'','noreport'=>true,'nobutton'=>true])
	            	</div>
	            	<div class="card-body">
	            		<div class="col-md-3">&nbsp;</div>
	            		<div class="col-md-6">
                            <form method="post" id="form" action="<?php echo route('password').'/'.auth()->user()->id;?>">
                                @csrf
	            				<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">Current Password <span class="text-danger">*</span></label>
						            	<div class="col-md-8">
						            		<input type="password" name="password" id="current_password" class="form-control" data-parsley-trigger="keyup" />
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">New Password <span class="text-danger">*</span></label>
						            	<div class="col-md-8">
						            		<input type="password" name="new_password" id="new_password" class="form-control"   data-parsley-trigger="keyup" />
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">Re-enter Password <span class="text-danger">*</span></label>
						            	<div class="col-md-8">
						            		<input type="password" name="new_password_confirmation" id="re_enter_new_password" class="form-control"  data-parsley-trigger="keyup" />
						            	</div>
						            </div>
					          	</div>
					          	<br />
					          	<div class="form-group text-center">
					          		<button type="submit"  id="submit_button" class="btn btn-success"><i class="fas fa-lock"></i> Change</button>
					          	</div>
	            			</form>
	            		</div>
	            		<div class="col-md-3">&nbsp;</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>
</body>
</html>
@include('layouts.footer')

