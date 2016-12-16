<?php

class Users{
    var $name = "Users";
    var $version = "20161204";
    
    function __construct() {
        $_SESSION["user_id"] = empty($_SESSION["user_id"]) ? NULL : $_SESSION["user_id"];
    }
    
    function postprocess_menu(){
        if($_SESSION["user_id"] === NULL){
            Page::$data["<!-- page_primary_menu -->"] .= load_data(MODULE_PATH . $this->name . "/templates/login_form.tpl.php");
        }
        else{
            Page::$data["<!-- page_primary_menu -->"] .= "<a href='./?q=logout'>Log out</a>";
        }
        //return $menu;
    }
}