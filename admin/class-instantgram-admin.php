<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Instantgram
 * @subpackage Instantgram/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Instantgram
 * @subpackage Instantgram/admin
 */
class PRI_Instantgram_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Instantgram_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name .'-admin', plugin_dir_url( __FILE__ ) . 'css/instantgram-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/instantgram-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_to_menu() {
        add_menu_page("Instantgram", "InstantGram", "manage_options", "instantgram-panel", ["PRI_Instantgram_Admin","instantgram_settings_page"],
            'dashicons-instagram',
            99);
    }

    public static function instantgram_settings_page() {
	    require plugin_dir_path( __FILE__ ).'partials/instantgram-admin-display.php';
    }

    public static function display_instantgram_profile_element() {
        ?>
        <input type="text" name="ig_profile" id="ig_profile" value="<?php echo get_option('ig_profile'); ?>" /><br />
        <i><small>https://instagram.com/<strong>profile_name</strong>/</small></i>
        <?php
    }

    public static function display_instantgram_gallery_followers_counter_element() {
        ?>
        <input type="checkbox" name="ig_gallery_counter_show" id="ig_gallery_counter_show" value="1" <?php if((int)get_option('ig_gallery_counter_show') == 1): ?> checked="checked"<?php endif?> />
        <?php
    }

    public static function display_instantgram_gallery_columns_element()
    {
        ?>
        <select name="ig_gallery_columns" id="ig_gallery_columns">
            <option value="1"<?php if((int)get_option('ig_gallery_columns') == 1): ?> selected="selected"<?php endif?>><?php _e('One column', 'instantgrams');?></option>
            <option value="3"<?php if((int)get_option('ig_gallery_columns') == 2): ?> selected="selected"<?php endif?>><?php _e('Two columns', 'instantgrams');?></option>
            <option value="3"<?php if((int)get_option('ig_gallery_columns') == 3): ?> selected="selected"<?php endif?>><?php _e('Three columns', 'instantgrams');?></option>
            <option value="4"<?php if((int)get_option('ig_gallery_columns') == 4): ?> selected="selected"<?php endif?>><?php _e('Four columns', 'instantgrams');?></option>
        </select>
        <?php
    }



    public function display_instantgram_config_fields()
    {
        add_settings_section("section", null, null, "instantgram-options");

        add_settings_field("ig_profile", __('Instagram profile name', 'instantgram'), ["PRI_Instantgram_Admin", "display_instantgram_profile_element"], "instantgram-options", "section");
        add_settings_field("ig_gallery_columns", __('Gallery columns count', 'instantgram'), ["PRI_Instantgram_Admin", "display_instantgram_gallery_columns_element"], "instantgram-options", "section");
        add_settings_field("ig_gallery_counter_show", __('Show followers counter', 'instantgram'), ["PRI_Instantgram_Admin", "display_instantgram_gallery_followers_counter_element"], "instantgram-options", "section");

        register_setting("section", "ig_profile");
        register_setting("section", "ig_gallery_columns");
        register_setting("section", "ig_gallery_counter_show");
    }


    /**
     * Callback function which is called when user changes profile name. We need to be sure that photos would be downloaded right after profile name has changed
     *
     * @param $old_value
     * @param string $new_value
     */
    public function clean_data_after_profile_name_update($old_value, $new_value = '')
    {
        if($new_value !== $old_value) {
            delete_option('ig_profile_updated');
        }
    }

}
