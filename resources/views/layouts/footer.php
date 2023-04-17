		</div>
	</body>
</html>

<script src="<?php echo JS_URL.'confirmdefaults.js'?>"></script>
<script src="<?php echo JS_URL.'confirm.js'?>"></script>
<script>
    timeout();
    var columns = [];
    var order=['0','desc'];
    var method_type='';
    var from_date='';
    var to_date='';
    var doctors ;
    var datatable='';
    let autorefresh=true;

// if the form is not login form then enable parsley
if($('.login').length<1){
        $('#form').parsley();
}


//create an array of columns for datatables to
//create data automatically according to the current table headers
$("th").each(
    function ()
		{
            let classname=$(this).attr('class')
            let th =classname.split(' ')[0]
            let th2 =classname.split(' ')[1]
            if(th=='action'){
                columns.push({data: th,name:th,orderable:false,searchable:false})
            }
            else if(th2=="admininfo"){
                columns.push({
                    "className":th2,
                    "data":th,
                    "defaultContent": '',
                    'visible' :$('.'+th2).length > 0?true:false,
                    "render": function (data)
                    {
                        return  ($('.'+th2).length > 0)?data:'';
                    }
                })
            }
            else
            columns.push({data: th,name:th})
        }
);



    $('#second_form').parsley();
    var url=$('#form').attr('action');
    listurl=url+'/list';
    var reporturl=url+'/getOrderReport';
	var fetchurl=url+"/max";
    function varname(start='',end=''){
        from_date=start
        to_date=end
    }
    function callback(response) {
        method_type = response;
    }

    function calllist(response) {
            doctors = response;
    }



    //all other ajax function need to be below this or they will throw error
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $( document ).ajaxSend(function() {
            $('.errormessage').html('');
            $('#action,#submit_button').attr('disabled', 'disabled');
        });
    $( document ).ajaxError(function( event, request, settings, thrownError ) {
        if (request.status == 422) {
            result('<div class="alert alert-danger text-center">'+request.responseJSON.message+'</div>');
                if(!$('.login').length>0){
                        $.each(request.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="'+i+'"]');
                        el.after($('<span class="text-danger errormessage">'+error[0]+'</span>'));
                    });
                }
        }
        else if ([401].includes(request.status)) {
            result('<div class="alert alert-danger text-center">'+request.responseJSON.message+ '</div>');
        }
        else if (request.status == 419) {
            result('<div class="alert alert-danger text-center">'+request.responseJSON.message+ ' Please relogin or refresh </div>');
        }
        else if(request.responseText!='')
            alert(request.responseText)
    });
    $( document ).ajaxComplete(function() {
        enableButton()
    })

//start datatables only if there is a table in the page
if($('#table').length>0){
    var datatable= $('#table').DataTable({
			"processing":true,
			"serverSide":true,
			"order":order,
			"ajax" : {
                        url:url,
                        dataType:'json',
                        data:{
                            from_date: function() { return from_date },
                            to_date: function() { return to_date }
                        }
			},
            columns: columns,
			"columnDefs":[
				{
					"targets":  [ 'action'],
					"orderable":false,
				},
            ],
		})}

