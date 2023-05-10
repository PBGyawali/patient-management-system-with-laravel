@include('layouts.new_header',['minimal'=>true])
    <div class="main-wrapper account-wrapper mt-3">
        <div class="account-page">
            @include('message')
            <div class="container">
                <h3 class="account-title">Change Password</h3>
                <div class="account-box">
                    <div class="account-wrapper">
                        <div class="account-logo">
                            <img src="{{IMAGES_URL.$info->facility_alternate_logo}}" alt="">
                        </div>
                        <form method="post" id="form" action="{{route('password').'/'.auth()->user()->id}}">
                            <div class="form-group">
                                <div class="row">
                                  <label class="col-12 text-center">Current Password <span class="text-danger">*</span></label>
                                  <div class="col-12">
                                      <input type="password" name="password" id="current_password" class="form-control" />
                                  </div>
                              </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                  <label class="col-12 text-center">New Password <span class="text-danger">*</span></label>
                                  <div class="col-12">
                                      <input type="password" name="new_password" id="new_password" class="form-control"    />
                                  </div>
                              </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                  <label class="col-12 text-center">New Password Confirmation <span class="text-danger">*</span></label>
                                  <div class="col-12">
                                      <input type="password" name="new_password_confirmation" id="re_enter_new_password" class="form-control"  />
                                  </div>
                              </div>
                            </div>                       
                            <div class="form-group mb-0 mt-5 text-center">
                                <button class="btn btn-primary btn-block account-btn" type="submit">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@include('layouts.footer')
@include('layouts.footer_script')