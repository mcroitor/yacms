<?php

class Page {

    public static $data = [
        "<!-- additional_metas -->" => "",
        "<!-- theme_path -->" => "",
        "<!-- additional_styles -->" => "",
        "<!-- additional_scripts -->" => "",
        "<!-- page_title -->" => "This is a page title",
        "<!-- page_header -->" => "This is a page header",
//        "<!-- page_primary_menu -->" => "Menu",
        "<!-- page_content -->" => "Content",
        "<!-- page_aside_content -->" => "Addblock",
        "<!-- page_footer -->" => "This is a page footer"
    ];
    static $modules = [];
    static $config = [];

    function __construct() {
        $_SESSION["user_level"] = isset($_SESSION["user_level"]) ? $_SESSION["user_level"] : 0;
        if (DEBUG) {
            write_log("--- PAGE OBJECT CREATING ---");
        }
        Page::load_config();
        Page::load_modules();
        if(!empty(Page::$config["page_title"])){
            Page::$data["<!-- page_title -->"] = Page::$config["page_title"];
        }
        if(!empty(Page::$config["page_header"])){
            Page::$data["<!-- page_header -->"] = Page::$config["page_header"];
        }
    }
// TODO #: refactor this to methods process_<method>
    public static function __hook($hook_name, $param = NULL) {
        $result = null;

        foreach (Page::$modules as $module) {
            $fn = "{$module->name}->{$hook_name}";
            write_log("test function '{$fn}'");
            if (method_exists($module, $hook_name)) {
                write_log("call function '{$fn}' with param {$param}");

                $result[$module->name] = $module->$hook_name($param);
            }
        }
        return $result;
    }

    /**
     * This function load configuration site
     */
    static function load_config() {
        $query = "SELECT variable_name, variable_value FROM config_tbl";
        $result = sql_query($query);

        foreach ($result as $value) {
            Page::$config[$value["variable_name"]] = $value["variable_value"];
        }
    }
    
    static function update_config(array $config) {
        foreach ($config as $param_name => $param_value){
            if(isset(Page::$config[$param_name])){
                Page::$config[$param_name] = $param_value;
                $query = "UPDATE config_tbl SET variable_value='{$param_value}' WHERE variable_name='{$param_name}'";
                sql_query($query, false);
            }
        }
    }

    static function get_template() {
        if (DEBUG) {
            write_log("--- PAGE TEMPLATE: " . Page::$config['default_theme'] . " ---");
        }
        return load_data(THEMES_PATH . Page::$config["default_theme"] . "/index.tpl.php");
    }

    static function load_modules() {
        $result = sql_query("SELECT module_name FROM modules_tbl");

        foreach ($result as $m) {
            if (DEBUG) {
                write_log("load module: " . $m["module_name"]);            
            }
            include_once(MODULE_PATH . strtolower($m["module_name"]) . "/{$m["module_name"]}.class.php");
            Page::$modules[$m["module_name"]] = new $m["module_name"]();
        }
    }

    public function Html() {
        if (DEBUG) {
            write_log("--- START PAGE GENERATING ---");
            write_log("REQUEST_URI: " . filter_input(INPUT_SERVER, "REQUEST_URI"));
            write_log("REMOTE_ADDR: " . filter_input(INPUT_SERVER, "REMOTE_ADDR"));
        }

        $path = filter_input(INPUT_GET, "q");
        if (empty($path) && isset(Page::$config["default_page"])) {
            Page::__hook(Page::$config["default_page"]);
        } else {
            Page::__hook(make_hook_name($path, "process"));
        }
        $tpl = Page::get_template();

        Page::__hook("preprocess_page");
        Page::$data["<!-- theme_path -->"] = "./themes/" . Page::$config["default_theme"];
        Page::__hook("postprocess_header");
        //$this->__hook("process_");
        $html = fill_template($tpl, Page::$data);

        Page::__hook("postprocess_page");
        return $html;
    }

}
