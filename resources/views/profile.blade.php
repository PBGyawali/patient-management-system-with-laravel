@include('layouts.new_header')
        @include('layouts.new_sidebar')
        @php
           $user= auth()->user();
        @endphp
        <div class="page-wrapper">
            @include('message')
            <div class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Edit Profile</h4>
                    </div>
                </div>
                <form action="{{route('user.update',['user'=>$user->getKey()])}}" method="POST" id="form" class="no-reset"enctype="multipart/form-data" >
                    <div class="card-box">
                        <h3 class="card-title">Basic Informations</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="profile-img-wrap">
                                    <img class="inline-block profile_image" id="image_preview" src="{{$user->profile}}" alt="user">
                                    <div class="fileupload btn">
                                        <span class="btn-text">Edit</span>
                                        <input class="file_upload upload" type="file" name="image" data-allowed_file='[<?php echo '"' . implode('","', ALLOWED_IMAGES) . '"'?>]' data-upload_time="later" accept="<?php echo "image/" . implode(", image/", ALLOWED_IMAGES);?>">
                                    </div>
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <label class="focus-label">Full Name</label>
                                                <input type="text" name="name"class="form-control floating" value="{{$user->name}}">
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <label class="focus-label">Birth Date</label>                                               
                                                    <input class="form-control" name="birthdate"type="date" value="{{$user->birthdate}}">                                          
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus select-focus">
                                                <label class="focus-label">Gender</label>
                                                <select name="gender"class="form-control floating">
                                                    <option value="" selected>Not mentioned</option>
                                                    <option value="male" @if($user->gender=='male') selected @endif>Male</option>
                                                    <option value="female" @if($user->gender=='female') selected @endif>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <label class="focus-label">User Name</label>
                                                <input type="text" name="username" class="form-control floating" value="{{$user->username}}">
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <h3 class="card-title">Contact Informations</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Address</label>
                                    <input type="text" name="address"class="form-control floating" value="{{$user->address}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">State</label>
                                    <input type="text" class="form-control floating" value="New York">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Country</label>
                                    <input type="text" class="form-control floating" value="{{$user->country??'United States'}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Email</label>
                                    <input type="email" name="email"class="form-control floating" value="{{$user->email}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Phone Number</label>
                                    <input type="text" name="contact_no"class="form-control floating" value="{{$user->contact_no}}">
                                </div>
                            </div>
                        </div>
                    </div>                   
                    
                    <div class="text-center m-t-20">
                        <button class="btn btn-primary submit-btn" type="submit">Save</button>
                    </div>
                </form>
            </div>
            
        </div>
        @include('layouts.footer')
        @include('layouts.footer_script')