-- CREATE DATABASE yacms_db;
-- CREATE USER 'yacms_user'@'localhost' IDENTIFIED BY PASSWORD('yapass');
-- grant all on yacms_db.* to 'yacms_user'@'localhost';
 
create table if not exists config_tbl(
    variable_id INTEGER primary key AUTOINCREMENT,
    variable_name text unique not null,
    variable_value text not null,
    variable_type text not null default 'string'
);

/*
 * accepted variable types: integer, float, string
 */

INSERT INTO config_tbl(variable_name, variable_value, variable_type) 
VALUES ('default_theme', 'default', 'string');

create table if not exists modules_tbl(
    module_id INTEGER primary key AUTOINCREMENT,
    module_name text unique not null,
    module_version text not null
);

CREATE TABLE menu_links_tbl (
    menu_id INTEGER PRIMARY KEY AUTOINCREMENT,
    menu_name TEXT NOT NULL,
    menu_link TEXT NOT NULL DEFAULT '#',
    menu_level INTEGER  NOT NULL DEFAULT '0',
    menu_weight INTEGER NOT NULL DEFAULT '10'
);

INSERT INTO menu_links_tbl (menu_name, menu_link, menu_level, menu_weight)
VALUES ('home', './', 0, 1);

-- register Menu module
INSERT INTO modules_tbl (module_name, module_version)
VALUES('Menu', '20161204');

CREATE TABLE users_tbl(
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_login text NOT NULL UNIQUE,
    user_password text NOT NULL,
    user_firstname text NOT NULL,
    user_lastname text NOT NULL DEFAULT 'Doe',
    user_email text NOT NULL,
    user_level INTEGER NOT NULL,
    user_registration INTEGER NOT NULL
);

-- login: admin, password: password
INSERT INTO users_tbl (user_login, user_password, user_firstname, user_lastname, user_email, user_level, user_registration)
VALUES('admin', 'advwtv/9yU5yQ', 'Admin', 'Super', 'admin@localhost', 100, '0');

-- if module menu is installed
INSERT INTO menu_links_tbl (menu_name, menu_link, menu_level, menu_weight)
 VALUES('log out', './?q=user/logout', 1, 1000);
INSERT INTO menu_links_tbl (menu_name, menu_link, menu_level, menu_weight)
VALUES('manage properties', './?q=properties/manage', 100, 10);

CREATE TABLE permissions_tbl(
    permission_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    level_name text NOT NULL,
    user_level INTEGER NOT NULL
);

INSERT INTO permissions_tbl (level_name, user_level)
VALUES ('guest', 0);
INSERT INTO permissions_tbl (level_name, user_level)
VALUES ('user', 1);
INSERT INTO permissions_tbl (level_name, user_level)
VALUES ('administrator', 100);

-- register Users module
INSERT INTO modules_tbl (module_name, module_version)
VALUES('Users', '20161204');

INSERT INTO menu_links_tbl (menu_name, menu_link, menu_level, menu_weight) 
VALUES ('manage modules', './?q=modules/manage', 100, 11);

INSERT INTO modules_tbl (module_name, module_version)
VALUES('modules', '20180929');