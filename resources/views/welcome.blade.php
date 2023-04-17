@include('config')
@include('layouts.header')
<style>
html {
     min-height: 100%;
}
body{
	background-color: #4e73df;
	background-image: linear-gradient(180deg,#4e73df 10%,#224abe 100%);
	background-size: cover;height: 100%;
	margin-top:0px;
	padding-top:0px;
}
</style>
	<body  >
		<div class="container container-fluid  col-md-6">
		<div class="logo text-center">
           <img src="<?php echo IMAGES_URL.$info->facility_logo?>" alt="" width="120" height="120" class="rounded-circle">
			</div>
		<h3 class="text-center text-white"><?php echo  $info->facility_name?> Patient Management System</h3>
			<div class="card ">
                @include('message')
				<div class="card-header bg-dark text-white text-center"><h4>Login Menu</h4></div>

				<div class="card-body">
				<fieldset class="border p-2 border-primary">
				<legend class= "text-center w-auto" style="width:auto">Sign in to your account</legend>
					<form method="post" id="form" action=" {{ route('login') }}">
                        @csrf
						<div class="form-group">
							<label>Username/Email </label>
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1"><i class="fa fa-user fa-md position-relative text-primary"></i></span>
								</div>
								<input type="text" name="email"  class="form-control" id="user_email"placeholder="Your Username/ Email Address..."  required/>
								</div>
								</div>
								<div class="form-group">
								    <label>Password</label>
								        <div class="input-group">
								                <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock fa-md position-relative text-primary"></i></span>
								            <input type="password" class="form-control "name="password" id="user_password"  data-parsley-excluded=true placeholder="Your Password" required/>
								                <span toggle="#password" class="input-group-text" ><i class="fa fa-fw fa-eye field-icon toggle-password"></i></span></div>

						                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success login">Login <i class="fa fa-arrow-circle-right"></i></button>
                                    <button type="button"  id="hint" class="btn btn-info">Login hint</button>
                                </div>
						</fieldset>
						<div class="copyright text-center">
                        &copy; <span class="current-year"><script>document.write(new Date().getFullYear())</script></span>
                        <span class="text-bold text-uppercase"> <?php echo $info->facility_name?></span>.
                        <span>All rights reserved</span>
					</div>
					</form>
				</div>
			</div>
            @include('layouts.footer')
<link rel="stylesheet" href="<?php echo CSS_URL.'parsley.css'?>" >
<script type="text/javascript" src="<?php echo JS_URL.'parsley.min.js'?>"></script>
<script type="text/javascript" src="<?php echo JS_URL.'popper.min.js'?>"></script>
<div id="wrapper">
        <div class="blocker"></div>
        <div  class="bg-dark text-white text-center py-0 px-2 mb-0" id="popup" style="border-radius:4px;font-size: 16px;display:none">
            <p class="text-warning py-0 my-0">For user login
            <p class="py-0 my-0">username: prakhar
            <p class="py-0 my-0">password: philieep </p>
            <p  class="text-warning py-0 my-0 ">For user login
            <p class="py-0 my-0">username: gyawali
            <p class="py-0 my-0">password: 123456<p>
            <p class="text-warning py-0 my-0">For admin login
            <p class="py-0 my-0">username: puskar
            <p class="py-0 my-0">password: philieep</p>
        </div>
        <div class="blocker"></div>
</div>
<script>
        var ref = $('#hint');
        var popup = $('#popup');

        ref.click(function(){
            popup.show();
                var popper = new Popper(ref,popup,{
                        placement: 'right',
                        modifiers: {
                                flip: {
                                        behavior: ['left', 'right', 'top','bottom']
                                },
                                offset: {
                                        enabled: true,offset: '0,10'
                                }
                        }
                });
                timeout()
        });
</script>

<script>
function update(data){
    $('#message').html('<div class="alert alert-success">Login success. Redirecting.......</div>');
    enableButton(true);
    window.location.assign('.'+data.response);
}
</script>
