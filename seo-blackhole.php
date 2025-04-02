<?php
/*
Plugin Name: Seo-BlackHole
Description: افزونه‌ای برای مدیریت کلمات کلیدی، تولید محتوا با هوش مصنوعی، و درج در وردپرس
Version: 1.0
Author: Mohamad
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit;
}

define('SEO_BLACKHOLE_PATH', plugin_dir_path(__FILE__));
define('SEO_BLACKHOLE_URL', plugin_dir_url(__FILE__));

// لود فایل‌های کمکی
require_once SEO_BLACKHOLE_PATH . 'includes/admin.php';
require_once SEO_BLACKHOLE_PATH . 'includes/content-manager.php';
require_once SEO_BLACKHOLE_PATH . 'includes/api-client.php';
require_once SEO_BLACKHOLE_PATH . 'includes/db-handler.php';

// فعال‌سازی افزونه
function seo_blackhole_activate() {
    seo_blackhole_create_table(); // ساخت جدول دیتابیس
}
register_activation_hook(__FILE__, 'seo_blackhole_activate');

// غیرفعال‌سازی افزونه
function seo_blackhole_deactivate() {
    // هیچ عملیاتی برای غیرفعال‌سازی نیاز نیست
}
register_deactivation_hook(__FILE__, 'seo_blackhole_deactivate');

// لود استایل‌ها و اسکریپت‌ها
function seo_blackhole_enqueue_assets() {
    wp_enqueue_style('seo-blackhole-style', SEO_BLACKHOLE_URL . 'assets/css/style.css', [], '1.0');
    wp_enqueue_script('seo-blackhole-script', SEO_BLACKHOLE_URL . 'assets/js/script.js', ['jquery'], '1.0', true);
    wp_localize_script('seo_blackhole-script', 'seoBlackholeAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('seo_blackhole_nonce')
    ]);
}
add_action('admin_enqueue_scripts', 'seo_blackhole_enqueue_assets');

// ثبت منوی ادمین
function seo_blackhole_admin_menu() {
    add_menu_page(
        'Seo BlackHole',
        'Seo BlackHole',
        'manage_options',
        'seo-blackhole',
        'seo_blackhole_admin_page',
        'dashicons-admin-tools',
        6
    );
}
add_action('admin_menu', 'seo_blackhole_admin_menu');