<div id="detailsModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form method="post" id="{{$id??'second_form'}}">
            <div class="modal-content">
                <span class="text-center position-absolute w-100 pl-0"id="form_message" style="z-index:50"></span>
                <div class="modal-header">
                        <h4 class="modal-title" id="modal_title"><i class="fa fa-eye"></i> {{ucwords($name??'')}} Details</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="modal_item_details">

                    </div>
                    <div class="modal-footer">
                        @isset($submit)
                        <button type="submit"  id="detail_submit_button" class="btn btn-success">Save</button>
                        @endisset
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
