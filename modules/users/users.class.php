<?php

class Users {

    var $name = "Users";
    var $version = "20161204";

    function __construct() {
        $_SESSION["user_id"] = empty($_SESSION["user_id"]) ? NULL : $_SESSION["user_id"];
    }

    function postprocess_menu() {
        if ($_SESSION["user_id"] === NULL) {
            Page::$data["<!-- page_primary_menu -->"] .= load_data(MODULE_PATH . $this->name . "/templates/login_form.tpl.php");
        } else {
            Page::$data["<!-- page_primary_menu -->"] .= "<a href='./?q=user/logout'>Log out</a>";
        }
        //return $menu;
    }

    function process_user_login() {
        // TODO #: filter this!
        $login = filter_input(INPUT_POST, "login");
        $password = filter_input(INPUT_POST, "password");
        if (empty($login) && empty($password)) {
            header("location:./");
            exit();
        }
        $crypted = crypt($password, $login);
        $query = "SELECT * FROM users_tbl WHERE user_login='{$login}' AND user_password='{$crypted}'";

        $result = sql_query($query);
        if (count($result) === 1) {
            $_SESSION["user_id"] = $result[0]["user_id"];
            $_SESSION["user_name"] = $result[0]["user_name"];
            $_SESSION["user_level"] = $result[0]["user_level"];
        }
        header("location:./");
        exit();
    }

}
