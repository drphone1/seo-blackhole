<?php
if (!defined('ABSPATH')) {
    exit;
}

// ساخت جدول دیتابیس
function seo_blackhole_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'seo_blackhole_keywords';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        keyword varchar(255) NOT NULL,
        headings text DEFAULT NULL,
        content longtext DEFAULT NULL,
        status varchar(20) DEFAULT 'pending',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY keyword (keyword)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
register_activation_hook(SEO_BLACKHOLE_PATH . 'seo-blackhole.php', 'seo_blackhole_create_table');

// افزودن کلمه کلیدی
function seo_blackhole_add_keyword($keyword) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'seo_blackhole_keywords';
    return $wpdb->insert(
        $table_name,
        [
            'keyword' => sanitize_text_field($keyword),
            'status' => 'pending'
        ],
        ['%s', '%s']
    );
}

// دریافت کلمات کلیدی
function seo_blackhole_get_keywords($status = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'seo_blackhole_keywords';
    $query = "SELECT * FROM $table_name";
    if ($status) {
        $query .= $wpdb->prepare(" WHERE status = %s", $status);
    }
    return $wpdb->get_results($query, ARRAY_A);
}

// به‌روزرسانی وضعیت کلمه کلیدی
function seo_blackhole_update_keyword($id, $data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'seo_blackhole_keywords';
    $allowed_fields = ['headings', 'content', 'status'];
    $sanitized_data = [];

    foreach ($data as $key => $value) {
        if (in_array($key, $allowed_fields)) {
            $sanitized_data[$key] = is_string($value) ? sanitize_text_field($value) : $value;
        }
    }

    return $wpdb->update(
        $table_name,
        $sanitized_data,
        ['id' => $id],
        array_fill(0, count($sanitized_data), '%s'),
        ['%d']
    );
}