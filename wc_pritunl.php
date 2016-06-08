<?php
/*
Plugin Name: WC Pritunl
Plugin URI: http://www.hotantz.com/
Description: Integration with Pritunl API
Author: Muhammad Umari Zulkifli
Author URI: http://www.hotantz.com/
Version: 2.0
Text Domain: wootickets-asia-info

Copyright: � 2016 xUmaRix
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
//referal from : http://stackoverflow.com/questions/28218580/woocommerce-hook-for-after-payment-complete-actions


add_action('user_register', 'new_user', 1);
add_action('delete_user', 'delete_user', 1);

add_action('subscription_expired','disable_user',1);
add_action('subscription_put_on-hold','disable_user',1);
add_action('activated_subscription','enable_user',1);
add_action('subscription_trial_end','disable_user',1);
add_action('cancelled_subscription','disable_user',1);
add_action('woocommerce_order_details_after_customer_details','add_key_link', 10, 1);

function new_user( $user_id ){
	$user_info = get_userdata($user_id);
 	$email = $user_info->user_email;
	$fname = $user_info->user_login;
	$get_key = escapeshellcmd('python /home/ubuntu/pritunl-script/get_key.py '.$email);
	$key = shell_exec($get_key);
	if ( ! $key ){
	$command = escapeshellcmd('python /home/ubuntu/pritunl-script/new_user.py '.$fname.' '.$email);
	shell_exec($command);
	}  
}

function add_key_link( $order_id) {
	$order = new WC_Order( $order_id );
	$email = $order->billing_email;
 	$command = escapeshellcmd('python /home/ubuntu/pritunl-script/get_key.py '.$email);
  	$key = shell_exec($command);
	if ( $key ){
	echo '<table class="shop_table shop_table_responsive customer_details"><tbody><tr><th>VPN Key Download Link</th><td data-title="VPN Link"><a href ='.$key.'</a>Download</td></tr></tbody</table>';	
	}
}

function disable_user($user_id){
	$user_info = get_userdata($user_id);
 	$email = $user_info->user_email;
	$command = escapeshellcmd('python /home/ubuntu/pritunl-script/dis_usr.py '.$email);
  	shell_exec($command);  
}

function enable_user($user_id){
	$user_info = get_userdata($user_id);
 	$email = $user_info->user_email;
	$command = escapeshellcmd('python /home/ubuntu/pritunl-script/en_usr.py '.$email);
  	shell_exec($command);  
}

function delete_user($user_id){
	$user_info = get_userdata($user_id);
 	$email = $user_info->user_email;
	$command = escapeshellcmd('python /home/ubuntu/pritunl-script/del_usr.py '.$email);
  	shell_exec($command);  
}
