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
 * Description of user
 *
 * @author Croitor Mihail <mcroitor@gmail.com>
 */
class user implements module {

    const GUEST = 0;
    const USER  = 1;
    const ADMIN = 100;
    
    private $name;
    private $level;
    
    
    public function __construct() {
        session_start();
        if(empty($_SESSION['user'])){
            $_SESSION['user'] = [];
            $_SESSION['user']['level'] = user::GUEST;
            $_SESSION['user']['name'] = 'Guest';
        }
        $this->name = $_SESSION['user']['name'];
        $this->level = $_SESSION['user']['level'];
    }
    
    public function data() {
        global $site;
        $site->logger->write_debug("complete page with login form");
        if($this->level === user::GUEST){
            $site->page->data["<!-- page_aside_content -->"] = file_get_contents(MODULE_DIR . "user/templates/login.template.php");
        }
        else{
            $site->page->data["<!-- page_aside_content -->"] = file_get_contents(MODULE_DIR . "user/templates/logout.template.php");
        }
    }
    
    public function process(string $post): void {
        global $site;
        $site->logger->write_debug("user->process() call.");
        $chunks = explode("/", $post);
        unset($chunks[0]);
        $method_name = implode("_", $chunks);
        if(method_exists($this, $method_name)){
            $this->$method_name();
        }
    }

    private function login(): void {
        // TODO #: authentication
        global $site;
        $db = $site->database;
        $username = filter_input(INPUT_POST, "username");
        $password = filter_input(INPUT_POST, "password");
        $key = crypt($username . $password, $password);
        $what = ["username" => $username, "password" => $key];
        if($db->exists("user", $what)){
            $user = $db->select("user", ["username", "email", "level"], $what)[0];
            $this->name = $user["username"];
            $this->level = $user["level"];
        }
        $_SESSION["user"]["name"] = $this->name;
        $_SESSION["user"]["level"] = $this->level;
    }
    
    private function logout(): void {
        session_destroy();
        header("location:/");
        exit();
    }

    public function info(): string {
        return "";
    }

    public function name(): string {
        return "user";
    }

    public function version(): string {
        return "202105101100";
    }

}
