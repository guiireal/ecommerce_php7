<?php

namespace GuiiReal;

use Rain\Tpl;

class Page {

    private $tpl;
    private $options = array();
    private $defaults = array(
        'header' => true,
        'footer' => true,
        'data' => array(),
    );

    public function __construct($options = array(), $tpl_dir = "/views/") {
        $this->options = array_merge($this->defaults, $options);
        $config = array(
            "tpl_dir" => $_SERVER['DOCUMENT_ROOT'] . $tpl_dir,
            "cache_dir" =>  $_SERVER['DOCUMENT_ROOT'] . '/views-cache/',
            "debug" => false
        );
        Tpl::configure($config);
        $this->tpl = new Tpl();
        $this->setData($this->options['data']);

        if ($this->options['header'])
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
        if ($this->options['footer'])
            $this->tpl->draw("footer");
    }

}