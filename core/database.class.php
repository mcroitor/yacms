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
 * PDO wrapper
 *
 * @author Croitor Mihail <mcroitor@gmail.com>
 */
class database {

    //put your code here
    private $pdo;

    public function __construct() {
        global $site;
        try {
            $this->pdo = new PDO($site->config->dsn);
        } catch (Exception $ex) {
            die('DB init Error: ' . $ex->getMessage());
        }
    }

    /**
     * Common query method
     * @global string $site
     * @param string $query
     * @param string $error
     * @param bool $need_fetch
     * @return array
     */
    public function query_sql(string $query, string $error = "Error: ", bool $need_fetch = true): array {
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

    /**
     * Method for dump parsing and execution
     * @param string $dump
     */
    public function parse_sqldump(string $dump) {
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

    /**
     * Method that removes SQL comments, used for dump execution.
     * @param string $string
     * @return string
     */
    private function strip_sqlcomment(string $string = ''): string {
        $RXSQLComments = '@(--[^\r\n]*)|(/\*[\w\W]*?(?=\*/)\*/)@ms';
        return (empty($string) ? '' : preg_replace($RXSQLComments, '', $string));
    }

    /**
     * Simplified selection.
     * @param string $table
     * @param array $data enumerate columns for selection. Sample: ['id', 'name'].
     * @param array $where associative conditions.
     * @param array $limit definition sample: ['from' => '1', 'total' => '100'].
     * @return array
     */
    public function select(string $table, array $data = ['*'], array $where = [], array $limit = []): array {
        $fields = implode(", ", $data);

        $query = "SELECT {$fields} FROM {$table}";
        if (!empty($where)) {
            $tmp = [];
            foreach ($where as $key => $value) {
                $tmp[] = "{$key}='{$value}'";
            }
            $query .= " WHERE " . implode(" AND ", $tmp);
        }
        if (!empty($limit)) {
            $query .= "LIMIT {$limit['from']}, {$limit['total']}";
        }

        return $this->query_sql($query);
    }

    /**
     * Delete rows from table <b>$table</b>. Condition is required.
     * @param string $table
     * @param array $conditions
     * @return array
     */
    public function delete(string $table, array $conditions): array {
        $tmp = [];
        foreach ($conditions as $key => $value) {
            $tmp[] = "{$key}={$value}";
        }
        $query = "DELETE FROM {$table} WHERE " . implode(" AND ", $tmp);
        return $this->query_sql($query, "Error: ", false);
    }

    /**
     * Update fields <b>$values</b> in table <b>$table</b>. <b>$values</b> and 
     * <b>$conditions</b> are required. 
     * @param string $table
     * @param array $values
     * @param array $conditions
     * @return array
     */
    public function update(string $table, array $values, array $conditions): array {
        $tmp1 = [];
        foreach ($conditions as $key => $value) {
            $tmp1[] = "{$key}='{$value}'";
        }
        $tmp2 = [];
        foreach ($values as $key => $value) {
            $tmp2[] = "{$key}='{$value}'";
        }

        $query = "UPDATE {$table} SET " . implode(", ", $tmp2) . " WHERE " . implode(" AND ", $tmp1);
        return $this->query_sql($query, "Error: ", false);
    }

    /**
     * Check if exists row with value(s) in table.
     * @param string $table
     * @param array $where
     * @return bool
     */
    public function exists(string $table, array $where): bool{
        $result = $this->select($table, ["count(*) as count"], $where);
        return $result[0]["count"] > 0;
    }
}

$site->database = new database();
