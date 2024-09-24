<?php

namespace App\Services\Payments;

use App\Models\DriverApprovalPayment;
use App\Models\DriverDetail;
use App\Models\DriverTransaction;
use App\Models\PassangerTransaction;
use App\Models\PassengerRideDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DpoPaymentService
{
    /**
     * Initiate payment service
     * @author | Created By => Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param int    $totalAmount
     * @param string $UserEmail
     * @return $response
     **/
    public function initPayment($totalAmount, $UserEmail)
    {
        $DateTime = new \DateTime();
        $data = array(
            'PAYGATE_ID'       => config('payment.dpo.paygate_id'),
            'REFERENCE'        => uniqid('pgtest_'),
            'AMOUNT'           => $totalAmount,
            'CURRENCY'         => 'ZAR', //https: //docs.paygate.co.za/#country-codes
            'RETURN_URL'       => route('passanger_payment_response'),
            'TRANSACTION_DATE' => $DateTime->format('Y-m-d H:i:s'),
            'LOCALE'           => 'en-za', //https: //docs.paygate.co.za/#locale-codes
            'COUNTRY'          => 'ZAF', // https: //docs.paygate.co.za/#country-codes
            'EMAIL'            => $UserEmail, //'sulata@mailinator.com',
            'NOTIFY_URL'       => route('notify_passanger_payment'),
        );

        $checksum         = md5(implode('', $data) . config('payment.dpo.paygate_secret'));
        $data['CHECKSUM'] = $checksum;
        $response         = Http::asForm()->post(config('payment.dpo.paygate_initiate_url'), $data);
        return $response;
    }

    /**
     * Process initiate or pending payment and response success or fail transection status 
     * @author | Created By => Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param object $transaction
     * @return bool $response | 1 = success, 0 = fail
     **/
    public function queryPayment($transaction)
    {
        $data = array(
            'PAYGATE_ID'     => config('payment.dpo.paygate_id'),
            'PAY_REQUEST_ID' => $transaction->pay_request_id,
            'REFERENCE'      => $transaction->reference,
        );

        $checksum         = md5(implode('', $data) . config('payment.dpo.paygate_secret'));
        $data['CHECKSUM'] = $checksum;
        $response         = Http::asForm()->post(config('payment.dpo.paygate_query_url'), $data);

        return parse_str($response->body(), $output);
    }
}
