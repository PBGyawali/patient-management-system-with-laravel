@foreach($doctors as $doctor)
    <div class="col-md-4 col-sm-4  col-lg-3">
        <div class="profile-widget">
            <div class="doctor-img">
                <a class="avatar" href="{{route('profile',['id'=>$doctor->getKey()])}}"><img alt="" src="{{$doctor->profile}}"></a>
            </div>
            @include('text_buttons',['class'=>'profile-action','id'=>$doctor->getKey(),'element'=>'doctor'])
            <h4 class="doctor-name text-ellipsis"><a href="{{route('profile',['id'=>$doctor->getKey()])}}">{{$doctor->doctor_name}}</a></h4>
            <div class="doc-prof">{{$doctor->specialization_name}}</div>
            <div class="user-country">
                <i class="fa fa-map-marker"></i> {{$doctor->address}}
            </div>
        </div>
    </div>
@endforeach