<?php

/**
* Creating option page
*
*/
function wpcpc_add_options_page () {

    add_options_page (
        'Comments Policy Checkbox', //the title tags of the page
        'Comments Policy', //the menu text
        'manage_options', //capability required
        __FILE__, //slug name
        'wpcpc_render_options_page' //function to output the page content
    );
}

add_action('admin_menu','wpcpc_add_options_page');


/**
* Rendering option page
*
*/
function wpcpc_render_options_page() { ?>
    <div class='wrap'>
        <h2>My Plugin Page Title</h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'wpcpc_options_group' ); ?>
            <h3>This is my option</h3>
            <p>Some text here.</p>
            <table>
                <tr valign="top">
                    <th scope="row">
                        <label for="wpcpc_policy_page_id">Label</label>
                    </th>
                    <td>
                        <select name='wpcpc_policy_page_id'>
                            <option></option>
                            <?php $pages = get_pages( array( 'lang' => '' ) ); ?>
                            <?php if ( $pages ) { ?>
                                <?php foreach ( $pages as $page ) { ?>
                                    <option value='<?php echo $page -> ID; ?>' <?php selected( get_option( 'wpcpc_policy_page_id' ), $page -> ID ); ?>><?php echo $page -> post_title; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button() ?>
        </form>
        <div class="">
            <h1><?php echo get_option('wpcpc_policy_page_id'); ?></h1>
        </div>
    </div>
<?php }


/**
* Register settings
*
*/
function wpcpc_register_settings() {

    register_setting (
        'wpcpc_options_group',
        'wpcpc_option_name'
    );

    register_setting (
        'wpcpc_options_group',
        'wpcpc_policy_page_id'
    );

    add_option ( 'wpcpc_option_name', 'This is my option value.');

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
    }

}

add_action ( 'init','wpcpc_register_strings' );

 ?>
