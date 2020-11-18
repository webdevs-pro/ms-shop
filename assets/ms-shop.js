jQuery(document).ready(function($) {



   $('#type-select').on('change', function(e) {
      e.preventDefault();

      var args = JSON.parse($(this).find(':selected').attr('data-args'));
      console.log(args);
      ms_shop_ajax_call(args);

   });


   $('#category-select li').on('click', function(e) {
      e.preventDefault();

      var args = JSON.parse($(this).attr('data-args'));
      console.log(args);
      ms_shop_ajax_call(args);

   });

   $('#category-filter').on('change', function(e) {
      e.preventDefault();

      var args = JSON.parse($(this).find(':selected').attr('data-args'));
      console.log(args);
      ms_shop_ajax_call(args);

   });


   function ms_shop_ajax_call(args) {
     
      $.ajax({
         url  : msShopAjax.url,
         type: 'POST',
         data: {
            action: 'ms_shop_page_content',
            args: args,
         },
         beforeSend : function( d ) {
            // console.log( 'Before send', d );
         }
      })
      .done( function( response, textStatus, jqXHR ) {
      //   console.log( 'AJAX done', textStatus, jqXHR, jqXHR.getAllResponseHeaders() );
         $('#ms-shop-content').html(response);
         // console.log(response);
      })
      .fail( function( jqXHR, textStatus, errorThrown ) {
         console.log( 'AJAX failed', jqXHR.getAllResponseHeaders(), textStatus, errorThrown );
      })
      .then( function( jqXHR, textStatus, errorThrown ) {
      //   console.log( 'AJAX after finished', jqXHR, textStatus, errorThrown );
      } );

   }

   
});
