<?php

class MSShopFilters {



   /**
    * 
    * constructor
    *
    */
	public function __construct($atts) {	

      $this->render($atts);

   }



   public function render($atts) {

      ?>
      <!-- <select id="type-select">
         <option data-args='{"cat":"54","children":"true","headings":"true"}' selected>Services</option>
         <option data-args='{"cat":"53","children":"true","headings":"true"}'>Products</option>
      </select> -->
      <?php

      // elements





      // child cats
      $parent_term = get_term($atts['cat'], 'product_cat');
      $child_terms = get_term_children($parent_term->term_id, 'product_cat');

      ?>


      <select id="category-filter">

         <?php 

         // all childrens
         $data_args = array(
            'cat' => $atts['cat'],
            'childrens' => true,
            'headings' => true
         );
         
         echo "<option data-args=".json_encode($data_args)." selected>All</option>";
         

         // childrens
         foreach($child_terms as $child_term_id) {

            $child_term = get_term($child_term_id, 'product_cat');

            $data_args = array(
               'cat' => $child_term_id,
               'childrens' => false,
               'headings' => true
            );

            echo "<option data-args=".json_encode($data_args).">".$child_term->name."</option>";

         } ?>

      </select>

      <?php

   }


















}