//same function for add button for multiple pages
    $('#add_button').click(function(){
        $('#form')[0].reset();
        let element = $(this).data('element') || '';
        $('#form').parsley().reset();
        //change the modal title from edit to add
        $('.modal-title').html("<i class='fa fa-plus'></i> Add "+element);
        $('#Modal').modal('show');
        $form=$('#Modal');
        //do not know if the form has input/submit or button/submit so doing both here
        $button= $form.find("button[type=submit]");
        $submit= $form.find("input[type=submit]");
        //changing the submit button values from edit, please wait etc to add
        $button.html("Add");
        $submit.val("Add");
        //incase the button could not be enabled due to ajax error or other problems
        $('.btn').attr('disabled', false);
        //change url through function
        callback('/create');
        //remove the previously created meesage and span if is it still there
        $('.errormessage,#span_item_details,#span_usergroup_details,#form_message,#user_uploaded_image').html('');
        if (typeof add_row== "function") {
            add_row();
        }
        $('#product_tax').attr('required',false);
        $('#product_tax').hide();
        $('#user_password').attr({'required': true,'data-parsley-minlength':'6','data-parsley-trigger':'on change'});
        $('#patient_visit_doctor_name').html('<option value="" >Select Department First</option>');
    });


    $(document).on('click', '.delete', function(){
    var id = $(this).attr('id');
    if(!id)
        id = $(this).data('id');
    var data={};
    var finalurl=url+'/'+id+'/delete'
    disable(finalurl,datatable,data,'delete the data','DELETE');
  });


  $(document).on('click', '.status', function(){
		var id = $(this).attr("id");
        if(!id)
            id = $(this).data('id');
		var status = $(this).data('status');
        var tableprefix=$(this).data('prefix');
        var finalurl=url+'/'+id+'/update'
        var data={}
        var column='status'
		var change="inactive";
        if(tableprefix)
            tableprefix +='_'
		if (status=='inactive')
			change="active";
            data[tableprefix+column] =change;
		disable(finalurl,datatable,data,'change the status');
  	});

      $(document).on('click', '.update', function(){
        var id =$(this).attr("id");
        if(!id)
            id = $(this).data('id');
        var element = $(this).data("prefix");
        $('#form_message').html('');
        var finalurl=url+'/'+id+'/edit';
		$('#form').parsley().reset();
		$.ajax({
			url:finalurl,
			method:"POST",
			data:{unit_id:id},
			dataType:"json",
			success:function(data)
			{
                if ("error" in data && data.error!=''){
                        result(data.error);
                    return
                }
                callback('/'+id+'/update');
                $('#Modal').modal('show');
                $('#action,#submit_button').html("Edit");
                $('#action,#submit_button').val("Edit");
                $('#product_tax').attr('required','required');
                $('#span_tax_details').html('');
                $('#product_tax').show();
                $('#user_password').attr('required', false);
                $('#modal_title').html("<i class='fas fa-edit'></i> Edit " +element);
                update(data);
			}
		})
    });

    $(document).on('click', '.view', function(){
        //get id from the id attribute and
        //if not available get it from the data id attribute
          let id = $(this).attr("id") || $(this).data('id');
          var finalurl=url+'/'+id+'/show';
          let element = $(this).data('prefix') || '';
          $.ajax({
				url:finalurl,
				method:"POST",
				data:{},
                dataType:'JSON',
				success:function(data){
                    $('#modal_item_details').html(data.response);
                    $('.modal-title').html('<i class="fas fa-eye"></i> View '+element);
                    $('#detailsModal').modal('show');
                    callback('/'+id+'/update');
                }
			})
  	});
    $(document).on('submit','#form,#second_form,.form', function(event){
    event.preventDefault();
    var finalurl=url+method_type;
    var form_data = new FormData(this);
    $form=$(this);
    $button= $form.find("button[type=submit]");
    $submit= $form.find("input[type=submit]");    //$(document.activeElement);
    buttonvalue=$button.html();
    submitvalue=$submit.val();
    if($form.parsley().isValid())
    {
        $.ajax({
            url:finalurl,
            method:"POST",
            data:form_data,
            dataType:'json',
            contentType:false,
            processData:false,
            beforeSend:function()
            {
                disableButton($button)
                disableButton($submit)
            },
            complete:function()
            {
                $button.html(buttonvalue);
                $submit.val(submitvalue);
            },
            success:function(data)
            {
                if(typeof data=="string")
                {
                    result(data);
                    return
                }
                if ("error" in data && data.error!=''){
                    result(data.error);
                    return
                }
                if ("success" in data && data.success!=''){
                    result(data.success,datatable);
                    return
                }
                if ("image" in data && data.image!=''){
                    $('#user_uploaded_image').html('<img src="'+data.image+'" class="img-thumbnail img-fluid rounded-circle" width="200" height="200" /><input type="hidden" name="hidden_user_image" value="'+data.image+'" />');
                }
                if($('.login').length>0){
                    update(data)
                    return
                }
                $('.modal').modal('hide');
                if($('.login,.no-reset,.reset').length<1){
                    $form[0].reset();
                }
                $form.parsley().reset();
                $('#user_password,.file_upload').val('');
                result(data.response,datatable)
                $('#span_tax_details').html('');
            }
        })
    }
});

        $(document).on('click', '#add_more', function(){
            count = $('.item_details').length;
            count = count + 1;
            add_row(count);
        });
        $(document).on('click', '.remove', function(){
            var row_no = $(this).attr("id");
            $('#row'+row_no).hide();
            $('#item_details_row'+row_no).remove();
        });
        function doctor(number){
    var output = '';
    var total=0;
    for (i = 0; i < doctors.length; i++) {
    if(doctors[i].department_id==number){
            output += '<option value="'+doctors[i].doctor_id+'">'+doctors[i].doctor_name+'</option>';
            total++;
        }
    }
    if(total==0)
            output = '<option value="">No Doctors Found</option>';
    return output;
}



