@extends('admin.layouts.app')

@section('pageTitle', 'Edit')

@section('customStyles')
    
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Car Make
        </h1>
        <div >
            <a class="btn btn-warning black_btn" href="{{ route('car-make-list') }}" title="Go back"> <i class="fa fa-angle-double-left"></i> Back</a>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
       <div class="row">
            <div class="col-xs-12">
                <div class="box"><!-- /.box -->
                <form id="doc_status" method="post" action="{{ route('car-make-update',$car_make->id) }}">
                    @csrf
                    <div class="box-body">         
                        <div class="col-md-6 divi_list ">
                            <div class="form-group">
                            <label for="full_name" class="control-label">Name</label>
                            <input type="text" class="form-control"  name="name" id="name" value="{{ isset($car_make->name) ? $car_make->name : '' }}" />
                            <div class='nameValidation' style="color:#ee8929;"></div>   
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <button type="button" onclick="validateCarMake()" name="Edit" id="Edit" class="btn btn-info update_btn">Update</button>                                    
                        </div> 
                    </div><!-- /.box-body -->
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
    
function validateCarMake(){
    let name = $("#name").val();
    if(name.length == ""){
        $('.nameValidation').text("Please Enter Make Name.");
        setTimeout(function(){ $(".nameValidation").text(""); }, 10000);
    }else{
        $("#doc_status").submit();
    }
}
  </script>
@endsection
