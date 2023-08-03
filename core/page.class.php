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

    public const ADDITIONAL_META = "additional_meta";
    public const THEME_PATH = "theme_path";
    public const ADDITIONAL_STYLE = "additional_style";
    public const ADDITIONAL_SCRIPT = "additional_script";
    public const TITLE = "page_title";
    public const HEADER = "page_header";
    public const PRIMARY_MENU = "page_primary_menu";
    public const CONTENT = "page_content";
    public const ASIDE_CONTENT = "page_aside_content";
    public const FOOTER = "page_footer";

    /**
     *
     * @var array
     */
    private $data = [
        self::ADDITIONAL_META => "",
        self::THEME_PATH => "",
        self::ADDITIONAL_STYLE => "",
        self::ADDITIONAL_SCRIPT => "",
        self::TITLE => "This is a page title",
        self::HEADER => "This is a page header",
        self::PRIMARY_MENU => "Menu",
        self::CONTENT => "Content",
        self::ASIDE_CONTENT => "Addblock",
        self::FOOTER => "This is a page footer"
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

    public function __construct() {
        \core\site::$logger->debug("--- PAGE OBJECT CREATING ---");

        $this->load_config();
        $this->load_modules();
        if (!empty($this->config["page_title"])) {
            $this->set_data(page::TITLE, $this->config["page_title"]);
        }
        if (!empty($this->config["page_header"])) {
            $this->set_data(page::HEADER, $this->config["page_header"]);
        }
        // set theme
        $this->set_data(self::THEME_PATH, $this->config["default_theme"]);
        $this->attachData(self::ADDITIONAL_STYLE,
            "<link rel='stylesheet' href='./themes/{$this->config['default_theme']}/main.css'>");
    }

    /**
     * load site configuration
     */
    private function load_config() {
        $result = \core\site::$database->select("config", ["name", "value"]);
        foreach ($result as $value) {
            $this->config[$value["name"]] = $value["value"];
        }
    }

    /**
     * load registered modules
     */
    private function load_modules() {
        $result = \core\site::$database->select("module", ["name"]);
        foreach ($result as $m) {
            $moduleName = $m["name"];
            \core\site::$logger->debug("load module: " . $moduleName);
            include_once \config::module_dir . \strtolower($moduleName) . "/{$moduleName}.class.php";
            $this->modules[$moduleName] = "\\module\\{$moduleName}\\{$moduleName}";
            if (\method_exists($this->modules[$moduleName], "init")) {
                $this->modules[$moduleName]::init();
            }
        }
    }

    public function get_module($moduleName) {
        return $this->modules[$moduleName];
    }

    /**
     * render HTML
     * @return string
     */
    public function render() {
        \core\site::$logger->debug("--- START PAGE GENERATING ---");
        \core\site::$logger->debug("REQUEST_URI: " . filter_input(INPUT_SERVER, "REQUEST_URI"));
        \core\site::$logger->debug("REMOTE_ADDR: " . filter_input(INPUT_SERVER, "REMOTE_ADDR"));

        $tpl = file_get_contents("./templates/page.template.php");
        $generator = new \mc\template($tpl, ["prefix" => "<!-- ", "suffix" => " -->"]);
        foreach ($this->modules as $module) {
            if (\method_exists($module, "data")) {
                $module::data();
            }
        }
        return $generator->fill($this->data)->value();
    }

    /**
     * Set data by property
     * @param string $property
     * @param string $value
     */
    public function set_data($property, $value) {
        $this->data[$property] = $value;
    }

    /**
     * Get data by property
     * @param string $property
     */
    public function get_data($property) {
        return $this->data[$property];
    }

    /**
     * append to data (concat) by property
     * @param string $property
     * @param string $value
     */
    public function attachData($property, $value) {
        $this->data[$property] .= $value;
    }
}
