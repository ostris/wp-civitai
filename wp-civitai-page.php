<?php

class WpCivitaiPage {
    protected $data;
    protected $component_name;
    protected $type;

    public function __construct($type, $data) {
        $this->data = $data;

        switch ($type) {
            case 'model':
                $this->component_name = 'SingleModelPage';
                break;
            default:
                $this->component_name = null;
                break;
        }
    }

    public function process_and_render() {
        if ($this->component_name == null) {
            return 'Invalid shortcode. Please specify a valid type';
        }
        // description is formatted html, render it
        ob_start();
        ?>
        <script>
            /** wp-civitai - <?php echo $this->component_name;?>*/
            if (typeof window.__CIVITAI_DATA__ === 'undefined') {
                window.__CIVITAI_DATA__ = {};
            }
            if (typeof window.__CIVITAI_DATA__.<?php echo $this->component_name; ?> === 'undefined') {
                window.__CIVITAI_DATA__.<?php echo $this->component_name; ?> = <?php echo json_encode($this->data); ?>;
            }
        </script>
        <div id="wp-civitai-<?php echo $this->component_name ?>"></div>
        <?php
        return ob_get_clean();
    }
}
