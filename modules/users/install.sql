CREATE TABLE users_tbl(
    user_id INT NOT NULL AUTO_INCREMENT,
    user_login VARCHAR(63) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    user_firstname VARCHAR(63) NOT NULL,
    user_lastname VARCHAR(63) NOT NULL DEFAULT 'Doe',
    user_email VARCHAR(127) NOT NULL,
    user_level INT NOT NULL,
    user_registration INT NOT NULL,
    PRIMARY KEY(user_id)
) DEFAULT CHARSET=utf8;

-- login: admin, password: password
INSERT INTO users_tbl (user_login, user_password, user_firstname, user_lastname, user_email, user_level, user_registration)
VALUES(null, 'admin', 'advwtv/9yU5yQ', 'Admin', 'Super', 'admin@localhost', 100, '0');

-- if module menu is installed
INSERT INTO menu_links_tbl (menu_name, menu_link, menu_level, menu_weight)
VALUES(null, 'log out', './?q=user/logout', 1, 1000);
INSERT INTO menu_links_tbl (menu_name, menu_link, menu_level, menu_weight)
VALUES(null, 'manage properties', './?q=properties/manage', 100, 10);

CREATE TABLE permissions_tbl(
    permission_id INT NOT NULL AUTO_INCREMENT,
    level_name VARCHAR(31) NOT NULL,
    user_level INT NOT NULL,
    PRIMARY KEY(permission_id)
) DEFAULT CHARSET=utf8;

INSERT INTO permissions_tbl (level_name, user_level)VALUES (NULL, 'guest', 0);
INSERT INTO permissions_tbl (level_name, user_level)VALUES (NULL, 'user', 1);
INSERT INTO permissions_tbl (level_name, user_level)VALUES (NULL, 'administrator', 100);

-- INSERT INTO modules_tbl (module_name, module_version) VALUES(NULL, 'Users', '20161204');