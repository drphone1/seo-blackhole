<?php
// جلوگیری از دسترسی مستقیم
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>Seo BlackHole</h1>
    <p>مدیریت کلمات کلیدی و تولید محتوا با هوش مصنوعی</p>

    <!-- فرم اضافه کردن کلمات کلیدی -->
    <form method="post" enctype="multipart/form-data">
        <?php wp_nonce_field('seo_blackhole_add_keyword', 'seo_blackhole_nonce'); ?>
        <h2>اضافه کردن کلمات کلیدی</h2>
        <textarea name="keywords" rows="5" cols="50" placeholder="کلمات کلیدی رو اینجا بنویس (هر خط یه کلمه)"></textarea><br>
        <input type="file" name="keyword_file" accept=".txt,.csv"><br>
        <input type="submit" name="add_keywords" class="button button-primary" value="اضافه کن">
    </form>

    <!-- نمایش جدول کلمات کلیدی -->
    <h2>لیست کلمات کلیدی</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>کلمه کلیدی</th>
                <th>هدینگ‌ها</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php seo_blackhole_display_keywords(); ?>
        </tbody>
    </table>
</div>