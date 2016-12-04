<?php

define("URL", "./");
define("LOG_PATH", URL."log/");
define("THEMES_PATH", URL."themes/");
define("MODULE_PATH", URL."modules/");

define("DEBUG", "1");

/**
 * Function creates html-code reference to CSS.
 * @param string $style_name The name of style file/ Extension may be ommited.
 * @param string $path The path to style file.
 * @return string Html reference created.
 */
function _style(string $style_name, string $path) {
    $stylename = str_replace(".css", "", $style_name);
    return "<link rel='stylesheet' href='{$path}{$stylename}.css' />";
}

/**
 * Function creates html-code reference to script.
 * @param string $script_name The name of script file. Extension may be ommited.
 * @param string $path The path to script file.
 * @param string $type The type of script. Implicit is Javascript
 * @return string Html reference created.
 */
function _script(string $script_name, string $path, $type = 'javascript') {
    $scriptname = str_replace(".js", "", $script_name);
    return "<script type='text/{$type}' src='{$path}{$scriptname}.js'></script>";
}

/**
 * Function creates an html reference.
 * @param string $link Name of reference.
 * @param string $url Reference to page.
 * @param string $style CSS style of reference.
 * @return string Html reference created.
 */
function _link(string $link, $url = "#", $style = 'link') {
    return "<a href='$url' class='$style'>$link</a>";
}

/**
 * Fill template with data indicated in param $data
 * @param string $template
 * @param array $data An associative array, each pair is pattern => value
 * @return string
 */
function fill_template($template, array $data) {
    $html = $template;
    foreach ($data as $pattern => $value) {
        $html = str_replace($pattern, $value, $html);
    }
    return $html;
}

/**
 * Function for logging.
 * @param type $data Indicates what data will be stored.
 * @param type $log_name Name of log file.
 */
function write_log($data, $log_name = "default.log") {
    if (isset($_SESSION["timezone"])) {
        date_default_timezone_set($_SESSION["timezone"]);
    }
    $str = date("Y-m-d H:i:s") . "\t: {$data}\n";
    $log = fopen(LOG_PATH . $log_name, "a");
    fwrite($log, $str);
    fclose($log);
}

/**
 * Default / debug log.
 * @param type $data Indicates what data will be stored.
 */
function debug_log($data) {
    if (DEBUG == "1") {
        write_log($data);
    }
}

/**
 * Simple file loading 
 * @param type $file_name
 * @return type
 */
function load_data($file_name) {
    if (!file_exists($file_name)) {
        $error = "error 01: '{$file_name}' not exists";
        write_log($error, "errors.log");
        exit($error);
    }
    $result = file_get_contents($file_name);
    return $result;
}

/**
 * 
 * @global type $db
 * @param type $query
 * @param type $error
 * @param type $need_fetch
 * @return type
 */
function sql_query($query, $error = "Error: ", $need_fetch = true) {
    global $db;
    write_log($query);

    $array = array();
    $result = $db->query($query);
    if (!$result) {
        $aux = "$error $query, " . $db->error;
        debug_log($aux);
        write_log($aux, "errors.log");
        exit($db->error);
    }

    if ($need_fetch) {
        while ($fetch = $result->fetch_array()) {
            $array[] = $fetch;
        }
    }
    return $array;
}

/**
 * Parse and execute sql dump.
 * @param type $dump
 */
function parse_sqldump($dump) {
    if (file_exists($dump)) {
        $sql = load_data($dump);
        $queries = explode(";", $sql);
        foreach ($queries as $query) {
            $query = trim($query);
            if ($query != '') {
                sql_query($query, "Installation error:", false);
            }
        }
    }
}
