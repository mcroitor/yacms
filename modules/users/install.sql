DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(255),
    user_password VARCHAR(255),
    user_level INT,
    PRIMARY KEY(user_id)
);

INSERT INTO users VALUES (null, "root", "paIZ4ohqoSopg", 15);

INSERT INTO menu_links VALUES (null, "logout", "./?path=logout", "primary", 100, 1);