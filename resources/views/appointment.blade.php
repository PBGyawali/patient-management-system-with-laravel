@include('config')
@include('layouts.header')
@include('layouts.sidebar')

	        <div class="col-sm-10 offset-sm-2 py-4">
                @include('message')
	            <div class="card">
	            	<div class="card-header">
                        @include('header_card',['element'=>'appointment'])
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="table">
	            				<thead>
	            					<tr>
                                        <th class="appointment_id">ID</th>
	            						<th class="patient_name">Patient Name</th>
										<th class="doctor_name">Visiting doctor Name</th>
										<th class="department_name">Department</th>
										<th class="appointment_start_time">Appointment Date Time</th>
										<th class="appointment_end_time">End Time</th>
										<th class="appointment_status">Status</th>
										<?php
										if(Auth::user()->is_admin())
											echo '<th class="appointment_enter_by">Enter By</th>';
										?>
										<th class="action">Action</th>
	            					</tr>
	            				</thead>
	            			</table>
	            		</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>

</body>
</html>

<div id="Modal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="form" action="<?=route('appointment')?>">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add appointment</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Full Name</label>
			            	<div class="col-md-8">
			            		<input type="text" name="patient_name" id="patient_name" class="form-control" data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Email</label>
			            	<div class="col-md-8">
			            		<input type="text" name="patient_email" id="patient_email" class="form-control" data-parsley-type="email" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Mobile No.</label>
			            	<div class="col-md-8">
			            		<input type="text" name="patient_mobile_no" id="patient_mobile_no" class="form-control" data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Date</label>
			            	<div class="col-md-8">
							<input class="form-control datepicker" id="datepicker" name="appointment_date"  autocomplete="off" required="required" data-date-format="dd-mm-yyyy">
			            	</div>
			            </div>
					  </div>
					  <div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Time</label>
			            	<div class="col-md-8">
							<input type="text" name="appointment_start_time" id="timepicker" class="form-control  timepicker" data-toggle="datetimepicker"  required onkeydown="return false" onpaste="return false;" ondrop="return false;" autocomplete="off" />
			            	</div>
			            </div>
					  </div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Department</label>
			            	<div class="col-md-8">
			            		<select name="appointment_department_id" id="patient_department" class="form-control" required data-parsley-trigger="keyup">
			            			<?php echo $department; ?>
			            		</select>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Choose Doctor</label>
			            	<div class="col-md-8">
			            		<select name="appointment_doctor_id" id="patient_visit_doctor_name" class="form-control" required data-parsley-trigger="keyup">
			            			<option value="">Select doctor</option>
			            		</select>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Reason to Visit</label>
			            	<div class="col-md-8">
			            		<textarea name="patient_reason_to_visit" id="patient_reason_to_visit" class="form-control" data-parsley-maxlength="400" data-parsley-trigger="keyup"></textarea>
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

@include('detail_modal',['name'=>'appointment'])
@include('layouts.footer')
<script>

$.ajax({
            'type': "POST",
            'dataType': 'json',
            'url': listurl,
            'success': function(data){
                calllist(data);
            },
        });
        function update(data){
            $('#patient_name').val(data.patient_name);
            $('#patient_email').val(data.patient_email);
            $('#patient_mobile_no').val(data.patient_contact);
            $('#timepicker').val(data.appointment_time);
            $('#datepicker').val(data.appointment_date);
            $('#patient_department').val(data.appointment_department_id);
            $('#patient_visit_doctor_name').html(data.appointment_doctor_id);
            $('#patient_reason_to_visit').val(data.appointment_reason);

        }
</script>

