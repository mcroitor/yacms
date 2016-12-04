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