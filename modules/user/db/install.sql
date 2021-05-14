CREATE TABLE user(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    login text NOT NULL UNIQUE,
    password text NOT NULL,
    firstname text NOT NULL,
    lastname text NOT NULL DEFAULT 'Doe',
    email text NOT NULL,
    level INTEGER NOT NULL,
    registration INTEGER NOT NULL
);

-- login: admin, password: password
INSERT INTO user (login, password, firstname, lastname, email, level, registration)
VALUES('admin', 'advwtv/9yU5yQ', 'Admin', 'Super', 'admin@localhost', 100, '0');

CREATE TABLE permissions(
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    level_name text NOT NULL,
    user_level INTEGER NOT NULL
);

INSERT INTO permissions (level_name, user_level) VALUES ('guest', 0);
INSERT INTO permissions (level_name, user_level) VALUES ('user', 1);
INSERT INTO permissions (level_name, user_level) VALUES ('administrator', 100);

