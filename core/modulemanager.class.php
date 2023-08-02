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

namespace core;

class modulemanager {

    public function get_modules() {
        $content = \scandir(\config::module_dir);
        $result = [];
        foreach ($content as $file) {
            if (\is_dir(\config::module_dir . $file) &&
                \file_exists(\config::module_dir . "{$file}/{$file}.class.php")) {
                $result[] = $file;
            }
        }
        return $result;
    }

    public function install($module) {
        global $site;
        if (\file_exists(\config::module_dir . "{$module}/db/install.sql")) {
            $site->database->parse_sqldump(\config::module_dir . "{$module}/db/install.sql");
        }

        include_once \config::module_dir . "{$module}/{$module}.class.php";

        $data = [
            "name" => $module::name(),
            "description" => $module::info(),
            "version" => $module::version()
        ];
        $site->database->insert("module", $data);
    }

    public function uninstall($module) {
        // TODO: implement this!
    }

}
