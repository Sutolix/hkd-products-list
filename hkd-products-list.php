<?php
/*
Plugin Name: Listagem de produtos
Requires Plugins: elementor, woocommerce
Description: Um addon para Elementor que cria uma listagem de produtos com abas por categoria.
Author: Hookod
Text Domain: nkd-products-list
Version: 0.1
Author URI: https://hookod.com
*/

defined('ABSPATH') || exit;

define('HPL_PATH', plugin_dir_path(__FILE__));
define('HPL_URL', plugin_dir_url(__FILE__));

/**
 *
 * @class HKDProductsList
 * @version 0.1
 */
if (!class_exists('HKDProductsList')) {
    class HKDProductsList
    {
        public function __construct()
        {
            add_action('elementor/init', [$this, 'init']);
            add_action('elementor/widgets/register', array($this, 'register_widget'));
        }

        public function init()
        {
            add_action('elementor/frontend/after_register_styles', function () {
                wp_register_style(
                    'hpl-products-list-style',
                    HPL_URL . '/assets/css/styles.css',
                    [],
                    NULL
                );
            });

            add_action('elementor/frontend/after_register_scripts', function () {
                wp_register_script(
                    'hpl-products-list-script',
                    HPL_URL . '/assets/js/scripts.js',
                    ['jquery'],
                    NULL,
                    true
                );
            });
        }

        public function register_widget($widgets_manager)
        {
            require_once(HPL_PATH . '/widgets/products-list.php');

            $widgets_manager->register(new \Elementor_HPL_Products_List());
        }
    }
}

new HKDProductsList();
