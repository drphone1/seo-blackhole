<?php
if (!defined('ABSPATH')) {
    exit;
}

// ارسال درخواست به سرور اوبونتو
function seo_blackhole_generate_content($keyword) {
    $url = 'http://your-ubuntu-ip:5000/generate'; // آدرس سرور اوبونتو
    $response = wp_remote_post($url, [
        'body' => json_encode(['keyword' => $keyword]),
        'headers' => ['Content-Type' => 'application/json'],
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('api_error', 'خطا در ارتباط با سرور');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['headings']) && isset($data['content'])) {
        return $data;
    }
    return new WP_Error('invalid_response', 'پاسخ نامعتبر از سرور');
}