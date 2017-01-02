<?php

/**
 * this is a simple example of module that add an WYSIWYG editor to the 
 * article textarea
 * module registration line: INSERT INTO modules_tbl VALUES(NULL, 'Nicedit', '20170102');
 */

class Nicedit{
    var $name = "Nicedit";
    var $version = "20170102";
    
    function __construct() {
        // TODO ##: do here something
    }
    
    function postprocess_header() {
        Page::$data["<!-- additional_scripts -->"] .= _script("nicedit", LIB_PATH . "nicedit/");
        Page::$data["<!-- additional_scripts -->"] .= _script("addnicedit", MODULE_PATH . $this->name . "/");
        
    }
}