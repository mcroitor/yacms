<?php

const WWW_DIR = __DIR__;
const MODULE_DIR = WWW_DIR . "/modules/";

$site = new stdClass();
$config = new stdClass();

$config->dsn = "sqlite:./databasename.db";
$config->debug = true;
$config->debugfile = "php://stdout";
$config->errorlogfile = "error.log";

// start site populating
$site->config = $config;