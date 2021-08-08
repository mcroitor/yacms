<?php
namespace core;
/*
 * The MIT License
 *
 * Copyright 2019 Croitor Mihail <mcroitor@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Description of log
 *
 * @author Croitor Mihail <mcroitor@gmail.com>
 */
class logger {
    var $logfile;
    
    public function __construct($logfile = "default.log") {
        $this->logfile = $logfile;
    }

    private function write($data, $logfile) {
        $file = !empty($logfile) ? $logfile : $this->logfile;
        if (isset($_SESSION["timezone"])) {
            \date_default_timezone_set($_SESSION["timezone"]);
        }
        $str = \date("Y-m-d H:i:s") . "\t: {$data}\n";
        \file_put_contents($file, $str, FILE_APPEND );
    }

    public function write_debug($data) {
        global $site;
        if($site->config->debug === true){
            $this->write($data, $site->config->debugfile);
        }
    }

}
