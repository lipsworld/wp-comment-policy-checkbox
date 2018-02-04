<?php

/**
 * WP Comment Policy Checkbox bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:       WP Comment Policy Checkbox
 * Plugin URI:        wpcpc
 * Description:       Add a required policy checkbox to any comment forms and a custom link to the policy polity text opening in new tab.
 * Version:           0.1.5
 * Author:            fcojgodoy
 * Author URI:        franciscogodoy.es
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-comment-policy-checkbox
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Admin
 *
 */
 if ( is_admin() ) {
    // We are in admin mode
    require_once ( plugin_dir_path ( __FILE__ ) . 'admin/wp-comment-policy-checkbox-admin.php' );
 }


/**
* Load the plugin text domain for translation.
*
*/
function wpcpc_load_plugin_textdomain() {

	load_plugin_textdomain(
		'wp-comment-policy-checkbox',
		false,
		basename( dirname( __FILE__ ) ) . '/languages/'
	);

}

add_action( 'plugins_loaded', 'wpcpc_load_plugin_textdomain' );


/**
* Create custom fields
*
*/
function wpcpc_custom_fields($fields) {

	// Multilingual strings
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
	$url = get_permalink ( get_option( 'wpcpc_policy_page_id' ) );
	$read_and_accept = __( 'I have read and accept the ', 'wp-comment-policy-checkbox' );

    $fields[ 'policy' ] =
        '<p class="comment-form-policy">'.
            '<label for="policy">
                <input name="policy" value="policy-key" class="comment-form-policy__input" type="checkbox" style="width:auto"' . $aria_req . '>
                ' . $read_and_accept . '
                <a href="' . esc_url( $url ) . '" target="_blanck" class="comment-form-policy__see-more-link">' . __('Privacy Policy', 'wp-comment-policy-checkbox') . '</a>
                <span class="comment-form-policy__required required">*</span>
            </label>
        </p>';

    return $fields;
}

add_filter('comment_form_default_fields', 'wpcpc_custom_fields');


/**
* Add the filter to check whether the comment meta data has been filled
*
*/
function wpcpc_verify_policy_check( $policydata ) {
    if ( ! isset( $_POST['policy'] ) )

    	wp_die( __( 'Error: you must accept the Privacy Policy.', 'wp-comment-policy-checkbox' ) . '<p><a href="javascript:history.back()">' . __('&laquo; Back') . '</a></p>');

    return $policydata;
}

add_filter( 'preprocess_comment', 'wpcpc_verify_policy_check' );
