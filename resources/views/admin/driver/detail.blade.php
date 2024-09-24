@extends('admin.layouts.app')

@section('pageTitle', 'Driver View')

@section('customStyles')
    
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            View Driver
        </h1>
        <div >
            <a class="btn btn-warning black_btn" href="{{ route('driver-list') }}" title="Go back"> <i class="fa fa-angle-double-left"></i> Back</a>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
                     <div class="col-xs-12">
                        <div class="box"><!-- /.box -->
                            <!-- <div class="box-header">
                                 <h3 class="box-title">View Driver</h3>
                                 
                            </div> -->
                            <?php //echo "<pre>"; print_r($driver);exit; ?>
                             <div class="box-body">           
                                 

                                    <div class="col-md-12 divi_list diriver_deatils_heading">
                                      <div class="form-group">
                                        <h3>Driver Details</h3>
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_top">
                                      <div class="form-group">
                                        <label for="full_name" class="control-label">Name</label>
                                        <input type="text" class="form-control"  name="full_name" id="full_name" value="{{isset($userRecord->full_name) ? $userRecord->full_name : 'N/A' }}"  readonly />
                                      </div>
                                    </div>
                                   
                                    <div class="col-md-6 divi_list diriver_deatils_top">
                                      <div class="form-group">
                                        <label for="email" class="control-label">Email</label>
                                        <input type="email" class="form-control"  name="email" id="email" value="{{isset($userRecord->email) ? $userRecord->email : 'N/A' }}" readonly />
                                      </div>
                                    </div>
                                   
                                    <div class="col-md-6 divi_list diriver_deatils_top">
                                      <div class="form-group">
                                        <label for="phone" class="control-label">Phone No</label>
                                        <input type="text" class="form-control"  name="mobile" id="phone" value="{{isset($userRecord->country_code) ? $userRecord->country_code : 'N/A' }}{{isset($userRecord->mobile) ? $userRecord->mobile : 'N/A' }} " readonly />
                                      </div>
                                    </div>

                                    

                                    <?php if(count($userBankRecord)>0){
                                      $i=0;
                                    foreach($userBankRecord as $bankKey => $bankVal){  
                                    ?>
                                    <div class="col-md-12 divi_list diriver_deatils_heading">
                                      <div class="form-group">
                                        <h3>Bank Details {{ ++$i }}</h3>
                                      </div>
                                    </div>
                                    
                                    <div class="col-md-6 divi_list diriver_deatils_top">
                                      <div class="form-group">
                                        <label for="bank_name" class="control-label">Bank Name</label>
                                        <input type="text" class="form-control"  name="bank_name" id="bank_name" value="{{isset($bankVal['bank_name']) ? $bankVal['bank_name'] : 'N/A' }}"  readonly />
                                      </div>
                                    </div>
                                   
                                    <div class="col-md-6 divi_list diriver_deatils_top">
                                      <div class="form-group">
                                        <label for="holder_name" class="control-label">Account Holder Name</label>
                                        <input type="text" class="form-control"  name="holder_name" id="holder_name" value="{{isset($bankVal['holder_name']) ? $bankVal['holder_name'] : 'N/A' }}" readonly />
                                      </div>
                                    </div>
                                   
                                    <div class="col-md-6 divi_list diriver_deatils_top">
                                      <div class="form-group">
                                        <label for="account_number" class="control-label">Account Number</label>
                                        <input type="text" class="form-control"  name="account_number" id="account_number" value="{{isset($bankVal['account_number']) ? $bankVal['account_number'] : 'N/A' }} " readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_top">
                                      <div class="form-group">
                                        <label for="branch_code" class="control-label">Branch Code</label>
                                        <input type="text" class="form-control"  name="branch_code" id="branch_code" value="{{isset($bankVal['branch_code']) ? $bankVal['branch_code'] : 'N/A' }}" readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_top">
                                      <div class="form-group">
                                        <label for="swift_code" class="control-label">Swift Code</label>
                                        <input type="text" class="form-control"  name="swift_code" id="swift_code" value="{{isset($bankVal['swift_code']) ? $bankVal['swift_code'] : 'N/A' }}" readonly />
                                      </div>
                                    </div>
                                      
                                      <?php } } ?>

                                    <div class="col-md-12 divi_list diriver_deatils_heading">
                                      <div class="form-group">
                                        <h3>Vehicle Details</h3>
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="owner_name" class="control-label">Owner Name</label>
                                        <input type="text" class="form-control"  name="owner_name" id="owner_name" value="{{isset($driver->owner_name) ? $driver->owner_name : 'N/A'}}"  readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="transmission" class="control-label">Transmission</label>
                                        <input type="text" class="form-control"  name="transmission" id="transmission" readonly value="{{isset($driver->transmission) ? $driver->transmission : 'N/A'}}" />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="transmission" class="control-label">Car Make</label>
                                        <input type="text" class="form-control"  name="transmission" id="transmission" readonly value="{{isset($driver->CarMake->name)?($driver->CarMake->name): 'N/A' }}" />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="transmission" class="control-label">Car Model</label>
                                        <input type="text" class="form-control"  name="transmission" id="transmission" readonly value="{{isset($driver->CarModel->name)?($driver->CarModel->name): 'N/A'}}" />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                      <label for="registration_number" class="control-label">Registration Number</label>
                                      <input type="text" class="form-control"  name="registration_number" id="registration_number" value="{{isset($driver->registration_number) ? $driver->registration_number : 'N/A'}} " readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                      <label for="license_number" class="control-label">License Number</label>
                                      <input type="text" class="form-control"  name="license_number" id="license_number" value="{{isset($driver->license_number) ? $driver->license_number : 'N/A'}}" readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                      <label for="vin_number" class="control-label">VIN Number</label>
                                      <input type="text" class="form-control"  name="vin_number" id="vin_number" value="{{isset($driver->vin_number) ? $driver->vin_number : 'N/A'}} " readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                      <label for="exterior_color" class="control-label">Exterior color</label>
                                      <input type="text" class="form-control"  name="exterior_color" id="exterior_color" value="{{isset($driver->exterior_color) ? $driver->exterior_color : 'N/A'}} " readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="interior_color" class="control-label">Interior color</label>
                                        <input type="text" class="form-control"  name="interior_color" id="interior_color" value="{{isset($driver->interior_color) ? $driver->interior_color : 'N/A'}} " readonly />

                                        </div>                                     
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                      <label for="start_date_registration" class="control-label">Start date registration</label>
                                        <input type="text" class="form-control"  name="start_date_registration" id="start_date_registration" value="{{isset($driver->start_date_registration) ? date('d-M-Y',strtotime($driver->start_date_registration)) : 'N/A'}} " readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                      <label for="end_date_road" class="control-label">End date road worthy</label>
                                        <input type="text" class="form-control"  name="end_date_road_worthy" id="end_date_road" value="{{isset($driver->end_date_road_worthy) ? date('d-M-Y',strtotime($driver->end_date_road_worthy)) : 'N/A'}} " readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="vehicle_license_expiry" class="control-label">Vehicle license expiry</label>
                                        <input type="text" class="form-control"  name="vehicle_license_expiry" id="vehicle_license_expiry" value="{{isset($driver->vehicle_license_expiry) ? date('d-M-Y',strtotime($driver->vehicle_license_expiry)) : 'N/A'}} "  readonly />
                                      </div>
                                    </div>

                                    
                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                      <label for="vehicle_description" class="control-label">Vehicle Description</label>
                                        @if(isset($driver->vehicle_description) && ($driver->vehicle_description != 0))
                                          <input type="text" class="form-control"  name="vehicle_description" id="vehicle_description" readonly value="{{isset($driver->DriverVehicleCarType->nick_name) ? $driver->DriverVehicleCarType->nick_name : 'N/A'}}" />
                                        @else
                                        <input type="text" class="form-control"  name="vehicle_description" id="vehicle_description" readonly value="N/A" />
                                         @endif
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="seating_capacity" class="control-label">Seating Capacity</label>
                                        <input type="text" class="form-control"  name="seating_capacity" id="seating_capacity" readonly value="{{isset($driver->seating_capacity) ? $driver->seating_capacity : 'N/A'}}" />
                                      </div>
                                    </div>                          

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="km_reading" class="control-label">KM Reading</label>
                                        <input type="text" class="form-control"  name="km_reading" id="km_reading" readonly value="{{isset($driver->km_reading) ? $driver->km_reading : 'N/A'}}" />
                                        <label for="interior_picture" class="control-label">KM Reading Photo</label>
                                        
                                        <?php if(isset($driver->document_picture) &&($driver->document_picture->km_reading_picture != "" && $driver->document_picture->km_reading_picture != null)){
                                          $docName1 = explode(".",$driver->document_picture->km_reading_picture);
                                          $docExtension1 = $docName1[1];
                                          if($docExtension1 == "pdf"){ ?>                                    
                                            <div class="driving_details_image">  
                                              <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->document_picture->km_reading_picture;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                            </div>
                                          <?php }  else{ ?>
                                          <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->document_picture->km_reading_picture;?>">
                                            <div class="driving_details_image"> 
                                              <img src="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->document_picture->km_reading_picture;?>">
                                            </div>
                                          </a>
                                          <?php } } else{ ?>
                                            <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                          </div>
                                          <?php } ?>
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="id_num" class="control-label">ID Number</label>
                                        <input type="text" class="form-control"  name="id_number" id="id_number" value="{{isset($driver->id_number) ? $driver->id_number : 'N/A'}}"  readonly />
                                        <label for="id_number_picture" class="control-label">Upload eNatis/Vehicle Registration Logbook Photo</label>
                                          
                                        <?php if(isset($driver->document_picture) && ($driver->document_picture != "" && $driver->document_picture != null)){
                                          $docName = explode(".",$driver->document_picture->id_number_picture);
                                          $docExtension = $docName[1];
                                          if($docExtension == "pdf"){ ?>                                    
                                            <div class="driving_details_image">  
                                              <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->document_picture->id_number_picture;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                            </div>
                                          <?php } else{ ?>
                                          <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->document_picture->id_number_picture;?>">
                                          <div class="driving_details_image">  
                                          <img src="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->document_picture->id_number_picture;?>" >
                                          </div>  
                                        </a>                                          
                                        <?php } } else{ ?>
                                          <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                          </div>
                                          <?php } ?>
                                        
                                      </div>
                                    </div>
                                    

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="interior_trim" class="control-label">Interior Trim</label>
                                        <input type="text" class="form-control"  name="interior_trim" id="interior_trim" readonly value="{{isset($driver->interior_trim) ? $driver->interior_trim : 'N/A'}}" />

                                      </div>
                                    </div> 
                                    

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="year" class="control-label">Year</label>
                                        <input type="text" class="form-control"  name="year" id="year" readonly value="{{isset($driver->year)?($driver->year): ''}}" />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="driver_id_number" class="control-label">Driver ID Number</label>
                                        <input type="text" class="form-control"  name="driver_id_number" id="driver_id_number" readonly value="{{isset($driver->driver_id_number) ? $driver->driver_id_number : 'N/A'}}" />

                                      </div>
                                    </div> 
                                    

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="driver_license_number" class="control-label">Driver License Number</label>
                                        <input type="text" class="form-control"  name="driver_license_number" id="driver_license_number" readonly value="{{isset($driver->driver_license_number)?($driver->driver_license_number): 'N/A'}}" />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="provinence" class="control-label">provinence</label>
                                        <input type="text" class="form-control"  name="provinence" id="provinence" readonly value="{{isset($driver->provinence)?($driver->provinence): 'N/A'}}" />
                                      </div>
                                    </div>
                                    

                                   <!-- // driver document  start -->


                                    <div class="col-md-12 divi_list diriver_deatils_heading">
                                      <div class="form-group">
                                        <h3>Important Documents</h3>
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="professional_driving_permit_name" class="control-label">Professional driving permit Photo</label>
                                        
                                          <?php if(isset($driver->driver_document) && ($driver->driver_document->professional_driving_permit_name != "") && ($driver->driver_document->professional_driving_permit_name != null)){
                                            $name1 = explode(".",$driver->driver_document->professional_driving_permit_name);
                                            $extension1 = $name1[1];
                                            if($extension1 == "pdf"){ ?>
                                            <div class="driving_details_image">
                                            <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->professional_driving_permit_name;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>                                        
                                            </div>
                                            <?php } else{?>
                                              <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->professional_driving_permit_name;?>"> 
                                                <div class="driving_details_image">
                                                  <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->professional_driving_permit_name;?>">
                                                </div>
                                              </a>
                                            <?php } } else {?>
                                            <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                          </div>
                                          <?php } ?>
                                            
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="driver_photo" class="control-label">Driver Photo</label>                                         
                                        <?php if(isset($driver->driver_document) && ($driver->driver_document->driver_photo != "" && $driver->driver_document->driver_photo != null)){
                                          $name2 = explode(".",$driver->driver_document->driver_photo);
                                          $extension2 = $name2[1];
                                          if($extension2 == "pdf"){ ?>  
                                          <div class="driving_details_image">                                          
                                            <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->driver_photo;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                          </div>
                                          <?php } else{ ?>
                                            <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->driver_photo;?>"> 
                                              <div class="driving_details_image"> 
                                                <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->driver_photo;?>">
                                              </div>
                                            </a>
                                            <?php } } else{?>
                                              <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                          </div>
                                          <?php } ?>
                                        </div>                                      
                                    </div>

                                   

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="safety_screening_result" class="control-label">Safety screening result Photo</label>
                                        
                                        <?php if(isset($driver->driver_document) && ($driver->driver_document->safety_screening_result != "" && $driver->driver_document->safety_screening_result != null )){
                                          $name4 = explode(".",$driver->driver_document->safety_screening_result);
                                          $extension4 = $name4[1];
                                          if($extension4 == "pdf"){
                                          ?> 
                                          <div class="driving_details_image">  
                                          <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->safety_screening_result;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                          </div>
                                          <?php } else{ ?>
                                            <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->safety_screening_result;?>"> 
                                              <div class="driving_details_image">  
                                              <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->safety_screening_result;?>">
                                              </div>
                                            </a>
                                            <?php } } else{?>
                                            <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                            </div>
                                          <?php } ?>
                                        
                                      </div>
                                    </div>

                                    

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="vehicle_card_double_disk" class="control-label">Operators card-double disk photo</label>
                                        
                                        <?php if(isset($driver->driver_document) && ($driver->driver_document->vehicle_card_double_disk != "" && $driver->driver_document->vehicle_card_double_disk != null )){
                                          $name6 = explode(".",$driver->driver_document->vehicle_card_double_disk);
                                          $extension6 = $name6[1];
                                          if($extension6 == "pdf"){
                                          ?>  
                                          <div class="driving_details_image"> 
                                          <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->vehicle_card_double_disk;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                          </div>
                                          <?php } else{ ?>
                                            <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->vehicle_card_double_disk;?>"> 
                                              <div class="driving_details_image"> 
                                                <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->vehicle_card_double_disk;?>">
                                              </div>
                                          </a>
                                            <?php } } else{ ?>
                                              <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                          </div>
                                          <?php } ?>
                                        </div>                                      
                                    </div>


                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="driving_evaluation_report" class="control-label">Driving Evaluation Report Photo (Optional)</label>                                        
                                        <?php if(isset($driver->driver_document) && ($driver->driver_document->driving_evaluation_report != "" && $driver->driver_document->driving_evaluation_report != null )){
                                          $name3 = explode(".",$driver->driver_document->driving_evaluation_report);
                                          $extension3 = $name3[1];
                                          if($extension3 == "pdf"){ ?>                                    
                                            <div class="driving_details_image">  
                                              <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->driving_evaluation_report;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                            </div>
                                            <?php } else{ ?>
                                              <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->driving_evaluation_report;?>"> 
                                                <div class="driving_details_image">  
                                                  <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->driving_evaluation_report;?>">
                                                </div>
                                              </a>
                                            <?php } } else{?>
                                              <div class="photo_not_uploaded" >                                            
                                                <p>Photo Not Uploaded</p>                                           
                                              </div>
                                          <?php } ?>
                                        </div>
                                      </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="vehicle_insurance_policy" class="control-label">Vehicle insurance policy Photo (Optional)</label>
                                        
                                        <?php if(isset($driver->driver_document) && ($driver->driver_document->vehicle_insurance_policy != "" && $driver->driver_document->vehicle_insurance_policy != null)){
                                          $name5 = explode(".",$driver->driver_document->vehicle_insurance_policy);
                                          $extension5 = $name5[1];
                                          if($extension5 == "pdf"){
                                          ?> 
                                          <div class="driving_details_image">  
                                          <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->vehicle_insurance_policy;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                          </div>
                                          <?php } else{ ?>
                                            <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->vehicle_insurance_policy;?>"> 
                                              <div class="driving_details_image">  
                                                <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $driver->driver_document->vehicle_insurance_policy;?>">
                                              </div>
                                            </a>
                                            <?php } } else{?>
                                              <div class="photo_not_uploaded" >                                            
                                                <p>Photo Not Uploaded</p>                                           
                                              </div>
                                          <?php } ?>
                                        </div>                                     
                                    </div>

                                    <div class="col-md-12 divi_list diriver_deatils_heading">
                                      <div class="form-group">
                                        <h3>Vehicle inspection  Documents</h3>
                                      </div>
                                    </div>

                                    <!-- <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="vehicle_inspection_id" class="control-label">Vehicle Inspection Id</label>                                       
                                        <input type="text" class="form-control"  name="vehicle_inspection_id" id="vehicle_inspection_id" value="{{isset($driver->driver_document->vehicle_inspection_id) ? $driver->driver_document->vehicle_inspection_id : 'N/A'}} " readonly />
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="locate_inspection_center_name" class="control-label">Inspection location center name</label>                                        
                                        <input type="text" class="form-control"  name="locate_inspection_center_name" id="locate_inspection_center_name" value="{{isset($driver->driver_document->locate_inspection_center_name) ? $driver->driver_document->locate_inspection_center_name : 'N/A'}} " readonly />
                                      </div>
                                    </div>


                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="vehicle_insurance_policy" class="control-label">Inspection Report Document</label>
                                        <?php //if(isset($driver->driver_document) && ($driver->driver_document->vehicle_document != "" && $driver->driver_document->vehicle_document != null)){
                                          //$name5 = explode(".",$driver->driver_document->vehicle_document);
                                          //$extension5 = $name5[1];
                                          //if($extension5 == "pdf"){
                                          ?> 
                                          <div class="driving_details_image">  
                                          <a href="<?php //echo config('constant.S3_PATH')?>driverDocuments/<?php //echo $driver->driver_document->vehicle_document;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                          </div>
                                          <?php //} else{ ?>
                                            <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php //echo config('constant.S3_PATH')?>driverDocuments/<?php //echo $driver->driver_document->vehicle_document;?>"> 
                                              <div class="driving_details_image">  
                                                <img src="<?php //echo config('constant.S3_PATH')?>driverDocuments/<?php //echo $driver->driver_document->vehicle_document;?>">
                                              </div>
                                            </a>
                                            <?php //} } else{?>
                                              <div class="photo_not_uploaded" >                                            
                                                <p>Photo Not Uploaded</p>                                           
                                              </div>
                                          <?php //} ?>
                                      </div>                                     
                                    </div> -->

                                    
                                                                       
                                       
                                        <?php  if(isset($driver->driver_vehicle_inspection_document) && (count($driver->driver_vehicle_inspection_document)>0)){
                                          foreach($driver->driver_vehicle_inspection_document as $keys => $values){
                                             ?>  
                                            <div class="col-md-6 divi_list diriver_deatils_bottom">  
                                            <div class="form-group">
                                              <?php if($values->vehicle_document_type == "front"){?>
                                            <label for="vehicle_card_double_disk" class="control-label" >Front Side Image </label>
                                                <?php if(($values->vehicle_inspection_document != "") && ($values->vehicle_inspection_document != null )){
                                                  $name7 = explode(".",$values->vehicle_inspection_document);
                                                  $extension7 = $name7[1];
                                                  if($extension7 == "pdf"){
                                                  ?>    
                                                  <div class="driving_details_image"> 
                                                  <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>                                            
                                                  </div>
                                                  <?php } else{ ?>
                                                    <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>"> 
                                                      <div class="driving_details_image"> 
                                                        <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>">
                                                      </div>
                                                    </a>
                                                    <?php } 
                                                     }  else{ ?>
                                                      <div class="photo_not_uploaded" >                                            
                                                        <p>Photo Not Uploaded</p>                                           
                                                      </div>
                                                    <?php }
                                                  } if($values->vehicle_document_type == "back"){ ?>
                                                    <label for="vehicle_card_double_disk" class="control-label" >Back Side Image </label>
                                                    <?php if(($values->vehicle_inspection_document != "") && ($values->vehicle_inspection_document != null )){
                                                    $name7 = explode(".",$values->vehicle_inspection_document);
                                                    $extension7 = $name7[1];
                                                    if($extension7 == "pdf"){
                                                    ?>    
                                                    <div class="driving_details_image"> 
                                                    <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>                                            
                                                    </div>
                                                    <?php } else{ ?>
                                                      <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>"> 
                                                      <div class="driving_details_image"> 
                                                        <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>">
                                                      </div>
                                                      </a>
                                                      <?php }  }  else { ?>
                                                      <div class="photo_not_uploaded" >                                            
                                                        <p>Photo Not Uploaded</p>                                           
                                                      </div>
                                                    <?php } ?>
                                                <?php } if($values->vehicle_document_type == "left"){ ?>
                                                <label for="vehicle_card_double_disk" class="control-label" >Left Side Image</label>
                                                <?php if(($values->vehicle_inspection_document != "") && ($values->vehicle_inspection_document != null )){
                                                  $name7 = explode(".",$values->vehicle_inspection_document);
                                                  $extension7 = $name7[1];
                                                  if($extension7 == "pdf"){
                                                  ?>    
                                                  <div class="driving_details_image"> 
                                                    <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>                                            
                                                  </div>
                                                  <?php } else{ ?>
                                                    <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>"> 
                                                      <div class="driving_details_image"> 
                                                        <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>">
                                                      </div>
                                                    </a>
                                                    <?php } 
                                                    }  else{ ?>
                                                    <div class="photo_not_uploaded" >                                            
                                                      <p>Photo Not Uploaded</p>                                           
                                                    </div>
                                                  <?php }
                                                  } if($values->vehicle_document_type == "right"){ ?>
                                                  <label for="vehicle_card_double_disk" class="control-label" >Right Side Image </label>
                                                  <?php if(($values->vehicle_inspection_document != "") && ($values->vehicle_inspection_document != null )){
                                                    $name7 = explode(".",$values->vehicle_inspection_document);
                                                    $extension7 = $name7[1];
                                                    if($extension7 == "pdf"){
                                                    ?>    
                                                    <div class="driving_details_image"> 
                                                      <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>                                            
                                                    </div>
                                                  <?php } else{ ?>
                                                    <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>"> 
                                                      <div class="driving_details_image"> 
                                                        <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>">
                                                      </div>
                                                    </a>
                                                    <?php } 
                                                    }  else{ ?>
                                                  <div class="photo_not_uploaded" >                                            
                                                    <p>Photo Not Uploaded</p>                                           
                                                  </div>
                                                  <?php } } ?>


                                                  <?php if($values->vehicle_document_type == "interiorFront"){ ?>
                                                    <label for="vehicle_card_double_disk" class="control-label" >Interior Front Image </label>
                                                    <?php if(($values->vehicle_inspection_document != "") && ($values->vehicle_inspection_document != null )){
                                                    $name7 = explode(".",$values->vehicle_inspection_document);
                                                    $extension7 = $name7[1];
                                                    if($extension7 == "pdf"){
                                                    ?>    
                                                  <div class="driving_details_image"> 
                                                    <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>                                            
                                                  </div>
                                                  <?php } else{ ?>
                                                    <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>"> 
                                                      <div class="driving_details_image"> 
                                                        <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>">
                                                      </div>
                                                    </a>
                                                    <?php } }  else{ ?>
                                                  <div class="photo_not_uploaded" >                                            
                                                    <p>Photo Not Uploaded</p>                                           
                                                  </div>
                                                <?php }  } ?>
                                                <?php if($values->vehicle_document_type == "interiorRear"){ ?>
                                                    <label for="vehicle_card_double_disk" class="control-label" >Interior Rear Image </label>
                                                    <?php if(($values->vehicle_inspection_document != "") && ($values->vehicle_inspection_document != null )){
                                                    $name7 = explode(".",$values->vehicle_inspection_document);
                                                    $extension7 = $name7[1];
                                                    if($extension7 == "pdf"){
                                                    ?>    
                                                  <div class="driving_details_image"> 
                                                    <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>                                            
                                                  </div>
                                                  <?php } else{ ?>
                                                    <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>"> 
                                                      <div class="driving_details_image"> 
                                                        <img src="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>">
                                                      </div>
                                                    </a>
                                                    <?php } }  else{ ?>
                                                  <div class="photo_not_uploaded" >                                            
                                                    <p>Photo Not Uploaded</p>                                           
                                                  </div>
                                                <?php }  } ?>

                                                      
                                            </div>
                                          </div>
                                          <?php } } else{ ?>
                                            <div class="col-md-6 divi_list diriver_deatils_bottom">
                                            <div class="form-group">
                                            <label for="vehicle_card_double_disk" class="control-label">Vehicle inspection photos</label> 
                                              <div class="photo_not_uploaded" >                                            
                                                  <p>Photo Not Uploaded</p>                                           
                                              </div>
                                          </div>
                                          </div>
                                        <?php } ?>                                      
                                      


                                    <!-- driver verification document start -->


                                    <div class="col-md-12 divi_list diriver_deatils_heading">
                                      <div class="form-group">
                                        <h3>Vehicle Inspection Results</h3>
                                      </div>
                                    </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="is_road_worth" class="control-label">The last roadworthiness date should be within one year</label>
                                        <input type="text" class="form-control"  name="is_road_worth" id="is_road_worth" value="
                                        <?php if(isset($driver->driver_verification_document->is_road_worth) && ($driver->driver_verification_document->is_road_worth == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_road_worth) && ($driver->driver_verification_document->is_road_worth == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?>
                                        " readonly />
                                       
                                          
                                        </div>
                                      </div>



                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="is_functional_defects" class="control-label">No functional falls/defects</label>
                                        <input type="text" class="form-control"  name="is_functional_defects" id="is_functional_defects" 
                                        value="
                                        <?php if(isset($driver->driver_verification_document->is_functional_defects) && ($driver->driver_verification_document->is_functional_defects == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_functional_defects) && ($driver->driver_verification_document->is_functional_defects == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?> " readonly />
                                       
                                      </div>
                                      </div>

                                      <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="is_hooter_present" class="control-label">Hooter</label>
                                        <input type="text" class="form-control"  name="is_hooter_present" id="is_hooter_present" value="
                                        <?php if(isset($driver->driver_verification_document->is_hooter_present) && ($driver->driver_verification_document->is_hooter_present == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_hooter_present) && ($driver->driver_verification_document->is_hooter_present == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?>  " readonly />
                                        
                                        </div>
                                      </div>
                                   

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">

                                        <label for="is_warning_light_present" class="control-label">No warning lights present</label>
                                        <input type="text" class="form-control"  name="is_warning_light_present" id="is_warning_light_present" value="
                                        <?php if(isset($driver->driver_verification_document->is_warning_light_present) && ($driver->driver_verification_document->is_warning_light_present == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_warning_light_present) && ($driver->driver_verification_document->is_warning_light_present == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?>  " readonly />
                                        
                                        
                                        </div>
                                      </div>
                                    

                                      

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">

                                        <label for="is_window_screen_wiper" class="control-label">Windscreen Wipers in Working Order</label>
                                        <input type="text" class="form-control"  name="is_window_screen_wiper" id="is_window_screen_wiper" value="
                                        <?php if(isset($driver->driver_verification_document->is_window_screen_wiper) && ($driver->driver_verification_document->is_window_screen_wiper == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_window_screen_wiper) && ($driver->driver_verification_document->is_window_screen_wiper == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?>  " readonly />
                                        
                                        
                                        </div>
                                      </div>
                                    


                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">

                                        <label for="is_head_light_present" class="control-label">Headlights in Working Order</label>
                                        <input type="text" class="form-control"  name="is_head_light_present" id="is_head_light_present" value="
                                        <?php if(isset($driver->driver_verification_document->is_head_light_present) && ($driver->driver_verification_document->is_head_light_present == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_head_light_present) && ($driver->driver_verification_document->is_head_light_present == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?>  " readonly />
                                        </div>
                                      </div>
                                    

                                   

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">

                                        <label for="is_indicator_light_present" class="control-label">Indicators/Hazards in Working Order</label>
                                        <input type="text" class="form-control"  name="is_indicator_light_present" id="is_indicator_light_present" value="
                                        <?php if(isset($driver->driver_verification_document->is_indicator_light_present) && ($driver->driver_verification_document->is_indicator_light_present == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_indicator_light_present) && ($driver->driver_verification_document->is_indicator_light_present == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?>  " readonly />
                                      </div>
                                      </div>
                                    
                
                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">

                                        <label for="is_brake_light_present" class="control-label">Brake Lights in Working Order</label>
                                        <input type="text" class="form-control"  name="is_brake_light_present" id="is_brake_light_present" value="
                                        <?php if(isset($driver->driver_verification_document->is_brake_light_present) && ($driver->driver_verification_document->is_brake_light_present == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_brake_light_present) && ($driver->driver_verification_document->is_brake_light_present == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?> " readonly /> 
                                      </div>
                                      </div>  
                                      
                                      <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="is_spare_jack_triangle_present" class="control-label">Spare wheel, Jack and Wheel Spanner at hand</label>
                                        <input type="text" class="form-control"  name="is_spare_jack_triangle_present" id="is_spare_jack_triangle_present" value="
                                        <?php if(isset($driver->driver_verification_document->is_spare_jack_triangle_present) && ($driver->driver_verification_document->is_spare_jack_triangle_present == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_spare_jack_triangle_present) && ($driver->driver_verification_document->is_spare_jack_triangle_present == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?>  " readonly />
                                      </div>
                                      </div>

                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">

                                        <label for="is_seat_belts_present" class="control-label">Seatbelts in Working Order</label>
                                        <input type="text" class="form-control"  name="is_seat_belts_present" id="is_seat_belts_present" value="
                                        <?php if(isset($driver->driver_verification_document->is_seat_belts_present) && ($driver->driver_verification_document->is_seat_belts_present == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_seat_belts_present) && ($driver->driver_verification_document->is_seat_belts_present == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?>  " readonly />
                                         </div>
                                    </div>

                                    <!-- <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        
                                        <label for="seat_belt_picture" class="control-label">Front Seatbelts Photo</label>
                                       
                                        <?php //if(isset($driver->verification_document_picture) && $driver->verification_document_picture->front_seat_belt_picture != "" && $driver->verification_document_picture->front_seat_belt_picture != null){
                                          //$name4 = explode(".",$driver->verification_document_picture->front_seat_belt_picture);
                                          //$extension4 = $name4[1];
                                          //if($extension4 == "pdf"){
                                          ?> 
                                          <div class="driving_details_image">  
                                          <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $driver->verification_document_picture->front_seat_belt_picture;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                          </div>
                                          <?php //} else{ ?>
                                          <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $driver->verification_document_picture->front_seat_belt_picture;?>">   
                                            <div class="driving_details_image"> 
                                            <img src="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $driver->verification_document_picture->front_seat_belt_picture;?>">
                                            </div>
                                          </a>
                                          <?php //} } else{ ?>
                                          <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                          </div>
                                          <?php //} ?> 
                                      </div>
                                    </div> -->


                                    
                                    
                                    <div class="col-md-12 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="is_wheels_present" class="control-label">All tyres are Roadworthy</label>
                                        <input type="text" class="form-control"  name="is_wheels_present" id="is_wheels_present" value="
                                        <?php if(isset($driver->driver_verification_document->is_wheels_present) && ($driver->driver_verification_document->is_wheels_present == 'Y')){
                                            echo "YES";
                                          }else if(isset($driver->driver_verification_document->is_wheels_present) && ($driver->driver_verification_document->is_wheels_present == 'N')){
                                            echo "NO";
                                          }else{
                                            echo "N/A";
                                          }
                                        ?> " readonly />
                                        </div>
                                      </div>


                                      <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="wheel_picture" class="control-label">Front Right Wheels Photo</label>
                                        
                                        <?php if(isset($driver->verification_document_picture) && $driver->verification_document_picture->front_right_wheel_picture != "" && $driver->verification_document_picture->front_right_wheel_picture != null){
                                          $name4 = explode(".",$driver->verification_document_picture->front_right_wheel_picture);
                                          $extension4 = $name4[1];
                                          if($extension4 == "pdf"){
                                          ?> 
                                          <div class="driving_details_image">  
                                          <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->front_right_wheel_picture;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                          </div>
                                          <?php } else{ ?>
                                          <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->front_right_wheel_picture;?>">   
                                          <div class="driving_details_image">
                                          <img src="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->front_right_wheel_picture;?>">
                                          </div>
                                          </a>
                                          <?php } } else{ ?>
                                            <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                          </div>
                                          <?php } ?>
                                        </div>
                                      </div>     
                                      
                                    <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="is_wheels_present" class="control-label">Front Left Wheels Photo</label>
                                        <?php if(isset($driver->verification_document_picture) && $driver->verification_document_picture->front_left_wheel_picture != "" && $driver->verification_document_picture->front_left_wheel_picture != null){
                                          $name4 = explode(".",$driver->verification_document_picture->front_left_wheel_picture);
                                          $extension4 = $name4[1];
                                          if($extension4 == "pdf"){
                                          ?> 
                                          <div class="driving_details_image">  
                                          <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->front_left_wheel_picture;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                          </div>
                                          <?php } else{ ?>
                                          <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->front_left_wheel_picture;?>">   
                                          <div class="driving_details_image">
                                          <img src="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->front_left_wheel_picture;?>">
                                          </div>
                                          </a>
                                          <?php } } else{ ?>
                                            <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                          </div>
                                          <?php } ?>
                                      </div>
                                    </div>

                                      <div class="col-md-6 divi_list diriver_deatils_bottom">
                                      <div class="form-group">
                                        <label for="wheel_picture" class="control-label">Back Left Wheels Photo</label>                                        
                                        <?php if(isset($driver->verification_document_picture) && $driver->verification_document_picture->back_left_wheel_picture != "" && $driver->verification_document_picture->back_left_wheel_picture != null){
                                          $name4 = explode(".",$driver->verification_document_picture->back_left_wheel_picture);
                                          $extension4 = $name4[1];
                                          if($extension4 == "pdf"){
                                          ?> 
                                          <div class="driving_details_image">  
                                          <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->back_left_wheel_picture;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                          </div>
                                          <?php } else{ ?>
                                          <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->back_left_wheel_picture;?>">   
                                          <div class="driving_details_image">
                                          <img src="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->back_left_wheel_picture;?>">
                                          </div>
                                          </a>
                                          <?php } } else{ ?>
                                            <div class="photo_not_uploaded" >                                            
                                              <p>Photo Not Uploaded</p>                                           
                                          </div>
                                          <?php } ?>
                                        </div>
                                      </div>

                                      <div class="col-md-6 divi_list diriver_deatils_bottom">
                                        <div class="form-group">
                                          <label for="wheel_picture" class="control-label">Back Right Wheels Photo</label>
                                          <?php if(isset($driver->verification_document_picture) && $driver->verification_document_picture->back_right_wheel_picture != "" && $driver->verification_document_picture->back_right_wheel_picture != null){
                                            $name4 = explode(".",$driver->verification_document_picture->back_right_wheel_picture);
                                            $extension4 = $name4[1];
                                            if($extension4 == "pdf"){
                                            ?> 
                                            <div class="driving_details_image">  
                                            <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->back_right_wheel_picture;?>" target="_blank"><image src="{{ PUBLIC_PATH.'assets/images/pdf.png' }}" ></a>
                                            </div>
                                            <?php } else{ ?>
                                            <a href="#myModal" data-toggle="modal" data-gallery="example-gallery"  data-img-url="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->back_right_wheel_picture;?>">   
                                            <div class="driving_details_image">
                                            <img src="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $driver->verification_document_picture->back_right_wheel_picture;?>">
                                            </div>
                                            </a>
                                            <?php } } else{ ?>
                                              <div class="photo_not_uploaded" >                                            
                                                <p>Photo Not Uploaded</p>                                           
                                            </div>
                                            <?php } ?>
                                            </div>
                                      </div>
                                
                                    <!-- driver verification document end -->                                    
                            </div><!-- /.box-body -->
                         </div><!-- /.box -->
                     </div>
                 </div>


                <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog photo_popup">
                    <div class="modal-content">
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                        <div class="modal-body text-center">
                            <img class="" src="#"/>
                        </div>
                    </div>
                    </div>
                </div>

    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->
@endsection
@section('customScript')

  <script>

function validateDocStatus(){
    let approval_status = $("#approval_status").val();
    let reject_document_reason = $("#reject_document_reason").val();
    if(approval_status.length == ""){
        $("#approval_status").focus();
    }else if(approval_status == 'A'){
        $("#doc_status").submit();
    }else if(approval_status == 'R'){
      if (!$.trim($("#reject_document_reason").val())) {
        $("#reject_document_reason").focus();
      }else{
        $("#doc_status").submit();
      }
    }
}

function get_document_status(status){
  console.log(status);
  if(status == 'R' || status == 'P'){
    $("#reason_div").show();
  }else{
    $("#reason_div").hide();
  }
}

$('div a').click(function(e) {
  $('#myModal img').attr('src', $(this).attr('data-img-url')); 
});

  </script>
@endsection
