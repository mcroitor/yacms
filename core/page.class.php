<?php

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
    public static $data = [
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
    static $modules = [];

    /**
     *
     * @var array 
     */
    static $config = [];
    private $site;

    /**
     * 
     * @global type $site
     */
    public function __construct() {
        global $site;
        $this->site = $site;

        $_SESSION["user_level"] = isset($_SESSION["user_level"]) ? $_SESSION["user_level"] : 0;

        $this->site->logger->write_debug("--- PAGE OBJECT CREATING ---");

        page::load_config();
        page::load_modules();
        if (!empty(page::$config["page_title"])) {
            page::$data["<!-- page_title -->"] = page::$config["page_title"];
        }
        if (!empty(page::$config["page_header"])) {
            page::$data["<!-- page_header -->"] = page::$config["page_header"];
        }
        // set theme
        page::$data["<!-- theme_path -->"]  = page::$config["default_theme"];
        page::$data["<!-- additional_style -->"] .= "<link rel='stylesheet' href='./themes/" .page::$config['default_theme'] . "/main.css'>";
    }

    /**
     * 
     * @global type $site
     */
    private static function load_config() {
        global $site;
        $query = "SELECT name, value FROM config";
        $result = $site->database->query_sql($query);
        foreach ($result as $value) {
            page::$config[$value["name"]] = $value["value"];
        }
    }

    /**
     * 
     * @global type $site
     */
    private static function load_modules() {
        global $site;
        $result = $site->database->query_sql("SELECT name FROM module");
        foreach ($result as $m) {
            $site->logger->write_debug("load module: " . $m["name"]);
//            include_once(MODULE_PATH . strtolower($m["name"]) . "/{$m["name"]}.class.php");
//            Page::$modules[$m["name"]] = new $m["name"]();
        }
    }

    /**
     * 
     * @global type $site
     * @return string
     */
    public function render() {
        global $site;
        $site->logger->write_debug("--- START PAGE GENERATING ---");
        $site->logger->write_debug("REQUEST_URI: " . filter_input(INPUT_SERVER, "REQUEST_URI"));
        $site->logger->write_debug("REMOTE_ADDR: " . filter_input(INPUT_SERVER, "REMOTE_ADDR"));

        $tpl = file_get_contents("./templates/page.template.php");
        $generator = new template($tpl);
        return $generator->fill(page::$data)->value();
    }

    public function process() {
        global $site;
        $post = filter_input(INPUT_POST, "q");
    }
}

$site->page = new page();
