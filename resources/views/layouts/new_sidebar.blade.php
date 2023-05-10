<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul>
				<li class="menu-title">Main</li>
				<li>
					<a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
				</li>
				<li>
					<a href="{{route('doctor')}}"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
				</li>
				<li>
					<a href="{{route('patient')}}"><i class="fa fa-wheelchair"></i> <span>Patients</span></a>
				</li>
				<li>
					<a href="{{route('user')}}"><i class="fa fa-user"></i> <span>Users</span></a>
				</li>
				<li>
					<a href="{{route('appointment')}}"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
				</li>
				<li>
					<a href="schedule"><i class="fa fa-calendar-check-o"></i> <span>Doctor Schedule</span></a>
				</li>
				<li>
					<a href="{{route('specialization')}}"><i class="fas fa-diagnoses"></i></i> <span>Specializations</span></a>
				</li>
				<li>
					<a href="{{route('department')}}"><i class="fa fa-hospital-o"></i> <span>Departments</span></a>
				</li>
				<li>
					<a href="{{route('tax')}}"><i class="fa fa-money"></i> <span>Taxes</span></a>
				</li>
				<li>
					<a href="{{route('service')}}"><i class="fa fa-money"></i> <span>Services</span></a>
				</li>											
				
				<li class="submenu">
					<a href="#"><i class="fa fa-envelope"></i> <span> Email</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a href="compose">Compose Mail</a></li>
						<li><a href="inbox">Inbox</a></li>
						<li><a href="mail-view">Mail View</a></li>
					</ul>
				</li>
				<li class="submenu">
					<a href="#"><i class="fa fa-commenting-o"></i> <span> Blog</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a href="blog">Blog</a></li>
						<li><a href="blog-details">Blog View</a></li>
						<li><a href="add-blog">Add Blog</a></li>
					</ul>
				</li>									
				<li>
					<a href="calendar"><i class="fa fa-calendar"></i> <span>Calendar</span></a>
				</li>								
			</ul>
		</div>
	</div>
</div>