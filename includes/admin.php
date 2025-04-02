<?php
if (!defined('ABSPATH')) {
    exit;
}

// رندر صفحه ادمین
function seo_blackhole_admin_page() {
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
    <?php
}

// نمایش کلمات کلیدی از دیتابیس
function seo_blackhole_display_keywords() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'seo_blackhole_keywords';
    $keywords = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    if ($keywords) {
        foreach ($keywords as $keyword) {
            $headings = esc_html($keyword['headings'] ?? 'در انتظار');
            $status = $keyword['status'] === 'published' ? 'منتشر شده' : 'در انتظار';
            ?>
            <tr>
                <td><?php echo esc_html($keyword['keyword']); ?></td>
                <td><?php echo $headings; ?></td>
                <td><?php echo esc_html($status); ?></td>
                <td>
                    <button class="button generate-content" data-id="<?php echo $keyword['id']; ?>">تولید محتوا</button>
                    <button class="button publish-content" data-id="<?php echo $keyword['id']; ?>" <?php echo $keyword['status'] === 'published' ? 'disabled' : ''; ?>>انتشار</button>
                </td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="4">هنوز کلمه کلیدی اضافه نشده</td></tr>';
    }
}

// پردازش فرم اضافه کردن کلمات کلیدی
function seo_blackhole_process_keywords() {
    if (isset($_POST['add_keywords']) && check_admin_referer('seo_blackhole_add_keyword', 'seo_blackhole_nonce')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'seo_blackhole_keywords';
        $keywords_input = sanitize_textarea_field($_POST['keywords'] ?? '');
        if ($keywords_input) {
            $keywords = explode("\n", trim($keywords_input));
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                if ($keyword) {
                    $wpdb->insert($table_name, ['keyword' => $keyword, 'status' => 'pending']);
                }
            }
        }
        if (isset($_FILES['keyword_file']) && $_FILES['keyword_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['keyword_file']['tmp_name'];
            $content = file_get_contents($file);
            $keywords = explode("\n", trim($content));
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                if ($keyword) {
                    $wpdb->insert($table_name, ['keyword' => $keyword, 'status' => 'pending']);
                }
            }
        }
        wp_redirect(admin_url('admin.php?page=seo-blackhole'));
        exit;
    }
}
add_action('admin_init', 'seo_blackhole_process_keywords');