<?php
$dbuser = "root";
$dbpwd = "";
$dbname = "composer_db";
$dbhost = "localhost";

$db = mysql_connect( $dbhost, $dbuser, $dbpwd) or die("<h2>sql connection error</h2>");
mysql_query('SET NAMES utf8');
mysql_select_db($dbname, $db) or die("<h2>database selection error</h2>".mysql_error());
?>
