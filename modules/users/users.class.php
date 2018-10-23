<?php

// TODO #: refactor this to methods process_<method>
class users {

    // user level
    const LEVEL_GUEST = 0;
    const LEVEL_USER = 1;
    const LEVEL_ADMINISTRATOR = 100;

    // main definition
    var $name = "users";
    var $version = "20161204";

    function __construct() {
        $_SESSION["user_id"] = empty($_SESSION["user_id"]) ? NULL : $_SESSION["user_id"];
        $_SESSION["user_level"] = empty($_SESSION["user_level"]) ? 0 : $_SESSION["user_level"];
    }

    function postprocess_menu() {
        if (empty($_SESSION["user_id"])) {
            Page::$data["<!-- page_primary_menu -->"] .= load_data(MODULE_PATH . $this->name . "/templates/login_form.tpl.php");
        }
        //return $menu;
    }

    function process_user_login() {
        // TODO #: filter this!
        $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
        if (empty($login) && empty($password)) {
            header("location:./");
            exit();
        }
        $crypted = crypt($password, $login);
        $query = "SELECT * FROM users_tbl WHERE user_login='{$login}' AND user_password='{$crypted}'";
        $result = sql_query($query);
        if (count($result) === 1) {
            $_SESSION["user_id"] = $result[0]["user_id"];
            $_SESSION["user_name"] = $result[0]["user_firstname"];
            $_SESSION["user_level"] = $result[0]["user_level"];
            Page::__hook("user_login");
        }
        header("location:./");
        exit();
    }

    function process_user_logout() {
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_name"]);
        unset($_SESSION["user_level"]);
        Page::__hook("user_logout");
        header("location:./");
        exit();
    }

    function check_permissions($level, $redirect = true, $redirect_url = "./") {
        if ($_SESSION["user_level"] < $level) {
            if ($redirect == true) {
                header("location:{$redirect_url}");
                exit();
            }
            return false;
        }
        return true;
    }

    function process_properties_manage() {
        $this->check_permissions(users::LEVEL_ADMINISTRATOR);
        $template = load_data(MODULE_PATH . $this->name . "/templates/properties.tpl.php");
        $query = "SELECT variable_id, variable_name, variable_value, variable_type FROM config_tbl";
        $result = sql_query($query);
        $data = ["<!-- properties-list -->" => "<form method='post' action='./?q=properties/update'>\n"
            . "<table>\n<tr>"
            . "<th class='width-200 right'>Variable Name</th>"
            . "<th class='width-200 right'>Variable Value</th></tr>\n"];
        foreach ($result as $value) {
            $data["<!-- properties-list -->"] .= "<tr><td class='right'>{$value['variable_name']}</td>"
                    . "<td class='right'><input type='text' name='{$value['variable_name']}' value='{$value['variable_value']}' /></td></tr>\n";
        }
        $data["<!-- properties-list -->"] .= "<tr><td colspan='2'><input type='submit'></td></tr>\n</table>";
        Page::$data["<!-- page_content -->"] = fill_template($template, $data);
    }

    function process_properties_update() {
        $config = filter_input_array(INPUT_POST);
        Page::update_config($config);
        header("location:./?q=properties/manage");
        exit();
    }

}
