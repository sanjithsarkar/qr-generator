<?php

function custom_api_route() {
    register_rest_route('api', '/insert', array(
        'methods' => 'POST',
        'callback' => 'custom_api_callback',
    ));
}
add_action('rest_api_init', 'custom_api_route');


function custom_api_callback($request) {

    $params = $request->get_params();
    $name = isset($params['name']);

//    $response = echo "sanjith";
    return sanjith;

}
