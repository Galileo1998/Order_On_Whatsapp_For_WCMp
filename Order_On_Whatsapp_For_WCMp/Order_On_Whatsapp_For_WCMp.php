<?php

/*

Plugin Name: Woocommerce Order On Whatsapp for WCMp

Plugin URI: https://codecanyon.net/user/go-web

Description: Increase sales by letting customers contact vendors via Whatsapp.

Version: 1.0

Author: Go-Web

Author URI: https://codecanyon.net/user/go-web

License: Commercial License

*/


// create custom plugin settings menu
add_action('admin_menu', 'WCMp_order_on_whatsapp_submenu');

//add styles

add_action('wp_enqueue_scripts', 'WCMp_order_on_whatsapp_styles');

function WCMp_order_on_whatsapp_styles() {

wp_enqueue_style('WCMp_order_on_whatsapp_style', plugins_url('css/WCMp_order_on_whatsapp_style.css', __FILE__));

  }

//* Admin Menu and Settings

function WCMp_order_on_whatsapp_submenu() {
	//create the admin menu for plugin district if not exists
	global $menu;
	$menuExist = false;
	foreach($menu as $item) {
	if(strtolower($item[0]) == strtolower('orderonwhatsapp')) {
	$menuExist = true;
	}
	}
	if(!$menuExist)

 add_menu_page('Whatsapp Settings', 'Whatsapp', 0, 'orderonwhatsapp-dashboard', 'WCMp_order_on_whatsapp_continueshopping_settings_page',plugins_url('/img/logo.png', __FILE__));
 //call register settings function
	add_action( 'admin_init', 'WCMp_order_on_whatsapp_register_orderonwhatsapp_settings' );
  }

function WCMp_order_on_whatsapp_register_orderonwhatsapp_settings() {
	//register our settings
	register_setting( 'orderonwhatsapp-settings-group', 'orderonwhatsapp_Beginning_message' );
	register_setting( 'orderonwhatsapp-settings-group', 'orderonwhatsapp_phone_number' );
}

function WCMp_order_on_whatsapp_continueshopping_settings_page() {
	?>
	<div class="WCMp_order_on_whatsapp_postbox_container" >
	<h2>
  <?php esc_html_e( 'Whatsapp Settings', 'text-domain' ); ?>
  </h2>
	<hr>
	<?php if( isset($_GET['settings-updated']) ) { ?>
	<div id="message" class="updated">
	<p><strong><?php esc_html_e('Settings saved.') ?></strong></p>
	</div>
  <?php } ?>
	<form method="post" action="options.php">
	<?php settings_fields( 'orderonwhatsapp-settings-group' ); ?>
	<?php do_settings_sections( 'orderonwhatsapp-settings-group' ); ?>
	<table class="form-table">
	<tr>
	<tr>
	<th scope="row">
<?php
  esc_html_e( 'Enter Message to appear on the WhatsApp conversation (E.g: Hi, I have a question about this product ):', 'text-domain' );
 ?>
  </th>
	<td><input type="textbox" name="orderonwhatsapp_Beginning_message" value="<?php esc_attr_e (get_option('orderonwhatsapp_Beginning_message')); ?>" /></td>
	</tr>

	</table>

	<?php submit_button(); ?>

	</form>
	</div>

<?php }

 // This is to get vendor phone number to the product single page.

  add_action( 'woocommerce_single_product_summary',	function() {


  // Get the author ID (the vendor ID) and phone number
    $vendor_id = get_post_field( 'post_author', get_the_id() );
    $vendor = get_wcmp_vendor($vendor_id);
    $WCMp_order_on_whatsapp_phone  = $vendor->phone;
    $WCMp_order_on_whatsapp_text = esc_html__( get_option('orderonwhatsapp_Beginning_message'));
    $WCMp_order_on_whatsapp_product_title= esc_html__( get_the_title());
  }, 26 );

function WCMp_order_on_whatsapp_custom_woocommerce_before_cart() {

  $vendor_id = get_post_field( 'post_author', get_the_id() );
  $vendor = get_wcmp_vendor($vendor_id);
  $WCMp_order_on_whatsapp_phone  = $vendor->phone;


	$WCMp_order_on_whatsapp_text = esc_html__( get_option('orderonwhatsapp_Beginning_message'));
	$WCMp_order_on_whatsapp_product_title= esc_html__( get_the_title());
  $WCMp_order_on_whatsapp_urlp = esc_url( get_permalink(get_the_ID()));
	$WCMp_order_on_whatsapp_urlimg=  esc_url( plugins_url('img/buttonOrder.png',__FILE__)) ;
  $WCMp_order_on_whatsapp_url_final=" https://api.whatsapp.com/send?phone=$WCMp_order_on_whatsapp_phone&text=$WCMp_order_on_whatsapp_text%20$WCMp_order_on_whatsapp_product_title:%20$WCMp_order_on_whatsapp_urlp";

echo'
  <form class="WCMp_order_on_whatsapp_form">
  <ul class="WCMp_order_on_whatsapp_ul">
  <a href="'.esc_url($WCMp_order_on_whatsapp_url_final).'">
  <li>
  <img type="image" style="border-radius: 5px;" src="'.esc_url ('https://empreser.hn/wp-content/uploads/2021/08/buttonOrder-2.png').'" ></img>
  </li>
  </a>
  </ul>
  </form>';
	 }
	 
function wcmp_add_vendor_whatsapp(){
    $vendor_id = get_post_field( 'post_author', get_the_id() );
    $vendor = get_wcmp_vendor($vendor_id);
	$phone_number = $vendor->phone;
	if($phone_number){?>
	<br>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <a href="https://api.whatsapp.com/send?phone=<?php echo $phone_number; ?>" class="float" target="_blank">
    <i class="fa fa-whatsapp my-float"></i>
</a>
	<?php
	}
}


add_action( 'woocommerce_after_add_to_cart_form' , 'WCMp_order_on_whatsapp_custom_woocommerce_before_cart', 10, 2 );
// register shortcode
add_shortcode('whatsapp_wcmp_vendor', 'wcmp_add_vendor_whatsapp');

add_action('after_wcmp_vendor_information','wcmp_add_vendor_whatsapp');
