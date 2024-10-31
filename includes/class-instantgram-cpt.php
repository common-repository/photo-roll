<?php

/**
 * Class responsible for setting up cuostom post type
 *
 *
 * @since      1.0.0
 * @package    Instantgram
 * @subpackage Instantgram/includes
 */
class PRI_Instantgram_Cpt {



    /**
     * Define the retrieve functionality of the plugin.
     *
     *
     * @since    1.0.0
     */
    public function __construct() {


    }

    public function register_ctp() {

        $labels = array(
            'name' => _x('Instagram Profiles', 'instantgram'),
            'singular_name' => _x('Instagram Profile', 'instantgram'),
            'menu_name' => _x('Instantgram', 'instantgram'),
            'name_admin_bar' => _x('Instantgram', 'instantgram'),
            'add_new' => _x('Add new instagram profile', 'instantgram'),
            'add_new_item' => __('Add new instagram profile', 'instantgram'),
            'new_item' => __('New profile', 'instantgram'),
            'edit_item' => __('Edit profile', 'instantgram'),
            'view_item' => __('Preview', 'instantgram'),
            'all_items' => __('All', 'instantgram'),
            'search_items' => __('Search', 'instantgram'),
            'parent_item_colon' => __('Parent:', 'instantgram'),
            'not_found' => __('Not found.', 'instantgram'),
            'not_found_in_trash' => __('Not found in trash.', 'instantgram'),

        );
        $args = [
            'labels' => $labels,
            'description' => __('Instagram profiles', 'instantgram'),
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'query_var' => false,
            'rewrite' => array('slug' => 'instantgram'),
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title'),
            'taxonomies' => array(),
        ];

        register_post_type('instantgram', $args);
    }

}
