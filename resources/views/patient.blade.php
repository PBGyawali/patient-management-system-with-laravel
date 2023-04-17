@include('config')
    @include('layouts.header')
        @include('layouts.sidebar')
	        <div class="col-sm-10 offset-sm-2 py-4">
                @include('message')
	            <div class="card">
	            	<div class="card-header">
	            		@include('header_card',['element'=>'patient','name'=>'History'])
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="table">
	            				<thead>
	            					<tr>
	            						<th class="patient_name">Patient Name</th>
										<th class="doctor_name">Visiting doctor Name</th>
										<th class="department_name">Department</th>
										<th class="patient_enter_time">In Time</th>
										<th class="patient_out_time">Out Time</th>
                                        <th class='patient_status'>Status</th>
                                        <?php
										if(Auth::user()->is_admin())
											echo '<th class="patient_enter_by admininfo">Enter By</th>';
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
    	<form method="post" id="form" action="<?= route('patient')?>">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add patient</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Name</label>
			            	<div class="col-md-8">
			            		<input type="text" name="patient_name" id="patient_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Email</label>
			            	<div class="col-md-8">
			            		<input type="text" name="patient_email" id="patient_email" class="form-control" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Mobile No.</label>
			            	<div class="col-md-8">
			            		<input type="text" name="patient_mobile_no" id="patient_mobile_no" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Address</label>
			            	<div class="col-md-8">
			            		<textarea name="patient_address" id="patient_address" class="form-control" required data-parsley-maxlength="400" data-parsley-trigger="keyup"></textarea>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Department</label>
			            	<div class="col-md-8">
			            		<select name="patient_department" id="patient_department" class="form-control" required data-parsley-trigger="keyup">
			            			<option value="">Select Departent</option>
                                    <?=$department?>
			            		</select>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Doctor To visit</label>
			            	<div class="col-md-8">
			            		<select name="patient_visit_doctor_name" id="patient_visit_doctor_name" class="form-control" required data-parsley-trigger="keyup">
			            			<option value="">Select Doctor first</option>
			            		</select>
			            	</div>
			            </div>
                      </div>
                      <div class="form-group">
                        <div class="row">
                          <label class="col-md-4 text-right">Patient Source </label>
                          <div class="col-md-8">
                          <select name="patient_source" id="patient_source" class="form-control" >
                              <option value="" selected>Select Patient Source</option>
                              <option value="regular">Regular</option>
                              <option value="outside">Outside</option>
                              <option value="referral">Referral</option>
                              </select>
                          </div>
                      </div>
                    </div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Reason to Visit</label>
			            	<div class="col-md-8">
			            		<textarea name="patient_reason_to_visit" id="patient_reason_to_visit" class="form-control"></textarea>
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
@include('detail_modal',['name'=>'patient history','submit'=>true])
@include('layouts.footer')
<script>

        $.ajax({
            'type': "POST",
            'dataType': 'json',
            'url': listurl,
            'success': function(data){
                calllist(data);
            },
        })

        function update(data){
            $('#patient_name').val(data.patient_name);
            $('#patient_email').val(data.patient_email);
            $('#patient_mobile_no').val(data.patient_mobile_no);
            $('#patient_address').val(data.patient_address);
            $('#patient_department').val(data['patient_department']);
            $('#patient_visit_doctor_name').html(data['patient_visit_doctor_name']);
            $('#patient_reason_to_visit').val(data.patient_reason_to_visit);
            $('#patient_remarks').val(data.patient_remarks);
            $('#patient_source').val(data.patient_source);
            }
</script>

