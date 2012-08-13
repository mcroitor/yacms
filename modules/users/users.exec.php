<?php

/*
 * TODO #1: Add code for logging
 */

function users_login() {
    if (!isset($_REQUEST["user_name"]) || !(isset($_REQUEST["user_password"]))) {
        __hook("log_in_fail");

        header("location:./");
        exit();
    }
    $user_name = $_REQUEST["user_name"];
    $user_password = $_REQUEST["user_password"];
    $crypt = crypt($user_name, $user_password);
    $query = "SELECT * FROM users WHERE user_name='{$user_name}' AND user_password='{$crypt}'";
    $result = sqlQuery($query);
    if (!isset($result[0])) {
        __hook("log_in_fail");

        header("location:./");
        exit();
    }
    $_SESSION["user_name"] = $user_name;
    $_SESSION["user_level"] = $result[0]["user_level"];

    __hook("log_in_accept");
    header("location:./");
    exit();
}

function users_logout() {
    unset($_SESSION["user_name"]);
    unset($_SESSION["user_level"]);

    __hook("log_out");

    header("location:./");
    exit();
}

function register_form() {
    $html = "<div class='panel flat'><form method='POST' action='./?path=login'>
            <input type='text' name='user_name' id='user_id' class='editable' />
            <input type='password' name='user_password' id='user_password' class='editable' />
            <input type='submit' />
          </form></div>";
    return $html;
}

function users_end_build_menu() {
    global $page;
    $user_menu = null;
    if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] == 0) {
        $user_menu = register_form();
    } else {
        $query = "SELECT * FROM menu_links WHERE menu_link_level<={$_SESSION['user_level']} AND menu_link_group='users' ORDER BY menu_link_weight ASC";
        $links = sqlQuery($query);

        $page->body["menu"][] = "<div class='menu'><div class='group'>user menu:</div>";
        foreach ($links as $value) {
            $menu_link = $value["menu_link_link"];
            $menu_name = $value["menu_link_name"];

            $page->body["menu"][] = "<a href='{$menu_link}' class='menu-button panel'>{$menu_name}</a>";
        }
        $page->body["menu"][] = "</div>";
    }

    $page->body["menu"][] = $user_menu;
}

writeLog("'Users' module is loaded");
global $modules;
$modules["users"] = "users";
?>
