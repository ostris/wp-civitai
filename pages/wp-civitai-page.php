<?php

class WpCivitaiPage {
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function process_and_render() {
        // this method will be overridden by child classes
    }
}
