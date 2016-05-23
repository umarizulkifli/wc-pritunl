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


add_filter( 'woocommerce_order_status_completed', 'new_user', 1);
function new_user( $order_id ){
  global $woocommerce;
  
  $order = new WC_Order( $order_id );
  $fname = $order->billing_first_name;
  $email = $order->billing_email;
  $command = escapeshellcmd('python /home/ubuntu/pritunl-script/new_user.py '.$fname.' '.$email);
  shell_exec($command);  
}

add_filter( 'woocommerce_email_before_order_table', 'add_link_back_to_order', 1);
function add_link_back_to_order( $order_id, $sent_to_admin) {
 	if ( ! $sent_to_admin ) {
	$order = new WC_Order( $order_id);
	$email = $order->billing_email;
 	$command = escapeshellcmd('python /home/ubuntu/pritunl-script/get_key.py '.$email);
  	$key = shell_exec($command);
	
	echo '<p>VPN Key Download Link: ' . $key . '</p>';
	
	}
}
add_filter('woocommerce_order_status_pending','disable_user',1);
add_filter('subscription_expired','disable_user', 1);
add_filter('woocommerce_delete_order_item','delete_user',1);

function disable_user($order_id){
	$order = new WC_Order( $order_id );
	$email = $order->billing_email;
	$command = escapeshellcmd('python /home/ubuntu/pritunl-script/dis_usr.py '.$email);
  	shell_exec($command);  
}

add_filter('woocommerce_renewal_order_payment_complete','enable_user',1);
function enable_user($order_id){
	$order = new WC_Order( $order_id );
	$email = $order->billing_email;
	$command = escapeshellcmd('python /home/ubuntu/pritunl-script/en_usr.py '.$email);
  	shell_exec($command);  
}

function delete_user($order_id){
	$order = new WC_Order( $order_id );
	$email = $order->billing_email;
	$command = escapeshellcmd('python /home/ubuntu/pritunl-script/del_usr.py '.$email);
  	shell_exec($command);  
}
