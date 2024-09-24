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
            Edit Driver Approval Amount
        </h1>
        <div >
            <a class="btn btn-warning black_btn" href="{{ route('approval-amount-list') }}" title="Go back"> <i class="fa fa-angle-double-left"></i> Back</a>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
       <div class="row">
            <div class="col-xs-12">
                <div class="box"><!-- /.box -->
                <form id="doc_status" method="post" action="{{ route('approval-amount-update',$payment->id) }}">
                    @csrf
                    <div class="box-body">         
                        <div class="col-md-6 divi_list ">
                            <div class="form-group">
                            <label for="amount" class="control-label">Amount</label>
                            <input type="text" class="form-control"  name="amount" id="amount" value="{{ isset($payment->amount) ? $payment->amount : 0.00 }}" onkeyup="amountCalculation()"/>
                            
                            </div>
                            <div class='amountValidation' style="color:#ee8929;"></div>   
                        </div>

                        <div class="col-md-6 divi_list ">
                            <div class="form-group">
                            <label for="percentage" class="control-label">Percentage (%)</label>
                            <input type="text" class="form-control"  name="percentage" id="percentage" id="percentage" value="{{ isset($payment->percentage) ? $payment->percentage : ' 0 %' }}" onkeyup="amountCalculation()" />
                            
                            </div>
                            <div class='percentageValidation' style="color:#ee8929;"></div>   
                        </div>

                        <div class="col-md-6 divi_list ">
                            <div class="form-group">
                            <label for="tax" class="control-label">Tax</label>
                            <input type="text" class="form-control"  name="tax" id="tax" value="{{ isset($payment->tax) ? $payment->tax : 0.00 }}" readonly />
                             
                            </div>
                            <div class='taxValidation' style="color:#ee8929;"></div> 
                        </div>

                        <div class="col-md-6 divi_list ">
                            <div class="form-group">
                            <label for="total_amount" class="control-label">Total Amount</label>
                            <input type="text" class="form-control"  name="total_amount" id="total_amount" value="{{ isset($payment->total_amount) ? $payment->total_amount : 0.00 }}" readonly />
                            
                            </div>
                            <div class='totalAmountValidation' style="color:#ee8929;"></div>   
                        </div>

                        <div class="card-footer clearfix">
                            <button type="button" onclick="validateApprovalAmount()" name="Edit" id="Edit" class="btn btn-info update_btn">Update</button>                                    
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
<script src="https://code.jquery.com/jquery-git.js"></script>
  <script>
    
function validateApprovalAmount(){
    let amount = $("#amount").val();
    let percentage = $("#percentage").val();
    // let tax = $("#tax").val();
    // let totalAmount = $("#total_amount").val();
    if(amount.length == ""){
        $('.amountValidation').text("Please Enter Amount.");
        setTimeout(function(){ $(".amountValidation").text(""); }, 10000);
    }else if(percentage.length == ""){
        $('.percentageValidation').text("Please Enter Percentage.");
        setTimeout(function(){ $(".percentageValidation").text(""); }, 10000);
    }else{
        $("#doc_status").submit();
    }
}

$('#amount').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});

$('#percentage').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});

function amountCalculation(){
    //var decimal=  /[^0-9\.]/g,''; 
    var amount = $("#amount").val();
    var percentage = $("#percentage").val();
    var totalAmount = 0.00;
    //console.log(percentage);
    if(amount != 0.00 && amount != ""){
        var taxAmount = ((percentage * amount) / 100).toFixed(2);
        //console.log("taxAmount ====",taxAmount);
        totalAmount = parseFloat(amount) + parseFloat(taxAmount);
        $("#tax").val(taxAmount);
        $("#total_amount").val(totalAmount.toFixed(2));
        
    }
    if(amount == "" || percentage == ""){
        $("#tax").val(0.00);
        $("#total_amount").val(0.00);
    }

}
  </script>
@endsection
