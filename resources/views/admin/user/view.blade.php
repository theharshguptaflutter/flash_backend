@extends('admin.layouts.app')

@section('pageTitle', 'View User')

@section('customStyles')
    
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            View User
        </h1>
        <div >
            <a class="btn btn-warning black_btn" href="{{ route('user-list') }}" title="Go back"> <i class="fa fa-angle-double-left"></i> Back</a>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
                     <div class="col-xs-12">
                        <div class="box"><!-- /.box -->
                            <div class="box-body">         
                                <div class="col-md-6 divi_list ">
                                    <div class="form-group">
                                    <label for="full_name" class="control-label">Name</label>
                                    <input type="text" class="form-control"  name="full_name" id="full_name" value="{{isset($userRecord->full_name) ? $userRecord->full_name : 'N/A' }}"  readonly />
                                    </div>
                                </div>
                                
                                <div class="col-md-6 divi_list ">
                                    <div class="form-group">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="email" class="form-control"  name="email" id="email" value="{{isset($userRecord->email) ? $userRecord->email : 'N/A' }}" readonly />
                                    </div>
                                </div>
                                
                                <div class="col-md-6 divi_list ">
                                    <div class="form-group">
                                    <label for="phone" class="control-label">Phone No</label>
                                    <input type="text" class="form-control"  name="mobile" id="phone" value="{{isset($userRecord->country_code) ? $userRecord->country_code : 'N/A' }}{{isset($userRecord->mobile) ? $userRecord->mobile : 'N/A' }} " readonly />
                                    </div>
                                </div>

                                <div class="col-md-6 divi_list ">
                                    <div class="form-group">
                                    <label for="user_type" class="control-label">User Type</label>
                                    <input type="text" class="form-control"  name="user_type" id="user_type" value="
                                    <?php if($userRecord->user_type == "P"){
                                        echo "Passenger";
                                    }else if($userRecord->user_type == "D"){
                                        echo "Driver";
                                    }else{
                                        echo "N/A";
                                    } ?>
                                    " readonly />
                                    </div>
                                </div>

                                <div class="col-md-6 divi_list ">
                                    <div class="form-group">
                                    <label for="status" class="control-label">Status</label>
                                    <input type="text" class="form-control"  name="status" id="status" value="
                                    <?php if($userRecord->status == "Y"){
                                        echo "Active";
                                    }else if($userRecord->status == "I"){
                                        echo "Inactive";
                                    }else{
                                        echo "Block";
                                    } ?>
                                    " readonly />
                                    </div>
                                </div>

                            </div><!-- /.box-body -->
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
