<?php

/**
 * Plugin Name: Fcy Ajax Archive
 * Plugin URI: https://github.com/ibmw
 * Description: This is a plugin that allows us to create an archive page by year and month.
 * Version: 1.0.1
 * Author: Olivier Maillot
 * Author URI: http://www.olivier-maillot.fr
 * License: Free
 */

add_action( 'wp_enqueue_scripts', 'ajax_archive_enqueue_scripts' );
function ajax_archive_enqueue_scripts() {
    if( is_page() ) {
		wp_enqueue_style( 'ajax-archivejs', plugins_url( '/ajax-archive.css', __FILE__ ) );
	}
	wp_enqueue_script( 'ajax-archivejs', plugins_url( '/ajax-function.js', __FILE__ ), array('jquery'), '1.0', true );
    wp_localize_script('ajax-archivejs', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
}

// list of year
add_action('mya_years_list', 'get_years_list');
function get_years_list()
{
    global $wpdb;
    static $yrs = array();

    $yrs = $wpdb->get_col("
        SELECT DISTINCT YEAR( post_date )
        FROM $wpdb->posts
        WHERE post_status = 'publish'
        ORDER BY post_date ASC
        ");
    // display list
    echo '<ul id="archive_years" class="fade-in animated4">';
    foreach ($yrs as $yr) {
         echo '<li data-year="' . $yr . '">' . $yr . '</li>';
    }
    echo '</ul>';
}

// list of month
add_action( 'wp_ajax_mya_req_month', 'mya_req_month' );
add_action( 'wp_ajax_nopriv_mya_req_month', 'mya_req_month' );
function mya_req_month() {
	global $wpdb;

    $yr = $_POST['yr'];

    // Request
    $mths = $wpdb->get_col("
        SELECT DISTINCT MONTH( post_date )
        FROM $wpdb->posts
        WHERE post_status = 'publish'
        AND post_type = 'post'
        AND YEAR(post_date) = '".$yr."'
        ORDER BY post_date ASC
    ");
    $list_of_months = array('>January', '>February', '>March', '>April', '>May', '>June', '>July', '>August', '>September', '>October', '>November', '>December');
    foreach ($mths as $mth) {
        $list_of_months[$mth-1] = 'class="active"' . $list_of_months[$mth-1];
    }
    echo '<ul id="archive_months">';
    $count = 1;
    foreach ($list_of_months as $list_of_month) {
        echo '<li data-month="' . $count . '" ' . $list_of_month . '</li>';
        $count++;
    }
    echo '</ul>';
    die();
}
// List of article
add_action( 'wp_ajax_mya_req_article', 'mya_req_article' );
add_action( 'wp_ajax_nopriv_mya_req_article', 'mya_req_article' );
function mya_req_article() {
	global $wpdb;

    $yr = $_POST['yr'];
    $mth = $_POST['mth'];

    // Req WP_QUERY
    $qry = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'orderby' => 'post_date',
            'order'   => 'DESC',
            'posts_per_page' => -1,
            'date_query' => array(
                                array(
                                    'year'  => $yr,
                                    'month' => $mth,
                                ),
                            ),
            );

    $article = new WP_Query( $qry );

    while ( $article->have_posts() ) {
	   $article->the_post();

        if(has_post_thumbnail(get_the_ID(), 'full'))
    		{
    		    $image_id = get_post_thumbnail_id(get_the_ID());
    		    $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
    		}
        $categories = get_the_category();
        $category = '';
        if($categories){
	       foreach($categories as $cat) {
		      $category .= $cat->cat_name. ' ';
	       }
        }
        echo '<a href="' . get_permalink() . '">
            <article class="slice" style="background-image: url(' . $image_thumb[0] . ');">
                <div class="table">
                    <div class="cell">
                        <div class="constrain">
                            <span class="kicker">' . $category . '</span>		                                                   <h2 class="slide-title">' . get_the_title() . '</h2>
                        </div>
                    </div>
                </div>
            </article>
        </a>';
    }
    die();
}

//[ajax-archive]
function ajax_archive_func( $atts ){
	do_action('mya_years_list');
    echo '<div id="result_month"></div>';
    echo '<div id="result_article"></div>';
}
add_shortcode( 'ajax-archive', 'ajax_archive_func' );
?>
