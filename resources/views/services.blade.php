@include('layouts.new_header')
        @include('layouts.new_sidebar')
        <div class="page-wrapper">
            @include('message')
            <div class="content">
                <div class="row">
                    @include('page_header',['element'=>'service'])
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="table"class="table table-striped custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="service_id">#</th>
                                        <th class="service_name">service Name </th>
                                        <th class="service_price">service price ({{$info->facility_currency}}) </th>
                                        <th class="service_status">Status</th>
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
              <form method="post" id="form" action="<?php echo route('service');?>">
                    <div class="modal-content">
                  <span class="text-center position-absolute w-100"id="form_message" style="z-index:50"></span>
                      <div class="modal-header">
                            <h4 class="modal-title" id="modal_title"></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <span id="form_message"></span>                          
                            <div class="form-group">
                                <label>service Name</label>
                                <input type="text" name="service_name" id="service_name" class="form-control" required data-parsly-pattern="/^[a-zA-Z0-9 \s]+$/" data-parsley-trigger="keyup" />
                            </div>
                          <div class="form-group">
                              <label>service price</label>
                              <input type="text" name="service_price" id="service_price" class="form-control" required data-parsly-pattern="^[0-9]{1,2}\.[0-9]{2}$" data-parsley-trigger="keyup" />
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
		@include('delete_modal',['element'=>'service'])
@include('layouts.footer')
@include('layouts.footer_script')