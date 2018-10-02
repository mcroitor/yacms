<?php

class modules {    
    // main definition
    var $name = "modules";
    var $version = "20180929";

    function __construct() {
    }

    function process_modules_manage(){
        Page::$modules["users"]->check_permissions(users::LEVEL_ADMINISTRATOR);
        $template = load_data(MODULE_PATH . $this->name . "/templates/modules.tpl.php");
        $data = ["<!-- modules-list -->" => "<table>"];
        $query = "SELECT * FROM modules_tbl";
        // installed modules
        $data["<!-- modules-list -->"] .= "<tr><th class='width-200 left'>Module Name</th><th class='width-200 left'>Module Version</th></tr>";
        $modules = sql_query($query);    
        foreach ($modules as $module) {
            $data["<!-- modules-list -->"] .= "<tr><td>{$module['module_name']}</td><td>{$module['module_version']}</td></tr>";
        }
        $data["<!-- modules-list -->"] .= "</table>";

        Page::$data["<!-- page_content -->"] = fill_template($template, $data);
    }
    
    function process_module_install(){
        Page::$modules["users"]->check_permissions(users::LEVEL_ADMINISTRATOR);
        $module_name = filter_input(INPUT_POST, "module_name", FILTER_SANITIZE_STRING);
        module_install($module_name);
        header("location:./?q=modules/manage");
        exit();
    }
}
