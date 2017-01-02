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
);

-- login: admin, password: password
INSERT INTO users_tbl VALUES(null, 'admin', 'advwtv/9yU5yQ', 'Admin', 'Super', 'admin@localhost', 100, '0');

-- if module menu is installed
INSERT INTO menu_links_tbl VALUES(null, 'log out', './?q=user/logout', 1);

CREATE TABLE permissions_tbl(
    permission_id INT NOT NULL AUT_INCREMENT,
    level_name VARCHAR(31) NOT NULL,
    user_level INT NOT NULL,
    PRIMARY KEY(permission_id)
);

INSERT INTO permissions_tbl(NULL, 'guest', 0);
INSERT INTO permissions_tbl(NULL, 'administrator', 100);

INSERT INTO modules_tbl VALUES(NULL, 'Users', '20161204');