@include('layouts.new_header')
        @include('layouts.new_sidebar')
        <div class="page-wrapper">
            @include('message')
            <div class="content">                
                <div class="row">
                    @include('page_header',['element'=>'department'])
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="table"class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="id">#</th>
                                        <th class="department_name">Department Name </th>
                                        <th class="doctors">Doctors</th>                                         
                                        <th class="department_capacity">Capacity</th>  
                                        <th class="department_status">Status</th>                                     
                                        <th class="action text-right">Action</th>
                                    </tr>
                                </thead>                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
         
        </div>
		@include('delete_modal',['element'=>'department'])


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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
              </form>
            </div>
      </div>
 @include('layouts.footer')
 @include('layouts.footer_script')