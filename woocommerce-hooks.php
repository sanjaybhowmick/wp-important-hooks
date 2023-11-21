<?php
// 1. Enable WooCommerce in existing WordPress theme
function mytheme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' ); 

// 2. Enable product gallery features
function mytheme_add_woocommerce_gallery() {
  add_theme_support( 'wc-product-gallery-zoom' );
  add_theme_support( 'wc-product-gallery-lightbox' );
  add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_gallery' );

// 3. Change number of products per page
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );
unction new_loop_shop_per_page( $cols ) {
  $cols = 12; // 12 products per page
  return $cols;
}

// 4. Change number or products per row to 3
 
add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
  function loop_columns() {
    return 3; // 3 products per row
  }
} 

// 5. Convert all checkout fields to uppercase
add_filter('woocommerce_checkout_posted_data', 'aq_custom_woocommerce_checkout_posted_data');
function aq_custom_woocommerce_checkout_posted_data($data){
if($data['billing_first_name']){
$data['billing_first_name'] = strtoupper($data['billing_first_name']);
}

if($data['billing_last_name']){
$data['billing_last_name'] = strtoupper($data['billing_last_name']);
}

if ($data['billing_company']) {
$data['billing_company'] = strtoupper($data['billing_company']); 
}

if ($data['billing_address_1']) {
$data['billing_address_1'] = strtoupper($data['billing_address_1']); 
}

if ($data['billing_address_2']) {
$data['billing_address_2'] = strtoupper($data['billing_address_2']); 
}

if ($data['billing_city']) {
$data['billing_city'] = strtoupper($data['billing_city']); 
}

if ($data['billing_postcode']) {
$data['billing_postcode'] = strtoupper($data['billing_postcode']); 
}

if ($data['billing_country']) {
$data['billing_country'] = strtoupper($data['billing_country']); 
}

if ($data['billing_state']) {
$data['billing_state'] = strtoupper($data['billing_state']); 
}

if ($data['billing_email']) {
$data['billing_email'] = strtoupper($data['billing_email']); 
}

if ($data['billing_phone']) {
$data['billing_phone'] = strtoupper($data['billing_phone']); 
}

if ($data['shipping_first_name']) {
$data['shipping_first_name'] = strtoupper($data['shipping_first_name']); 
}

if ($data['shipping_last_name']) {
$data['shipping_last_name'] = strtoupper($data['shipping_last_name']); 
}

if ($data['shipping_company']) {
$data['shipping_company'] = strtoupper($data['shipping_company']); 
}

if ($data['shipping_address_1']) {
$data['shipping_address_1'] = strtoupper($data['shipping_address_1']); 
}

if ($data['shipping_address_2']) {
$data['shipping_address_2'] = strtoupper($data['shipping_address_2']); 
}

if ($data['shipping_city']) {
$data['shipping_city'] = strtoupper($data['shipping_city']); 
}

if ($data['shipping_postcode']) {
$data['shipping_postcode'] = strtoupper($data['shipping_postcode']); 
}

if ($data['shipping_country']) {
$data['shipping_country'] = strtoupper($data['shipping_country']); 
}

if ($data['shipping_state']) {
$data['shipping_state'] = strtoupper($data['shipping_state']); 
}
    
if ($data['order_comments']) {
$data['order_comments'] = strtoupper($data['order_comments']); 
}   

return $data;
}

// 6. Add contact form 7 in product page when the product is out of stock
add_action( 'woocommerce_single_product_summary', 'woocommerce_single_product_inquiry', 30 );

function woocommerce_single_product_inquiry() {
   global $product;
   if ( ! $product->is_in_stock() ) {
echo '<button type="submit" id="trigger_cf" class="single_add_to_cart_button button alt">Let me know when this product is back in stock.</button>';
echo '<div id="product_inq" style="display:none">';
echo do_shortcode('[contact-form-7 id="6823" title="Product enquiry form"]');
echo '</div>';
   }
    else {
     return;
         }
}


add_action( 'woocommerce_single_product_summary', 'inquiry_on_click_show_form_and_populate', 40 );
function inquiry_on_click_show_form_and_populate() {
?>
<script type="text/javascript">
jQuery('#trigger_cf').on('click', function(){
if ( jQuery(this).text() == 'Let me know when this product is back in stock.' ) {
jQuery('#product_inq').css("display","block");
jQuery('input[name="your-subject"]').val('<?php the_title(); ?>');
jQuery("#trigger_cf").html('Close the form');
} else {
jQuery('#product_inq').hide();
jQuery("#trigger_cf").html('Let me know when this product is back in stock.');
}
});
</script>
<?php

}

// 7. Unset ALL Shipping Rates in ALL Zones when ANY Free Shipping Rate is Available
add_filter( 'woocommerce_package_rates', 'bbloomer_unset_shipping_when_free_is_available_all_zones', 10, 2 );
   
function bbloomer_unset_shipping_when_free_is_available_all_zones( $rates, $package ) {
      
$all_free_rates = array();
     
foreach ( $rates as $rate_id => $rate ) {
      if ( 'free_shipping' === $rate->method_id ) {
         $all_free_rates[ $rate_id ] = $rate;
         break;
      }
}
     
if ( empty( $all_free_rates )) {
        return $rates;
} else {
        return $all_free_rates;
} 
 
}
?>