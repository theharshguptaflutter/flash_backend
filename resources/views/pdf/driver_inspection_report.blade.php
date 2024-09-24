<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Invoces</title>

    <style type="text/css">
        body,table{
            font-family:Arial,Helvetica,sans-serif;
            color:#666;
            margin: 0;
            font-size: 13px;
        }
        img {
            max-width: 100%;
        }
        @media screen and (max-width: 625px){
            .responsive {
                width: 100% !important;
                max-width:100% !important;
                min-width:100% !important;
                display: inline-block !important;
            }
            table tr td .social_media table {
                width: auto !important;
                max-width: auto !important;
                min-width: auto !important;
                margin: 0 8px !important;
                padding: 0 !important;
            }
            table {
            width:100% !important;
            max-width:100% !important;
            min-width:100% !important;
            display:block !important;
            padding-left:0 !important;
            padding-right:0 !important;
            margin-left:0 !important;
            margin-right:0 !important;
            text-align:center !important;
        }

        } @media screen and (max-width: 625px){
            td {
            width:100% !important;
            max-width:100% !important;
            text-align:center !important;
            min-width:100%;
            display:block !important;
            padding-left:0 !important;
            padding-right:0 !important;
            margin-left:0 !important;
            margin-right:0 !important;
            float:left;
        }

        } @media screen and (max-width: 625px){
            tr {
            width:100% !important;
            max-width:100% !important;
            text-align:center !important;
            min-width:100%;
            display:block !important;
        }

        } @media screen and (max-width: 625px){
            tbody {
            width:100% !important;
            max-width:100% !important;
            text-align:center !important;
            min-width:100%;
            display:block;
            padding-left:0 !important;
            padding-right:0 !important;
            margin-left:0 !important;
            margin-right:0 !important;
        }

        } @media screen and (max-width: 625px){
            img {
            width:auto !important;
            max-width:100% !important;
            height:auto !important;
            display:inline-block !important;
        }

        } @media screen and (max-width: 625px){
            table td {
            width:100% !important;
            max-width:100% !important;
            text-align:center !important;
            min-width:100%;
            display:block !important;
            padding-left:0 !important;
            padding-right:0 !important;
            margin-left:0 !important;
            margin-right:0 !important;
            float:left;
            height:auto;
        }

        } @media screen and (max-width: 625px){
            table td table td {
            width:100% !important;
            max-width:100% !important;
            text-align:center !important;
            min-width:100%;
            display:block !important;
            padding-left: !important;
            padding-right:0 !important;
            margin-left:0 !important;
            margin-right:0 !important;
            float:left;
            height:auto;
        }

        } @media screen and (max-width: 625px){
            table td table {
            width:100% !important;
            max-width:100% !important;
            text-align:center !important;
            min-width:100%;
            display:block !important;
            padding-left:30px !important;
            padding-right:30px !important;
            margin-left:0 !important;
            margin-right:0 !important;
            float:left;
        }

        } 
    </style>
    
