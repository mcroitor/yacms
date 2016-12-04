<?php

class Page {

    public static $data = [
        "<!-- additional_metas -->" => "",
        "<!-- theme_path -->" => "",
        "<!-- additional_styles -->" => "",
        "<!-- additional_scripts -->" => "",
        "<!-- page_title -->" => "This is a page title",
        "<!-- page_header -->" => "This is a page header",
        "<!-- page_primary_menu -->" => "Menu",
        "<!-- page_content -->" => "Content",
        "<!-- page_aside_content -->" => "Addblock",
        "<!-- page_footer -->" => "This is a page footer"
    ];
    var $modules;
    var $config;

    function __construct() {
        $this->config = $this->load_config();        
        $this->modules = $this->load_modules();
    }

    function __hook($hook_name, $param = NULL) {
        $result = null;
        
        foreach ($this->modules as $module_name) {

            $fn = "{$module_name->name}->{$hook_name}";
            write_log("test function '{$fn}'");
            if (method_exists($module_name, $hook_name)) {
                write_log("call function '{$fn}' with param {$param}");

                $result[$module_name->name] = $module_name->$hook_name($param);
            }
        }
        return $result;
    }

    /**
     * This function load configuration site
     */
    function load_config() {
        $config = [];
        $query = "SELECT variable_name, variable_value FROM config_tbl";
        $result = sql_query($query);

        foreach ($result as $value) {
            $config[$value["variable_name"]] = $value["variable_value"];
        }
        return $config;
    }

    function get_template() {
        return load_data(THEMES_PATH . $this->config["default_theme"] . "/index.tpl.php");
    }
    
    function load_modules(){
        $_r = [];
        $result = sql_query("SELECT * FROM modules_tbl");

        foreach ($result as $m) {
            write_log("load module: " . $m["module_name"]);
            include_once(MODULE_PATH . "{$m["module_name"]}/{$m["module_name"]}.class.php");
            $_r[] = new $m["module_name"]();
        }
        return $_r;

    }

    function Html() {
        if (DEBUG) {
            write_log("--- START PAGE GENERATING ---");
            write_log("REQUEST_URI: " . filter_input(INPUT_SERVER, "REQUEST_URI"));
            write_log("REMOTE_ADDR: " . filter_input(INPUT_SERVER, "REMOTE_ADDR"));
        }

        $tpl = $this->get_template();
        
        $this->__hook("preprocess_page");
        Page::$data["<!-- theme_path -->"] = THEMES_PATH . $this->config["default_theme"];
        $this->__hook("postprocess_header");
        $this->__hook("postprocess_menu");
        //$this->__hook("process_");
        $html = fill_template($tpl, Page::$data);
        
        $this->__hook("postprocess_page");
        return $html;
    }

}
