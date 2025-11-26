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

function lv_news_cards_shortcode()
{
    // Query lấy 3 bài mới nhất
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post_status'    => 'publish'
    );
    $query = new WP_Query($args);

    ob_start();
?>

    <section class="lv_newsSection_wrapper">
        <div class="lv_newsSection_grid">

            <?php while ($query->have_posts()) : $query->the_post(); ?>

                <div class="lv_newsCard_wrapper">

                    <!-- IMAGE -->
                    <a href="<?php the_permalink(); ?>" class="lv_newsCard_imageBox">
                        <?php if (has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('full', ['class' => 'lv_newsCard_image']); ?>
                        <?php endif; ?>
                    </a>

                    <!-- TITLE -->
                    <h3 class="lv_newsCard_title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                    <!-- BADGE (post category đầu tiên) -->
                    <?php
                    $cats = get_the_category();
                    if ($cats) {
                        $cat_name = $cats[0]->name;
                    } else {
                        $cat_name = "Tin tức";
                    }
                    ?>
                    <span class="lv_newsCard_badge">
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_2043_127)">
                                <path d="M6.79662 1.20654L9.91163 6.63569H3.68161L6.79662 1.20654Z" fill="white" />
                                <path d="M3.68134 13.2714C2.27376 13.2714 1.1327 12.0561 1.1327 10.5569C1.1327 9.05764 2.27376 7.84229 3.68134 7.84229C5.08892 7.84229 6.22999 9.05764 6.22999 10.5569C6.22999 12.0561 5.08892 13.2714 3.68134 13.2714Z" fill="white" />
                                <path d="M11.8936 8.14355H7.36264V12.9695H11.8936V8.14355Z" fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0_2043_127">
                                    <rect width="13.5928" height="14.4777" fill="white" transform="matrix(-1 0 0 1 13.5928 0)" />
                                </clipPath>
                            </defs>
                        </svg>
                        <?= esc_html($cat_name); ?>
                    </span>



                    <!-- DESCRIPTION -->
                    <p class="lv_newsCard_desc">
                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                    </p>

                    <!-- BUTTON -->
                    <a href="<?php the_permalink(); ?>" class="lv_newsCard_more">XEM THÊM >>></a>

                </div>

            <?php endwhile;
            wp_reset_postdata(); ?>

        </div>
    </section>

<?php
    return ob_get_clean();
}
add_shortcode('news_cards', 'lv_news_cards_shortcode');
