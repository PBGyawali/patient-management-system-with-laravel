<div class="row">
    <div class="col-12 col-md-4">
        <h3 class="card-title">{{ucwords($element).' '.($name??'List')}}</h3>
    </div>
    <div class="col-6 col-md-4">
        <div class="row input-daterange">
            @empty($noreport)
            <div class="col-6 px-sm-0">
                <input type="text" name="from_date" id="from_date" class="form-control form-control-sm" placeholder="From Date" readonly />
            </div>
            <div class="col-6 px-sm-0">
                <input type="text" name="to_date" id="to_date" class="form-control form-control-sm" placeholder="To Date" readonly />
            </div>
            @endempty
        </div>
    </div>
    <div class="col-3 col-md-2 px-sm-0">
        @empty($noreport)
        <button type="button"  id="filter" class="btn btn-info btn-sm pl-sm-1"><i class="fas fa-filter"></i></button>
        <button type="button"  id="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
        @endempty
    </div>
    <div class="col-3 col-md-2  text-right">
        @empty($nobutton)
        <button type="button" name="add" id="add_button" data-element="{{ucfirst($element)}}" data-toggle="modal" data-target="#Modal" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add</button>
        @endempty
    </div>
</div>
