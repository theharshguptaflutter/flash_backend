@extends('admin.layouts.app')
@section('pageTitle', 'User List')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
        User Listing
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> <b>Dashboard</b></a></li>
			<li><a href="{{ route('user-list') }}"><i class="fa fa-dashboard"></i> <b>User Management</b></a></li>
			<li class="active">List</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">                    
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

					<div class="col-md-12">
						<div class="row">
							<form class="form-horizontal" method="GET" id="search-table-form">
								
								<div class="col-md-3 divi_list">
									<div class="form-group">
										<label for="search_full_name" class="control-label">Name</label>
										<input type="text" class="form-control SearchInput" value="{{isset($search_data['search_full_name']) ? $search_data['search_full_name'] : ''}}" name="search_full_name" id="search_full_name" />
									</div>
								</div>
								<div class="col-md-3 divi_list">
									<div class="form-group">
										<label for="search_email" class="control-label">Email</label>
										<input type="text" class="form-control SearchInput" value="{{isset($search_data['search_email']) ? $search_data['search_email'] : ''}}" name="search_email" id="search_email" />
									</div>
								</div>
								<div class="col-md-3 divi_list">
									<div class="form-group">
										<label for="search_mobile" class="control-label">Phone No</label>
										<input type="text" class="form-control SearchInput" value="{{isset($search_data['search_mobile']) ? $search_data['search_mobile'] : ''}}" name="search_mobile" id="search_mobile" />
									</div>
								</div>
								<div class="col-md-3 divi_list">
									<div class="form-group">
										<label for="search_status" class="control-label">User Type</label>
										<select name="search_status" class="form-control SearchInput" id="search_status">
											<option value="">Select</option>
											<option value="P" {{(isset($search_data['search_status']) && $search_data['search_status']=='P' )? 'selected' : ''}}>Passenger</option>
											<option value="D" {{(isset($search_data['search_status']) && $search_data['search_status']=='D' )? 'selected' : ''}}>Driver</option>
                                        </select>
									</div>
								</div>
								<div class="col-md-3 divi_list mt-0">
									<div class="form-group">
										<a href="{{route('user-list')}}"  class="btn btn-primary blue_btn "><i class="fa fa-refresh"></i></a>
									</div>
								</div>
							</form>
						</div>
                	</div>
					<div class="col-md-12 list_table">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped" style="min-width: max-content;">
							<thead>
								<tr>
									<th style="width: 10px">#</th>
									<th>Name</th>
									<th>Email Id</th>
									<th>Phone Number</th>
                                    <th>User Type</th>
									<th>Status</th>
									<th>Created at</th>
									<th style="width: 100px;">Action</th>
									
								</tr>
							</thead>
							<tbody>
                                
								@if(!$userList->isEmpty())
								@php 
									$i=0;
								@endphp                                        
								
								@foreach($userList as $list)
									<tr id="row_{{$list->id}}">
										<td align="center">{{ ++$i }}</td>
										<td align="center">{{ $list->full_name }}</td>
										<td align="center">{{ $list->email }}</td>
										<td align="center">{{ $list->country_code }}{{ $list->mobile }}</td>
                                      
                                        @if($list->user_type == 'D')
                                                <td align="center" valign="middle"><span>Driver</span></td>
                                            @elseif($list->user_type == 'P')
                                                <td align="center" valign="middle"><span >Passenger</span></td>
											@else
												<td align="center" valign="middle"><span>N/A</span></td>
                                            @endif
                                       
											@if($list->status == 'Y')
                                                <td align="center" valign="middle"><a  class="status_btn" onclick="statusChange('<?php echo $list->id ?>','I')"><span>Active</span></a></td>
                                            @elseif($list->status == 'I')
                                                <td align="center" valign="middle"><a class="status_btn" onclick="statusChange('<?php echo $list->id ?>','Y')"><span >Inactive</span></a></td>
											@else
												<td align="center" valign="middle"><a href="javascript:void(0);"><span>Block</span></a></td>
                                            @endif

										<td align="center">{{ isset($list->created_at) ?date('d-M-Y',strtotime($list->created_at)) :'' }}</td>
										<td  align="center"><a href="{{route('user-view', $list->id)}}" class="btn btn-sm btn-warning blue_btn" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
									</tr>
								@endforeach
								@else
								<tr>
									<td colspan="8" style="text-align: center;"><span >No record(s) found</span></td>
									<td style="display:none;">&nbsp;</td>
									<td style="display:none;">&nbsp;</td>
									<td style="display:none;">&nbsp;</td>
									<td style="display:none;">&nbsp;</td>
									<td style="display:none;">&nbsp;</td>
									<td style="display:none;">&nbsp;</td>
									<td style="display:none;">&nbsp;</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
					<div class="col" style="text-align: center;">
						<div class="pagination center">
                        {{ (!empty($userList)) ? $userList->links('admin.layouts.admin-pagination.bootstrap-4') : '' }}
						</div>  
					</div>
        </div><!-- /.box -->
			</div>
		</div>
	</section>
	<!-- /.content -->
</div><!-- /.content-wrapper -->
@endsection
@section('customScript')
<script>
	$('.SearchInput').change(function(e){    
    	$("#search-table-form").submit();    
	});

	function statusChange(userid,isActive){ 
		var confirmMessage = window.confirm("Are you sure you want to change the status?");
		if(confirmMessage){
			$.ajax({
			type: 'post',
			url: '<?php echo route('status_change')?>',
			data: {userid:userid,isActive:isActive,'_token':'<?php echo csrf_token();?>'},
			success: function (result) {
				if(result == "Y"){
					window.location.reload();
					window.alert("Status change successfully");
				}else{
					window.alert("Status not change successfully");
				}
			}
			});
		}
	}

</script>
@endsection
