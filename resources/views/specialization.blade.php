@include('config')
@include('layouts.header')
@include('layouts.sidebar')
<div class="col-sm-10 offset-sm-2 py-4">
    @include('message')
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-secondary">
                    <div class="card-header">
                            @include('header_card',['element'=>'Specialization'])
                        <div class="clear:both"></div>
                   	</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table id="table" class="table table-bordered table-striped">
                                    <thead><tr>
                                        <th class="specialization_id">ID</th>
                                        <th class="specialization_name">Specialization Name</th>
                                        <th class="specialization_status">Status</th>
                                        <th class="action">Action</th>
                                    </tr></thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



	<div id="Modal" class="modal fade">
  	    <div class="modal-dialog">
            <form method="post" id="form"  action="<?php echo route('specialization')?>">
                @csrf
      		    <div class="modal-content">
                    <span class="text-center position-absolute w-100 pl-0"id="form_message" style="z-index:50"></span>
        		    <div class="modal-header">

          			    <h4 class="modal-title" id="modal_title"><i class="fa fa-plus"></i>Add specialization</h4>
          			    <button type="button" class="close" data-dismiss="modal">&times;</button>
        		    </div>
        		    <div class="modal-body">

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 text-left">Enter data</label>
                                <div class="col-md-9 px-0 pr-1">
                                    <input type="text" name="specialization_name" id="specialization_name" class="form-control"  required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-trigger="keyup" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <input type="submit" id="submit_button" class="btn btn-success"value="Add" />
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
      		</div>
    	</form>
  	</div>
</div>
@include('layouts.footer')
<script>
    function update(data){
        $('#specialization_name').val(data.specialization_name);
}
</script>




