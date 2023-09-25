<?php

/**
 * Plugin Name: QR Generator
 * Plugin URI: ""
 * Description: QR-Generator is a plugin developed by vue and Tailwind.
 * Author: Sanjith
 * Author URI: ""
 * Version: 1.0
 */
define('WPM_URL', plugin_dir_url(__FILE__));
define('WPM_DIR', plugin_dir_path(__FILE__));

define('WPM_VERSION', '1.0.5');

// This will automatically update, when you run dev or production
define('WPM_DEVELOPMENT', 'yes');

define('QR_GENERATOR_SLUG', 'qr_generator');



class QrCodePlugin {
    public function boot()
    {
        $this->loadClasses();
        $this->registerShortCodes();
        $this->ActivatePlugin();
        $this->renderMenu();
        $this->disableUpdateNag();
        \WPPluginWithVueTailwind\Classes\Routes::init();
    }

    public function loadClasses()
    {
        require WPM_DIR . 'includes/autoload.php';
    }

    public function renderMenu()
    {
        add_action('admin_menu', function () {
            if (!current_user_can('manage_options')) {
                return;
            }
            global $submenu;
            add_menu_page(
                'WPPluginQRGenerator',
                'QR Generator',
                'manage_options',
                'qr-generator.php',
                array($this, 'renderAdminPage'),
                'dashicons-editor-code',
                25
            );
            $submenu['qr-generator.php']['dashboard'] = array(
                'Dashboard',
                'manage_options',
                'admin.php?page=qr-generator.php#/',
            );
            $submenu['qr-generator.php']['Create QR'] = array(
                'Create QR',
                'manage_options',
                'admin.php?page=qr-generator.php#/create-qr',
            );
            $submenu['qr-generator.php']['My QR'] = array(
                'My QR Codes',
                'manage_options',
                'admin.php?page=qr-generator.php#/my-qr',
            );
        });
    }

    public function renderAdminPage()
    {
        $loadAssets = new \WPPluginWithVueTailwind\Classes\LoadAssets();
        $loadAssets->admin();

        $WPWVT = apply_filters('WPWVT/admin_app_vars', array(
            'assets_url' => WPM_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php'),
            'resturl' => site_url('wp-json/'.QR_GENERATOR_SLUG.'/api/')
        ));

        wp_localize_script('WPWVT-script-boot', 'qr_generator', $WPWVT);

        echo '<div class="WPWVT-admin-page" id="WPWVT_app">
            <div class="main-menu text-white-200 bg-wheat-600 p-4">
                <router-link to="/">
                    Home
                </router-link> |
                <router-link to="/create-qr" >
                    Create QR
                </router-link>
            </div>
            <hr/>
            <router-view></router-view>
        </div>';
    }

    public function registerShortCodes()
    {
        // your shortcode here
    }

    // disable update nag on admin dashboard
    public function disableUpdateNag()
    {
        add_action('admin_init', function () {
            $disablePages = [
                'wpp-plugin-with-vue-tailwind.php',
            ];

            if (isset($_GET['page']) && in_array($_GET['page'], $disablePages)) {
                remove_all_actions('admin_notices');
            }
        }, 20);
    }

    public function ActivatePlugin()
    {
        //activation deactivation hook
        register_activation_hook(__FILE__, function ($newWorkWide) {
            require_once(WPM_DIR . 'includes/Classes/Activator.php');
            $activator = new \WPPluginWithVueTailwind\Classes\Activator();
            $activator->migrateDatabases($newWorkWide);
        });
    }
}

(new QrCodePlugin())->boot();
