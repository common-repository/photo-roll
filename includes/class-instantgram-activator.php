<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Instantgram
 * @subpackage Instantgram/includes
 */
class PRI_Instantgram_Activator {

	/**
	 * During activation we need to add first custom post type
	 *
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

	    global $wpdb;

	    $sql = 'select * from '.$wpdb->prefix.'posts where post_type = "instantgram"';
	    $result = $wpdb->get_results($sql, ARRAY_A);
	    if(count($result) > 0)
	        return;

	    $post = [
            'post_type' => 'instantgram',
            'post_title' => 'Instantgram profile',
            'post_status' => 'publish'
        ];

        wp_insert_post($post);
	}

}
