<?php
/*
Plugin Name: WP Comments Policy Checkbox
Author: Plugin Author
Text Domain: wpcpc
*/

//Exit if accessed directly
if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}


require_once ( plugin_dir_path ( __FILE__ ) . 'wpcpc-admin.php' );


/**
* When the akismet option is updated, run the registration call.
*
* This should only be run when the option is updated from the Jetpack/WP.com
* API call, and only if the new key is different than the old key.
*
* @param mixed  $old_value   The old option value.
* @param mixed  $value       The new option value.
*/

add_filter('comment_form_default_fields', 'wpcpc_custom_fields');

function wpcpc_custom_fields($fields) {

    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    /**
     * Detect plugin. For use on Front End only.
     */
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    // check if Polylang is activate
    if ( is_plugin_active( 'polylang/polylang.php' ) ) {
        // Polylang pll_get_post function to get permalinks
        $url = get_permalink ( pll_get_post( get_option( 'wpcpc_option_name2' ) ) );
    } else {
        $url = get_permalink ( get_option( 'wpcpc_option_name2' ) );
    }

    if ( function_exists( 'pll_register_string' ) ) {
        $read_and_accept = pll__( 'I have read and accept the ' );
    } else {
        $read_and_accept = __( 'I have read and accept the ', 'wpcpc' );
    }

    $fields[ 'policy' ] =
        '<p class="comment-form-policy">'.
            '<label for="policy">
                <input name="policy" value="policy-key" class="" type="checkbox" style="width:auto"' . $aria_req . '>
                ' . $read_and_accept . '
                <a href="' . $url . '" target="_blanck">' . __('Privacy Policy', 'wpcpc') . '</a>
                <span class="required">*</span>
            </label>
        </p>';

    return $fields;
}

// Add the filter to check whether the comment meta data has been filled
add_filter( 'preprocess_comment', 'wpcpc_verify_policy_check' );

function wpcpc_verify_policy_check( $polictydata ) {
    // $back_text = __('&laquo; Back');
    if ( ! isset( $_POST['policy'] ) )
    wp_die( __( 'Error: you must accept the Privacy Policy.', 'wpcpc' ) . '<p><a href="javascript:history.back()">' . __('&laquo; Back') . '</a></p>');

    return $polictydata;
}
