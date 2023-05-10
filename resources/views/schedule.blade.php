@include('layouts.new_header')
        @include('layouts.new_sidebar')
        <div class="page-wrapper">
			@include('message')
            <div class="content">
                <div class="row">
                    @include('page_header',['element'=>'schedule'])
                </div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table id="table"class="table table-border table-striped custom-table mb-0">
								<thead>
									<tr>
										<th class="doctor_name">Doctor Name</th>
										<th class="department_name">Department</th>
										<th class="available_days">Available Days</th>
										<th class="available_time">Available Time</th>
										<th class="schedule_status">Status</th>
										<th class="action text-right">Action</th>
									</tr>
								</thead>								
							</table>
						</div>
                </div>
                </div>
            </div>            
        </div>
		@include('delete_modal',['element'=>'schedule'])
        

		<div id="Modal" class="modal fade">
            <div class="modal-dialog">
              <form method="post" id="form" action="<?php echo route('schedule');?>">
                    <div class="modal-content">                  
                      <div class="modal-header">
                            <h4 class="modal-title" id="modal_title"></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <span id="form_message"></span>                          
                            <div class="form-group">
                                <label>Doctor Name</label>
								<select name="doctor_id" id="doctor_id" class="form-control" required>
									{!!$doctor_list!!}
								</select>                              
                            </div>
							<div class="form-group">
                                <label>Department Name</label>
								<select name="department_id" id="department_id" class="form-control" required>
									{!!$department_list!!}
								</select>		
                            </div>
							<div class="form-group">
                                <label>Available Days</label>
								<div class="row">
									<span class="col">
										<input type="checkbox" name="available_days[]" class="available_days" value="Sunday"/> Sunday
									</span>
									<span class="col">
										<input type="checkbox" name="available_days[]" class="available_days"  value="Monday" /> Monday
									</span>
									<span class="col">
										<input type="checkbox" name="available_days[]" class="available_days" value="Tuesday"/> Tuesday
									</span>																
								</div>
								<div class="row">
									<span class="col">
										<input type="checkbox" name="available_days[]" class="available_days"  value="Wednesday"/> Wednesday
									</span>
									<span class="col">
										<input type="checkbox" name="available_days[]" class="available_days" value="Thursday"/> Thursday
									</span>
									<span class="col">
										<input type="checkbox" name="available_days[]" class="available_days" value="Friday"/> Friday
									</span>									
								</div>
                            </div>
                          <div class="form-group">
                              <label>Schedule Start time</label>
                              <input type="text" name="schedule_start_time" id="schedule_start_time" class="form-control time timepicker" required />
                          </div>
						  <div class="form-group">
							<label>Schedule End Time</label>
							<input type="text" name="schedule_end_time" id="schedule_end_time" class="form-control timepicker" required />
						</div>
                      </div>
                      <div class="modal-footer">
                            <button type="submit"  id="submit_button" class="btn btn-success">Add</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
              </form>
            </div>
      </div>
		@include('delete_modal',['element'=>'schedule'])
		@include('layouts.footer')
        @include('layouts.footer_script')