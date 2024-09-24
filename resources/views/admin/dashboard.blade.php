@extends('admin.layouts.app')

@section('pageTitle', 'Dashboard')

@section('customStyles')
 
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
          <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="javascript:void(0);"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        Welcome to admin panel
    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->
@endsection
@section('customScript')

@endsection
