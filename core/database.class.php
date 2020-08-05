<?php

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
 * Description of database
 *
 * @author Croitor Mihail <mcroitor@gmail.com>
 */
class database {

    //put your code here
    var $pdo;

    function __construct() {
        global $site;
        try {
            $this->pdo = new PDO($site->config->dsn);
        } catch (Exception $exc) {
            die('DB Error');
        }
    }

    public function query_sql($query, $error = "Error: ", $need_fetch = true) {
        global $site;
        $array = array();
        $result = $this->pdo->query($query);
        if ($result === false) {
            $aux = "{$error} {$query}: "
                    . $this->pdo->errorInfo()[0]
                    . " : "
                    . $this->pdo->errorInfo()[1]
                    . ", message = "
                    . $this->pdo->errorInfo()[2];
            $site->logger->write_debug($aux);
            //$site->logger->write($aux);
            exit($aux);
        }
        if ($need_fetch) {
            $array = $result->fetchAll();
        }
        return $array;
    }

    public function parse_sqldump($dump) {
        if (file_exists($dump)) {
            $sql = str_replace(["\n\r", "\r\n", "\n\n"], "\n", file_get_contents($dump));
            $queries = explode(";", $sql);
            foreach ($queries as $query) {
                $query = $this->strip_sqlcomment(trim($query));
                if ($query != '') {
                    $this->query_sql($query, "parse error:", false);
                }
            }
        }
    }

    private function strip_sqlcomment($string = '') {
        $RXSQLComments = '@(--[^\r\n]*)|(/\*[\w\W]*?(?=\*/)\*/)@ms';
        return (($string == '') ? '' : preg_replace($RXSQLComments, '', $string));
    }

    /**
     * select $data from $table 
     * @param string $table
     * @param string[] $data
     */
    public function select($table, $data){
        $fields = implode(", ", $data);        
        $query = "SELECT {$fields} FROM {$table}";
        return $this->query_sql($query);
    }
}

$site->database = new database();
