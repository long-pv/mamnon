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
 * get currernt lang.
 */
define('LANG', function_exists('pll_current_language') ? pll_current_language('slug') : 'vi');

/**
 * Enqueue scripts and styles.
 */
function child_theme_scripts()
{
    // matchHeight
    wp_enqueue_script('child_theme-script-matchHeight', CHILD_URI . '/assets/inc/matchHeight/jquery.matchHeight.js', array('jquery'), _S_VERSION, true);

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
if (function_exists('acf_add_options_page')) {
    // Trang cài đặt chính
    acf_add_options_page(array(
        'page_title' => 'Theme Settings',
        'menu_title' => 'Theme Settings',
        'menu_slug'  => 'theme-settings',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'position'   => 80
    ));
}
// end

// load widgets library by elementor
function load_custom_widgets()
{
    require CHILD_PATH . '/elementor-widgets/index.php';
}
// add_action('elementor/init', 'load_custom_widgets');
// end

function lv_news_cards_shortcode()
{
    $news_id = get_field('news_' . LANG, 'option') ?? '';

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
                    <h3 class="lv_newsCard_title" data-mh='lv_newsCard_title'>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                    <!-- BADGE (post category đầu tiên) -->
                    <?php
                    $cats = get_the_category();
                    if (! empty($cats)) :
                    ?>
                        <span class="lv_newsCard_badge <?php echo ($news_id == $cats[0]->term_id) ? 'news' : ''; ?>">
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
                            <?php
                            echo $cats[0]->name;
                            ?>
                        </span>
                    <?php endif; ?>

                    <!-- DESCRIPTION -->
                    <div class="lv_newsCard_desc" data-mh="lv_newsCard_desc">
                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                    </div>

                    <!-- BUTTON -->
                    <a href="<?php the_permalink(); ?>" class="lv_newsCard_more">
                        <?php echo LANG == 'vi' ? 'XEM THÊM' : 'SEE MORE'; ?> >>>
                    </a>
                </div>

            <?php endwhile;
            wp_reset_postdata(); ?>

        </div>
    </section>

<?php
    return ob_get_clean();
}
add_shortcode('news_cards', 'lv_news_cards_shortcode');

function display_latest_posts_shortcode()
{
    ob_start(); // Bắt đầu buổi ghi đệm HTML
    $news_id = get_field('news_' . LANG, 'option') ?? '';

    // Lấy 3 bài viết mới nhất
    $args = array(
        'post_type' => 'post', // Lấy bài viết
        'posts_per_page' => 3, // Lấy 3 bài viết
        'orderby' => 'date', // Sắp xếp theo ngày đăng
        'order' => 'DESC', // Sắp xếp giảm dần theo ngày đăng
        'cat'  => $news_id,
    );

    $latest_posts = new WP_Query($args);

    if ($latest_posts->have_posts()) :
        echo '<div class="lv_news_container">';
        echo '<div class="lv_news_grid">';

        // Hiển thị bài viết lớn đầu tiên
        $first_post = $latest_posts->posts[0];
        echo '<div class="lv_news_bigItem">';
        echo '<a href="' . get_permalink($first_post->ID) . '" class="lv_news_bigThumbWrapper">';
        echo '<img src="' . get_the_post_thumbnail_url($first_post->ID, 'full') . '" alt="' . get_the_title($first_post->ID) . '" />';
        echo '</a>';
        echo '<div class="lv_news_bigContent">';
        echo '<div class="lv_news_bigHeading">';
        echo '<a href="' . get_permalink($first_post->ID) . '">' . get_the_title($first_post->ID) . '</a>';
        echo '</div>';
        echo '<div class="lv_news_bigExcerpt">' . wp_trim_words($first_post->post_content, 20) . '</div>';
        echo '<div>';
        echo '<a href="' . get_permalink($first_post->ID) . '" class="lv_news_bigReadmore">' . (LANG == 'vi' ? 'XEM CHI TIẾT' : 'VIEW DETAILS') . ' >>></a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // Hiển thị các bài viết nhỏ
        echo '<div class="lv_news_smallList">';
        for ($i = 1; $i < count($latest_posts->posts); $i++) {
            $post = $latest_posts->posts[$i];
            echo '<div class="lv_news_smallItem">';
            echo '<a href="' . get_permalink($post->ID) . '" class="lv_news_smallThumbWrapper">';
            echo '<img src="' . get_the_post_thumbnail_url($post->ID, 'thumbnail') . '" alt="ảnh nhỏ ' . ($i + 1) . '" />';
            echo '</a>';
            echo '<div class="lv_news_smallContent">';
            echo '<div class="lv_news_smallHeading">';
            echo '<a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a>';
            echo '</div>';
            echo '<div class="lv_news_smallExcerpt">' . wp_trim_words($post->post_content, 20) . '</div>';
            echo '<a href="' . get_permalink($post->ID) . '" class="lv_news_smallReadmore">' . (LANG == 'vi' ? 'XEM THÊM' : 'SEE MORE') . ' >>></a>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>'; // Kết thúc lv_news_smallList

        echo '</div>'; // Kết thúc lv_news_grid
        echo '</div>'; // Kết thúc lv_news_container
    endif;

    wp_reset_postdata(); // Reset lại dữ liệu post query

    return ob_get_clean(); // Kết thúc ghi đệm và trả về kết quả
}
add_shortcode('latest_posts', 'display_latest_posts_shortcode');

function lv_event_cards_shortcode()
{
    // Query lấy 3 event mới nhất
    $events_id = get_field('events_' . LANG, 'option') ?? '';

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'cat'            => $events_id,
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
                    <h3 class="lv_newsCard_title" data-mh="lv_newsCard_title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                    <!-- BADGE (taxonomy: category đầu tiên nếu có) -->
                    <?php
                    $cats = get_the_category();
                    if (! empty($cats)) :
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
                            <?php
                            echo $cats[0]->name;
                            ?>
                        </span>
                    <?php endif; ?>

                    <!-- DESCRIPTION -->
                    <div class="lv_newsCard_desc" data-mh="lv_newsCard_desc">
                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                    </div>

                    <!-- BUTTON -->
                    <a href="<?php the_permalink(); ?>" class="lv_newsCard_more">
                        <?php echo LANG == 'vi' ? 'XEM THÊM' : 'SEE MORE'; ?> >>>
                    </a>

                </div>

            <?php endwhile;
            wp_reset_postdata(); ?>

        </div>
    </section>

<?php
    return ob_get_clean();
}
add_shortcode('event_cards', 'lv_event_cards_shortcode');


function lv_event_cards_paging_shortcode()
{
    // Lấy trang hiện tại
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $events_id = get_field('events_' . LANG, 'option') ?? '';

    // Query event — 12 bài
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'cat'            => $events_id,
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
                    <h3 class="lv_newsCard_title" data-mh="lv_newsCard_title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                    <!-- BADGE -->
                    <?php
                    $cats = get_the_category();
                    if (! empty($cats)) :
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

                            <?php
                            echo $cats[0]->name;
                            ?>
                        </span>
                    <?php endif; ?>

                    <!-- DESCRIPTION -->
                    <div class="lv_newsCard_desc" data-mh="lv_newsCard_desc">
                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                    </div>

                    <!-- BUTTON -->
                    <a href="<?php the_permalink(); ?>" class="lv_newsCard_more"><?php echo LANG == 'vi' ? 'XEM THÊM' : 'SEE MORE'; ?> >>></a>

                </div>

            <?php endwhile;
            wp_reset_postdata(); ?>

        </div>

        <!-- PAGINATION -->
        <div class="pagination_custom">
            <?php
            echo paginate_links(array(
                'total'     => $query->max_num_pages,
                'current'   => $paged,
                'end_size' => 2,
                'mid_size' => 1,
                'prev_text' => '',
                'next_text' => '',
            ));
            ?>
        </div>

    </section>

<?php
    return ob_get_clean();
}
add_shortcode('event_cards_paging', 'lv_event_cards_paging_shortcode');

function lv_post_cards_paging_shortcode()
{
    // Lấy trang hiện tại
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $news_id = get_field('news_' . LANG, 'option') ?? '';

    // Query post
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'cat'            => $news_id,
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
                    <h3 class="lv_newsCard_title" data-mh="lv_newsCard_title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                    <!-- BADGE -->
                    <?php
                    $cats = get_the_category();
                    if (! empty($cats)) :
                    ?>
                        <span class="lv_newsCard_badge news">
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
                            <?php
                            echo $cats[0]->name;
                            ?>
                        </span>
                    <?php endif; ?>

                    <!-- DESCRIPTION -->
                    <div class="lv_newsCard_desc" data-mh="lv_newsCard_desc">
                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                    </div>

                    <!-- BUTTON -->
                    <a href="<?php the_permalink(); ?>" class="lv_newsCard_more"><?php echo LANG == 'vi' ? 'XEM THÊM' : 'SEE MORE'; ?> >>></a>

                </div>

            <?php endwhile;
            wp_reset_postdata(); ?>

        </div>

        <!-- PAGINATION -->
        <div class="pagination_custom">
            <?php
            echo paginate_links(array(
                'total'     => $query->max_num_pages,
                'current'   => $paged,
                'end_size'  => 2,
                'mid_size'  => 1,
                'prev_text' => '',
                'next_text' => '',
            ));
            ?>
        </div>

    </section>

<?php
    return ob_get_clean();
}
add_shortcode('post_cards_paging', 'lv_post_cards_paging_shortcode');

// Thêm field URL icon vào admin menu
add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item) {
    $icon = get_post_meta($item_id, '_menu_item_icon', true);
?>
    <p class="description description-wide">
        <label>
            Icon URL:<br>
            <input type="text"
                name="menu-item-icon[<?php echo $item_id; ?>]"
                value="<?php echo esc_attr($icon); ?>"
                class="widefat code edit-menu-item-icon"
                placeholder="https://domain.com/icon.png" />
        </label>
    </p>
    <?php
}, 10, 2);

