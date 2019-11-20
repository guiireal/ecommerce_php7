<?php

namespace GuiiReal;

use Rain\Tpl;

class Page {

    private $tpl;
    private $options = array();
    private $defaults = array(
        'data' => array(),
    );

    public function __construct($options = array()) {
        $this->options = array_merge($this->defaults, $options);
        $config = array(
            "tpl_dir" => $_SERVER['DOCUMENT_ROOT'] . '/views/',
            "cache_dir" =>  $_SERVER['DOCUMENT_ROOT'] . '/views-cache/',
            "debug" => false
        );
        Tpl::configure($config);
        $this->tpl = new Tpl();
        $this->setData($this->options['data']);

        $this->tpl->draw("header");
    }

    public function setTpl($templateName, $data = array(), $returnHTML = false) {
        $this->setData($data);
        return $this->tpl->draw($templateName, $returnHTML);
    }

    private function setData($data = array()) {
        foreach ($data as $key => $value) $this->tpl->assign($key, $value);
    }

    public function __destruct() {
        $this->tpl->draw("footer");
    }

}