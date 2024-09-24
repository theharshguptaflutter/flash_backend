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
                        <td align="center" valign="top" style="100%; font-weight: 600; font-size: 24px; color: #000;">Invoice</td>
                    </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td align="center" valign="top" style="100%;"><a href="javascript:void(0);"><img src="<?php echo config('constant.S3_PATH')?>logo/logo_1659962130163.jpg" width="200px"></a></td>
                    </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 20px 0">
                    <tr>
                        <td align="left" valign="top" style="width:20%;font-size:12px;">User Full Name: <span style="text-transform: uppercase;">{{isset($list->user->full_name)?($list->user->full_name): ""}}</span></td>
                        <td align="left" valign="top" style="width:20%;font-size:12px;">User Email: <span >{{isset($list->user->email)?($list->user->email): ""}}</span></td>
                        <td align="left" valign="top" style="width:35%;font-size:12px;">User Phone: <span >{{isset($list->user->country_code)?($list->user->country_code): ""}} {{isset($list->user->mobile)?($list->user->mobile): ""}}</span></td>
                        <td align="left" valign="top" style="width:25%;font-size:12px;">Inspector: <span style="text-transform: uppercase;">Super Admin</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" width="100%">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 20px; border: solid 1px #ccc;">
                    <thead>
                        <th colspan="2" style="background: #0091ec; padding: 5px; border: none; color: #fff;">Payment Details</th>
                    </thead>
                    <tbody>    
                        <tr>
                        <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Transaction Id</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span>{{isset($list->driver_transaction->transaction_id)?($list->driver_transaction->transaction_id): 0}}</span></td>
                                    </tr>
                                </table>
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Amount</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span>R {{isset($list->driver_transaction->amount)?($list->driver_transaction->amount): 0}}</span></td>
                                    </tr>
                                </table>
                            </td>
                            <td align="left" valign="top" style="width: 50%; border-bottom: solid 1px #ccc;">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" valign="top" style="width: 35%; padding: 5px; box-sizing: border-box;">Transaction Date</td>
                                        <td align="left" valign="top" style="width: 65%; padding: 5px; box-sizing: border-box;"><span>{{isset($list->driver_transaction->transaction_date)?date('jS F, Y', strtotime($list->driver_transaction->transaction_date)): ""}}</span></td>
                                    </tr>
                                </table>
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