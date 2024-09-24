@extends('admin.layouts.app')

@section('pageTitle', 'Add')

@section('customStyles')
    
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Car Model
        </h1>
        <div >
            <a class="btn btn-warning black_btn" href="{{ route('car-model-list') }}" title="Go back"> <i class="fa fa-angle-double-left"></i> Back</a>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
       <div class="row">
            <div class="col-xs-12">
                <div class="box"><!-- /.box -->
                <form id="doc_status" method="post" action="{{ route('car-model-store') }}">
                    @csrf
                    <div class="box-body"> 
                        <div class="col-md-6 divi_list ">
                            <div class="form-group">
                            <label for="full_name" class="control-label">Car Make</label>
                            <select name="car_make_id" class="form-control" id="car_make_id">
                                <option value="">Select</option>
                                @if(count($carMakeList)>0)
                                    @foreach($carMakeList as $key => $val)
                                    <option value="{{ $val['id'] }}" >{{ $val['name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class='makeValidation' style="color:#ee8929;"></div>   
                            </div>
                        </div>        
                        <div class="col-md-6 divi_list ">
                            <div class="form-group">
                            <label for="full_name" class="control-label">Car Model Name</label>
                            <input type="text" class="form-control"  name="name" id="name" placeholder="Enter Car Model Name" />
                            <div class='nameValidation' style="color:#ee8929;"></div>   
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <button type="button" onclick="validateCarModel()" name="Add" id="add" class="btn btn-info update_btn">Add</button>                                    
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
    
function validateCarModel(){
    let name = $("#name").val();
    let car_make_id = $("#car_make_id").val();
    if(car_make_id.length == ""){
        $('.makeValidation').text("Please Enter Make Name.");
        setTimeout(function(){ $(".makeValidation").text(""); }, 10000);
    }
    else if(name.length == ""){
        $('.nameValidation').text("Please Enter Model Name.");
        setTimeout(function(){ $(".nameValidation").text(""); }, 10000);
    }else{
        $("#doc_status").submit();
    }
}
  </script>
@endsection
