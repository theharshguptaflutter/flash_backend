<?php

namespace App\Services;

use App\Models\PassangerTransaction;
use App\Models\PassengerRideDetail;
use App\Models\UserToken;
use App\Services\Payments\DpoPaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    const SUCCESS               = "OK";
    const SOMETHING_WRONG       = "Something went Wrong";
    const PAYMENT_NOT_VERIFIED  = "Payment couldn't be verified";

    protected $dpoPaymentService;
    protected $transactionService;
    /**
     * @todo | Description => If include new payment service in future then just create one new service file like => DpoPaymentService under "Payments" folder
     * and change only calling $this->dpoPaynentService to another payment service. But benefit, the function name is same. So, we done
     * change controller or service code. Just include new service neme peoperly & it's work properly.
     * @author Created By => Subhodeep Bhattacharjee on 23 Feb 2023 <subhodeepbhat@technoexponent.com> 
     */
    public function __construct(
        DpoPaymentService $dpoPaymentService, 
        TransactionService $transactionService
    ) {
        $this->dpoPaymentService  = $dpoPaymentService;
        $this->transactionService = $transactionService;
    }

    /**
     * Initiate customer payment and store details in passanger teansection table
     * @author | Created By => Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param array $request validated() array
     * @return response $response
     */
    public function initCustomerPayment(array $request)
    {
        $userId = Auth::user()->id;
        $UserData = Auth::user();
        $UserEmail = $UserData->email;

        $response = $this->dpoPaymentService->initPayment($request['pay_amount'], $UserEmail);

        parse_str($response->body(), $output);

        if (!empty($output)) {
            // Store in passasnger teansection table by this method
            $transectionData     = $this->transactionService->storePassangerTransection($userId, $output);

            $response['output']  = $output;
            $response['user_id'] = $userId;

            return $response;
            //return view('payment.form', compact('output'));
        }

        // You can show some error messages here or redirect to another URL
        // $response['status'] = 400;
        // $response['error'] = "Something went Wrong!";

        return PaymentService::SOMETHING_WRONG;
        // return $this->dpoPaymentService->initCustomerPayment($request);
    }

    /**
     * Update pending customer payment in passanger teansection table
     * @author | Created By => Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param array $request validated() array
     * @return response $response
     */
    public function queryCustomerPayment($request)
    {
        $transaction = $this->transactionService->getTransectionData($request);

        if (!empty($transaction)) {

            $output = $this->dpoPaymentService->queryPayment($transaction);

            if (!empty($output) and $output['TRANSACTION_STATUS'] == '1') {
                // Transaction successful
                $this->transactionService->updatePassangerTransection($transaction, $request, 'successful');
                return PaymentService::SUCCESS;
            }

            // Transaction failed
            $this->transactionService->updatePassangerTransection($transaction, $request, 'failed');
            return PaymentService::PAYMENT_NOT_VERIFIED;
        }

        return PaymentService::SOMETHING_WRONG;
    }
}
