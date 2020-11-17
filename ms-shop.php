<?php
/**
* Plugin Name: MS Shop Shortcodes
* Plugin URI: https://github.com/webdevs-pro/ms-shop
* Description: Custom shortcodes for shop page.
* Version: 1.0
* Author: Magnific Soft
* Author URI: https://magnificsoft.com/
*/




// include files
require_once dirname( __FILE__ ) . '/inc/class-filters.php';
require_once dirname( __FILE__ ) . '/inc/class-content.php';
require_once dirname( __FILE__ ) . '/inc/class-nav.php';




// enqueue scripts and styles
add_action('wp_enqueue_scripts', function() {
   wp_register_style( 'ms-shop-styles', plugins_url('/assets/ms-shop.css', __FILE__), false, '1.0.0', 'all');
   wp_enqueue_style( 'ms-shop-styles' );
   wp_enqueue_script( 'ms-shop-script', plugins_url('/assets/ms-shop.js', __FILE__), array( 'jquery' ), '1.0.0' );
   wp_localize_script( 
      'ms-shop-script', 
      'msShopAjax', 
      array(
          'url'   => admin_url( 'admin-ajax.php' ),
      )
   );
});




// add content shortcode
add_shortcode('ms-shop-content', function($atts) {

   ob_start();

      echo '<div id="ms-shop-content" data-args="' . json_encode($atts) . '">';
      new MSShopContent($atts);
      echo '</div>';
      
   $content = ob_get_clean();
   return $content;

});



// add filters shortcode
add_shortcode('ms-shop-filters', function($atts) {
   ob_start();

      echo '<div id="ms-shop-filters">';
      new MSShopFilters($atts);
      echo '</div>';

   $content = ob_get_clean();
   return $content;
});




// add nav shortcode
add_shortcode('ms-shop-nav', function($atts) {
   ob_start();

      echo '<div id="ms-shop-nav">';
      new MSShopNav($atts);
      echo '</div>';

   $content = ob_get_clean();
   return $content;
});



add_action( 'wp_ajax_ms_shop_page_content', 'ms_shop_page_ajax_content' ); 
add_action( 'wp_ajax_nopriv_ms_shop_page_content',  'ms_shop_page_ajax_content' );
function ms_shop_page_ajax_content() {

   $args = $_POST['args'];

   if(is_array($args)) {
      new MSShopContent($args);
   }

   die();

}