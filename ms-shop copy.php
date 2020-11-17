<?php
/**
* Plugin Name: MS Shop
* Plugin URI: https://github.com/webdevs-pro/ms-shop
* Description: Custom shortcode for shop page.
* Version: 1.0
* Author: Magnific Soft
* Author URI: https://magnificsoft.com/
*/



class MS_Shop_Page {

   public $html;

   /**
    * 
    * constructor
    *
    */
	public function __construct() {	

      add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));



      $this->html = '';



      if (wp_doing_ajax()) {
         error_log('ajax');
      } else {
         $this->render_shortcode();
         error_log('not ajax');
      }
   }




   /**
    * 
    * enqueue scripts
    *
    */
   public function enqueue_scripts() {
      wp_register_style( 'ms-shop-styles', plugins_url('/assets/ms-shop.css', __FILE__), false, '1.0.0', 'all');
      wp_enqueue_style( 'ms-shop-styles' );
      wp_enqueue_script( 'ms-shop-script', plugins_url('/assets/ms-shop.js', __FILE__), array( 'jquery' ) );
      wp_localize_script( 
         'ms-shop-script', 
         'msShopAjax', 
         array(
             'url'   => admin_url( 'admin-ajax.php' ),
         )
     );
   }




   /**
    * 
    * ajax response
    *
    */
   public function ms_shop_page_ajax_query() {

      $args = $_POST['args'];

      if(is_array($args)) {
         $this->render_content($args);
      }

      die();
        
   }
   



   /**
    * 
    * add html
    *
    */
   public function add_html($html) {

      $this->html .= $html;
      
   }
   
   


   /**
    * 
    * render shortcode
    *
    * atts: type [booking, simple]
    *
    */
   public function render_shortcode($atts) {

      // TODO
      // default params


      $this->render_header($atts);

      $this->render_content();

      $this->render_footer();
      
      return $this->html;
   }
   


   /**
    * 
    * render header
    *
    */
    public function render_header($atts) {

      $this->add_html('<div id="ms_shop" data-settings=' . json_encode($atts) . '>'); // main container 

         $this->add_html('<div id="ms_shop_header">'); // header 

            $this->add_html('<div id="button" class="button">test</div>');

            $this->add_html('
               <select id="categories-select">
                  <option value="" data-cat="54" data-type="booking" selected>Services</option>
                  <option value="" data-cat="53" data-type="simple">Products</option>
               </select>');

         $this->add_html('<div>'); // header 

         $this->add_html('<div id="ms_shop_content">'); // content 




   }




   /**
    * 
    * render content
    *
    */
   public function render_content($atts) {

      // render parent category
      if(($atts['cat'] == '54' && $atts['type'] == 'booking') || ($atts['cat'] == '53' && $atts['type'] == 'simple')) {

         $parent_cat = get_term($atts['cat'], 'product_cat');
         $child_cats = get_term_children($parent_cat->term_id, 'product_cat');

         foreach($child_cats as $cat) {

            $cat_title = get_term($cat, 'product_cat')->name;
      
            $cat_cover_image = get_term_meta($cat, 'category_cover_image', true);
      
            ?>
            <div class="ms-products-category-heading"
               style="background-image: url(' <?php echo wp_get_attachment_image_url($cat_cover_image , 'full') ?> ')">
               <div class="ms-products-category-title"><?php echo $cat_title ?></div>
            </div>
            <?php
      
      
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
                     'terms'    => $atts['type'],
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

   }




   /**
    * 
    * render content
    *
    */
    public function render_footer() {



         $this->add_html('<div>'); // content 
      
      $this->add_html('<div>'); // main container 

   }

}

new MS_Shop_Page();      

add_action( 'wp_ajax_ms_shop_page_query', array( $this, 'ms_shop_page_ajax_query' ) ); 
add_action( 'wp_ajax_nopriv_ms_shop_page_query', array( $this, 'ms_shop_page_ajax_query' ) );
add_shortcode('ms-shop-page', array($this, 'render_shortcode'));