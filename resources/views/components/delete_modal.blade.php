<div id="delete-modal" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" id="forms" action="">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{asset('img')}}/sent.png" alt="" width="50" height="46">
                <h3>Are you sure want to delete this {{ucwords($element??'element')}}?</h3>
                <div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>