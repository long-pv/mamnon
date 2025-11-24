<?php
define('CHILD_URI', get_stylesheet_directory_uri());
define('CHILD_PATH', get_stylesheet_directory());
define('TEMPLATE_PATH', CHILD_PATH . '/elementor-widgets/template/');
if (!defined('_S_VERSION')) {
    define('_S_VERSION', '1.0.0');
}
if (!defined('WP_MEMORY_LIMIT')) {
    define('WP_MEMORY_LIMIT', '256M');
}
if (!defined('WP_MAX_MEMORY_LIMIT')) {
    define('WP_MAX_MEMORY_LIMIT', '512M');
}

/**
 * Enqueue scripts and styles.
 */
function child_theme_scripts()
{
    // slick
    // wp_enqueue_style('child_theme-style-slick-theme', CHILD_URI . '/assets/inc/slick/slick-theme.css', array(), _S_VERSION);
    // wp_enqueue_style('child_theme-style-slick', CHILD_URI . '/assets/inc/slick/slick.css', array(), _S_VERSION);
    // wp_enqueue_script('child_theme-script-slick', CHILD_URI . '/assets/inc/slick/slick.min.js', array('jquery'), _S_VERSION, true);

    // add custom main css/js
    $main_css_file_path = CHILD_PATH . '/assets/css/main.css';
    $main_js_file_path = CHILD_PATH . '/assets/js/main.js';
    $ver_main_css = file_exists($main_css_file_path) ? filemtime($main_css_file_path) : _S_VERSION;
    $ver_main_js = file_exists($main_js_file_path) ? filemtime($main_js_file_path) : _S_VERSION;
    wp_enqueue_style('child_theme-style-main', CHILD_URI . '/assets/css/main.css', array(), $ver_main_css);
    wp_enqueue_script('child_theme-script-main', CHILD_URI . '/assets/js/main.js', array('jquery'), $ver_main_js, true);

    // ajax admin
    wp_localize_script('child_theme-script-main', 'custom_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'child_theme_scripts');

// The function "write_log" is used to write debug logs to a file in PHP.
// function write_log($log = null, $title = 'Debug')
// {
//     if ($log) {
//         if (is_array($log) || is_object($log)) {
//             $log = print_r($log, true);
//         }

//         $timestamp = date('Y-m-d H:i:s');
//         $text = '[' . $timestamp . '] : ' . $title . ' - Log: ' . $log . "\n";
//         $log_file = WP_CONTENT_DIR . '/debug.log';
//         $file_handle = fopen($log_file, 'a');
//         fwrite($file_handle, $text);
//         fclose($file_handle);
//     }
// }

// Tạo menu theme settings chung
// Setup theme setting page
// if (function_exists('acf_add_options_page')) {
//     // Trang cài đặt chính
//     acf_add_options_page(array(
//         'page_title' => 'Theme Settings',
//         'menu_title' => 'Theme Settings',
//         'menu_slug'  => 'theme-settings',
//         'capability' => 'edit_posts',
//         'redirect'   => false,
//         'position'   => 80
//     ));
// }
// end

// stop upgrading ACF pro plugin
// add_filter('site_transient_update_plugins', 'disable_plugins_update');
// function disable_plugins_update($value)
// {
//     // disable acf pro
//     if (isset($value->response['advanced-custom-fields-pro/acf.php'])) {
//         unset($value->response['advanced-custom-fields-pro/acf.php']);
//     }
//     return $value;
// }

// load widgets library by elementor
function load_custom_widgets()
{
    require CHILD_PATH . '/elementor-widgets/index.php';
}
// add_action('elementor/init', 'load_custom_widgets');
// end

// tối đa revision
// add_filter('wp_revisions_to_keep', function ($num, $post) {
//     return 5;
// }, 10, 2);

add_shortcode('bcn_display', function () {
    if (function_exists('bcn_display')) {
        ob_start();
        bcn_display();
        return ob_get_clean();
    }
});

// Thêm skin custom cho Elementor Archive Posts
add_action('elementor/widget/archive-posts/skins_init', function ($widget) {

    class Skin_Custom_Archive extends \Elementor\Skin_Base
    {
        public function get_id()
        {
            return 'custom_archive';
        }

        public function get_title()
        {
            return __('Custom Archive Layout', 'textdomain');
        }

        public function render()
        {
            global $wp_query;

            if (!$wp_query->have_posts()) {
                echo '<p>No posts found.</p>';
                return;
            }

            // Chỉ hiển thị container khi có bài viết
            echo '<section class="lv_postList" aria-label="Danh sách bài viết"><div class="lv_postList__container">';

            while ($wp_query->have_posts()) {
                $wp_query->the_post();

                // Lấy sẵn dữ liệu cần kiểm tra
                $has_thumb = has_post_thumbnail();
                $title     = get_the_title();
                $author    = get_the_author();
                $date_iso  = get_the_date('c');
                $date_hmn  = get_the_date();
                $excerpt   = get_the_excerpt();
?>

                <article class="lv_postList__item">
                    <?php if ($has_thumb) { ?>
                        <a class="lv_postList__media" href="<?php the_permalink(); ?>" aria-label="<?php echo $title; ?>">
                            <?php
                            // Ảnh từ hàm WP để có đầy đủ HTML (srcset/sizes…)
                            the_post_thumbnail('full', array('class' => 'lv_postList__thumb'));
                            ?>
                        </a>
                    <?php } ?>

                    <div class="lv_postList__content">
                        <?php if ($author || $date_hmn) { ?>
                            <div class="lv_postList__meta">
                                <?php if ($author) { ?>
                                    <span class="lv_postList__author"><?php the_author(); ?></span>
                                <?php } ?>
                                <?php if ($author && $date_hmn) { ?>
                                    <span class="lv_postList__dot" aria-hidden="true">•</span>
                                <?php } ?>
                                <?php if ($date_hmn) { ?>
                                    <time class="lv_postList__date" datetime="<?php echo $date_iso; ?>"><?php echo $date_hmn; ?></time>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if ($title) { ?>
                            <a class="lv_postList__titleLink" href="<?php the_permalink(); ?>">
                                <h3 class="lv_postList__title">
                                    <?php the_title(); ?>
                                </h3>
                            </a>

                        <?php } ?>

                        <?php if ($excerpt) { ?>
                            <div class="lv_postList__excerpt"><?php echo $excerpt; ?></div>
                        <?php } ?>

                        <a class="lv_postList__cta" href="<?php the_permalink(); ?>">
                            <?php _e('Xem thêm'); ?>

                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 12.6665L9.55374 8.52047C9.81054 8.22088 9.81054 7.7788 9.55374 7.4792L6 3.33317" stroke="#1263D3" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </a>
                    </div>
                </article>
<?php }

            echo '</div>'; // .lv_postList__container

            // ===== Pagination (có kiểm tra trước khi in HTML) =====
            $max_pages = (int) $wp_query->max_num_pages;
            if ($max_pages > 1) {
                $links = paginate_links(array(
                    'total'     => $max_pages,
                    'current'   => max(1, get_query_var('paged')),
                    'end_size'  => 2,
                    'mid_size'  => 1,
                    'prev_text' => __('Prev', 'basetheme'),
                    'next_text' => __('Next', 'basetheme'),
                ));

                if ($links) {
                    echo '<div class="pagination">';
                    echo $links;
                    echo '</div>';
                }
            }

            echo '</section>';

            wp_reset_postdata();
        }
    }

    // Đăng ký skin custom vào Archive Posts widget
    $widget->add_skin(new Skin_Custom_Archive($widget));
});
