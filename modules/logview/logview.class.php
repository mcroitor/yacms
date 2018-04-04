<?php
/**
 * TODO #1: add pagination
 * TODO #2: realize access to log
 */
class logview{
    var $name = "logview";
    var $version = "20180403";
    
    var $current_page = 0;
    var $lines_in_page = 20;
    
    
    function process_log_view(){
        Page::$modules["users"]->check_permissions(users::LEVEL_ADMINISTRATOR);
        
        $log_file = LOG_PATH . "errors.log";
        $logdata = file($log_file);
        $result = "";
        for($i = 0; $i != $this->lines_in_page; ++$i){
            $index = $i + $this->current_page * $this->lines_in_page;
            if(isset($logdata[$index]) == false){
                break;
            }
            $result .= $logdata[$index] . "\n";
        }
        Page::$data["<!-- page_content -->"] = "<h3>Error log:</h3>"
                . "<div class='center'><textarea class='width-90p height-100'>{$result}</textarea></div>";
    }
}
