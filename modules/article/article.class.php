<?php

/*
 * The MIT License
 *
 * Copyright 2021 XiaomiPRO.
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
 * Description of article
 *
 * @author XiaomiPRO
 */
class article implements module {

    protected $page;
    protected $total;
    
    public function __construct() {
        $this->page = 0;
        $this->total = 12;
    }
    
    public static function info(): string {
        return "";
    }

    public static function name(): string {
        return __CLASS__;
    }

    public static function version(): string {
        return "202105101900";
    }

    public function process(string $param) {
        $chunks = explode("/", $param);
        // unset($chunks[0]);
        $method_name = $chunks[1];
        if(method_exists($this, $method_name)){
            $this->$method_name($chunks);
        }
    }
    
    public function view(array $article_id = []){
        if(empty($article_id)){
            // show page of articles
        }
        else{
            // show article
        }
    }
}
