DROP TABLE article_tbl;

DELETE FROM menu_links_tbl WHERE menu_link='./?q=view/articles';
INSERT INTO menu_links_tbl WHERE menu_link='./?q=add/article';

DELETE FROM config_tbl WHERE variable_name='nr_articles';