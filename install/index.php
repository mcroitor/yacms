<?php
session_start();
define("LOG_PATH", "../log/");
define("MODULE_PATH", "../modules/");
include_once "../include/common.lib.php";

$_SESSION["__stage"] = empty($_SESSION["__stage"]) ? 0 : $_SESSION["__stage"];

//includeFile("../config.php");
echo "<h2>setup</h2>";
echo "<h3>stage {$_SESSION['__stage']}</h3>";

if ($_SESSION["__stage"] === 0) {
    unset($_SESSION["__stage"]);
    if (file_exists("../config.php")) {
        die("<h2>site is installed!</h2>");
    }

    echo "<h3>Configuring...</h3>";
    echo "<fieldset><legend>database configuration</legend>";
    echo "<form method='post'><table>";
    echo "<tr><td>host</td><td><input type='text' name='host' value='localhost' required='required' /></td></tr>";
    echo "<tr><td>user</td><td><input type='text' name='user' required='required' /></td></tr>";
    echo "<tr><td>password</td><td><input type='text' name='password' required='required' /></td></tr>";
    echo "<tr><td>DB name</td><td><input type='text' name='dbname' required='required' /></td></tr>";
    echo "<tr><td colspan='2'><input type='submit' value='install' /></td></tr>";
    $_SESSION["__stage"] = 1;
    exit();
}

if ($_SESSION["__stage"] === 1) {
    echo "<h3>configuring...</h3>";
    unset($_SESSION["__stage"]);
    $cfgfile = file_get_contents("./config.sample.php");

    $data = [];
    $data["// "] = "";
    $data["localhost_"] = filter_input(INPUT_POST, "host");
    $data["root_"] = filter_input(INPUT_POST, "user");
    $data["password_"] = filter_input(INPUT_POST, "password");
    $data["db_name_"] = filter_input(INPUT_POST, "dbname");

    file_put_contents("../config.php", fill_template($cfgfile, $data));
    echo "<p>done!</p>";
    echo "<p>install tables ... </p>";
    include_once '../config.php';
    parse_sqldump("install.sql");
    echo "<p>tables installed!</p>";
    echo "<form method='post'><input type='submit' value='Next' /></form>";
    $_SESSION["__stage"] = 2;
    exit();
}

if ($_SESSION["__stage"] === 2) {
    unset($_SESSION["__stage"]);
    echo "<h3>installing modules...</h3>";
    // insert into modules_tbl values (null, 'users', '20141205', 1);
    $modules = ["menu", "users", "article"];
    include_once '../config.php';
    foreach ($modules as $module) {
        echo "<ul>install module '{$module}'";
        $result = module_install($module);
        $tmp = $result ? "done!" : "failed!";
        echo " ... {$tmp}</ul>";
    }
    echo "<form method='post'><input type='submit' value='Next' /></form>";
    $_SESSION["__stage"] = 3;
    exit();
}

if ($_SESSION["__stage"] === 3) {
    unset($_SESSION["__stage"]);
    echo "<h3>Setting up site...</h3>";
    include_once '../config.php';

    echo "<form method='post'><input type='submit' value='Next' /></form>";
    $_SESSION["__stage"] = 4;
    exit();
}

if ($_SESSION["__stage"] === 4) {
    unset($_SESSION["__stage"]);
    echo "<h3>Done!</h3>";
    echo "<form method='post'><input type='submit' value='Done' /></form>";
    exit();
}
