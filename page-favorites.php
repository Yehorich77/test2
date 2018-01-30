<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
get_header();
?>

<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php
            wp_enqueue_script('favorites', get_stylesheet_directory_uri() . '/js/favorites.js');


            $aID = [];
            foreach ($_COOKIE as $key => $id) {
              if (strstr($key, 'favorite-')) {
                $aID[] = $id;
              }
            }
            //obj_dump($aID);
            if (!empty($aID)) {
              $posts = get_posts(array(
                  'include' => $aID,
                  'post_type' => 'movie'
                ));
              foreach ($posts as $post) {
                echo '<p><a href="' . get_post_permalink($post->ID) . '">' . $post->post_title;
                echo '  -  Price: ' . get_post_meta($post->ID, '_movie_price', true) . get_woocommerce_currency_symbol() . '</a> <a href="" class="remove_from_favorites" data-id="'. $post->ID .'"><button> Remove from favorites </button></a>  </p>';
                
              }
            }
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
