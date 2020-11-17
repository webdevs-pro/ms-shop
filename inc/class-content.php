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

      echo '<pre>' . print_r($atts, true) . '</pre><br>';

   }














}