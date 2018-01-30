<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
  exit;
require_once($_SERVER['DOCUMENT_ROOT'] . '/dump.php' ); //for debug only
if (!function_exists('chld_thm_cfg_parent_css')):

  function chld_thm_cfg_parent_css() {
    wp_enqueue_style('chld_thm_cfg_parent', trailingslashit(get_template_directory_uri()) . 'style.css', array());
     wp_enqueue_script('favorites', get_stylesheet_directory_uri() . '/js/favorites.js');

  }

endif;
add_action('wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10);

// END ENQUEUE PARENT ACTION

add_action('after_setup_theme', 'woocommerce_support');

// Adding woocommerce in the Theme

function woocommerce_support() {
  add_theme_support('woocommerce');
}

if (!function_exists('movies')) {

// Register Custom Post Type
  function movies() {

    $labels = array(
        'name' => 'movies',
        'singular_name' => 'movie',
        'menu_name' => 'Movies',
        'name_admin_bar' => 'Movies',
        'archives' => 'Movies Archives',
        'attributes' => 'Movies Attributes',
        'parent_item_colon' => 'Parent Item:',
        'all_items' => 'All Items',
        'add_new_item' => 'Add New Item',
        'add_new' => 'Add New movie',
        'new_item' => 'New Item',
        'edit_item' => 'Edit Item',
        'update_item' => 'Update Item',
        'view_item' => 'View Item',
        'view_items' => 'View Items',
        'search_items' => 'Search Item',
        'not_found' => 'Not found',
        'not_found_in_trash' => 'Not found in Trash',
        'featured_image' => 'Featured Image',
        'set_featured_image' => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image' => 'Use as featured image',
        'insert_into_item' => 'Insert into item',
        'uploaded_to_this_item' => 'Uploaded to this item',
        'items_list' => 'Items list',
        'items_list_navigation' => 'Items list navigation',
        'filter_items_list' => 'Filter items list',
    );
    $args = array(
        'label' => 'movie',
        'description' => 'Movies Description',
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'taxonomies' => array('category', 'post_tag'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 2,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    );
    register_post_type('movie', $args);
  }

  add_action('init', 'movies', 0);
}
// Adding Sub Title in Movies posts

function unprefix_subtitle($post) {

  if (!in_array($post->post_type, ['movie'], true)) {
    return;
  }

  $_stitle = sanitize_text_field(get_post_meta($post->ID, '_unprefix_subtitle', true));

  echo '<label for="unprefix_subtitle">' . __('Sub Title: ') . '</label>';
  echo '<input type="text" name="unprefix_subtitle" id="unprefix_subtitle" value="' . $_stitle . '" size="80" spellcheck="true" autocomplete="off" />';
}

function movie_price($post) {

  if (!in_array($post->post_type, ['movie'], true)) {
    return;
  }

  $_stitle = get_post_meta($post->ID, '_movie_price', true);

  echo '<label for="movie_price">' . __('Price: ') . '</label>';
  echo '<input type="text" name="movie_price" id="movie_price" value="' . $_stitle . '" size="20" spellcheck="true" autocomplete="off" />';
}



function unprefix_save_subtitle($post_ID, $post, $update) {

  if (!in_array($post->post_type, ['movie'], true)) {
    return;
  }

  // Prevent to execute twice.
  if (defined('DOING_AJAX') && DOING_AJAX) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // Get the subtitle value from $_POST.
  $_stitle = filter_input(INPUT_POST, 'unprefix_subtitle', FILTER_SANITIZE_STRING);

  if ($update) {
    // Update the post meta.
    update_post_meta($post_ID, '_unprefix_subtitle', sanitize_text_field($_stitle));
  } else if (!empty($_stitle)) {
    // Add unique post meta.
    add_post_meta($post_ID, '_unprefix_subtitle', sanitize_text_field($_stitle), true);
  }
}

function unprefix_save_price($post_ID, $post, $update) {

  if (!in_array($post->post_type, ['movie'], true)) {
    return;
  }

  // Prevent to execute twice.
  if (defined('DOING_AJAX') && DOING_AJAX) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // Get the subtitle value from $_POST.
  $_stitle = filter_input(INPUT_POST, 'movie_price', FILTER_SANITIZE_STRING);

  if ($update) {
    // Update the post meta.
    update_post_meta($post_ID, '_movie_price', sanitize_text_field($_stitle));
  } else if (!empty($_stitle)) {
    // Add unique post meta.
    add_post_meta($post_ID, '_movie_price', sanitize_text_field($_stitle), true);
  }
}
add_action('edit_form_after_title', 'unprefix_subtitle', 20);
add_action('wp_insert_post', 'unprefix_save_subtitle', 20, 3);

add_action('edit_form_after_title', 'movie_price', 20);
add_action('wp_insert_post', 'unprefix_save_price', 20, 3);


/* Add field Skype in page User edit in admin panel */

function true_add_contacts($contactmethods) {

  $contactmethods['skype'] = 'Skype:';
  return $contactmethods;
}

add_filter('user_contactmethods', 'true_add_contacts', 10, 1);

add_action('register_form', 'show_fields');
add_action('register_post', 'check_fields', 10, 3);
add_action('user_register', 'register_fields');

// Add the "Skype" field to the registration form in WordPress 

function show_fields() {
  ?>
  <p>
      <label>Skype:<br/>
      <input id="skype" class="input" type="text" value="" name="skype" /></label>
  </p>

  <?php
}

// Field Skype check function 
function check_fields($login, $email, $errors) {
   
  global $skype;
  if ($_POST['skype'] == '') {
    $errors->add('empty_realname', "ERROR: Skype?");
  } else {
    $skype = $_POST['skype'];
  }

  return $errors;
}

function register_fields($user_id, $password = "", $meta = array()) {
  update_user_meta($user_id, 'skype', $_POST['skype']);
}




//remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );

// Redirect to PayPal
add_filter('woocommerce_add_to_cart_redirect', 'custom_add_to_cart_redirect');

function custom_add_to_cart_redirect()
{
    wc_clear_notices();
    $checkout_url = "/cart";
    return $checkout_url;
}

include('custom_product.php');