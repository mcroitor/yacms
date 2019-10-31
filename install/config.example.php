<?php

$site = new stdClass();
$config = new stdClass();

$config->dsn = "sqlite:./databasename.db";
$config->debug = true;
$config->debuglog = "php://stdout";
$config->errorlog = "error.log";

// start site populating
$site->config = $config;