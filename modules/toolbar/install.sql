CREATE TABLE toolbar_links (
    toolbar_link_id INT NOT NULL AUTO_INCREMENT,
    toolbar_link_name VARCHAR(255),
    toolbar_link_link VARCHAR(255),
    toolbar_link_owner INT,
    PRIMARY KEY(toolbar_link_id)
);