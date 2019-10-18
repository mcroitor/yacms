CREATE TABLE articles_tbl(
    article_id INT NOT NULL AUTO_INCREMENT,
    article_title VARCHAR(255) NOT NULL,
    article_body TEXT NOT NULL,
    article_author_id INT NOT NULL,
    article_date_published DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(article_id)
) DEFAULT CHARSET=utf8;

INSERT INTO menu_links_tbl (menu_name, menu_link, menu_level, menu_weight) 
VALUES ('Articles', './?q=article/get', 0, 10);
INSERT INTO menu_links_tbl (menu_name, menu_link, menu_level, menu_weight)
VALUES ('Add Article', './?q=article/post', 1, 10);

INSERT INTO config_tbl (variable_name, variable_value, variable_type)
VALUES ('nr_articles', '10', 'integer');

INSERT INTO articles_tbl (article_title, article_body, article_author_id, article_date_published)
VALUES(
    'First Message',
    '<p>This is a first page!</p><p>Nice to meet you here!</p>',
    1,
    NOW()
);

-- INSERT INTO modules_tbl (module_name, module_version) VALUES(NULL, 'Article', '20161221');