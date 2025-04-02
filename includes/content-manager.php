<?php
if (!defined('ABSPATH')) {
    exit;
}

// درج پست جدید
function seo_blackhole_insert_post($title, $content, $meta = []) {
    $post_id = wp_insert_post([
        'post_title'    => wp_strip_all_tags($title),
        'post_content'  => wp_kses_post($content),
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id(),
        'post_type'     => 'post',
    ]);

    if ($post_id && !is_wp_error($post_id)) {
        foreach ($meta as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
        return $post_id;
    }
    return false;
}

// بکاپ پست‌ها
function seo_blackhole_backup_posts() {
    $posts = get_posts(['numberposts' => -1]);
    $backup = [];
    foreach ($posts as $post) {
        $backup[] = [
            'title'   => $post->post_title,
            'content' => $post->post_content,
            'meta'    => get_post_meta($post->ID),
        ];
    }
    file_put_contents(SEO_BLACKHOLE_PATH . 'backup/posts-backup.json', json_encode($backup));
}

// ریستور پست‌ها از فایل بکاپ
function seo_blackhole_restore_posts() {
    $backup_file = SEO_BLACKHOLE_PATH . 'backup/posts-backup.json';
    if (file_exists($backup_file)) {
        $backup = json_decode(file_get_contents($backup_file), true);
        foreach ($backup as $post_data) {
            seo_blackhole_insert_post($post_data['title'], $post_data['content'], $post_data['meta']);
        }
    }
}