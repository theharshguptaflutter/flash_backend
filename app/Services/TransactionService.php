<?php

namespace App\Services;

use App\Models\PassangerTransaction;
use App\Models\PassengerRideDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    /**
     * Store passanger teansection data in database
     * @author | Created By => Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param int   $user_id
     * @param array $data
     * @return bool $result save()
     **/
    public function storePassangerTransection($userID, array $data)
    {
        return DB::transaction(function () use ($userID, $data) {
            $result = new PassangerTransaction();

            $result->user_id            = $userID;
            $result->pay_request_id     = $data['PAY_REQUEST_ID'];
            $result->paygate_id         = $data['PAYGATE_ID'];
            $result->reference          = $data['REFERENCE'];
            $result->checksum           = $data['CHECKSUM'];
            $result->transaction_status = 'initiated';

            // Save passanger teansection data in database
            return $result->save();
        });
    }


    /**
     * Get initiate teansection details as per PAYGATE_ID & PAY_REQUEST_ID
     * @param  $request
     * @return PassangerTransaction instance
     */
    public function getTransectionData($request)
    {
        return PassangerTransaction::where('paygate_id', $request->PAYGATE_ID)
                ->where('pay_request_id', $request->PAY_REQUEST_ID)
                ->where('transaction_status', 'initiated')
                ->first();
    }

    /**
     * Update transection with payment status
     * @author Created By => Subhodeep Bhattacharjee 
     * @param  object $transaction
     * @param  object $request
     * @param  string $status
     * @return PassangerTransaction instance
     */
    public function updatePassangerTransection($transaction, $request, $status)
    {
        // Log::info($output);
        return DB::transaction(function () use ($transaction, $request, $status) {   
            // Transaction successful
            $transaction->update([
                'reference'          => $request->REFERENCE,
                'transaction_status' => $status,
                'result_code'        => $request->RESULT_CODE,
                'auth_code'          => $request->AUTH_CODE,
                'currency'           => $request->CURRENCY,
                'amount'             => $request->AMOUNT,
                'result_desc'        => $request->RESULT_DESC,
                'transaction_id'     => $request->TRANSACTION_ID,
                'pay_method'         => $request->PAY_METHOD,
                'pay_method_detail'  => $request->PAY_METHOD_DETAIL,
                'vault_id'           => $request->VAULT_ID,
                'payvault_data_1'    => $request->PAYVAULT_DATA_1,
                'payvault_data_2'    => $request->PAYVAULT_DATA_2,
                'checksum'           => $request->CHECKSUM,
                'transaction_date'   => date('Y-m-d H:i:s'),
            ]);
        });

        // return "Payment couldn't be verified";

    }

    /**
     * Update data in passanger_ride_details table
     */
    public function getPassangerTransaction($request)
    {
        $transaction = PassangerTransaction::where('pay_request_id', $request->PAY_REQUEST_ID)->first();

        if (empty($transaction)) {
            return "Something went wrong";
        }

        if ($transaction->transaction_status == "successful") {
            return $transaction;
        }
    }

}