<?php

namespace WPPluginWithVueTailwind\Classes;

use WPPluginWithVueTailwind\Classes\Controllers\QrCodeController;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class Routes
{
    public static function init()
    {
        add_action("rest_api_init", function () {
            (new static())->registerRoutes();
        });

    }

    private function registerRoutes()
    {

        register_rest_route(QR_GENERATOR_SLUG . '/api', '/getdata/(?P<id>\d+)', array(
            'method' => 'GET',
            'callback' => function ($params) {
                return (new QrCodeController)->test($params);
            },
        ));


        register_rest_route(QR_GENERATOR_SLUG . '/api', '/insert', [
            'methods' => 'POST',
            'callback' => function ($params) {
                return (new QrCodeController)->insertData($params);
            },
        ]);

        register_rest_route(QR_GENERATOR_SLUG . '/api', '/get/data', [
            'methods' => 'GET',
            'callback' => [new QrCodeController, 'getMyData'],
        ]);

        register_rest_route(QR_GENERATOR_SLUG . '/api', '/get/data/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [new QrCodeController, 'getMyDataById'],
        ]);


        register_rest_route(QR_GENERATOR_SLUG . '/api', '/update/(?P<id>\d+)', [
            'methods' => 'POST',
            'callback' => [new QrCodeController, 'updateData'],
        ]);

        register_rest_route(QR_GENERATOR_SLUG . '/api', '/delete/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => function ($params) {
                return (new QrCodeController)->deleteDataById($params);
            },
        ]);

    }

}