<?php

if (!class_exists('WpCivitaiSettings')) {
    class WpCivitaiSettings {
        public function __construct() {
            register_activation_hook(WP_CIVITAI_FILE, array($this, 'add_default_settings'));
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
        }

        public function add_default_settings(): void {
            $default_settings = array(
                'api-key' => null,
                'civitai-username' => null,
                'cache-minutes' => 60,
                'hide-nsfw' => true
            );

            $current_settings = get_option('wp_civitai_options', false);

            // If no settings are saved, we save the defaults
            if (false === $current_settings) {
                add_option('wp_civitai_options', $default_settings);
            } else {
                // make sure all settings are set
                foreach ($default_settings as $key => $value) {
                    if (!isset($current_settings[$key])) {
                        $current_settings[$key] = $value;
                    }
                }
                // save the updated settings
                update_option('wp_civitai_options', $current_settings);
            }
        }

        public function add_plugin_page() {
            // This page will be under "Settings"

//            $icon_url = plugins_url('wp-civitai/images/civitai-icon.png');
            $icon_url = 'dashicons-schedule';

            add_menu_page(
                'Civitai', // page_title
                'Civitai', // menu_title
                'manage_options', // capability
                'wp_civitai', // menu_slug
                array($this, 'create_admin_page'), // function
                $icon_url, // icon_url
                100 // position
            );
        }

        public function create_admin_page() {
            // Set class property
            $this->options = get_option('wp_civitai_options');
            ?>
            <div class="wrap">
                <h1>WP Civitai</h1>
                <form method="post" action="options.php">
                    <?php
                    // This prints out all hidden setting fields
                    settings_fields('wp_civitai_group');
                    do_settings_sections('wp-civitai-setting-admin');
                    submit_button();
                    ?>
                </form>
            </div>
            <?php
        }

        public function page_init(): void {
            register_setting(
                'wp_civitai_group', // Option group
                'wp_civitai_options', // Option name
                array($this, 'sanitize') // Sanitize
            );

            add_settings_section(
                'setting_section_id', // ID
                'Settings', // Title
                function () {
                    ?>
                    <p>
                        We will keep a cache of the api calls to civitai for the cache minutes you set below. This will
                        help speed up your site and reduce the number of api calls to civitai. Good for everyone!
                    </p>

                    <p>
                        To use just put the shortcode on any post. Current supported methods are:
                    </p>
                    <ul>
                        <li>
                            <code>[civitai type="model" id="1234"]</code> - This will display the model with the id of 1234
                        </li>
                    </ul>
                    <?php
                }, // Callback
                'wp-civitai-setting-admin' // Page
            );

            add_settings_field(
                'api-key', // ID
                'API Key', // Title
                array($this, 'api_key_callback'), // Callback
                'wp-civitai-setting-admin', // Page
                'setting_section_id' // Section
            );

            add_settings_field(
                'civitai-username',
                'Civitai Username',
                array($this, 'username_callback'),
                'wp-civitai-setting-admin',
                'setting_section_id'
            );

            add_settings_field(
                'cache-minutes',
                'Cache Minutes',
                array($this, 'cache_minutes_callback'),
                'wp-civitai-setting-admin',
                'setting_section_id'
            );

            add_settings_field(
                'hide-nsfw',
                'Hide NSFW',
                array($this, 'hide_nsfw_callback'),
                'wp-civitai-setting-admin',
                'setting_section_id'
            );
        }


        public function sanitize($input): array {
            $new_input = array();
            if (isset($input['api-key']))
                $new_input['api-key'] = sanitize_text_field($input['api-key']);

            if (isset($input['civitai-username']))
                $new_input['civitai-username'] = sanitize_text_field($input['civitai-username']);

            if (isset($input['cache-minutes']))
                $new_input['cache-minutes'] = absint($input['cache-minutes']);

            if (isset($input['hide-nsfw']))
                $new_input['hide-nsfw'] = absint($input['hide-nsfw']);

            return $new_input;
        }

        public function api_key_callback(): void {
            printf(
                '<input type="text" id="api-key" name="wp_civitai_options[api-key]" value="%s" />',
                isset($this->options['api-key']) ? esc_attr($this->options['api-key']) : ''
            );
        }


        public function username_callback(): void {
            printf(
                '<input type="text" id="civitai-username" name="wp_civitai_options[civitai-username]" value="%s" />',
                isset($this->options['civitai-username']) ? esc_attr($this->options['civitai-username']) : ''
            );
        }

        public function cache_minutes_callback(): void {
            printf(
                '<input type="number" id="cache-minutes" name="wp_civitai_options[cache-minutes]" value="%s" />',
                isset($this->options['cache-minutes']) ? esc_attr($this->options['cache-minutes']) : ''
            );
        }

        public function hide_nsfw_callback(): void {
            printf(
                '<input type="checkbox" id="hide-nsfw" name="wp_civitai_options[hide-nsfw]" value="1" %s />',
                isset($this->options['hide-nsfw']) && $this->options['hide-nsfw'] == 1 ? 'checked' : ''
            );
        }
    }

}
