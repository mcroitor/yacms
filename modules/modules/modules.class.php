<?php

class users {

    
    // main definition
    var $name = "users";
    var $version = "20180929";

    function __construct() {
    }

    function process_modules_manage(){
        Page::$modules["users"]->check_permissions(users::LEVEL_ADMINISTRATOR);
        $template = load_data(MODULE_PATH . $this->name . "/templates/modules.tpl.php");
        $data = ["<!-- modules-list -->" => ""];
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
