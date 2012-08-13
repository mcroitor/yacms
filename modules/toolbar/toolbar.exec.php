<?php

function toolbar_preload_page(){
    global $page;
    if(isset($_SESSION["user_level"]) && $_SESSION["user_level"] > 0){
        $page->html = str_replace("<body>", "<body><div id='toolbar' class='panel'><!-- toolbar --></div>", $page->html);
        $page->html = str_replace("<!-- toolbar -->", buildToolBar(), $page->html);
    }
}

function toolbar_end_build_header(){
    global $page;
    $page->header["style"]["toolbar"] = "<style>
        #toolbar {
            text-align:left;
            height:16px;
            background-color: black;
        }
        </style>";
}

function buildToolBar(){
    
}

writeLog("'toolbar' module is loaded");
global $modules;
$modules["toolbar"] = "toolbar";
?>
