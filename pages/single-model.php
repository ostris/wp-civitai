<?php
require_once plugin_dir_path(__FILE__) . 'wp-civitai-page.php';

// example of data https://civitai.com/api/v1/models/98755
// docs on data https://github.com/civitai/civitai/wiki/REST-API-Reference#get-apiv1modelsmodelid


class WpCivitaiSingleModelPage extends WpCivitaiPage {
    public function __construct($data) {
        parent::__construct($data);
    }

    public function process_and_render() {
        // description is formatted html, render it
        ob_start();
        ?>

        <div class="civitai-model">
            <?php echo $this->data['description']; ?>
        </div>

        <?php
        return ob_get_clean();
    }
}
