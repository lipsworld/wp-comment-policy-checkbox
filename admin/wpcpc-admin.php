<?php

/**
* Creating setting field
*
*/
function wpcpc_add_setting_field () {

    add_settings_field (
        'myprefix_setting-id',
        __( 'Comments policy checkbox', 'wp-comment-policy-checkbox'),
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
            <p> <?php echo __( 'The check box for reading and accepting the privacy policy in the comment forms is active. To deactivate it you must deactivate the WP Comment Policy Check plugin.', 'wp-comment-policy-checkbox') ?> </p>

            <p>
                <?php echo __( 'The page with the privacy policy to which you will link the text of the checkbox will be: ', 'wp-comment-policy-checkbox') ?>
                <select name='wpcpc_policy_page_id'>

                    <?php $empty_option_value = __( '-- none --', 'wp-comment-policy-checkbox' ); ?>

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
