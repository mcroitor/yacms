CREATE TABLE articles_tbl(
    article_id INT NOT NULL AUTO_INCREMENT,
    article_title VARCHAR(255) NOT NULL,
    article_body TEXT NOT NULL,
    article_author_id INT NOT NULL,
    article_data_published DATETIME NOT NULL,
    PRIMARY KEY(article_id)
);

INSERT INTO menu_links_tbl VALUES (NULL, 'Articles', './?q=view/articles', 0, 10);
INSERT INTO menu_links_tbl VALUES (NULL, 'Add Article', './?q=add/article', 1, 10);

INSERT INTO config_tbl VALUES (NULL, 'nr_articles', '10');

INSERT INTO articles_tbl VALUES(
    NULL,
    'First Message',
    '<p>This is a first page!</p><p>Nice to meet you here!</p>',
    1,
    NOW()
);

INSERT INTO modules_tbl VALUES(NULL, 'Article', '20161221');