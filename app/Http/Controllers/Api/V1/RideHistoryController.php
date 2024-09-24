<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ride\CurrentRideAccessRequest;
use App\Http\Requests\Ride\StoreRideRequest;
use App\Http\Resources\Ride\CurrentRideStepCollection;
use App\Http\Resources\Ride\StoreRideCollection;
use App\Services\RideHistoryService;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;

class RideHistoryController extends Controller
{  
    use ApiTrait;
    protected $rideHistoryService;

    public function __construct(RideHistoryService $rideHistoryService)
    {
        $this->rideHistoryService = $rideHistoryService;
    }

    public function store(StoreRideRequest $request)
    {
        $response = $this->rideHistoryService->store($request->validated());
        
        // Hidden this value for this API permanently. No one cannot visi this fields.
        $response->makeHidden(['id', 'created_at', 'updated_at']);
        return $this->apiResponse(200, __('Insert successfully'), $response, [], []);
    }

    /**
     * This is return the current ride details
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * 5 Waters - 40 / 8
     * @param App\Http\Requests\Ride\CurrentRideAccessRequest $requrst
     * @return 
     */
    public function getCurrentRideStep(CurrentRideAccessRequest $request)
    {
        $response = $this->rideHistoryService->currentRideStep($request->validated());
        $response = new CurrentRideStepCollection($response);
        return $this->apiResponse(200, __('Current ride details'), $response, [], []);
    }
}