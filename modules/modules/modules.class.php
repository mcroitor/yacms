<?php

class modules {

    // main definition
    var $name = "modules";
    var $version = "20180929";

    function __construct() {
        
    }

    function process_modules_manage() {
        Page::$modules["users"]->check_permissions(users::LEVEL_ADMINISTRATOR);
        $template = load_data(MODULE_PATH . $this->name . "/templates/modules.tpl.php");
        $data = ["<!-- modules-list -->" => "<table>\n"];
        $query = "SELECT * FROM modules_tbl";
        // installed modules
        $data["<!-- modules-list -->"] .= "<tr>"
                . "<th class='width-400 left'>Module Name</th>"
                . "<th class='width-200 left'>Module Version</th>"
                . "<th class='width-50 left'>Remove</th>\n"
                . "<th class='left'>&nbsp;</th></tr>\n";
        $modules = sql_query($query);
        foreach ($modules as $module) {
            $data["<!-- modules-list -->"] .= "<tr>"
                    . "<td>{$module['module_name']}</td>"
                    . "<td>{$module['module_version']}</td>"
                    . "<td><a href='./?q=module/remove/&mid={$module['module_id']}'><div class='icon fa-remove'>&nbsp;</div></a></td>"
                    . "<td>&nbsp;</td></tr>\n";
        }
        $data["<!-- modules-list -->"] .= "</table>";

        Page::$data["<!-- page_content -->"] = fill_template($template, $data);
    }

    function process_module_install() {
        Page::$modules["users"]->check_permissions(users::LEVEL_ADMINISTRATOR);
        if ($this->module_upload() === true) {
            $module_name = explode(".", $_FILES['module']['name'])[0];
            module_install($module_name);
            // TODO: if install failed?
        }
        header("location:./?q=modules/manage");
        exit();
    }

    private function module_upload() {
        switch ($_FILES['module']['error']) {
            case UPLOAD_ERR_OK:
                $filename = $_FILES['module']['tmp_name'];
                break;
            case UPLOAD_ERR_NO_FILE:
                write_log('No file sent.');
                return false;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                write_log('Exceeded filesize limit.');
                return false;
            default:
                write_log('Unknown errors.');
                return false;
        }
        $zip = new ZipArchive();
        if ($zip->open($filename) === true) {
            $zip->extractTo(MODULE_PATH);
            $zip->close();
            return true;
        }
        return false;
    }

    function process_module_remove(){
        Page::$modules["users"]->check_permissions(users::LEVEL_ADMINISTRATOR);
        header("location:./?q=modules/manage");
        exit();
    }
}
