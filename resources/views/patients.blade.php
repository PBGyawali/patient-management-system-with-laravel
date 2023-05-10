@include('layouts.new_header')
        @include('layouts.new_sidebar')
        <div class="page-wrapper">
			@include('message')
            <div class="content">
                <div class="row">
                    @include('page_header',['element'=>'patient'])
                </div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table id="table" class="table table-border table-striped custom-table datatable mb-0">
								<thead>
									<tr>
										<th class="patient_name">Name</th>
										<th class="age">Age (Years)</th>
										<th class="email">Email</th>
										<th class="gender">Gender</th>
										<th class="address">Address</th>
										<th class="contact_no">Phone</th>										
										<th class="action text-right">Action</th>
									</tr>
								</thead>								
							</table>
						</div>
					</div>
                </div>
            </div>
            
        </div>

		
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
							  <input type="text" name="email" id="email" class="form-control" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
						  </div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
						  <label class="col-md-4 text-right">Patient Mobile No.</label>
						  <div class="col-md-8">
							  <input type="text" name="contact_no" id="contact_no" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="15" data-parsley-trigger="keyup" />
						  </div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
						  <label class="col-md-4 text-right">Patient Address</label>
						  <div class="col-md-8">
							  <textarea name="address" id="address" class="form-control" required data-parsley-maxlength="400" data-parsley-trigger="keyup"></textarea>
						  </div>
					 	</div>
					</div>
					<div class="form-group">
						<div class="row">
						  <label class="col-md-4 text-right">Medical History</label>
						  <div class="col-md-8">
							  <textarea name="medical_history" id="medical_history" class="form-control" ></textarea>
						  </div>
					 	</div>
					</div>
					<div class="form-group">
						<div class="row">
						  <label class="col-md-4 text-right">Patient birthdate</label>
						  <div class="col-md-8">
							<input type="date" name="birthdate" id="birthdate" class="form-control" required >
						  </div>
					 	</div>
					</div>
					<div class="form-group">
						<div class="row">
						  <label class="col-12">Patient gender</label>
						  <div class="col-12">
							<input type="radio" name="gender"  class=" gender" required value="male"> Male
							<input type="radio" name="gender" class=" gender" required value="female"> Female
						  </div>
					 	</div>
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
@include('detail_modal',['name'=>'patient'])
@include('delete_modal',['element'=>'patient'])
@include('layouts.footer')
@include('layouts.footer_script')