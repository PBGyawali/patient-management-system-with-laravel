@include('config')
@include('layouts.header')
@include('layouts.sidebar')
<?php
$setup=false;
if(session()->has('setup') && session()->exists('setup')||isset($setuppage))
{
    $setup=true;
}
?>
<div class="col-sm-10 offset-sm-2 py-4">
<div class="d-flex flex-column " id="content-wrapper">
<div id="content">
<div class="container-fluid ">
 <div class="col-12 p-0">
<div class="d-flex flex-column" ><!-- Never added anything in the bottom -->
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?php echo ($setup)?'System Configuration':'Setting'?></h1>
                    <!-- DataTales Example -->
                    @include('message')
                    <form method="post" id="form" class="no-reset" enctype="multipart/form-data" action="<?= route('settings_update')?>">

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="m-0 font-weight-bold text-primary"><?php echo ($setup)?'Set up Account':'Setting'?></h6>
                                    </div>
                                    <div clas="col text-right" >
                                        <button type="submit"  id="submit_button" class="btn btn-primary btn-sm"> <?php echo ($setup)?'<i class="fas fa-save"></i>  Set Up':'<i class="fas fa-edit"></i> Edit'?></button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>facility Name</label>
                                            <input type="text" name="facility_name" value="<?php echo ($setup)?'':$info->facility_name?>"id="facility_name" required class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label>facility Email</label>
                                            <input type="email" name="facility_email" value="<?php echo ($setup)?'':$info->facility_email?>"id="facility_email" required class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label>facility Contact No.</label>
                                            <input type="text" name="facility_contact_no" value="<?php echo ($setup)?'':$info->facility_contact_no?>"id="facility_contact_no" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label>facility Address</label>
                                            <input type="text" name="facility_address" value="<?php echo ($setup)?'':$info->facility_address?>"id="facility_address" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                            <div class="form-group">
                                                <label>Patient Target</label>
                                                <input type="number" name="facility_target" value="<?php echo ($setup)?'':$info->facility_target?>"id="facility_target" class="form-control" />
                                            </div>
                                        <div class="form-group">
                                            <label>Currency</label>
                                            <?php  echo  $currencylist; ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Timezone</label>
                                            <?php  echo  $timezonelist; ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Secret Password</label>
                                            <input type="password" name="secret_password" value=""id="secret_password" class="form-control" />
                                            <span class="text-muted">This is required for access to secret field. Leave blank for no change</span><br />
                                        </div>
                                        <div class="form-group">
                                            <label>Select Logo</label><br />
                                            <input type="file" name="facility_logo" class="file_upload" id="facility_logo" data-allowed_file='[<?php echo '"' . implode('","', ALLOWED_IMAGES) . '"'?>]' data-upload_time="later" accept="<?php echo "image/" . implode(", image/", ALLOWED_IMAGES);?>"  />
                                            <br />
                                            <span class="text-muted">Only <?php  echo join(' and ', array_filter(array_merge(array(join(', ', array_slice(ALLOWED_IMAGES, 0, -1))), array_slice(ALLOWED_IMAGES, -1)), 'strlen'));?> extensions are supported</span><br />
                                            <span id="uploaded_logo"></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if($setup):?>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Admin Email Address</label>
                                            <input type="email" name="email" id="admin_email" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Admin Username</label>
                                            <input type="text" name="name" id="admin_name" class="form-control" required data-parsley-trigger="keyup" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Admin Password</label>
                                            <input type="password" name="password" id="admin_password" class="form-control" required data-parsley-trigger="keyup" />
                                        </div>
                                    </div>
                                </div>
                                <?php endif?>
                            </div>
                        </div>
                        <input type="hidden" name="branch_id" id="admin_password" value="<?php echo ($setup)?'':$info->branch_id?>" />
                    </form>
                    @include('layouts.footer')


