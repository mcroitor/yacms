<?php

class Article {

    var $name = "Article";
    var $version = "20161221";

    function __construct() {
        $_SESSION["articles_start"] = empty($_SESSION["articles_start"]) ? 0 : $_SESSION["articles_start"];
    }
    
    function process_view_articles(){
        Page::$data["<!-- page_content -->"] = "";
        $result = sql_query("SELECT * FROM articles_tbl LIMIT {$_SESSION['articles_start']}, " . Page::$config['nr_articles']);
        $template = load_data(MODULE_PATH . $this->name . "/templates/article.tpl.php");
        foreach ($result as $article) {
            $data = [];
            $data["<!-- article_title -->"] = $article["article_title"];
            $data["<!-- article_info -->"] = "<span class='author'>Author</span>, Published <span class='date'>{$article["article_date"]}</span>";
            $data["<!-- article_body -->"] = $article["article_body"];
            $data["<!-- article_footer -->"] = "";
            Page::$data["<!-- page_content -->"] .= fill_template($template, $data);
        }
    }
}
