<?php

function writeLog($data, $log_name = "default.log") {
    global $config;
    if (isset($config["timezone"]))
        date_default_timezone_set($config["timezone"]);
    else
        date_default_timezone_set("Europe/London");
    $str = date("Y-m-d H:i:s") . "\t: {$data}\n";
    $log = fopen(LOG_PATH . $log_name, "a");
    fwrite($log, $str);
    fclose($log);
}

function includeFile($file_name, $multiplicity = false) {
    if (!file_exists($file_name)) {
        $error = "error 01: '{$file_name}' not exists";
        writeLog($error, "errors.log");
        exit($error);
    }

    if (DEBUG == "1")
        writeLog("included {$file_name} file");
    if ($multiplicity == true)
        include $file_name;
    else
        include_once $file_name;
}

function loadData($file_name) {
    if (!file_exists($file_name)) {
        $error = "error 01: '{$file_name}' not exists";
        writeLog($error, "errors.log");
        exit($error);
    }
    $result = file_get_contents($file_name);
    return $result;
}

function loadConfig() {
    $config = array();
    $result = sqlQuery("SELECT * FROM config");
    foreach ($result as $value) {
        $config[$value["variable_name"]] = $value["variable_value"];
    }
    return $config;
}

function sqlQuery($query, $error = "Error: ", $need_fetch = true) {
    writeLog($query);

    $array = array();
    $result = mysql_query($query);
    if (!$result) {
        $aux = "$error $query, " . mysql_error();

        if (DEBUG == "1")
            writeLog($aux);

        writeLog($aux, "errors.log");
        exit(mysql_error());
    }

    if ($need_fetch) {
        while ($fetch = mysql_fetch_array($result)) {
            $array[] = $fetch;
        }
    }
    return $array;
}

// ------- modules -------
function loadModules() {
    if (file_exists(CACHE_PATH . "modules.php")) {
        includeFile(CACHE_PATH . "modules.php");
        return;
    }

    $query = "SELECT module_name FROM modules WHERE module_enabled=1";

    $modules = sqlQuery($query);

//    foreach ($modules as $module) {
//        includeFile(MODULES_PATH . "{$module['module_name']}/{$module['module_name']}.exec.php");
//    }

    cacheModules($modules);
    includeFile(CACHE_PATH . "modules.php");
}

function cacheModules($modules) {
    $f = fopen(CACHE_PATH . "modules.php", "w");
    fwrite($f, "<?php\n\n");
    foreach ($modules as $module) {
        fwrite($f, "includeFile(MODULE_PATH . \"{$module['module_name']}/{$module['module_name']}.exec.php\");\n");
    }
    fwrite($f, "\n?>");
}

function installModule($module_name) {
    $module_path = MODULES_PATH . "{$module_name}/";
    if (file_exists("{$module_path}install.sql"))
        parseSqlDump("{$module_path}install.sql");

    $query = "INSERT INTO modules VALUES (NULL, '{$module_name}', 0)";
    sqlQuery($query, "module installation error: ", false);
}

function enableModule($module_name) {
    $query = "UPDATE modules SET module_enabled=1 WHERE module_name='{$module_name}'";
    sqlQuery($query, "module enabling error: ", false);
}

function disableModule($module_name) {
    $query = "UPDATE modules SET module_enabled=0 WHERE module_name='{$module_name}'";
    sqlQuery($query, "module disabling error: ", false);
}

function uninstallModule($module_name) {
    $module_path = ROOT_PATH . "{$module_name}/{$module_name}/";
    if (file_exists("{$module_path}uninstall.sql"))
        parseSqlDump("{$module_path}uninstall.sql");

    $query = "DELETE FROM modules WHERE module_name='{$module_name}'";
    sqlQuery($query, "module uninstallation error: ", false);
}

function getNewModules() {
    $all_modules = array();
    $modules_name = array();
    foreach (scandir(MODULES_PATH) as $element) {
        if (is_dir(MODULES_PATH . $element) && file_exists(MODULES_PATH . "{$element}/{$element}.exec.php")) {
            $all_modules[] = $element;
        }
    }

    $query = "SELECT module_name FROM modules";
    $modules = sqlQuery($query);
    foreach ($modules as $value) {
        $modules_name[] = $value["module_name"];
    }

    return array_diff($all_modules, $modules_name);
}

function moduleManager() {
    $html = "";
    
    return $html;
}

// hooks, strbuilders
function __hook($hook_name, $param = NULL) {
    global $modules;
    $result = null;
    foreach ($modules as $module_name) {

        $fn = "{$module_name}_{$hook_name}";
        writeLog("test function '{$fn}'");
        if (function_exists($fn)) {
            writeLog("call function '{$fn}'");
            if ($param == NULL)
                $result = $fn();
            else {
                $result = $fn($param);
            }
        }
    }
    return $result;
}

function _style($style_name, $path) {
    $style_name = str_replace(".css", "", $style_name);
    return "<link rel='stylesheet' href='{$path}{$style_name}.css' />";
}

function _script($script_name, $path, $type = 'javascript') {
    $script_name = str_replace(".js", "", $script_name);
    return "<script type='text/{$type}' src='{$path}{$script_name}.js'></script>";
}

?>
