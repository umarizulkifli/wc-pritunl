<?php
/*
Plugin Name: WC Pritunl
Plugin URI: http://freedom.globaltransit.net/
Description: Integration with Pritunl API
Author: Muhammad Umari Zulkifli
Author URI: http://freedom.globaltransit.net
Version: 1.0
Text Domain: wootickets-asia-info

Copyright: � 2016 xUmaRix
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
//referal from : http://stackoverflow.com/questions/28218580/woocommerce-hook-for-after-payment-complete-actions


add_filter( 'woocommerce_order_status_completed', 'create_user', 1);
function create_user( $order_id ){
  global $woocommerce;
  
  $order = new WC_Order( $order_id );
  $fname = $order->billing_first_name;
  $email = $order->billing_email;
  $command = escapeshellcmd('python /home/ubuntu/pritunl-script/new_user.py freedomvpn '. $fname . ' '.$email);
  
}
