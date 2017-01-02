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
            $data["<!-- article_info -->"] = "<span class='author'>Author</span>, Published <span class='date'>{$article["article_date_published"]}</span>";
            $data["<!-- article_body -->"] = $article["article_body"];
            $data["<!-- article_footer -->"] = "";
            Page::$data["<!-- page_content -->"] .= fill_template($template, $data);
        }
    }
    
    function process_add_article(){
        // TODO ##: check permissions
        $template = load_data(MODULE_PATH . $this->name . "/templates/article_form.tpl.php");
        Page::$data["<!-- page_content -->"] = fill_template($template, []);
    }
    
    function process_article_add(){
        // TODO ##: check permissions
        // TODO ##: insert new article in the table
        $article_title = filter_input(INPUT_POST, "article_title");
        $article_body = filter_input(INPUT_POST, "article_body");
        $article_author_id = $_SESSION["user_id"];
        
        sql_query("INSERT INTO articles_tbl VALUES (NULL, "
                . "'{$article_title}', "
                . "'{$article_body}', "
                . "{$article_author_id}, "
                . "NULL)", "article adding error: ", false);
    }
}
