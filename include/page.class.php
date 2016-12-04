<?php

class Page {

    var $data;

    function __construct() {
        $this->data = [];
    }

    function __hook($hook_name, $param = NULL) {
        $result = null;
        foreach ($this->modules as $module_name) {

            $fn = "{$module_name->name}->{$hook_name}";
            write_log("test function '{$fn}'");
            if (method_exists($module_name, $hook_name)) {
                write_log("call function '{$fn}' with param {$param}");

                $result[$module_name->name] = $module_name->$hook_name($param);
            }
        }
        return $result;
    }

    function Html(){
        $html = "<!doctype html>";
        return $html;
    }
}
