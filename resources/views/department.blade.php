@include('config')
    @include('layouts.header')
        @include('layouts.sidebar')
	        <div class="col-10 offset-sm-2 py-4">
                @include('message')
	            <div class="card">
	            	<div class="card-header">
                        @include('header_card',['element'=>'department'])
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="table">
	            				<thead>
	            					<tr>
										<th class="department_name">Department Name</th>
										<th class="department_capacity">Capacity</th>
										<th class="doctors">Department Doctor List</th>
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
    	<form method="post" id="form" action="<?php echo route('department');?>">
      		<div class="modal-content">
            <span class="text-center position-absolute w-100"id="form_message" style="z-index:50"></span>
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title"></h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-12 ">Department Name</label>
			            	<div class="col-12">
			            		<input type="text" name="department_name" id="department_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s/()-]+$/" data-parsley-trigger="keyup" />
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
	$('#department_name').val(data.department_name);
}
</script>

