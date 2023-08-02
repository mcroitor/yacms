<?php

namespace module\article;
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

use \core\page;

/**
 * Description of article
 *
 * @author XiaomiPRO
 */
class article implements \core\module
{

    protected $page;
    protected $total;

    public function __construct()
    {
        $this->page = 0;
        $this->total = 12;
    }

    public static function info(): string
    {
        return "";
    }

    public static function name(): string
    {
        return __CLASS__;
    }

    public static function version(): string
    {
        return "202105101900";
    }

    public function process(string $param)
    {
        $chunks = \explode("/", $param);
        $methodName = $chunks[1];
        if (\method_exists($this, $methodName)) {
            $this->$methodName($chunks);
        }
    }

    public function view(array $articleId = [])
    {
        global $site;
        $database = $site->database;
        $result = "";
        $tpl = \file_get_contents(\config::module_dir . "/article/templates/article.template.php");
        $generator = new \mc\template($tpl, ["prefix" => "<!-- ", "suffix" => " -->"]);

        if (empty($articleId)) {
            // show page of articles
            $articles = $database->select(
                "article", ['*'], [],
                ['offset' => $this->page, 'limit' => $this->total]
            );
            foreach ($articles as $article) {
                $result .= $generator->fill($article)->value();
            }
        } else {
            // show article
            $article = $database->select("article", ["*"], ["id" => $articleId[0]]);
            $result .= $generator->fill($article)->value();
        }
        return $result;
    }

    public function data() {
        global $site;
        $site->page->data[page::CONTENT] = $this->view([]);
        $site->page->data[page::PRIMARY_MENU] =
            "<a href='./?q=article/create'>create article</a>";
    }
}
