<?php
	$username=Auth::user()->username??'';
	$profile_image=Auth::user()->profile??'';
	$website=$info->facility_name??'';
	?>
<div class="container-fluid mt-0 top-0 sticky-top bg-dark pt-2" >
	    <div class="row">
	        <div class="col col-md-6">
			<a data-toggle="collapse" href="" data-target=".collapse" role="button">
				<h4 class=" mt-2 mb-2 text-center text-white">
				<?php echo $website?>  
				<span class="d-none d-md-inline-block"> Patient Management System </span> 
				<span class="d-inline-block d-md-none"> PMS</span> </h4>
	        </a>
			</div>
			<div class="col-2 col-md-6 sidebar text-right">
			<ul class="nav navbar-nav navbar-right">
			<div class="shadow dropdown-list dropdown-menu  dropdown-menu-right " aria-labelledby="alertsDropdown"></div>
                            <li class="dropdown " role="presentation">
							<a  data-toggle="dropdown" aria-expanded="false" href="#">
							<span class="label label-pill label-danger count text-white"><?php echo ucwords(Auth::user()->username??'') ?></span>
							<span id="user_uploaded_image_small" class="mt-0">
							<img src="<?php echo Auth::user()->profile??''; ?>" class="img-fluid rounded-circle profile_image" width="30" height="30"/></a></span>
									<div class="dropdown-menu shadow dropdown-menu-right animated--grow-in" role="menu">
									<a class="dropdown-item" role="presentation" href="profile"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-600"></i>&nbsp;Profile</a>
									<a class="dropdown-item" role="presentation" href="change_password"><i class="fas fa-key fa-sm fa-fw mr-2 text-gray-600"></i>&nbsp;Change password</a>
									<?php if(Auth::user()->is_admin()):?>
									<a class="dropdown-item" role="presentation" href="settings"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-600"></i>&nbsp;Settings</a>
									<?php endif	?>
                                     <div class="dropdown-divider"></div>
                                     <form action="{{route('logout')}}" method="post" class="  logout_form">
                                        @csrf

                                        <button class="dropdown-item logout shadow-none btn " role="presentation" type="submit"
                                         title="Clicking this button will log you out." >
											<i class="fas fa-sign-out-alt mr-2 "></i>&nbsp;Logout</button>
                                        </form>

                                    </div>
				  					</div>
					</li>
	        </div>
	        </div>
	    </div>
	</div>
	<div class="container-fluid">
	    <div class="row vh-100">
	        <div class="col-sm-2  collapse text-center show sidebar bg-dark pt-2 position-fixed px-0" >
	            <ul class="nav flex-column pt-2 vh-100 sidebar" id="sidebar" >
	            	<li class="nav-item">
	                    <a class="nav-link <?php echo $dashboard_active; ?>" href="dashboard"><span class="ml-2 "><i class="fas fa-tachometer-alt"></i> <span class="d-sm-none d-md-inline md:text-base sm:text-sm">Dashboard</span></span></a>
					</li>
					<li class="nav-item">
	                    <a class="nav-link <?php echo $patient_active; ?>" href="patient"><span class="ml-2 "><i class="fas fa-wheelchair"></i>&nbsp;&nbsp;<span class="d-sm-none d-md-inline md:text-base sm:text-sm">Patient</span></span></a>
					</li>
					<li class="nav-item">
	                    <a class="nav-link <?php echo $appointment_active; ?>" href="appointment"><span class="ml-2 "><i class="fas fa-calendar"></i>&nbsp;&nbsp;<span class="d-sm-none d-md-inline md:text-base sm:text-sm">Appointments</span></span></a>
	                </li>
	            	<?php if(Auth::user()->is_admin()):?>
	            	<li class="nav-item">
	                    <a class="nav-link <?php echo $user_active; ?>" href="user"><span class="ml-2 "><i class="fas fa-users"></i> <span class="d-sm-none d-md-inline md:text-base sm:text-sm">User</span> </span></a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link <?php echo $department_active; ?>" href="department"><span class="ml-2 "><i class="fas fa-hospital"></i> <span class="d-sm-none d-md-inline md:text-base sm:text-sm">Department</span></span></a>
					</li>
					<li class="nav-item">
	                    <a class="nav-link <?php echo $specialization_active; ?>" href="specialization"><span class="ml-2 "><i class="fas fa-diagnoses"></i> <span class="d-sm-none d-md-inline md:text-base sm:text-sm ">Specialization</span></span></a>
					</li>
					<li class="nav-item">
	                    <a class="nav-link <?php echo $doctor_active; ?>" href="doctor"><span class="ml-2 "><i class="fas fa-user-md"></i><span class="d-sm-none d-md-inline md:text-base sm:text-sm">Doctor</span> </span></a>
	                </li>
	            	<?php endif	?>
	            </ul>
	        </div>
