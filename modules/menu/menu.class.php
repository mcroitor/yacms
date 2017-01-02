<?php

class Menu{
    var $name = "Menu";
    var $version = "20161216";
    
    function __construct(){
        
    }
    
    function postprocess_header(){
        Page::$data["<!-- page_primary_menu -->"] = "";
        $query = "SELECT * FROM menu_links_tbl WHERE menu_level <= {$_SESSION['user_level']} ORDER BY menu_weight ASC";
        $result = sql_query($query);
        foreach ($result as $r) {
            Page::$data["<!-- page_primary_menu -->"] .= _link(strtoupper($r["menu_name"]), $r["menu_link"]);
        }
        Page::__hook("postprocess_menu");
    }
}
