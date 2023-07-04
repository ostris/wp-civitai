<?php
/*
Plugin Name: WP Civitai
Plugin URI: http://ostris.com/wp-civitai
Description: A plugin to integrate Civitai with WordPress. Allow you to cache and display Civitai content on your site.
Version: 0.1
Author: Ostris (Jaret Burkett)
Author URI: http://ostris.com
License: GNU GPLv3
*/

define('WP_CIVITAI_FILE', __FILE__);
define('WP_CIVITAI_PATH', plugin_dir_path(__FILE__));
define('WP_CIVITAI_URL', plugin_dir_url(__FILE__));


require_once WP_CIVITAI_PATH . 'civitai-settings.php';

if (!class_exists('WpCivitai')) {
    class WpCivitai {
        public function __construct() {
            register_activation_hook(WP_CIVITAI_FILE, array($this, 'install'));
            add_shortcode('civitai', array($this, 'shortcode_output'));
        }

        public function shortcode_output($atts) {
            $atts = shortcode_atts(array(
                'type' => 'model',
                'id' => '',
            ), $atts, 'civitai');

            $type = $atts['type'];

            if ($atts['type'] == 'model') {
                // Construct the endpoint URL based on the model id
                $endpoint = 'https://civitai.com/api/v1/models/' . $atts['id'];
            } else {
                // Print an error message
                return 'Invalid shortcode. Please specify a valid type';
            }

            $data = $this->get_data($endpoint);

            // Format the data as needed for output
            $output = $this->format_output($type, $data);

            return $output;
        }

        private function format_output($type, $data) {
            // Format the data as needed for output

            if ($type == 'model') {
                // Format the data as needed for output
            } else {
                // Print an error message
                return 'Invalid shortcode. Please specify a valid type';
            }

            // For now, we'll just return it as is
            return print_r($data, true);
        }

        private function api_call($endpoint) {
            // Get API key and username from options
            $options = get_option('wp_civitai_options');
            $api_key = $options['api-key'];
            $username = $options['username'];

            // Prepare the API request
            $args = array(
                'headers' => array(//                    'Authorization' => 'Basic ' . base64_encode($username . ':' . $api_key)
                )
            );

            // Make the request
            $response = wp_remote_get($endpoint, $args);

            // Check for errors
            if (is_wp_error($response)) {
                // Log the error and return null
                error_log($response->get_error_message());
                return null;
            }

            // Decode the response body
            $data = json_decode(wp_remote_retrieve_body($response), true);

            return $data;
        }


        public function install(): void {
            global $wpdb;

            $table_name = $wpdb->prefix . "civitai";

            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                $charset_collate = $wpdb->get_charset_collate();

                $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                endpoint varchar(255) NOT NULL,
                date_added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                response text NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
            }
            $default_settings = array(
                'api-key' => null,
                'username' => null,
                'cache-minutes' => 60,
                'hide-nsfw' => true
            );
            add_option('wp_civitai_options', $default_settings);
        }

        public function get_data($endpoint) {
            global $wpdb;
            $table_name = $wpdb->prefix . "civitai";

            $result = $wpdb->get_row(
                $wpdb->prepare(
                    "
                SELECT * FROM $table_name
                WHERE endpoint = %s
                ",
                    $endpoint
                )
            );

            if ($result) {
                $data = maybe_unserialize($result->response);

                if ($this->is_cache_expired($result->date_added)) {
                    // Cache is expired, flag for update
                    return $this->update_data($endpoint, $data);
                }

                // Return cached data
                return $data;
            } else {
                // No data found, flag for update
                return $this->update_data($endpoint, null);
            }
        }

        public function update_data($endpoint, $data) {
            global $wpdb;
            $table_name = $wpdb->prefix . "civitai";

            // Make API call
            $new_data = $this->api_call($endpoint);

            if ($new_data) {
                // If API call was successful, save new data to DB
                $wpdb->replace(
                    $table_name,
                    array(
                        'endpoint' => $endpoint,
                        'date_added' => current_time('mysql'),
                        'response' => maybe_serialize($new_data)
                    ),
                    array('%s', '%s', '%s')
                );

                // Return new data
                return $new_data;
            } else {
                // If API call failed, return old data if available
                return $data;
            }
        }

        private function is_cache_expired($date): bool {
            $cache_minutes = get_option('wp_civitai_options')['cache-minutes'];
            $expiration_date = strtotime($date . ' + ' . $cache_minutes . ' minutes');

            // If the current time is past the expiration date, cache is expired
            return current_time('timestamp') > $expiration_date;
        }
    }
}

if (class_exists('WpCivitai')) {
    $wpCivitai = new WpCivitai();
}
if (class_exists('WpCivitaiSettings')) {
    $wpCivitaiSettings = new WpCivitaiSettings();
}

