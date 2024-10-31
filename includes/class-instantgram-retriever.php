<?php

/**
 * Class responsible for comunication with remote service and saving images locally.
 *
 *
 * @since      1.0.0
 * @package    Instantgram
 * @subpackage Instantgram/includes
 */
class PRI_Instantgram_Retriever {

    private static $DATA_ENDPOINTS;
    private static $DATA_RETRIEVER_ENDPOINT = null;

    /**
     * Define the retrieve functionality of the plugin.
     *
     *
     * @since    1.0.0
     */
    public function __construct() {

        self::$DATA_ENDPOINTS = 'https://instantgram.app';
    }

    /**
     * Method is responsible for parsing json retrieved from remote host.
     * Data gained from service are being saving in local database as attachments
     *
     * @param $postId int
     * @param $data json
     * @throws Exception
     *
     * @since     1.0.0
     * @return    void
     */
    public function save_data($postId, $data) {

        global $wpdb;

        $ids_table = get_post_meta($postId, 'ig_photos_ids', true);
        $ids_table = empty($ids_table) ? [] : unserialize($ids_table);

        $followers = $data->followers;
        update_post_meta($postId, 'ig_followers', $followers);

        foreach($data->images as $image) {

            $src = $image->src->src;

            $caption = $image->caption;
            $shortcode = $image->shortcode;


            //we need to check if image has already been added to local db
            $sql = 'select * from '.$wpdb->prefix.'postmeta where meta_key="ig_shortcode" and meta_value = %s';
            $sql = $wpdb->prepare($sql, $shortcode);

            $shortcodeExists = $wpdb->get_row($sql, ARRAY_A);
            if(!empty($shortcodeExists))
                continue;


            $slug = self::slugify($caption);
            if(empty($slug))
                $slug = 'ig-'.md5(microtime());

            //we need to ensure that filenames are to long, due to caption length can be longer then max acceptable value
            $slug = substr($slug, 0, 50);

            $temp_dir = get_temp_dir();
            $filepath = $temp_dir.$slug.'.jpg';

            $this->grab_image($src, $filepath);
            $attachment_id = $this->add_attachment($filepath, $caption);
            if($attachment_id == null)
                continue;
            $ids_table[] = $attachment_id;
            unlink($filepath);

            update_post_meta($attachment_id, 'ig_shortcode', $shortcode);
            update_post_meta($postId, 'ig_photos_ids', serialize($ids_table));
        }

    }

    /**
     * Responsible for saving file as an wordpress attachment post
     *
     * @param $file local file path to image
     * @param $title attachment title, alternative text
     * @return int|WP_Error
     *
     * @since 1.0.0
     */
    public function add_attachment($file, $title)
    {
        $filename = basename($file);

        $upload_file = wp_upload_bits($filename, null, file_get_contents($file));
        if (!$upload_file['error']) {
            $wp_filetype = wp_check_filetype($filename, null );
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_parent' => null,
                'post_title' => $title,//preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id = 0);
            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                wp_update_attachment_metadata( $attachment_id,  $attachment_data );
                update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );

                return $attachment_id;
            }
        }
    }

    /**
     * Method responsible for retrieving data from remote service
     *
     * @param null $profile
     * @param null $tag
     * @return mixed
     * @since     1.0.0
     */
    public function get_remote_data($profile = null, $tag = null) {
        $this->get_retriever_endpoint();
        $url = $profile != null ? 'profile='.$profile : 'tag='.$tag;
        $url = self::$DATA_RETRIEVER_ENDPOINT . '?' .$url;
        $content = file_get_contents($url);
        $data = json_decode($content);

        return $data;
    }

    /**
     * Method responsible for downloading remote image file and saving him in given path
     *
     * @param $url remote url to image file
     * @param $saveto target file path on local file system
     * @since     1.0.0
     */
    private function grab_image($url, $saveto){

        $response = wp_remote_get($url);
        if(is_wp_error ($response)) {
            return false;
        }

        if(file_exists($saveto)){
            unlink($saveto);
        }
        $raw = wp_remote_retrieve_body( $response );

        $fp = fopen($saveto,'x');
        fwrite($fp, $raw);
        fclose($fp);
    }

    /**
     * @param $string
     * @param array $replace
     * @param string $delimiter
     * @return false|string|string[]|null
     * @throws Exception
     *
     * @since     1.0.0
     */
    public static function slugify($string, $replace = array(), $delimiter = '-') {

        if (!extension_loaded('iconv')) {
            throw new Exception('iconv module not loaded');
        }
        // Save the old locale and set the new locale to UTF-8
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        if (!empty($replace)) {
            $clean = str_replace((array) $replace, ' ', $clean);
        }
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        // Revert back to the old locale
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }

    /**
     * Method reponsible for figuring out the retrieving endpoint url
     *
     * @return string
     */
    private function get_retriever_endpoint() {

        //if endpoint has been already set, then there is no need to proceed
        if(self::$DATA_RETRIEVER_ENDPOINT != null)
            return self::$DATA_RETRIEVER_ENDPOINT;

        //downloading enpoints list
        $endpoints = file_get_contents(self::$DATA_ENDPOINTS);
        $endpoints = json_decode($endpoints);

        //return false if list is empty
        if(empty($endpoints))
            return false;
        if(is_array($endpoints) == false)
            return false;

        //setting up randomly one of the item from endpoint list
        self::$DATA_RETRIEVER_ENDPOINT = $endpoints[array_rand($endpoints)];
        return self::$DATA_RETRIEVER_ENDPOINT;
    }
}
