<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DriverDetail;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Models\DriverTransaction;
use App\Services\PaymentService;
use App\Services\TransactionService;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use PDF;
use Mail;


class PaymentController extends Controller
{
    protected $paymentService;
    protected $transactionService;
    public function __construct(PaymentService $paymentService, TransactionService $transactionService)
    {
        $this->paymentService     = $paymentService;
        $this->transactionService = $transactionService;
    }

    public function notify(Request $request) {
        //https://github.com/raakeshkumar/laravel-paygate/blob/main/.env.example
        // Since this URL is being called by external system, we will need to excempt CSRF token for this.

        $transaction = DriverTransaction::where('paygate_id', $request->PAYGATE_ID)
            ->where('pay_request_id', $request->PAY_REQUEST_ID)
            ->where('transaction_status', 'initiated')
            ->first();

        if (!empty($transaction)) {

            $data = array(
                'PAYGATE_ID'     => env('PAYGATE_ID'),
                'PAY_REQUEST_ID' => $transaction->pay_request_id,
                'REFERENCE'      => $transaction->reference,
            );

            $checksum         = md5(implode('', $data) . env('PAYGATE_SECRET'));
            $data['CHECKSUM'] = $checksum;
            $response         = Http::asForm()->post(env('PAYGATE_QUERY_URL'), $data);

            parse_str($response->body(), $output);
            //Log::info($output);
            if (!empty($output) and $output['TRANSACTION_STATUS'] == '1') {
                // Transaction successful
                $transaction->update([
                    'reference'          => $request->REFERENCE,
                    'transaction_status' => 'successful',
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

                return "OK";
            }

            $transaction->update([
                'reference'          => $request->REFERENCE,
                'transaction_status' => 'failed',
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
            return "Payment couldn't be verified";

        }

        return "Something went wrong";
    }

    public function pg_response(Request $request) {
        $transaction = DriverTransaction::where('pay_request_id', $request->PAY_REQUEST_ID)
            ->first();

        if (empty($transaction)) {
            return "Something went wrong";
        }

        if($transaction->transaction_status == "successful") {
            $driverDetails = DriverDetail::where('user_id',$transaction->user_id)->first();
            if(isset($driverDetails)){
                $driverDetails->driver_complete_date = date('Y-m-d H:i:s');
                $driverDetails->is_driver_complete = "Y";
                $driverDetails->is_update_inspection = "Y"; //only show in home page   
                $driverDetails->is_payment_completed = "Y";                 
                $driverDetails->save();

                if($driverDetails->is_admin_approve == "R"){
                    $driverDetails->is_admin_approve = "P";
                    $driverDetails->reject_document_reason = "";
                    $driverDetails->save();
                }
                // $userRecord = User::where('id',$driverDetails->user_id)->first();

                // $driverDetailId = $driverDetails->id;

                // $paymentList = DriverDetail::with(['user' => function($query){
                //     $query->select('id', 'full_name', 'email','country_code','mobile');
                // }])
                // ->with('driver_transaction')
                // ->where('user_id',$driverDetails->user_id)
                // ->where('id',$driverDetailId)->first();
                // if(isset($paymentList)){  
                //     $user_email = $userRecord->email;
                //     $user_full_name = $userRecord->full_name;

                //     $data = [
                //         'list' => $paymentList, 
                //     ];
                    
                //     $pdf = PDF::loadView('pdf/driver_payment_invoice', $data);

                //     $data["email"] = $user_email; //"aatmaninfotech@gmail.com";
                //     $data["fullName"] = $user_full_name;
                //     $data["title"] = "Payment Invoice";
                //     $data["body"] = "Your Invoice attach below.";

                //     Mail::send('emails.driver_payment_invoice', $data, function($message)use($data, $pdf) {
                //         $message->to($data["email"], $data["fullName"])
                //                 ->subject($data["title"])
                //                 ->attachData($pdf->output(), "invoice.pdf");
                //     });
                // }

            }
        }

        return view("payment.response", compact('transaction'));
    }

    /**
     * Notify passanger payment and update transection success
     */
    public function notifyPassangerPayment(Request $request)
    {
        return $this->paymentService->queryCustomerPayment($request);
    }

    /**
     * Return view with $transection
     * @return view with teansevtion status => $transaction
     */
    public function passangerPgResponse(Request $request)
    {
        $transaction = $this->transactionService->getPassangerTransaction($request);
        return view("payment.response", compact('transaction'));
    }
}