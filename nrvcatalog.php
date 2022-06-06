<?php
/*
    Plugin Name: NrvCatalog
    Plugin URI: https://nirvanius.ru
    Description: Plugi for create custom catalog and product pages
    Version: 1.0
    Author: Nirvanius
    Author URI: https://nirvanius.ru
    Text Domain: nrvcatalog
    Domain Path: /lang
    License: GPLv2
    Copyright (C) 2022 Nirvanius
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ){
    die;
}
define('NRVCATALOG_PATH', plugin_dir_path(__FILE__));
if (!class_exists('nrvCatalogCpt')){
    require NRVCATALOG_PATH.'inc/class-nrvcatalogcpt.php';
}
require NRVCATALOG_PATH.'inc/class-nrvcatalog-template-loader.php';
require NRVCATALOG_PATH.'inc/class-nvcatalog-shortcode.php';
require NRVCATALOG_PATH.'inc/class-nrvcatalog-excel.php';

class nrvCatalog{
    public function register(){
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front']);
        add_action('plugins_loaded', [$this, 'load_text_domain']);
        add_filter('manage_catalog_posts_columns', [$this, 'my_custom_column']);
        add_action( 'manage_catalog_posts_custom_column', [$this, 'nrvcatalog_custom_column']);
    }

    public function nrvcatalog_custom_column ($column_name){
        if ( $column_name === 'short' ) {
            $id_post= get_the_ID();
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';
        $product_key = $wpdb->get_var("SELECT key_name FROM $table_name WHERE id_post = $id_post");
             echo '[nrvcatalog name="'.$product_key.'"]';
            }
    }

    public function my_custom_column($columns){
        $columns['short'] = esc_html__('Shortcoder', 'nrvcatalog');
    return $columns;
    }

    public function load_text_domain(){
        load_plugin_textdomain('nrvcatalog', false, dirname(plugin_basename(__FILE__)).'/lang');
    }

    public function enqueue_admin(){
        wp_enqueue_style('nrvCatalog_style', plugins_url('/assets/css/admin/style.css', __FILE__));
    }

    public function enqueue_front(){
        wp_enqueue_style('nrvCatalog_style', plugins_url('/assets/css/front/style.css', __FILE__));
        wp_enqueue_style('nrvCatalog_catalog_style', plugins_url('/assets/css/front/catalog-style.css', __FILE__));
    }

    static function activation(){
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
        $test= $wpdb->query("SELECT * FROM $table_name LIMIT 1");
        if (!$test){
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $sql = "CREATE TABLE {$table_name} (
        key_name varchar(30) NOT NULL,
        id_post int NOT NULL,
        name varchar(30) NOT NULL,
        image_link varchar(255) NOT NULL,
        referal_link varchar(255) NOT NULL,
        credit varchar(30) NOT NULL,
        period varchar(30) NOT NULL,
        rate varchar(30) NOT NULL,
        age varchar(30) NOT NULL,
        rating	 int NOT NULL,
        approval int NOT NULL,
        telephone varchar(30) NOT NULL,
        license varchar(30) NOT NULL,
        address varchar(255) NOT NULL,
        description text NOT NULL,
        note varchar(30) NOT NULL default '',
        PRIMARY KEY  (key_name),
        KEY (id_post)
        ) {$charset_collate};";
        dbDelta( $sql );
        }

        if (!class_exists('nrvCatalogCpt')){
            require NRVCATALOG_PATH.'inc/class-nrvcatalogcpt.php';
        }
        $rewrite= new nrvCatalogCpt ();
        $rewrite->custom_post_type();
        flush_rewrite_rules();
    }

    static function deactivation(){
        flush_rewrite_rules();
    }

}
if( class_exists('nrvCatalog')){
    $nrvCatalog = new nrvCatalog();
    $nrvCatalog->register();
}

register_activation_hook(__FILE__, array($nrvCatalog, 'activation'));
register_deactivation_hook(__FILE__, array($nrvCatalog, 'deactivation'));