// Lưu URL icon
add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id) {
    if (isset($_POST['menu-item-icon'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_icon', esc_url_raw($_POST['menu-item-icon'][$menu_item_db_id]));
    } else {
        delete_post_meta($menu_item_db_id, '_menu_item_icon');
    }
}, 10, 2);

// Hiển thị icon ngoài frontend
add_filter('walker_nav_menu_start_el', function ($item_output, $item) {
    $icon = get_post_meta($item->ID, '_menu_item_icon', true);

    if ($icon && !empty($item->url)) {
        $icon_html = sprintf(
            '<a href="%s" class="menu-icon-link" style="padding: 0px; margin:0px;"><img src="%s" alt="" class="menu-icon" /></a>',
            esc_url($item->url),
            esc_url($icon)
        );

        // Icon link đứng TRƯỚC link menu gốc
        $item_output = $icon_html . $item_output;
    }
    return $item_output;
}, 10, 2);

// SHORTCODE: [news_list]
function lv_newsList_shortcode()
{
    $query = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'post_status'    => 'publish'
    ));

    ob_start();

    if ($query->have_posts()) {
        echo '<div class="lv_newsList_wrapper">';

        while ($query->have_posts()) {
            $query->the_post();

            $img = get_the_post_thumbnail_url(get_the_ID(), 'medium');
    ?>
            <div class="lv_newsList_item">

                <div class="lv_newsList_imgBox">
                    <a href="<?php the_permalink(); ?>">
                        <?php if ($img): ?>
                            <img class="lv_newsList_img" src="<?php echo $img; ?>" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                    </a>
                </div>

                <div class="lv_newsList_content">
                    <div class="lv_newsList_titleBox">
                        <a href="<?php the_permalink(); ?>">
                            <h3 class="lv_newsList_title"><?php the_title(); ?></h3>
                        </a>
                    </div>
                </div>

            </div>
<?php
        }

        echo '</div>';
        wp_reset_postdata();
    }

    return ob_get_clean();
}
add_shortcode('news_list', 'lv_newsList_shortcode');
