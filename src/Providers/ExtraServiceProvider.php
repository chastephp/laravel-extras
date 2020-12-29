<?php

namespace ChastePhp\LaravelExtras\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ExtraServiceProvider extends ServiceProvider
{
    /**
     *
     * @return void
     */
    public function boot()
    {

        Validator::extend('mobile', function ($attribute, $value, $parameters, $validator) {
            return (bool)preg_match("/^1\d{10}$/", $value);
        });

        Validator::extend('nickname', function ($attribute, $value, $parameters, $validator) {
            return (bool)preg_match("/^[\u4e00-\u9fa5a-zA-Z][\u4e00-\u9fa5_a-zA-Z0-9]{3,7}$/", $value);
        });

        Validator::extend('id_no', function ($attribute, $value, $parameters, $validator) {
            return (bool)preg_match("/^(\d{14}|\d{17})(\d{1}|x|X)$/", $value);
        });

        /**
         * success
         * @param $msg string|mixed
         * @param $data mixed
         * @return \Illuminate\Http\JsonResponse
         */
        Response::macro('success', function ($msg = 'success', $data = null) {
            $ret = ['code' => 0, 'message' => $msg, 'data' => $data];
            if (!is_string($msg)) {
                list($data, $msg) = [$msg, $data ?? 'success'];
            }
            if ($ret['data'] === null) {
                unset($ret['data']);
            }

            return Response::json($ret,
                200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        });

        /**
         * error
         *
         * @return \Illuminate\Http\JsonResponse
         */
        Response::macro('error', function ($msg = 'error', $data = null) {
            $ret = ['code' => 1, 'message' => $msg, 'data' => $data];
            if ($data === null) {
                unset($ret['data']);
            }
            return Response::json($ret,
                200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        });

        /**
         * system error, illegal request ...
         *
         * @return \Illuminate\Http\JsonResponse
         */
        Response::macro('bad', function ($msg = 'service unavailable ', $data = null) {
            $ret = ['code' => 1, 'message' => $msg, 'data' => $data];
            if ($data === null || !config('app.debug')) { //data is null or debug is false
                unset($ret['data']);
            }
            return Response::json($ret,
                200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        });

        /**
         * unauthenticated or token expired
         *
         * @return \Illuminate\Http\JsonResponse
         */
        Response::macro('unauthenticated', function ($msg = 'unauthenticated', $data = null) {

            $ret = ['code' => 40001, 'message' => $msg, 'data' => $data];
            if ($data === null || !config('app.debug')) { //data is null or debug is false
                unset($ret['data']);
            }
            return Response::json($ret,
                200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);

        });
    }
}
