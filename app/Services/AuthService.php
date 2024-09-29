<?php

namespace App\Services;

use App\Services\Auth\DriverRegisterService;
use App\Services\Auth\LoginService;
use App\Services\Auth\VerifyOtpService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthService
{
    protected $loginService;
    protected $verifyOtpService;
    protected $driverRegisterService;

    public function __construct(
        LoginService $loginService,
        VerifyOtpService $verifyOtpService,
        DriverRegisterService $driverRegisterService
    ) {
        $this->loginService = $loginService;
        $this->verifyOtpService = $verifyOtpService;
        $this->driverRegisterService = $driverRegisterService;
    }

    /**
     * Check authentication
     * @param array $request validated
     * @param int $document_step
     * @return array response with status code
     */
    public function varifyLogin(array $request, $document_step) 
    {
        return $request;
        Log::info($request);
        
        return $this->loginService->login($request, $document_step);
    }

    /**
     * Send OTP
     * @param array $request validated
     */
    public function verifyPhone(array $request)
    {
        return $this->verifyOtpService->verifyPhoneNumber($request);
    }

    public function verifyOtp(array $request)
    {
        return $this->verifyOtpService->verifyOTP($request);
    }

    /**
     * Resend new OTP
     * @param array $request validated
     */
    public function resendOtp(array $request)
    {
        return $this->verifyOtpService->resendNewOtp($request);
    }

    /**
     * Logout API
     * @return destroy\ session
     */
    public function logout()
    {
        return $this->loginService->logout();
    }

    /* ======| Driver registration all steps |====== */
    /**
     * Driver registeation first step
     * @return $response
     */
    public function registerFirstStep(array $request)
    {
        return $this->driverRegisterService->registerStepOne($request);
    }

    /**
     * Driver registration second step
     * @return $response
     */
    public function registerSecondStep(array $request)
    {
        return $this->driverRegisterService->registerStepTwo($request);
    }

    /**
     * Driver registration third step
     * @return $response
     */
    public function registerThirdStep(array $request)
    {
        return $this->driverRegisterService->registerStepThree($request);
    }

    /**
     * Driver registration fourth step
     * @return $response
     */
    public function registerFourthStep(array $request)
    {
        return $this->driverRegisterService->registerStepFour($request);
    }

    /**
     * Driver registration five step
     * @return $response
     */
    public function registerFifthStep(array $request)
    {
        return $this->driverRegisterService->registerStepFive($request);
    }
}