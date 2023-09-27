<?php

namespace WPPluginWithVueTailwind\Classes;

class CustomRoute
{
    public static function customRule()
    {
        add_action('init', function () {
            (new static())->customRoutes();
        });
    }

    public function customRoutes()
    {

        add_rewrite_rule(
            'qrcode/([^/]+)/?$',
            'index.php?qr_id=$matches[1]',
            'top'
        );


        add_filter('query_vars', function ($query_vars) {
            $query_vars[] = 'qr_id';
            return $query_vars;
        });


        add_action('template_include', function ($template) {
            if (get_query_var('qr_id') == false || get_query_var('qr_id') == '') {
                return $template;
            }

            return QR_GENERATOR_DIR . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'MyView.php';
        });
    }
}