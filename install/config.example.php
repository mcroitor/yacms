<?php

$site = new stdClass();
$config = new stdClass();

$config->dsn = "sqlite:./databasename.db";
$config->debug = true;
$config->debugfile = "php://stdout";
$config->errorlogfile = "error.log";

// start site populating
$site->config = $config;