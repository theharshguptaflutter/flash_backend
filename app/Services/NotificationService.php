<?php

namespace App\Services;

use App\Models\UserToken;

class NotificationService
{
    /**
     * Send notification to passanger
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param $request
     * @return $message_status
     */
    public function sendNotifications($request)
    {
        // $user = UserToken::where('user_id', $request->id)->get()->toArray();
        // $ftoken = $user[0]['fcm_token'];
        $ftoken = $this->getFCMToken($request->id);

        $noti = array("body" => $request->message, "title" => $request->message, "sound" => "default");
        $token = isset($ftoken) ? $ftoken : "";
        if ($token != null && $token != "") {

            $data = array(
                "sound" => "default",
                "body" => $request->message,
                "title" => $request->message,
                "content_available" => true,
                "priority" => "high",
                // "passengerRecord"=> $authUser,
                // "rideDetails"=> $initiateRideRecord,
            );
            $message_status = $this->sendNotification($token, $data, $noti, 'P');
        }
        return $message_status;
    }

    /**
     * Send notification to driver
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param $request
     * @return $message_status
     */
    public function sendDriverNotifications($request)
    {
        $ftoken = $this->getFCMToken($request->id);

        $noti = array("body" => $request->message, "title" => $request->message, "sound" => "default");
        $token = isset($ftoken) ? $ftoken : "";
        if ($token != null && $token != "") {

            $data = array(
                "sound" => "default",
                "body" => $request->message,
                "title" => $request->message,
                "content_available" => true,
                "priority" => "high",
                // "passengerRecord"=> $authUser,
                // "rideDetails"=> $initiateRideRecord,
            );

            $message_status = $this->sendNotification($token, $data, $noti, 'D');
        }
        return $message_status;
    }

    /**
     * Send user FCM token
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param int $user_id
     * @return string $ftoken
     */
    private function getFCMToken($user_id)
    {
        $user = UserToken::where('user_id', $user_id)->get()->toArray();
        $ftoken = $user[0]['fcm_token'];

        return $ftoken;
    }

    /**
     * Send notification to driver
     * @param string $tokens
     * @param array $data
     * @param $notification = NULL
     */
    // private function sendDriversNotification($tokens, $data, $notification = NULL)
    // {
    //     $url = 'https://fcm.googleapis.com/fcm/send';
    //     $fields = array(
    //         'notification' => $notification,
    //         'registration_ids' => array($tokens),
    //         'priority' => 'high',
    //         'data' => $data
    //     );

    //     $headers = array(
    //         'Authorization:key = ' . env('FCM_DRIVER_SERVER_KEY'),
    //         'Content-Type: application/json'
    //     );
    //     return $this->getCurlData($url, json_encode($fields), $headers);
    // }

    /**
     * Send notification to user
     * @param string $tokens
     * @param array $data
     * @param $notification = NULL
     * @param string $userType
     */
    private function sendNotification($tokens, $data, $notification = NULL, $userType)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'notification' => $notification,
            'registration_ids' => array($tokens),
            'priority' => 'high',
            'data' => $data
        );

        $headers = array(
            'Authorization:key = ' . ($userType == 'P') ? env('FCM_PASSENGER_SERVER_KEY') : env('FCM_DRIVER_SERVER_KEY'),
            'Content-Type: application/json'
        );

        return $this->getCurlData($url, json_encode($fields), $headers);
    }

    /**
     * Call API via curl
     * @param string $url
     * @param json_encode (array) $poststr
     * @param $headers = NULL
     */
    public function getCurlData($url, $poststr, $headers = NULL)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $poststr);

        if ($headers != NULL) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        //curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $curlData = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        } else {
            curl_close($curl);
            return $curlData;
        }
    }
}