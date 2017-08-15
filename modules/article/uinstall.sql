DROP TABLE article_tbl;

DELETE FROM menu_links_tbl WHERE menu_link='./?q=articles/get';
INSERT INTO menu_links_tbl WHERE menu_link='./?q=articles/post';

DELETE FROM config_tbl WHERE variable_name='nr_articles';