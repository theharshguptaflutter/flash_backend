@extends('admin.layouts.app')
@section('pageTitle', 'Car Make List')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
        Car Make Listing
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> <b>Dashboard</b></a></li>
			<li><a href="{{ route('car-make-list') }}"><i class="fa fa-dashboard"></i> <b>Car Make</b></a></li>
			<li class="active">List</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">   
                    <div class="box-header with-border">
                        <a href="{{ route('car-make-add') }}" style="float:right;" ><button class="btn btn-info update_btn">Add</button></a>
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
					<div class="col-md-12 list_table">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped" style="min-width: max-content;">
							<thead>
								<tr>
									<th style="width: 10px">#</th>
									<th>Name</th>
                                    <th>Created at</th>
									<th style="width: 100px;">Action</th>
									
								</tr>
							</thead>
							<tbody>
                                
								@if(!$carMakeList->isEmpty())
								@php 
									$i=0;
								@endphp                                        
								
								@foreach($carMakeList as $list)
									<tr id="row_{{$list->id}}">
										<td align="center">{{ ++$i }}</td>
										<td align="center">{{ $list->name }}</td>
                                        <td align="center">{{ isset($list->created_at) ?date('d-M-Y',strtotime($list->created_at)) :'' }}</td>
										<td  align="center">
                                            <a href="{{route('car-make-view', $list->id)}}" class="btn btn-sm btn-warning blue_btn" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            <a href="{{route('car-make-delete', $list->id)}}" onclick="return confirm('are you want to delete {{ $list->name }} ?');" class="btn btn-sm btn-warning" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        </td>
									</tr>
								@endforeach
								@else
								<tr>
									<td colspan="3" style="text-align: center;"><span >No record(s) found</span></td>
									<td style="display:none;">&nbsp;</td>
									<td style="display:none;">&nbsp;</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
					<div class="col" style="text-align: center;">
						<div class="pagination center">
                        {{ (!empty($carMakeList)) ? $carMakeList->links('admin.layouts.admin-pagination.bootstrap-4') : '' }}
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

</script>
@endsection
