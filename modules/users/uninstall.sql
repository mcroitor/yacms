DROP TABLE IF EXISTS users_tbl;
DROP TABLE IF EXISTS permissions_tbl;

DELETE FROM menu_links_tbl WHERE menu_name='log out' AND menu_link='./?q=user/logout'
DELETE FROM menu_links_tbl WHERE menu_name='manage properties' AND menu_link='./?q=properties/manage'