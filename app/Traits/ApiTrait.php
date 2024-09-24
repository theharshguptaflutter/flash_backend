<?php

namespace App\Traits;

trait ApiTrait
{
    public function apiResponse($status, $message, $data, $error, $meta = [], $info = [])
    {
        return is_array($error) ?
            response()->json([
                'status'   => $status,
                'message'  => __($message),
                'data'     => $data,
                'meta'     => $meta,
                'error'    => [],
                'info'     => $info,
            ], $status) :

            response()->json([
                'status'   => $status,
                'message'  => __($message),
                'data'     => $data,
                'meta'     => $meta,
                'error'    => [
                    [
                        "field" => "genric",
                        "errors" => [$error]
                    ],
                ],
                'info' => $info,
            ], $status);
    }
}
