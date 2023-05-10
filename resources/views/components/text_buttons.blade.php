<div class="dropdown dropdown-action text-right {{$class??''}}">
    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
    <div class="dropdown-menu dropdown-menu-right">
        <button class="dropdown-item btn btn-warning btn-sm update" data-prefix="{{ucwords($element??'')}}" data-id="{{$id??0}}" ><i class="fa fa-edit m-r-5"></i> Edit</button>
        <button class="dropdown-item btn btn-danger btn-sm delete text-white" data-id="{{$id??0}}" data-toggle="modal" data-target="#delete-modal"><i class="fa fa-trash m-r-5"></i> Delete</button>
    </div>
</div>