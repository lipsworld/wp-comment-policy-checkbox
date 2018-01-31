<?php

/**
* Creating option page
*
*/
// function wpcpc_add_options_page () {
//
//     add_options_page (
//         'Comments Policy Checkbox', //the title tags of the page
//         'Comments Policy', //the menu text
//         'manage_options', //capability required
//         __FILE__, //slug name
//         'wpcpc_render_options_page' //function to output the page content
//     );
// }

// add_action('admin_menu','wpcpc_add_options_page');


/**
* Creating setting field
*
*/
function wpcpc_add_setting_field () {

    add_settings_field (
        'myprefix_setting-id',
        __( 'Comments policy checkbox', 'wpcpc'),
        'wpcpc_render_options_page',
        'discussion'
        // 'myprefix_settings-section-name',
        // array( 'label_for' => 'myprefix_setting-id' )
    );

}

add_action('admin_menu','wpcpc_add_setting_field');


/**
* Rendering option page
*
*/
function wpcpc_render_options_page() { ?>
    <div class='wrap'>
        <form method="post" action="options.php">
            <?php settings_fields( 'wpcpc_options_group' ); ?>
            <p> <?php echo __( 'The check box for reading and accepting the privacy policy in the comment forms is active. To deactivate it you must deactivate the WP Comment Policy Check plugin.', 'wpcpc') ?> </p>

            <p>
                <?php echo __( 'The page with the privacy policy to which you will link the text of the checkbox will be: ', 'wpcpc') ?>
                <select name='wpcpc_policy_page_id'>

                    <?php if ( function_exists( 'pll_register_string' ) ) {

                        $empty_option_value = pll__( '-- none --' );

                    } else {

                        $empty_option_value = __( '-- none --', 'wpcpc' );

                    } ?>

                    <option> <?php echo $empty_option_value ?> </option>

                    <?php $pages = get_pages( array( 'lang' => '' ) ); ?>
                    <?php if ( $pages ) { ?>
                        <?php foreach ( $pages as $page ) { ?>
                            <option value='<?php echo $page -> ID; ?>' <?php selected( get_option( 'wpcpc_policy_page_id' ), $page -> ID ); ?>><?php echo $page -> post_title; ?></option>
                        <?php } ?>
                    <?php } ?>

                </select>
            </p>
        </form>
    </div>
<?php }


/**
* Register settings
*
*/
function wpcpc_register_settings() {

    register_setting (
        'wpcpc_options_group',
        'wpcpc_policy_page_id'
    );

    add_option( 'wpcpc_policy_page_id', '1' );

}

add_action( 'admin_init', 'wpcpc_register_settings' );


/**
* Register strings - Polylang Compatibility
*
*/
function wpcpc_register_strings () {

    if ( function_exists( 'pll_register_string' ) ) {

        pll_register_string ( 'Read and accept', 'I have read and accept the ', 'wpcpc' );
        pll_register_string ( 'Policy accept check error', 'Error: you must accept the Privacy Policy.', 'wpcpc' );
        pll_register_string ( 'Empty option value', '-- none --', 'wpcpc' );
    }

}

add_action ( 'init','wpcpc_register_strings' );

 ?>
