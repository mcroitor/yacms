<?php

const WWW_DIR = __DIR__;
const MODULE_DIR = WWW_DIR . "/modules/";
const CORE_DIR = WWW_DIR . "/core/";

$config = new stdClass();

$config->dsn = "sqlite:" . WWW_DIR . "/databasename.db";
$config->debug = true;
$config->debugfile = "php://stdout";
$config->errorlogfile = "error.log";
