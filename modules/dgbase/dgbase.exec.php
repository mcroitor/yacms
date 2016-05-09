<?php

function dgbase_build_header(&$h){
    $h["style"]["dgbase"] = _style("dgbase", MODULE_PATH."dgbase/");    
    writeLog("{$h["style"]["dgbase"]}");
    return $h;
}

function dgbase_problems_manage(){
    
}

function dgbase_problem_show(){
    
}

function dgbase_problems_show(){
    writeLog("show all problems");
    $query = "SELECT pid, fen, stipulation FROM problem_base LIMIT 12";
    $query_result = sqlQuery($query);
    $html = "<div>";
    foreach ($query_result as $problem) {
        $html .= "<div class='diagram'><img src='" .LIB_PATH . "diagram/?fen=" .$problem["fen"] . "'></div>";
    }
    $html .= "</div>";
    return $html;
}

writeLog("'dgbase' module is loaded");
global $modules;
$modules["dgbase"] = "dgbase";
?>
