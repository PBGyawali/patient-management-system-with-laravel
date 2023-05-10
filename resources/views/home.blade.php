@include('layouts.new_header')
        @include('layouts.new_sidebar')
        <div class="page-wrapper">
			@include('message')
            <div class="content">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
							<span class="dash-widget-bg1"><i class="fa fa-stethoscope" aria-hidden="true"></i></span>
							<div class="dash-widget-info text-right">
								<h3>{{$total_doctors}}</h3>
								<span class="widget-title1">Doctors <i class="fa fa-check" aria-hidden="true"></i></span>
							</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <span class="dash-widget-bg2"><i class="fa fa-user-o"></i></span>
                            <div class="dash-widget-info text-right">
                                <h3>{{$total_patient}}</h3>
                                <span class="widget-title2">Patients <i class="fa fa-check" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <span class="dash-widget-bg3"><i class="fa fa-user-md" aria-hidden="true"></i></span>
                            <div class="dash-widget-info text-right">
                                <h3>{{$total_active_appointments}}</h3>
                                <span class="widget-title3">Attend <i class="fa fa-check" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <span class="dash-widget-bg4"><i class="fa fa-heartbeat" aria-hidden="true"></i></span>
                            <div class="dash-widget-info text-right">
                                <h3>{{$total_pending_appointments}}</h3>
                                <span class="widget-title4">Pending <i class="fa fa-check" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                </div>


				<div class="row">
					<div class="col-md-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<div class="row">
									<div class="col">
										<h6 class="m-0 font-weight-bold text-primary">Live Patient Status</h6>
									</div>
									<div class="col text-right">
										<div class="dropdown no-arrow" id="form" action="<?= route('patient_status');?>">
											<button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">
												<i class="fa fa-ellipsis-v text-gray-400"></i>
											</button>
											<div class="dropdown-menu shadow dropdown-menu-right animated--fade-in text-center"
												role="menu">
												<span class="text-base ">Action:</span>
												<span class="dropdown-item refresh_now" role="presentation" >Refresh Now</span>
												<span class="dropdown-item start_auto_refresh" role="presentation">Start auto Refresh</span>
												<span class="dropdown-item stop_auto_refresh" role="presentation">Stop auto Refresh</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
									<div id="patient_status" class="row">
									</div>
							</div>                                            
						</div>
					</div>
				</div>


				<div class="row">					
					<div class="col-12 ">					
							@include('chart',['single_value'=>$fullmonthvalue,'month'=>$fullmonth,'element'=>'patient'])
						
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-6 col-lg-8 col-xl-8">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title d-inline-block">Upcoming Appointments</h4> <a href="appointment" class="btn btn-primary float-right">View all</a>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="table mb-0">
										<thead class="d-none">
											<tr>
												<th>Patient Name</th>
												<th>Doctor Name</th>
												<th>Timing</th>
												<th class="text-right">Status</th>
											</tr>
										</thead>
										<tbody>
											@foreach($appointments as $appointment)
											<tr>
												<td style="min-width: 200px;">
													<a class="avatar" href="profile">{{substr($appointment->patient_name, 0, 1)}}</a>													
													<h2><a href="profile">{{$appointment->patient_name}} <span>{{$appointment->address}}</span></a></h2>
												</td>                 
												<td>
													<h5 class="time-title p-0">Appointment With</h5>
													<p>Dr. {{$appointment->doctor_name}}</p>
												</td>
												<td>
													<h5 class="time-title p-0">Timing</h5>
													<p>{{$appointment->appointment_start_time->format('H:i')}}</p>
												</td>
												<td class="text-right">
													<a href="{{route('appointment')}}" class="btn btn-outline-primary take-btn">Take up</a>
												</td>
											</tr>
											@endforeach	
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-4">
                        <div class="card member-panel">
							<div class="card-header bg-white">
								<h4 class="card-title mb-0">Doctors</h4>
							</div>
                            <div class="card-body">
                                <ul class="contact-list">
									@foreach ($doctors as $doctor)
									<li>
                                        <div class="contact-cont">
                                            <div class="float-left user-img m-r-10">
                                                <a href="profile" title="{{$doctor->doctor_name}}"><img src="{{$doctor->profile}}" alt="" class="w-40 rounded-circle"><span class="status {{array_values(['online','offline','away'])[array_rand(array_values(['online','offline','away']))];}}"></span></a>
                                            </div>
                                            <div class="contact-info">
                                                <span class="contact-name text-ellipsis">{{$doctor->doctor_name}}</span>
                                                <span class="contact-date">{{array_values(['MBBS, MD','BMBS','MS, MD','MBBS','MD'])[array_rand(array_values(['MBBS, MD','BMBS','MS, MD','MBBS','MD']))];}}</span>
                                            </div>
                                        </div>
                                    </li>
									@endforeach                                    
                                </ul>
                            </div>
                            <div class="card-footer text-center bg-white">
                                <a href="{{route('doctor')}}" class="text-muted">View all Doctors</a>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="row">
					<div class="col-12 col-md-6 col-lg-8 col-xl-8">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title d-inline-block">New Patients </h4> <a href="patients" class="btn btn-primary float-right">View all</a>
							</div>
							<div class="card-block">
								<div class="table-responsive">
									<table class="table mb-0 new-patient-table">
										<tbody>
											@foreach($patient_histories as $patient)
											<tr>
												<td>
													<img width="28" height="28" class="rounded-circle" src="{{asset('img')}}/user.jpg" alt=""> 
													<h2>{{$patient->patient_name}}</h2>
												</td>
												<td>
													<h5 class="time-title p-0">Visted Doctor</h5>
													<p>Dr. {{$patient->doctor_name}}</p>
												</td>
												<td>
													<h5 class="time-title p-0">Patient source</h5>
													<p>{{$patient->patient_source}}</p>
												</td>
											
												<td><button class="btn btn-primary btn-primary-one float-right">{{$patient->patient_reason_to_visit??'Fever'}}</button></td>
											</tr>
											@endforeach	
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					
				</div>
            </div>
            
        </div>
        @include('layouts.footer')
        @include('layouts.footer_script')