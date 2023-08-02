<?php

class config {
    public const www_dir = __DIR__;
    public const module_dir = self::www_dir . "/module/";
    public const core_dir = self::www_dir . "/core/";

    public const dsn = "sqlite:" . self::www_dir . "/databasename.db";

    public const core = [
        "database/mc/crud",
        "database/mc/database",
        "database/mc/query",
        "logger/mc/logger",
        "router/mc/route",
        "router/mc/router",
        "template/mc/template",
    ];

    public static $debug = true;
    public static $debugfile = "php://stdout";
    public static $errorlogfile = "error.log";

    public static function load() {
        foreach (self::core as $class_name) {
            include_once self::core_dir . "{$class_name}.php";
        }
    }
}

\config::load();