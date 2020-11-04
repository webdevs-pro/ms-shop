<?php
/**
* Plugin Name: MS Shop
* Plugin URI: https://github.com/webdevs-pro/ms-shop
* Description: Custom shortcode for shop page.
* Version: 1.0
* Author: Magnific Soft
* Author URI: https://magnificsoft.com/
*/


// load assets
add_action('wp_enqueue_scripts', 'ms_shop_enqueue_scripts');
function ms_shop_enqueue_scripts() {
   wp_register_style( 'ms-shop-styles', plugins_url('/assets/ms-shop.css', __FILE__), false, '1.0.0', 'all');
   wp_enqueue_style( 'ms-shop-styles' );
   wp_enqueue_script( 'ms-shop-script', plugins_url('/assets/ms-shop.js', __FILE__), array( 'jquery' ) );
}


function ms_render_shop( $atts ) {

   $type = $atts['type'];

   $parent_cat = get_term_by('slug', $atts['cat'], 'product_cat');

   $child_cats = get_term_children($parent_cat->term_id, 'product_cat');

   $html = '';

   foreach($child_cats as $cat) {

      $cat_title = get_term($cat, 'product_cat')->name;

      $cat_cover_image = get_term_meta($cat, 'category_cover_image', true);

      $html .= '
         <div class="ms-products-category">
            <div class="ms-products-category-heading" style="background-image: url(' . wp_get_attachment_image_url($cat_cover_image , 'full') . ')">
               <div class="ms-products-category-heading">
                  ' . $cat_title . '
               </div>
            </div>
      ';


      $args = array(
         'numberposts' => -1,
         'post_type' => 'product',
         // 'orderby' => 'date',
         // 'order' => 'DESC',
         'tax_query' => array(
            'relation' => 'AND',
            array(
               'taxonomy' => 'product_type',
               'field'    => 'slug',
               'terms'    => $type,
            ),
            array(
               'taxonomy' => 'product_cat',
               'field'    => 'term_id',
               'terms'    => $cat,
            ),
         ),
      );
      $products = get_posts($args);

      if($products) {

         $html .= '
            <div class="ms-products">
         
         ';        

         foreach($products as $product) {

            $html .= '
               <div class="ms-product product">
            


               </div>
            ';


         }

         $html .= '</div> <!-- ms-products -->';        

      }

      $html .= '</div> <!-- ms-products-category -->';

   }




   return $html;

}
add_shortcode( 'ms-shop', 'ms_render_shop' );

