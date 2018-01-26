<?php


/**
 * Detect plugin. For use on Front End only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// check for plugin using plugin name
if ( is_plugin_active( 'plugin-directory/plugin-file.php' ) ) {
  //plugin is activated
}
