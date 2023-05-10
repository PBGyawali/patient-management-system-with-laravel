

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
    var doctor_list;
    var dataTable='';
    var autoRefresh=true;
    var hideTimeoutId;
    var clearTimeoutId;
// if the form is not login form then enable parsley
if($('.login').length<1 && typeof 'parsley'=='function'){
        $('#form').parsley();
        $('#second_form').parsley();
}





//create an array of columns for dataTables to
    //create data automatically according to the current table headers
    $("th").each(
        function ()
            {
                var className=$(this).attr('class')
                if(className){
                        var th =className.split(' ')[0]
                        var th2 =className.split(' ')[1]
                        var th3 =className.split(' ')[2]

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
                        });
                    }
                    else{
                        columns.push({data: th,name:th})
                    }
                    if(th2=='order'){
                        var lastIndex = columns.length - 1;
                        order=[lastIndex,th3||'desc'];
                    };
                }
            }
    );



    
    var url=$('#form').attr('action');
    listurl=url+'/list';
    var reportUrl=url+'/getOrderReport';
	var fetchurl=url+"/max";

    function defineDateRange(start='',end=''){
        from_date=start
        to_date=end
    }


    function callback(...args) {
        if (args.length === 0||args.length === 1) {
            method_type = args;
        } else {
            method_type="/" + args.join("/");
        }
    }

    $('#toggle_refresh').click(function(e){
        autoRefresh = !autoRefresh;
        $('#toggle_refresh').attr('title',`Toggle auto refresh. Currently set to ${autoRefresh}`)
                        .toggleClass('btn-danger btn-success')
                        .find('i').toggleClass('fa-toggle-on fa-toggle-off');

    });

    function modifyUrl(currentUrl, ...replaceArgs) {
        if (!currentUrl.trim().startsWith("http://localhost/")) {
            currentUrl = currentUrl.replace("http", "https");
        }

        for (let i = 0; i < replaceArgs.length; i += 2) {
            const replaceableText = replaceArgs[i];
            const replaceableValue = replaceArgs[i + 1];
            currentUrl = currentUrl.replace(encodeURIComponent(replaceableText), encodeURIComponent(replaceableValue))
                                    .replace(replaceableText, encodeURIComponent(replaceableValue));
        }

        return currentUrl;
    }

    function findList(number,element='doctor',checkColumn='department'){
            // must have variable declared with the format ${element}_list
            let output = '';
            var total=0;
            let checkedList=window[element+'_list'];
            if(checkedList){
                for (i = 0; i < checkedList.length; i++) {
                    if(checkedList[i][checkColumn+'_id']==number){
                    output += '<option value="'+checkedList[i][element+'_id']+'">'+checkedList[i][element+'_name']+'</option>';
                    total++;
                    }
                }
                if(total==0)
                        output = '<option value="">No '+element+' Found</option>';
            }
            return output;
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
            showMessage(request.responseJSON.message);
                if(!$('.login').length>0){
                        $.each(request.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="'+i+'"]');
                        el.after($('<span class="text-danger errormessage">'+error[0]+'</span>'));
                    });
                }
        }
        else if ([401].includes(request.status)) {
            showMessage(request.responseJSON.message);
        }
        else if (request.status == 419) {
            showMessage(request.responseJSON.message+ ' Please relogin or refresh');
        }
        else if(request.responseText!='')
            alert(request.responseText)
    });
    $( document ).ajaxComplete(function() {
        enableButton()
    })



    reset_patient_status();
  setInterval(function(){
   if(autoRefresh && $('#patient_status').length){
        reset_patient_status();
    }
    }, 15000);

    $(document).on('click', '.refresh_now', function(){
    reset_patient_status();
    });
    function reset_patient_status()
    {
        if($('#patient_status').length){
            ajaxCall(url).then(function(result) {
                $('#patient_status').html(result);
            })
        }
    }
