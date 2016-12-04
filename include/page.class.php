<?php

class Page {

    var $data;
    var $config;

    function __construct() {
        $this->config = $this->load_config();
        $this->data = [];
        $this->data["<!-- additional_metas -->"] = "";
        $this->data["<!-- theme_path -->"] = THEMES_PATH . $this->config["default_theme"];
        $this->data["<!-- additional_styles -->"] = "";
        $this->data["<!-- additional_scripts -->"] = "";
        $this->data["<!-- page_title -->"] = "This is a page title";
        $this->data["<!-- page_header -->"] = "This is a page header";
        $this->data["<!-- page_primary_menu -->"] = "Menu";
        $this->data["<!-- page_content -->"] = "Content";
        $this->data["<!-- page_aside_content -->"] = "Addblock";
        $this->data["<!-- page_footer -->"] = "This is a page footer";
        
    }

    static function __hook($hook_name, $param = NULL) {
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

    function Html() {
        if (DEBUG) {
            write_log("--- START PAGE GENERATING ---");
            write_log("REQUEST_URI: " . filter_input(INPUT_SERVER, "REQUEST_URI"));
            write_log("REMOTE_ADDR: " . filter_input(INPUT_SERVER, "REMOTE_ADDR"));
        }

        $tpl = $this->get_template();

        $html = fill_template($tpl, $this->data);
        return $html;
    }

}