$(document).on('change', '#patient_department', function(){
		var doctor_id = $('#patient_department').val();
        data=doctor(doctor_id)
        $('#patient_visit_doctor_name').html(data);
	})

    $('.input-daterange, .datepicker').datepicker({
		todayBtn: "linked",
        format: "yyyy-mm-dd",
        autoclose: true
    });
	var date = new Date();
    date.setDate(date.getDate());

    $('.timepicker').timeselector({
    min:'08:30',
	max:'18:30',
	step: 5,

  });
    $(".timepicker").on("change.datetimepicker", function (e){
        $('.timepicker').datetimepicker('minDate', e.date);
    });

    $(document).on('click', '.stop_auto_refresh', function(){
        if(!autorefresh)
            alert('autorefresh is already stopped');
        autorefresh=false;
    });
        $(document).on('click', '.start_auto_refresh', function(){
            if(autorefresh)
                alert('autorefresh is already started');
            autorefresh=true;
        });

    function enableButton(value=false){
    $('.btn').attr('disabled', false);
    $('.btn').css({"filter": "","-webkit-filter": ""});
    enableText(value);
}
function enableText(value=false,buttonvalue=''){
	if (!value)
	timeout();
    if (buttonvalue)
        $('#submit_button').html(buttonvalue);
    $('#hint').html('Login hint');
}
function disableButton(element='.btn'){
    $(element).css({"filter": "grayscale(100%)","-webkit-filter": "grayscale(100%)"});
    $(element).attr('disabled', 'disabled');
	$(element).html('Please wait...');
    $(element).val('Please wait...');

}
    function  hide()
	{
        $('.error, .message, .alert,#popup').slideUp();
    }

    function clear(){
        $('#message,#form_message,.alert').html('')
    }
    function timeout(datatable='')
	{
		setTimeout(function(){hide();}, 7000);

		setTimeout(function(){clear();}, 10000);
		if(datatable)
		datatable.ajax.reload();
    }

    function result(data,dataTable=''){
        $('.errormessage').html('');
		$('#message,#form_message').fadeIn().html(data);
		timeout(dataTable);
    }
    $('#filter').click(function(){
  		from_date = $('#from_date').val();
        to_date = $('#to_date').val();
        if(from_date==''){
            alert('Start date range needs to be selected')
            return
          }
        if(to_date =='')
        to_date =current_date();
       varname(from_date, to_date);
        datatable.ajax.reload();
      });


  	$('#refresh').click(function(){
  		$('#from_date,#to_date').val('');
          varname();
          datatable.ajax.reload();
  	});
      $('#report').click(function(){
  		var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(from_date==''){
            alert('Start date range needs to be selected')
            return
          }
        if(to_date =='')
        to_date =current_date();
        var table=$(this).data('table')
        reporturl=url+'/'+from_date+'/'+to_date+'/'+table
        window.open(reporturl);
      });

      $('#downloadPdf').click(function(event) {
        event.preventDefault();  // get size of report page
  var reportPageHeight = $('#chart').innerHeight();
  var reportPageWidth = $('#chart').innerWidth();

  // create a new canvas object that we will populate with all other canvas objects
  var pdfCanvas = $('<canvas />').attr({
    id: "canvaspdf",
    width: reportPageWidth,
    height: reportPageHeight
  });

  // keep track canvas position
  var pdfctx = $(pdfCanvas)[0].getContext('2d');
  var pdfctxX = 0;
  var pdfctxY = 0;
  var buffer = 100;

  // for each chart.js chart
  $("canvas").each(function(index) {
    // get the chart height/width
    var canvasHeight = $(this).innerHeight();
    var canvasWidth = $(this).innerWidth();

    // draw the chart into the new canvas
    pdfctx.drawImage($(this)[0], pdfctxX, pdfctxY, canvasWidth, canvasHeight);
    pdfctxX += canvasWidth + buffer;

    // our report page is in a grid pattern so replicate that in the new canvas
    if (index % 2 === 1) {
      pdfctxX = 0;
      pdfctxY += canvasHeight + buffer;
    }
  });

  // create new pdf and add our new canvas as an image
  var pdf = new jsPDF('l', 'pt', [reportPageWidth, reportPageHeight]);
  pdf.addImage($(pdfCanvas)[0], 'PNG', 0, 0);

  // download the pdf
  pdf.save('filename.pdf');
});

function printReport(response) {
	var mywindow = window.open('', '', 'height=400,width=600');
	mywindow.document.write('</head><body>');
	mywindow.document.write(response);
	mywindow.document.write('</body></html>');
	mywindow.document.close(); // necessary for IE >= 10
	mywindow.focus(); // necessary for IE >= 10
	mywindow.resizeTo(screen.width, screen.height);
		}// /success function

//function for confirmtion and warning dialogs
	function disable(finalurl,datatable,data,message="change the status",postmethod='POST'){
        $.confirm
        ({
            title: 'Confirmation please!',
            content: "This will "+ message+". Are you sure?",
			type: 'blue',
            buttons:{
						Yes: {
							btnClass: 'btn-blue',
							action: function() {
								$.ajax({
									url:finalurl,
									method:postmethod,
									data:data,
                                    dataType:"JSON",
									success:function(response){
                                        if(typeof response=="string")
                                        {
                                            result(response,datatable);
                                            return
                                        }
                                        if ("error" in response && response.error!=''){
                                             result(response.error);
                                            return
                                        }
                                        else if ("response" in response)
                                            result(response.response,datatable);
                                        else if ("success" in response)
                                            result(response.success,datatable);
                                        else
                                        result(response,datatable);
									}
								});
							}
						},
					}
        });
    }
//returns current date
    function current_date(format='-'){
        var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            return yyyy+ format + mm + format + dd;
    }


    //function to create alert messages
    function showAlert($content,$title='Error')
	{
			   $.alert({
						   title: $title,
						   content: $content,
						   buttons:
						   {
								   No: {
									   text:'OK',
									   btnClass: 'btn-blue',
								   },
								   Yes:{
									   isHidden: true,
								   }
						   }
					   });
	   }

</script>
