CREATE TABLE menu_links_tbl (
    menu_id INT NOT NULL AUTO_INCREMENT,
    menu_name VARCHAR(63) NOT NULL,
    menu_link VARCHAR(255) NOT NULL DEFAULT '#',
    menu_level INT NOT NULL DEFAULT '0',
    menu_weight INT NOT NULL DEFAULT '10',
    PRIMARY KEY(menu_id)
);

INSERT INTO menu_links_tbl VALUES (NULL, 'home', './', 0, 1);

INSERT INTO modules_tbl VALUES(NULL, 'Menu', '20161216');