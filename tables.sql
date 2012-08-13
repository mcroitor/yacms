CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_level INT,
    PRIMARY KEY(user_id)
);

CREATE TABLE modules (
    module_id INT NOT NULL AUTO_INCREMENT,
    module_name VARCHAR(255) NOT NULL,
    module_enabled INT,
    PRIMARY KEY(module_id)
);

CREATE TABLE datas (
    data_id INT NOT NULL AUTO_INCREMENT,
    data_dt VARCHAR(64),
    data_author_id INT,
    data_title VARCHAR(255),
    data_content TEXT,
    PRIMARY KEY(data_id)
);

CREATE TABLE config (
    variable_id INT NOT NULL AUTO_INCREMENT,
    variable_name VARCHAR(255),
    variable_value VARCHAR(255),
    PRIMARY KEY (variable_id)
);

CREATE TABLE menu_links (
    menu_link_id INT NOT NULL AUTO_INCREMENT,
    menu_link_name VARCHAR(255),
    menu_link_link VARCHAR(255),
    menu_link_group VARCHAR(255),
    menu_link_weight INT,
    menu_link_level INT,
    PRIMARY KEY(menu_link_id)
);

INSERT INTO config VALUES (null, "timezone", "Europe/Chisinau");
INSERT INTO config VALUES (null, "default-style", "default");

INSERT INTO menu_links VALUES (null, "home", "./", "primary", 0, 0);
