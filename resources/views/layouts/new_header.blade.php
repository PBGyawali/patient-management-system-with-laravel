@include('config')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$info->facility_name??'Medical'}} - Hospital Management  System</title>
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/style.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/jquery-confirm.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/fullcalendar.min.css">
    
</head>

<body>
    <div class="main-wrapper">
        <div class="header">
			<div class="header-left">
				<a href="{{route('dashboard')}}" class="logo">
					<img src="{{asset('img')}}/logo.png" width="35" height="35" alt=""> <span>{{$info->facility_name??'Preclinic'}}</span>
				</a>
			</div>
            @auth
			<a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown d-none d-sm-block">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"><i class="fa fa-bell-o"></i> <span class="badge badge-pill bg-danger float-right">3</span></a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span>Notifications</span>
                        </div>
                        <div class="drop-scroll">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <a href="activities">
                                        <div class="media">
											<span class="avatar">
												<img alt="John Doe" src="{{asset('img')}}/user.jpg" class="img-fluid rounded-circle">
											</span>
											<div class="media-body">
												<p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>
												<p class="noti-time"><span class="notification-time">4 mins ago</span></p>
											</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities">
                                        <div class="media">
											<span class="avatar">V</span>
											<div class="media-body">
												<p class="noti-details"><span class="noti-title">Tarah Shropshire</span> changed the task name <span class="noti-title">Appointment booking with payment gateway</span></p>
												<p class="noti-time"><span class="notification-time">6 mins ago</span></p>
											</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities">
                                        <div class="media">
											<span class="avatar">L</span>
											<div class="media-body">
												<p class="noti-details"><span class="noti-title">Misty Tison</span> added <span class="noti-title">Domenic Houston</span> and <span class="noti-title">Claire Mapes</span> to project <span class="noti-title">Doctor available module</span></p>
												<p class="noti-time"><span class="notification-time">8 mins ago</span></p>
											</div>
                                        </div>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                        
                    </div>
                </li>                
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img"><img class="rounded-circle profile_image" src="{{auth()->user()->profile}}" width="40" alt="Admin">
							<span class="status online"></span></span>
                        <span>{{auth()->user()->username}}</span>
                    </a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="{{route('profile')}}">My Profile</a>
						<a class="dropdown-item" href="{{route('password')}}">Change Password</a>
						<a class="dropdown-item" href="{{route('settings')}}">Settings</a>
						<a class="dropdown-item logout_form" href="{{route('logout')}}">Logout</a>
					</div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{route('profile')}}">My Profile</a>
                    <a class="dropdown-item" href="{{route('password')}}">Change Password</a>
                    <a class="dropdown-item" href="{{route('settings')}}">Settings</a>
                    <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
                </div>
            </div>
            @endauth
        </div>