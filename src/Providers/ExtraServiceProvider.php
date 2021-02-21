<?php

namespace ChastePhp\LaravelExtras\Providers;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

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
         * Route a resource to a controller.
         *
         * @param  string  $name
         * @param  string  $controller
         * @param  array  $options
         * @return \Illuminate\Routing\PendingResourceRegistration
         * @static
         */
        Route::macro('api', function ($name, $controller, $only = ['list', 'create', 'update', 'info', 'delete']) {
            $name = rtrim($name, '/').'/';
            $only = (array) $only;
            in_array('list', $only) && $this->get($name.'list', join('@', [$controller, 'list']));
            in_array('create', $only) && $this->post($name.'create', join('@', [$controller, 'create']));
            in_array('update', $only) && $this->post($name.'update', join('@', [$controller, 'update']));
            in_array('info', $only) && $this->get($name.'info', join('@', [$controller, 'info']));
            in_array('delete', $only) && $this->post($name.'delete', join('@', [$controller, 'delete']));
        });


        /**
         * success
         * @param  $msg string
         * @param  $data mixed
         * @return Illuminate\Http\JsonResponse
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
         * @return Illuminate\Http\JsonResponse
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
         * @return Illuminate\Http\JsonResponse
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
         * @return Illuminate\Http\JsonResponse
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
