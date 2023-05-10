<div class="col-sm-5 col-5">
    <h4 class="page-title">{{ucwords(Str::plural($element??'Test page'))}}</h4>
</div>
<div class="col-sm-7 col-7 text-right m-b-30">
    <button id="add_button" data-element="{{ucwords($element??'Test element')}}"class="btn btn-primary btn-rounded add_button"><i class="fa fa-plus"></i> Add {{ucwords($element??'Test element')}}</button>
</div>