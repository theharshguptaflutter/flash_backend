@extends('admin.layouts.app')
@section('pageTitle', 'Profile')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Profile Settings
		</h1>
		<ol class="breadcrumb">
			<li><a href="javascript:void(0);"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Settings</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Update Profile</h3>
					</div>
					@if($errors->any())
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							@foreach($errors->all() as $error)
								<p>{{ $error }}</p>
							@endforeach
						</div>
					@endif
					@if(session('success'))
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						{{ session('success') }}
					</div>
					@endif
					<!-- form start -->
          			<h2>Profile Setting</h2>
					<form class="form-horizontal" name="settings_form" action="{{url('admin/Profile-Save')}}" method="post" enctype="multipart/form-data" id="admin_profile">
						{{ csrf_field() }}
						<div class="box-body">
						<?php
							$path = "";
							if(Auth::user()->profile_picture != NULL){
								$path = Auth::user()->profile_picture; 
							}                                
						?>
              				<div class="form-group">
								<label for="profile_image" class="col-sm-2 control-label">Profile Picture</label>
								<div class="col-sm-6">
								<span class="btn btn-default btn-file">
									Browse <input type="file" accept="image/*" name="profile_image" id="upload"/>
								</span>
									<p class="help-block" id="thumb_image_help">Current Profile Picture</p>
									<img style="border-radius:10px;" class="list_table_img" id="img" src="@if(isset(Auth::user()->profile_picture)) {{ PUBLIC_PATH.'images/'. $path }} @else {{ asset('assets/noimage.jpg') }} @endif" alt="No Logo">
								</div>
							</div>

							<div class="form-group">
								<label for="first_name" class="col-sm-2 control-label">Name*</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="first_name" name="full_name" value="{{Auth::user()->full_name}}" />
								</div>
							</div>

      						

              				<div class="form-group">
								<label for="email" class="col-sm-2 control-label">Email*</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="email" name="email" value="{{Auth::user()->email}}" readonly />
								</div>
							</div>

							<div class="form-group">
								<label for="phone_code" class="col-sm-2 control-label">Phone Code*</label>
								<div class="col-sm-6">
								<input type="text" class="form-control" id="phone_code" name="country_code" value="{{Auth::user()->country_code}}" onkeypress="return isNumber(event)"/>
									
								</div>
							</div>

              				<div class="form-group">
								<label for="phone" class="col-sm-2 control-label">Phone*</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="phone" name="mobile" value="{{Auth::user()->mobile}}" onkeypress="return isNumber(event)"/>
								</div>
							</div>
						</div><!-- /.box-body -->
						<div class="box-footer">
							<button type="button" class="btn btn-info pull-right" onclick="validateProfile()">Save</button>
						</div><!-- /.box-footer -->
					</form>
					<h2>Security Setting</h2>

					<form class="form-horizontal" name="settings_form" action="{{ url('admin/Change-Password') }}" method="post" enctype="multipart/form-data" id="change_password">
					{{ csrf_field() }}
						<div class="box-body">
							<div class="form-group">
								<label for="old_password" class="col-sm-2 control-label">Old Password</label>
								<div class="col-sm-6">
									<input type="password" class="form-control" required id="old_password" name="old_password" value="" />
								</div>
							</div>

							<div class="form-group">
								<label for="new_password" class="col-sm-2 control-label">New Password</label>
								<div class="col-sm-6">
									<input type="password" class="form-control" required id="new_password" name="new_password" value="" />
								</div>
							</div>

							<div class="form-group">
								<label for="confirm_password" class="col-sm-2 control-label">Confirm Password</label>
								<div class="col-sm-6">
									<input type="password" class="form-control" required id="confirm_password" name="confirm_password" value="" />
								</div>
							</div>
						</div><!-- /.box-body -->
						<div class="box-footer">
							<button type="button" class="btn btn-info pull-right" onclick="validateChangePassword()">Save</button>
						</div><!-- /.box-footer -->
					</form>

				</div><!-- /.box -->
			</div>
		</div>
	</section>
						<!-- /.content -->
</div><!-- /.content-wrapper -->
@endsection
@section('customScript')
	<script>
	function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
	$(function(){
		$('#upload').change(function(){
			var input = this;
			var url = $(this).val();
			var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg"))
			 {
					var reader = new FileReader();

					reader.onload = function (e) {
						 $('#img').attr('src', e.target.result);
					}
				 reader.readAsDataURL(input.files[0]);
			}
		});
	});

	function validateProfile(){
        let fname = $("#first_name").val();
		let phone = $("#phone").val();
		let phone_code = $("#phone_code").val();
        if(fname.trim().length == ""){
            $("#first_name").focus();
        }else if(phone_code.trim().length == ""){
            $("#phone_code").focus();
        }else if(phone.trim().length == ""){
            $("#phone").focus();
        }else{
			$("#admin_profile").submit();
		}
    }

	function validateChangePassword(){
        let old_password = $("#old_password").val();
        let new_password = $("#new_password").val();
		let confirm_password = $("#confirm_password").val();
        if(old_password.trim().length == ""){
            $("#old_password").focus();
        } else if(new_password.trim().length == ""){
            $("#new_password").focus();
        }else if(confirm_password.trim().length == ""){
            $("#confirm_password").focus();
        }else{
            $("#change_password").submit();
        }
    }



	</script>
@endsection
