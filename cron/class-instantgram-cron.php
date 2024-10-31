<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Instantgram
 * @subpackage Instantgram/cron
 */

/**
 * The cron-specific functionality of the plugin.
 *
 *
 * @package    Instantgram
 * @subpackage Instantgram/cron
 */
class PRI_Instantgram_Cron {



    /**
     * Retriever responsible for comunication with remote service
     *
     * @since    1.0.0
     * @access   protected
     * @var      Instantgram_Retriever    $retriever    Retrieves remote data
     */
	protected $retriever;


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
        $this->retriever = new PRI_Instantgram_Retriever();


    }

	public function cron_schedules($schedules) {

        if(!isset($schedules["5min"])){
            $schedules["5min"] = array(
                'interval' => 5*60,
                'display' => __('Once every 5 minutes', 'instantgram'));
        }
        if(!isset($schedules["30min"])){
            $schedules["30min"] = array(
                'interval' => 30*60,
                'display' => __('Once every 30 minutes', 'instantgram'));
        }

        return $schedules;
    }


    /**
     * Method which is called by hook ig_cron_action
     * @throws Exception
     */
    public function update() {

        $profile = get_option('ig_profile');

        if(empty($profile))
            return;

        $profile = str_replace(['https://www.instagram.com', 'https://instagram.com'], ['', ''], $profile);
        $profile = str_replace(['/'], [''], $profile);

        $profile_updated = get_option('ig_profile_updated', date('Y-m-d H:i:s', strtotime('now - 4 hours')));

        if(strtotime(date('Y-m-d H:i:s')) < strtotime($profile_updated . ' + 3 hours'))
            return;

        $data = $this->retriever->get_remote_data($profile);

        $instantgram_posts = get_posts([
            'post_type' => 'instantgram'
        ]);

        if(count($instantgram_posts) == 0)
            return;

        $this->retriever->save_data($instantgram_posts[0]->ID, $data);

        update_option('ig_profile_updated', date('Y-m-d H:i:s'));
    }

}
