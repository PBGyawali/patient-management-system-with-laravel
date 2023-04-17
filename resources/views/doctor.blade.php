@include('config')
    @include('layouts.header')
        @include('layouts.sidebar')

	        <div class="col-sm-10 offset-sm-2 py-4">
                @include('message')
	            <div class="card">
                    <div class="card-header">
                        @include('header_card',['element'=>'doctor'])
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="table">
	            				<thead>
	            					<tr>
	            						<th class="doctor_name">Doctor Name</th>
										<th class="department_name">Department</th>
										<th class="specialization_name">Specialization</th>
										<th class="email">Email</th>
										<th class="contact_no">Contact no</th>
										<th class="address">Address</th>
										<th class="doctor_status">Status</th>
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
    	<form method="post" action="<?= route('doctor');?>"id="form">
      		<div class="modal-content">
                <span class="text-center position-absolute w-100" id="form_message" style="z-index:50"></span>
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add doctor</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Doctor Name</label>
			            	<div class="col-md-8">
			            		<input type="text" name="doctor_name" id="doctor_name" class="form-control"  data-parsley-pattern="/^[a-zA-Z\s.]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Identifying Email</label>
			            	<div class="col-md-8">
								<select name="doctor_email" id="doctor_email" class="form-control" data-parsley-trigger="on change">
			            		<option value="">Select Doctor Email</option>
									<?php echo $email; ?>
									</select>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Department</label>
			            	<div class="col-md-8">
			            		<select name="department_id" id="doctor_department" class="form-control" required data-parsley-trigger="on change">
			            			<option value="">Select Departent</option>
			            			<?php echo $department; ?>
			            		</select>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Specialization</label>
			            	<div class="col-md-8">
			            		<select name="specialization_id" id="doctor_specialization" class="form-control" required data-parsley-trigger="on change">
									<option value="">Select specialization</option>
									<?php echo $specialization; ?>
			            		</select>
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
@include('detail_modal',['name'=>'doctor'])
@include('layouts.footer')
<script>
    function update(data){
        $('#doctor_name').val(data.doctor_name);
        $('#doctor_email').val(data.email);
        $('#doctor_department').val(data['department_id']);
        $('#doctor_specialization').val(data['specialization_id']);
        }
</script>

