<?php

namespace ChastePhp\LaravelExtras\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;

class ExtraServiceProvider extends ServiceProvider
{
    /**
     *
     * @return void
     */
    public function boot()
    {

        Validator::extend('mobile', function ($attribute, $value, $parameters, $validator) {
            return (bool) preg_match("/^1\d{10}$/", $value);
        });

        Validator::extend('nickname', function ($attribute, $value, $parameters, $validator) {
            return (bool) preg_match("/^[\u4e00-\u9fa5a-zA-Z][\u4e00-\u9fa5_a-zA-Z0-9]{3,7}$/", $value);
        });

        Validator::extend('id_no', function ($attribute, $value, $parameters, $validator) {
            return (bool) preg_match("/^(\d{14}|\d{17})(\d{1}|x|X)$/", $value);
        });

        /**
         * success
         * @param  $msg string
         * @param  $data mixed
         * @return JsonResponse
         */
        Response::macro('success', function (string $msg = 'success', $data = null) {
            $ret = ['code' => 0, 'message' => $msg, 'data' => $data];

            if ($ret['data'] instanceof AbstractPaginator) {
                $ret['data'] = Arr::only($ret['data']->toArray(), ['data', 'current_page', 'per_page', "total"]);
            }

            if ($ret['data'] === null) {
                unset($ret['data']);
            }

            return Response::json($ret, 200, [], JSON_UNESCAPED_UNICODE);
        });

        /**
         * error
         * @param  $msg string
         * @param  $data mixed
         * @return JsonResponse
         */
        Response::macro('error', function (string $msg = 'error', $data = null) {
            $ret = ['code' => 1, 'message' => $msg, 'data' => $data];

            if ($ret['data'] instanceof AbstractPaginator) {
                $ret['data'] = Arr::only($ret['data']->toArray(), ['data', 'current_page', 'per_page', "total"]);
            }

            if ($ret['data'] === null) {
                unset($ret['data']);
            }

            return Response::json($ret, 200, [], JSON_UNESCAPED_UNICODE);
        });

        /**
         * system error, illegal request ...
         * @param  $msg string
         * @param  $data mixed
         * @return JsonResponse
         */
        Response::macro('bad', function (string $msg = 'service unavailable ', $data = null) {
            $ret = ['code' => -1, 'message' => $msg, 'data' => $data];

            if ($ret['data'] instanceof AbstractPaginator) {
                $ret['data'] = Arr::only($ret['data']->toArray(), ['data', 'current_page', 'per_page', "total"]);
            }

            if ($ret['data'] === null) {
                unset($ret['data']);
            }

            return Response::json($ret, 200, [], JSON_UNESCAPED_UNICODE);
        });

        /**
         * unauthenticated or token expired
         * @param  $msg string
         * @param  $data mixed
         * @return JsonResponse
         */
        Response::macro('unauthenticated', function (string $msg = 'unauthenticated', $data = null) {
            $ret = ['code' => 40001, 'message' => $msg, 'data' => $data];

            if ($ret['data'] instanceof AbstractPaginator) {
                $ret['data'] = Arr::only($ret['data']->toArray(), ['data', 'current_page', 'per_page', "total"]);
            }

            if ($ret['data'] === null) {
                unset($ret['data']);
            }

            return Response::json($ret, 200, [], JSON_UNESCAPED_UNICODE);
        });
    }
}