//start dataTables only if there is a table in the page
if($('#table').length && !$.fn.DataTable.isDataTable($('#table'))){
    dataTable= $('#table').DataTable({
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



        if(typeof listurl!='undefined' && listurl){
                let allowedDomain=['appointment','patient'];
                let restrictedDomain=['status'];
                var listPage= allowedDomain.some(function(allowedDomain) {
                    return listurl.indexOf(allowedDomain) !== -1;
                });
                var nonListPage= restrictedDomain.some(function(restrictedDomain) {
                    return listurl.indexOf(restrictedDomain) !== -1;
                });
                if(listPage && !nonListPage){
                    listurl=modifyUrl(listurl);
                    ajaxCall(listurl).then(function(result) {
                        $.each(result, function(key, value){
                        window[key+'_list']=value
                    })
                })
            }
        }


//same function for add button for multiple pages
    $('#add_button').click(function(e){
        let element=$(this).data('element');
        if(typeof CKEDITOR !== 'undefined')
        CKEDITOR.instances.body.setData('');
        let modal=$(this).data('target') ||'#Modal';
        $form=$(modal).find('form');
        $form[0].reset();
        if(typeof 'parsley'=='function'){
            $form.parsley().reset();
        }        
        $title=$(modal).find('.modal-title');
        $title.html("<i class='fa fa-plus'></i> Add "+element);
        //do not know if the form has input/submit or button/submit so doing both here
        $button= $form.find("button[type=submit]");
        $submit= $form.find("input[type=submit]");
        //changing the submit button values from edit, please wait etc to add
        $button.html('<i class="fa fa-paper-plane"></i>'+ " Store "+element).attr('disabled', false);
        $submit.val('<i class="fa fa-paper-plane"></i>'+  " Store "+element).attr('disabled', false);
        //adding extra string to current base url
        callback('/create');
        $('#publish,#anonymity').attr('checked',false);
      	$("#featured_image").prop('required', true);
       $('.errormessage,#span_item_details,#append_ticket,#form_message,#append_comment').html('');
        var clickedElement = $(e.target);
        if (clickedElement.is('a') || clickedElement.parents('a').length > 0) {
            const linkElement = clickedElement.is('a') ? clickedElement : clickedElement.parents('a').eq(0);
            if (linkElement.attr('href') !== 'javascript:;') {
                //if the link tag does not have javascript value
                //then it takes to another page so cancel any further actions
                return;
            }
        }
        element = element.toLowerCase().replace(/\s/g, "_");
        //if add_row function exist in the page then call it
        if(typeof window['add_row_'+element] == 'function')
            window['add_row_'+element]();
        else if ( typeof add_row == 'function' )
            add_row();
        $('#user_password').attr({'required': true,'data-parsley-minlength':'6','data-parsley-trigger':'on change'});
        $('#brand_id').html('<option value="" >Select Category First</option>');
        $('#patient_visit_doctor_name').html('<option value="" >Select Department First</option>');
        $(modal).modal().show();
    });


    $(document).on('click', '.delete', function(){
        let id = $(this).attr("id")||$(this).data('id');
        let data={};
        let finalUrl=url+'/'+id+'/delete'
        return;
    disable(finalUrl,data,'delete the data','DELETE');
  });


  $(document).on('click', '.status', function(){
        // Get the ID attribute, or use the data ID if it's not present
        const id = $(this).attr('id') || $(this).data('id');
        // Get the current status and table prefix from data attributes
        const status = $(this).data('status');
        const tablePrefix = $(this).data('prefix');
        //final url to use to send the request
        callback(id,'update');
        let finalUrl=url+method_type;
        let data={}
        const column = 'status';
        let newStatus = 'active';
        if (status === 'active') {
            newStatus = 'inactive';
        }
        columnWithPrefix = column;
        //add the table prefix and column variable to make the column name
        if (tablePrefix) {
            columnWithPrefix = `${tablePrefix}_${column}`;
        }
        data[columnWithPrefix] = newStatus;
        disable(finalUrl,data,'change the status');
  	});

      $(document).on('click', '.update', function(){
        let id = $(this).attr("id")||$(this).data('id')
        var element = $(this).data("prefix");
        let modal=$(this).data('target') ||'#Modal';        
        var finalUrl=url+'/'+id+'/edit'
        $('#form_message').html('');
        $form=$(modal).find('form');
        if(!$form)
        return
        $form[0].reset();
        if(typeof 'parsley'=='function'){
            $form.parsley().reset();
        }
        ajaxCall(finalUrl).then(function(result) {
            callback(id,'update');
            if($('.tickets').length<=0){
                    $(modal).modal('show');
            }
            $form=$(modal).find('form');
            $button= $form.find("button[type=submit]");
            $submit= $form.find("input[type=submit]");
            $button.html("Edit " +element);
            $submit.val("Edit "+element);
            $('#span_tax_details,.object_details').html('');
            $('#user_password,#featured_image').attr('required', false);
            $('.btn').attr('disabled', false);
            $title=$(modal).find('.modal-title');
            $title.html("<i class='fas fa-edit'></i> Edit " +element);
            element = element.toLowerCase().replace(/\s/g, "_");
            if (typeof window[element+'_update']== "function") {
                window[element+'_update'](result);
            }
            else if(typeof 'update'== "function")
            update(result);
            else
            easy_update(result);
        })
    });


      $(document).on('click', '.view', function(){
        let id = $(this).attr("id")||$(this).data('id')
        callback(id,'show');
        var finalUrl=url+method_type
        let element = $(this).data('prefix') || '';
        ajaxCall(finalUrl).then(function(result) {
            $('#detailsModal').modal('show');
            $('#modal_item_details').html(result);
            callback(id,'update');
            $('.modal-title').html('<i class="fas fa-eye"></i> View '+element);
        })
    });


    $(document).on('submit','#form,#second_form,.form', function(event){
        event.preventDefault();
        var finalUrl=url+method_type;
        var form_data = new FormData(this);
        $form=$(this);
        $button= $form.find("button[type=submit]"); 
        buttonvalue=$button.html();        
        if(!typeof 'parsley'=='function'||$form.parsley().isValid())
        {
            ajaxCall(finalUrl,form_data).then(function(result) {
                        if($('.login').length>0){
                            update(result)
                            return
                        }
                        if($('.no-close').length<=0){
                            $('#Modal,.modal').modal('hide');
                        }
                        if($('.login,.no-reset,.reset').length<=0){
                            $form[0].reset()
                        }
                        $form.parsley().reset();
                        $('#span_tax_details,.item_details').html('');
                        $('.file_upload,.password').val('');
                        $button.html(buttonvalue);                       
                }).catch(function(error) {
                    $button.html(buttonvalue);                   
                });
        }
    });

        $(document).on('click', '#add_more,.add_more', function(){
            let element=$(this).data('element');
            let count = $('.item_details').length;
            if (typeof window['add_row_'+element]== "function") {
                window['add_row_'+element](count);
            }
            else
            add_row(count);
        });


        $(document).on('click', '.remove', function(){
            var rowNumber = $(this).attr("id");
            $('#row'+rowNumber).hide();
            $('#item_details_row'+rowNumber).remove();
        });



    $(document).on('change', '#patient_department', function(){
		var doctor_id = $('#patient_department').val();
        data=findList(doctor_id)
        $('#patient_visit_doctor_name').html(data);
	})

  

	

    $(document).on('click', '.stop_auto_refresh', function(){
        if(!autoRefresh)
            alert('autorefresh is already stopped');
        autoRefresh=false;
    });
        $(document).on('click', '.start_auto_refresh', function(){
            if(autoRefresh)
                alert('autorefresh is already started');
            autoRefresh=true;
        });

    function enableButton(value=false){
    $('.btn').attr('disabled', false);
    $('.btn').css({"filter": "","-webkit-filter": ""});
    }
    function disableButton(element='button[type="submit"].btn'){
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

    function timeout()
	{
        clearTimeout(hideTimeoutId);
        clearTimeout(clearTimeoutId);

        hideTimeoutId = setTimeout(function(){hide();}, 10000);
        clearTimeoutId = setTimeout(function(){clear();}, 15000);

        if(dataTable && autoRefresh) {
            dataTable.ajax.reload();
        }
    }


    function result(data){
        $('.errormessage').html('');
		$('#message,#form_message').fadeIn().html(data);
		timeout();
    }


  	$('#refresh').click(function(){
  		$('#from_date,#to_date').val('');
          defineDateRange();
          dataTable.ajax.reload();
  	});

      $('#export,#report,#filter').click(function(){
  		var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(!from_date){
            showAlert('Start date range needs to be selected')
            return
        }
        if(!to_date)
        to_date =current_date();
        var currentUrl=$(this).data('url')
        if(currentUrl){
                reportUrl=modifyUrl(currentUrl,':from_date', from_date,':to_date',to_date)
                window.open(reportUrl);
        }
        else{
            defineDateRange(from_date, to_date);
            dataTable.ajax.reload();
        }
  	});



//function for confirmtion and warning dialogs
	function disable(finalUrl,data,message="change the status",postmethod='POST'){
        $.confirm
        ({
            title: 'Confirmation please!',
            content: "This will "+ message+". Are you sure?",
			type: 'blue',
            buttons:{
						Yes: {
							btnClass: 'btn-blue',
							action: function() {
                                ajaxCall(finalUrl,data,postmethod);
							}
						},
					}
        });
    }
//returns current date
    function current_date(separator = '-',format = 'yyyy-mm-dd') {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');//January is 0!
        const dd = String(today.getDate()).padStart(2, '0');

        if (format === 'yyyy-mm-dd') {
            return `${yyyy}${separator}${mm}${separator}${dd}`;
        } else if (format === 'dd-mm-yyyy') {
            return `${dd}${separator}${mm}${separator}${yyyy}`;
        } else if (format === 'mm-dd-yyyy') {
            return `${mm}${separator}${dd}${separator}${yyyy}`;
        } else {
            throw new Error(`Invalid date format: ${format}`);
        }
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


    function ajaxCall(sendUrl,sendData=[],postmethod='POST') {
        sendUrl=modifyUrl(sendUrl)
            return new Promise(function(resolve, reject) {
                let contentType="application/x-www-form-urlencoded; charset=UTF-8";
                let processData=true;
                if (sendData.constructor === FormData) {
                    contentType=false;
                    processData=false;
                }
                $.ajax({
                    url: sendUrl,
                    method:postmethod,
                    data:sendData,
                    dataType:"JSON",
                    contentType:contentType,
                    processData:processData,
                    success: function(response) {
                        if(typeof response=="object"){
                            let responseKeys=['error','response','success','warning']
                            let classKeys=['danger','success','success','warning']
                            $.each(responseKeys, function(key, value){

                                if (value in response && response[value]){
                                    if($('<div>').html(response[value]).find('div').length) {
                                        result(response[value]);
                                    } else {
                                        let classValue=classKeys[key];
                                        showMessage(response[value],classValue);
                                    }
                                }
                            });
                            if ("image" in response && response.image!=''){
                                $('#profile_image,.profile_image').attr('src',response.image);
                            }
                            if ("redirect" in response && response.redirect!=''){
                                window.location.assign('.'+response.redirect);
                            }
                            if ("update" in response && response.update!=''){                               
                                update(response.update)
                            }
                        }

                        resolve(response);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        reject(errorThrown);
                    }
                });
            });
    }


    /*template section start*/

    function showMessage(message,type='danger')
	{

        let response=
        `<div class="alert alert-${type} alert-dismissible fade show">
            <button type="button" class="close" onclick="hide()">&times;</button>
                            ${message}
        </div>`
        result(response)
	}

    /*template section end*/

        function appointment_update(data){
            easy_update(data)
            $('#patient_mobile_no').val(data.patient_contact);
            $('#timepicker').val(data.appointment_time);
            $('#datepicker').val(data.appointment_date);
            $('#patient_department').val(data.appointment_department_id);
            $('#patient_visit_doctor_name').html(data.appointment_doctor_id);
            $('#patient_reason_to_visit').val(data.appointment_reason);
        }


        function patient_history_update(data){
            easy_update(data)
            let doctors=findList(data['patient_department'])
            $('#patient_visit_doctor_name').html(doctors);
        }


            function easy_update(data){
                $.each(data, function(key, value){
                    $(`#${key}`).val(data[key]);
                });
            }


            function schedule_update(data){
                $('#doctor_id').val(data.doctor_id);
                $('#department_id').val(data.department_id);
                $('#schedule_start_time').val(data.schedule_start_time);
                $('#schedule_end_time').val(data.schedule_end_time);              
                const checkboxArray = $('.available_days');                   
                const valuesToCheck = data.available_days;
                // Split the string into an array of values and remove spaces
                let valuesArray = valuesToCheck.split(',').map(value => value.trim());
                    checkboxArray.each(function() {
                    const checkboxValue = $(this).val();
                    if (valuesToCheck.indexOf(checkboxValue) !== -1) {
                        $(this).prop('checked', true);
                    }
                    });   
            }

            function user_update(data){
                $('#user_password').removeAttr('required data-parsley-minlength data-parsley-trigger' );
                $('#user_name').val(data.username);
                $('#user_contact_no').val(data.contact_no);
                $('#user_email').val(data.email);
                $('#user_type').val(data.user_type);
                $('#user_uploaded_image').html('<img src="'+data.profile+'" class="img-fluid img-thumbnail" width="75" height="75" /><input type="hidden" name="hidden_user_image" value="'+data.profile+'" />');
            }

</script>
