<?php

namespace core;

/*
 * The MIT License
 *
 * Copyright 2019 Croitor Mihail <mcroitor@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Description of page
 *
 * @author Croitor Mihail <mcroitor@gmail.com>
 */
class page {

    /**
     *
     * @var array 
     */
    public $data = [
        "<!-- additional_meta -->" => "",
        "<!-- theme_path -->" => "",
        "<!-- additional_style -->" => "",
        "<!-- additional_script -->" => "",
        "<!-- page_title -->" => "This is a page title",
        "<!-- page_header -->" => "This is a page header",
        "<!-- page_primary_menu -->" => "Menu",
        "<!-- page_content -->" => "Content",
        "<!-- page_aside_content -->" => "Addblock",
        "<!-- page_footer -->" => "This is a page footer"
    ];

    /**
     *
     * @var array 
     */
    public $modules = [];

    /**
     *
     * @var array 
     */
    public $config = [];
    private $site;

    /**
     * 
     */
    public function __construct() {
        global $site;
        $this->site = $site;

        $this->site->logger->write_debug("--- PAGE OBJECT CREATING ---");

        $this->load_config();
        $this->load_modules();
        if (!empty($this->config["page_title"])) {
            $this->data["<!-- page_title -->"] = $this->config["page_title"];
        }
        if (!empty($this->config["page_header"])) {
            $this->data["<!-- page_header -->"] = $this->config["page_header"];
        }
        // set theme
        $this->data["<!-- theme_path -->"] = $this->config["default_theme"];
        $this->data["<!-- additional_style -->"] .= "<link rel='stylesheet' href='./themes/" . $this->config['default_theme'] . "/main.css'>";
    }

    /**
     * 
     */
    private function load_config() {
        $result = $this->site->database->select("config", ["name", "value"]);
        foreach ($result as $value) {
            $this->config[$value["name"]] = $value["value"];
        }
    }

    /**
     * 
     */
    private function load_modules() {
        $result = $this->site->database->select("module", ["name"]);
        foreach ($result as $m) {
            $module_name = $m["name"];
            $this->site->logger->write_debug("load module: " . $module_name);
            include_once(MODULE_DIR . \strtolower($module_name) . "/{$module_name}.class.php");
            $class_name = "\\module\\{$module_name}\\{$module_name}";
            $this->modules[$module_name] = new $class_name();
        }
    }

    /**
     * 
     * @return string
     */
    public function render() {
        $this->site->logger->write_debug("--- START PAGE GENERATING ---");
        $this->site->logger->write_debug("REQUEST_URI: " . filter_input(INPUT_SERVER, "REQUEST_URI"));
        $this->site->logger->write_debug("REMOTE_ADDR: " . filter_input(INPUT_SERVER, "REMOTE_ADDR"));

        $tpl = file_get_contents("./templates/page.template.php");
        $generator = new \core\template($tpl);
        foreach ($this->modules as $module) {
            if (\method_exists($module, "data")) {
                $module->data();
            }
        }
        return $generator->fill($this->data)->value();
    }

    /**
     * 
     */
    public function process() {
        $this->site->logger->write_debug("page->process() call.");
        $q = \filter_input(INPUT_GET, "q") ?? "";
        $module_name = \explode("/", $q)[0];
        $this->site->logger->write_debug("q = {$q}");
        $this->site->logger->write_debug("try to access module '{$module_name}'.");
        if (!empty($this->modules[$module_name])) {
            $this->modules[$module_name]->process($q);
        }
    }

}
