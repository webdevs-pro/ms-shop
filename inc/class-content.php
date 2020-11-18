<?php

class MSShopContent {



   /**
    * 
    * constructor
    *
    */
	public function __construct($atts) {	

      // if (wp_doing_ajax()) {
      //    $this->render($args);
      //    error_log('ajax');
      // } else {
      //    echo '<div id="ms_shop_content">';
      //    $this->render($args);
      //    echo '</div>';
      //    error_log('not ajax');
      // }

      $this->render($atts);

   }



   public function render($atts) {

      // echo '<pre>' . print_r($atts, true) . '</pre><br>';

      $parent_term = get_term($atts['cat'], 'product_cat');

      if ($atts['childrens'] == 'true') {
         $child_terms = get_term_children($parent_term->term_id, 'product_cat');
      } else {
         $child_terms[] = $atts['cat'];
      }

      // echo '<pre>' . print_r($child_terms, true) . '</pre><br>';

      foreach($child_terms as $child_term_id) {

         // render cat heading
         if ($atts['headings'] == 'true') {
            $args = array(
               'term_id' => $child_term_id
            );
            $this->render_term_heading($args);
         }

         // render products
         $args = array(
            'term_id' => $child_term_id
         );
         $this->render_term_products($args);

      }

   }




   public function render_term_heading($args) {

      $term_title = get_term($args['term_id'], 'product_cat')->name;
      
      $term_cover_image = get_term_meta($args['term_id'], 'category_cover_image', true);

      ?>
      <div class="ms-products-category-heading"
         style="background-image: url(' <?php echo wp_get_attachment_image_url($term_cover_image , 'full') ?> ')">
         <div class="ms-products-category-title"><?php echo $term_title ?></div>
      </div>
      <?php

   }




   public function render_term_products($args) {

      $query_args = array(
         'numberposts' => -1,
         'post_type' => 'product',
         'orderby' => 'title',
         'order' => 'ASC',
         'tax_query' => array(
            array(
               'taxonomy' => 'product_cat',
               'field'    => 'term_id',
               'terms'    => $args['term_id'],
            ),
         ),
      );
      $products = get_posts($query_args);

      // echo '<pre>' . print_r($products, true) . '</pre><br>';

      if($products) {      

         foreach($products as $product) {
            ?>
            <div class="ms-product product product-id-<?php echo $product->ID ?>">

               <div class="ms-product-image">
                  <img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id($product->ID), 'full') ?>">
               </div>

               <div class="ms-product-info">
                  <h3><?php echo $product->post_title ?></h3>
               </div>

            </div>
            <?php


            // [ID] => 1237
            // [post_author] => 1
            // [post_date] => 2020-11-03 08:33:28
            // [post_date_gmt] => 2020-11-03 06:33:28
            // [post_content] => Lorem impsum dolor sit ametLorem impsum dolor sit ametLorem impsum dolor sit ametLorem impsum dolor sit ametLorem impsum dolor sit ametLorem impsum dolor sit ametLorem impsum dolor sit ametLore
            // [post_title] => test service
            // [post_excerpt] => 
            // [post_status] => publish
            // [comment_status] => open
            // [ping_status] => closed
            // [post_password] => 
            // [post_name] => test-service
            // [to_ping] => 
            // [pinged] => 
            // [post_modified] => 2020-11-04 00:03:56
            // [post_modified_gmt] => 2020-11-03 22:03:56
            // [post_content_filtered] => 
            // [post_parent] => 0
            // [guid] => http://casaya-spa.test/?post_type=product&p=1237
            // [menu_order] => 0
            // [post_type] => product
            // [post_mime_type] => 
            // [comment_count] => 0
            // [filter] => raw
         }

      }

   }


}