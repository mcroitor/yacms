<?php

/**
 * @author Croitor Mihail <mcroitor@gmail.com>
 */

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    define("SEP", "\\");
} else {
    define("SEP", "/");
}

define("URL", $_SERVER["DOCUMENT_ROOT"] . SEP);
if (defined("LOG_PATH") === false) {
    define("LOG_PATH", URL . "log" . SEP);
}
if (defined("THEMES_PATH") === false) {
    define("THEMES_PATH", URL . "themes" . SEP);
}
if (defined("MODULE_PATH") === false) {
    define("MODULE_PATH", URL . "modules" . SEP);
}
if (defined("LIB_PATH") === false) {
    define("LIB_PATH", URL . "libs" . SEP);
}
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
function _link($link, $url = "#", $style = 'link') {
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
 * Simple wrapper for sql queries. The use of popular frameworks
 * may be better solution
 * @global type $db
 * @param type $query
 * @param type $error
 * @param type $need_fetch
 * @return type
 */
function sql_query($query, $error = "Error: ", $need_fetch = true) {
    global $pdo;
    write_log($query);

    $array = array();
    $result = $pdo->query($query);
    if ($result === false) {
        $aux = "{$error} {$query}: "
                . $pdo->errorInfo()[0]
                . " : "
                . $pdo->errorInfo()[1]
                . ", message = "
                . $pdo->errorInfo()[2];
        debug_log($aux);
        write_log($aux, "errors.log");
        exit($aux);
    }

    if ($need_fetch) {
        $array = $result->fetchAll();
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
        $sql = str_replace("\n\r", "\n", $sql);
        $sql = str_replace("\r\n", "\n", $sql);
        $sql = str_replace("\n\n", "\n", $sql);
        $queries = explode(";", $sql);
        foreach ($queries as $query) {
            $query = trim($query);
            if ($query != '') {
                sql_query($query, "Installation error:", false);
            }
        }
    }
}

/**
 * create a function name from path. path is transmitted get parameter q 
 * @param type $path
 * @param type $prefix
 * @param type $postfix
 * @return type
 */
// TODO #: refactor this to methods process_<method>
function make_hook_name($path, $prefix = "", $postfix = "") {
    $fn = str_replace("_", "", $path);
    $fn = str_replace("/", "_", $fn);
    if ($prefix != "") {
        $fn = "{$prefix}_{$fn}";
    }
    if ($postfix != "") {
        $fn = "{$fn}_{$postfix}";
    }
    /* if (DEBUG) writeLog("test function {$fn}"); */
    return $fn;
}

/**
 * Test module and install
 * @param type $module_name
 */
function module_install($module_name) {
    $module_folder = MODULE_PATH . "{$module_name}/";
    $module_path = $module_folder . "{$module_name}.class.php";
    $module_install_sql = $module_folder . "install.sql";

    if (file_exists($module_path) === FALSE) {
        return false;
    }
    if (file_exists($module_install_sql) === TRUE) {
        try {
            parse_sqldump($module_install_sql);
        } catch (Exception $e) {
            write_log($e->getMessage(), "errors.log");
            write_log("failed execute {$module_install_sql}", "errors.log");
            return false;
        }
    }

    include_once $module_path;
    $module = new $module_name();
    sql_query(
            "INSERT INTO modules_tbl VALUES ("
            . "NULL, "
            . "'{$module->name}', "
            . "'{$module->version}')",
            "Module {$module_name} registration error: ",
            false);
    return true;
}
