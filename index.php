<?php
session_start();
if(!isset($_SESSION["user_name"])) $_SESSION["user_level"] = 0;

if(file_exists("./include/defines.php")){
    include_once './include/defines.php';
}
if (file_exists("./include/common.php"))
    include_once "./include/common.php";
else
    exit("error 01: 'common.php' not exists");

if (file_exists("./config.php"))
    include_once "./config.php";
else
    exit("error 01: 'config.php' not exists");
$config = loadConfig();

loadModules();

if (file_exists("./include/page.php"))
    include_once "./include/page.php";
else
    exit("error 01: 'page.php' not exists");

$page = new Page($config["default-style"]);

if(isset($_GET["path"]))
    $page->processPath($_GET["path"]);

echo $page->renderPage();
?>