</head>
<body>
@if(isset($list) && !empty($list))
<div class="billing_table" style="max-width: 900px; margin: 0 auto; padding: 20px 20px; box-sizing: border-box;">
    <table class="table" cellpadding="0" cellspacing="0" border="0" style="width: 100%; border-collapse: collapse; border-style: solid;">
        <tr>
            <td align="left" valign="top">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding:0 0 20px 0">
                    <tr>
                        <td align="center" valign="top" style="100%; font-weight: 600; font-size: 24px; color: #000;">Vehicle Inspection</td>
                    </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td align="center" valign="top" style="100%;"><a href="javascript:void(0);"><img src="<?php echo config('constant.S3_PATH')?>logo/profile_1667886822644.jpg" width="200px"></a></td>
                    </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 20px 0">
                    <tr>
                        <td align="left" valign="top" style="width:20%;font-size:12px;">Inspection date: {{isset($list->inspection_date)?date('jS F, Y', strtotime($list->inspection_date)): ""}}</td>
                        <td align="left" valign="top" style="width:20%;font-size:12px;">Inspection ID: {{isset($list->unique_inspection_id)?($list->unique_inspection_id): ""}}</td>
                        <td align="left" valign="top" style="width:35%;font-size:12px;">Inspection Center: <span style="text-transform: uppercase;">{{isset($list->driver_document)?($list->driver_document->locate_inspection_center_name): ""}}</span></td>
                        <td align="left" valign="top" style="width:25%;font-size:12px;">Inspector: <span style="text-transform: uppercase;">Super Admin</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" width="100%">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 20px; border: solid 1px #ccc;">
                    <thead>
                        <th colspan="2" style="background: #0091ec; padding: 5px; border: none; color: #fff;">Driver Details</th>
                    </thead>
                    <tbody>    
                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Full Name</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->user->full_name)?($list->user->full_name): ""}}</span></td>
                                    </tr>
                                </table>
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Contact Number</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->user->country_code)?($list->user->country_code): ""}} {{isset($list->user->mobile)?($list->user->mobile): ""}}</span></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 50%">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Email Address</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span>{{isset($list->user->email)?($list->user->email): ""}}</span></td>
                                    </tr>
                                </table>
                            </td>
                            <td align="left" valign="top" style="width: 50%"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" width="100%">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 20px; border: solid 1px #ccc;">
                    <thead>
                        <th colspan="2" style="background: #0091ec; padding: 5px; border: none; color: #fff;">Vehicle Details</th>
                    </thead>
                    <tbody>    
                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Register Owner Name</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->owner_name)?($list->owner_name): ""}}</span></td>
                                    </tr>
                                </table>
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">ID Number</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->id_number)?($list->id_number): ""}}</span></td>
                                    </tr>
                                </table>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box;">
                                        <?php if(isset($list->document_picture->id_number_picture) && ($list->document_picture->id_number_picture != "")) {?>
                                            <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $list->document_picture->id_number_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">Upload eNatis/Vehicle Registration Logbook Photo</a>
                                        <?php } else{?>
                                            <a href="#" style="font-weight: 700; text-decoration: none;">N/A</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Make</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->CarMake->name)?($list->CarMake->name): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Model</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->CarModel->name)?($list->CarModel->name): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Driver ID Number</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->driver_id_number)?($list->driver_id_number): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Driver License Number</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->driver_license_number)?($list->driver_license_number): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Vehicle Description</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">
                                        @if(isset($list->vehicle_description) && ($list->vehicle_description != 0))
                                        {{isset($list->DriverVehicleCarType->nick_name) ? $list->DriverVehicleCarType->nick_name : 'N/A'}}
                                        @else
                                        N/A
                                        @endif                                    
                                    </span></td>
                                    </tr>
                                </table>
                               
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Year</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->year)?($list->year): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Registration number</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->registration_number)?($list->registration_number): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">License Number</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->license_number)?($list->license_number): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                            
                        </tr>
                        <tr>
                        <td align="left" valign="top" style="width: 50%;border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Vehicle license expiration</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->vehicle_license_expiry)?date('jS F, Y', strtotime($list->vehicle_license_expiry)): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">VIN Number</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->vin_number)?($list->vin_number): ""}}</span></td>
                                    </tr>
                                </table>
                               
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Exterior color</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->exterior_color)?($list->exterior_color): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Interior Color</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->interior_color)?($list->interior_color): ""}}</span></td>
                                    </tr>
                                </table>
                               
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Interior trim</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->interior_trim)?($list->interior_trim): ""}}</span></td>
                                    </tr>
                                </table>
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Transmission</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->transmission)?($list->transmission): ""}}</span></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        

                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Seating capacity</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->seating_capacity)?$list->seating_capacity: ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">KM reading</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->km_reading)?($list->km_reading): ""}}</span></td>
                                    </tr>
                                </table>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box;">
                                        <?php if(isset($list->document_picture->km_reading_picture) && ($list->document_picture->km_reading_picture != "")) {?>
                                                <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $list->document_picture->km_reading_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">KM Reading Photo</a>
                                            <?php } else{?>
                                                <a href="#" style="font-weight: 700; text-decoration: none;">N/A</a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                </table>
                            </td>                            
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Date of first registration</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->start_date_registration)?date('jS F, Y', strtotime($list->start_date_registration)): ""}}</span></td>
                                    </tr>
                                </table>
                               
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Last date of roadworthy</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->end_date_road_worthy)?date('jS F, Y', strtotime($list->end_date_road_worthy)): ""}}</span></td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Provinence</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;">{{isset($list->provinence)?($list->provinence): ""}}</span></td>
                                    </tr>
                                </table>
                               
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;"></td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span style="text-transform: uppercase;"></span></td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td align="left" valign="top" width="100%">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 20px; border: solid 1px #ccc;">
                    <thead>
                        <th colspan="2" style="background: #0091ec; padding: 5px; border: none; color: #fff;">Important Documents</th>
                    </thead>
                    <tbody>    
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Professional Driving Permit Photo</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php if($list->driver_document->professional_driving_permit_name != ""){ 
                                            $name1 = explode(".",$list->driver_document->professional_driving_permit_name);
                                            $extension1 = $name1[1];
                                            if($extension1 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->professional_driving_permit_name;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->professional_driving_permit_name;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } } else {?>
                                            <a style="font-weight: 700; text-decoration: none;" href="#">N/A</a>
                                            <?php }?>
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Driver Photo</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php if($list->driver_document->driver_photo != ""){ $name1 = explode(".",$list->driver_document->driver_photo);
                                            $extension1 = $name1[1];
                                            if($extension1 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->driver_photo;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->driver_photo;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } }else{ ?>
                                            <a href="#" style="font-weight: 700; text-decoration: none;">N/A</a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Driving Evaluation Report Photo (Optional)</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php  if($list->driver_document->driving_evaluation_report != "" && ($list->driver_document->driving_evaluation_report != null)){
                                            $name1 = explode(".",$list->driver_document->driving_evaluation_report);
                                            $extension1 = $name1[1];
                                            if($extension1 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->driving_evaluation_report;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->driving_evaluation_report;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php }}else{ ?>
                                            <a href="#" style="font-weight: 700; text-decoration: none;">N/A</a>
                                            <?php } ?>
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Safety Screening Result Photo</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php if($list->driver_document->safety_screening_result != ""){ $name1 = explode(".",$list->driver_document->safety_screening_result);
                                            $extension1 = $name1[1];
                                            if($extension1 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->safety_screening_result;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->safety_screening_result;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } }else{?>
                                            <a href="#" style="font-weight: 700; text-decoration: none;">N/A</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Vehicle Insurance Policy Photo (Optional)</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php if($list->driver_document->vehicle_insurance_policy != "" && ($list->driver_document->vehicle_insurance_policy != null)){
                                             $name1 = explode(".",$list->driver_document->vehicle_insurance_policy);
                                            $extension1 = $name1[1];
                                            if($extension1 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->vehicle_insurance_policy;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->vehicle_insurance_policy;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php }}else{ ?>
                                            <a href="#" style="font-weight: 700; text-decoration: none;">N/A</a>
                                            <?php } ?>
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Operators Card-Double Disk Photo</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php if($list->driver_document->vehicle_card_double_disk != "" ){ $name1 = explode(".",$list->driver_document->vehicle_card_double_disk);
                                            $extension1 = $name1[1];
                                            if($extension1 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->vehicle_card_double_disk;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $list->driver_document->vehicle_card_double_disk;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } }else{?>
                                            <a href="#" style="font-weight: 700; text-decoration: none;">N/A</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" width="100%">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 20px; border: solid 1px #ccc;">
                    <thead>
                        <th colspan="2" style="background: #0091ec; padding: 5px; border: none; color: #fff;">Vehicle Inspection Documents</th>
                    </thead>
                    <tbody>    
                        <?php if(isset($list->driver_vehicle_inspection_document) && (count($list->driver_vehicle_inspection_document)>0)){
                                          foreach($list->driver_vehicle_inspection_document as $keys => $values){
                                             ?>  

                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <?php if($values->vehicle_document_type == "front"){
                                        $name7 = explode(".",$values->vehicle_inspection_document);
                                        $extension7 = $name7[1];                                       
                                    ?>
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Front Side Image</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box;border-right:solid 1px #ccc;">
                                        <?php if($extension7 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } ?>
                                            
                                        </td>
                                    </tr>
                                    <?php } ?>


                                    <?php if($values->vehicle_document_type == "back"){
                                        $name7 = explode(".",$values->vehicle_inspection_document);
                                        $extension7 = $name7[1];                                       
                                    ?>
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Back Side Image</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box;border-right:solid 1px #ccc;">
                                        <?php if($extension7 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } ?>
                                            
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if($values->vehicle_document_type == "left"){
                                        $name7 = explode(".",$values->vehicle_inspection_document);
                                        $extension7 = $name7[1];                                       
                                    ?>
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Left Side Image</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box;border-right:solid 1px #ccc;">
                                        <?php if($extension7 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } ?>
                                            
                                        </td>
                                    </tr>
                                    <?php } ?>


                                    <?php if($values->vehicle_document_type == "right"){
                                        $name7 = explode(".",$values->vehicle_inspection_document);
                                        $extension7 = $name7[1];                                       
                                    ?>
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Right Side Image</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box;border-right:solid 1px #ccc;">
                                        <?php if($extension7 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } ?>
                                            
                                        </td>
                                    </tr>
                                    <?php } ?>


                                   <?php if($values->vehicle_document_type == "interiorFront"){
                                        $name7 = explode(".",$values->vehicle_inspection_document);
                                        $extension7 = $name7[1];                                       
                                    ?>
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Interior Front Image</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box;border-right:solid 1px #ccc;">
                                        <?php if($extension7 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php  } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } ?>
                                            
                                        </td>
                                    </tr>
                                    <?php } ?> 

                                    <?php if($values->vehicle_document_type == "interiorRear"){
                                        $name7 = explode(".",$values->vehicle_inspection_document);
                                        $extension7 = $name7[1];                                       
                                    ?>
                                    <tr>
                                        <td align="left" valign="center" style="width: 50%; padding: 5px; box-sizing: border-box; font-size: 14px; color: #000">Interior Rear Image</td>
                                        <td align="right" valign="middle" style="width: 50%; padding: 5px 5px 5px 5px; box-sizing: border-box;border-right:solid 1px #ccc;">
                                        <?php if($extension7 == "pdf"){ ?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" target="_blank" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Pdf</a>
                                            <?php  } else{?>
                                                <a href="<?php echo config('constant.S3_PATH')?>driverDocuments/<?php echo $values->vehicle_inspection_document;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } ?>
                                            
                                        </td>
                                    </tr>
                                    <?php } ?> 
                                    
                                </table>
                            </td>
                        </tr>

                        <?php } } ?>


                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td align="left" valign="top" width="100%">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 20px; border: solid 1px #ccc;">
                    <thead>
                        <th colspan="2" style="background: #0091ec; padding: 5px; border: none; color: #fff;">Vehicle Inspection Results</th>
                    </thead>
                    <tbody>    
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">The last roadworthiness date should be within one year</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_road_worth == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">No functional falls/defects</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_functional_defects == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">No warning lights present</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_warning_light_present == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                 <!-- <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php  //if($list->verification_document_picture->warning_light_picture){?>    
                                        <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $list->verification_document_picture->warning_light_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                    <?php //} else{ ?> 
                                        <a href="#" style="font-weight: 700; text-decoration: none;">N/A</a>
                                        <?php// } ?>   
                                    </td>
                                    </tr>
                                </table> -->
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">All tyres are Roadworthy</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_wheels_present == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php if($list->verification_document_picture->front_right_wheel_picture != ""){?>    
                                        <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $list->verification_document_picture->front_right_wheel_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                    <?php } ?>    
                                    </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php if($list->verification_document_picture->front_left_wheel_picture != ""){?>       
                                        <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $list->verification_document_picture->front_left_wheel_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php } ?>        
                                    </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php if($list->verification_document_picture->back_left_wheel_picture != ""){?>       
                                        <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $list->verification_document_picture->back_left_wheel_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                            <?php } ?>    
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php if($list->verification_document_picture->back_right_wheel_picture != ""){?>       
                                        <a href="<?php echo config('constant.S3_PATH')?>inspectionDocuments/<?php echo $list->verification_document_picture->back_right_wheel_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                            <?php } ?>    
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">Steering</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_steering_present == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr> -->
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">Windscreen Wipers in Working Order</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_window_screen_wiper == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                <!-- <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php //if($list->verification_document_picture->window_screen_picture != ""){?>    
                                        <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $list->verification_document_picture->window_screen_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php //} ?>       
                                    </td>
                                    </tr>
                                </table> -->
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">Headlights in Working Order</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_head_light_present == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                <!-- <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php //if($list->verification_document_picture->head_light_picture != ""){?> 
                                            <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $list->verification_document_picture->head_light_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                            <?php //} ?>     
                                        </td>
                                    </tr>
                                </table> -->
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">Indicators/Hazards in Working Order</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_indicator_light_present == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                <!-- <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php //if($list->verification_document_picture->indicator_light_picture != ""){?> 
                                            <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $list->verification_document_picture->indicator_light_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php //} ?>
                                        </td>
                                    </tr>
                                </table> -->
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">Brake Lights in Working Order</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_brake_light_present == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                <!-- <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php //if($list->verification_document_picture->brake_light_picture != ""){?> 
                                            <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $list->verification_document_picture->brake_light_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                            <?php //} ?>
                                        </td>
                                    </tr>
                                </table> -->
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">Hooter</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_hooter_present == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                               
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">Seatbelts in Working Order</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_seat_belts_present == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                <!-- <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php //if($list->verification_document_picture->front_seat_belt_picture != ""){?> 
                                            <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $list->verification_document_picture->front_seat_belt_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                            <?php //} ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php //if($list->verification_document_picture->passenger_seat_belt_picture != ""){?> 
                                            <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $list->verification_document_picture->passenger_seat_belt_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                            <?php //} ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php //if($list->verification_document_picture->rear_seat_belt_picture != ""){?> 
                                            <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $list->verification_document_picture->rear_seat_belt_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                            <?php //} ?>
                                        </td>
                                    </tr>
                                </table> -->
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="width: 100%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box;">Spare wheel, Jack and Wheel Spanner at hand</td>
                                        <td align="right" valign="top" style="width: 50%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;"><span style="text-transform: uppercase;">
                                        @if($list->driver_verification_document->is_spare_jack_triangle_present == "Y")
                                            YES
                                            @else
                                            NO
                                        @endif
                                    </span></td>
                                    </tr>
                                </table>
                                <!-- <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 100%; padding: 5px; box-sizing: border-box; border-right:solid 1px #ccc;">
                                        <?php //if($list->verification_document_picture->jack_triangle_picture != ""){?>     
                                        <a href="<?php //echo config('constant.S3_PATH')?>inspectionDocuments/<?php //echo $list->verification_document_picture->jack_triangle_picture;?>" style="color: #186fd9; font-weight: 700; text-decoration: none;">View Photo</a>
                                        <?php //} ?>    
                                    </td>
                                    </tr>
                                </table> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</div>
@endif
</body>
</html>