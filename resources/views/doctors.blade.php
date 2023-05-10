@include('layouts.new_header')
        @include('layouts.new_sidebar')
        <div class="page-wrapper">
            @include('message')
            <div class="content">
                <div class="row">
                    @include('page_header',['element'=>'doctor'])
                </div>
				        <div class="row doctor-grid" id="doctors-list">
                    @include('doctor_list')
                </div>
				    <div class="row">
                    <div class="col-sm-12">
                        <div class="see-all">
                            <a class="see-all-btn" href="javascript:void(0);" data-url="{{route('doctor')}}"data-page="2">Load More</a>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
        
      
          
          
          
          
          
          
          
		@include('delete_modal',['element'=>'doctor'])
        @include('detail_modal',['name'=>'doctor'])
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
                                      <span class="text-gray-800 text-muted">Required only if doctor email is empty</span>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                  <label class="col-md-4 text-right">Doctor Address</label>
                                  <div class="col-md-8">
                                      <input type="text" name="address" id="address" class="form-control"   data-parsley-maxlength="150" data-parsley-trigger="keyup" />
                                      <span class="text-gray-800 text-muted">Required only if doctor email is empty</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                  <label class="col-md-4 text-right">Identifying Email</label>
                                  <div class="col-md-8">
                                      <select name="doctor_email" id="email" class="form-control" data-parsley-trigger="on change">
                                      <option value="">Select Doctor Email</option>
                                          <?php echo $email; ?>
                                          </select>
                                  </div>
                              </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                  <label class="col-md-4 text-right">Email</label>
                                  <div class="col-md-8">
                                    <input type="email" name="email" id="new_doctor_email" class="form-control"  data-parsley-type="email" data-parsley-maxlength="255" data-parsley-trigger="keyup" />
                                  <span class="text-gray-800 text-muted">Required only if doctor email is empty</span>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                  <label class="col-md-4 text-right">Department</label>
                                  <div class="col-md-8">
                                      <select name="department_id" id="department_id" class="form-control" required data-parsley-trigger="on change">
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
                                      <select name="specialization_id" id="specialization_id" class="form-control" required data-parsley-trigger="on change">
                                          <option value="">Select specialization</option>
                                          <?php echo $specialization; ?>
                                      </select>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                  <label class="col-md-4 text-right">Profile Image</label>
                                  <div class="col-md-8">
                                    <input type="file" name="profile_image" id="file_upload" class="form-control file_upload"   />
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

<script>
  $(document).on('click', '.see-all-btn', function() {
    let baseUrl=$(this).data('url');
      var nextPage = $(this).data('page');
      $.get('./doctor'+'?page=' + nextPage, function(data) {
          $('#doctors-list').append(data);
          $('.see-all-btn').data('page', nextPage + 1);
      });
  });
  </script>