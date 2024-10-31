<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Instantgram
 * @subpackage Instantgram/public

 */
class PRI_Instantgram_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->register_shortcodes();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'css/instantgram-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'js/instantgram-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Responsible for registering gallery shortcode
     *
     * @since    1.0.0
     */
	public function register_shortcodes() {
        if(shortcode_exists('ig_instantgram_gallery') == false) {
            add_shortcode('ig_instantgram_gallery', [$this, 'render_gallery']);
        }
    }

    /**
     * Shortcode gallery renderer
     *
     * @since    1.0.0
     * @param array $atts
     * @return string|void
     */
    public function render_gallery($atts = []) {

        $instantgram_posts = get_posts([
            'post_type' => 'instantgram'
        ]);

        if(count($instantgram_posts) == 0)
            return;

        $attachments = unserialize(get_post_meta($instantgram_posts[0]->ID, 'ig_photos_ids', true));
        rsort($attachments);

        $columns = (int)get_option('ig_gallery_columns', 3);
        $profile = (int)get_option('ig_profile');
        $followers_counter = (int)get_option('ig_gallery_counter_show');
        $followers = get_post_meta($instantgram_posts[0]->ID, 'ig_followers', true);
        $followers = apply_filters('ig_number_short', $followers);

        $html = '';
        if($followers_counter == 1) {
            $html .= '<div class="instantgram-info">
                          <a href="https://instagram.com/' . $profile . '" rel="nofollow" target="_blank"><strong> ' . $followers . '</strong> ' . __('followers', 'instantgram') . '</a>
                      </div>';
        }
        $html .= '<div class="instantgram ig-cols-'.$columns.'">';
        foreach ($attachments as $attachment_id) {

            $src = wp_get_attachment_image_src($attachment_id, 'full');
            if(is_array($src) == false || count($src) == 0)
                continue;
            $shortcode = get_post_meta($attachment_id, 'ig_shortcode', true);
            $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', TRUE);
            $html .= '<a href="https://instagram.com/p/'.$shortcode.'/" rel="nofollow" target="_blank"><img src="'.$src[0].'" alt="'.$image_alt.'" /></a>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Function that converts a numeric value into an exact abbreviation
     */
    public function number_format_short( $n, $precision = 1 ) {

        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }
        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $n_format = str_replace( $dotzero, '', $n_format );
        }
        return $n_format . $suffix;
    }
}
