-- CREATE DATABASE yacms_db;
-- CREATE USER 'yacms_user'@'localhost' IDENTIFIED BY PASSWORD('yapass');
-- grant all on yacms_db.* to 'yacms_user'@'localhost';
 
create table if not exists config_tbl(
    variable_id int not null auto_increment,
    variable_name varchar(63) unique not null,
    variable_value varchar(63) not null,
    variable_type varchar(63) not null default 'string',
    primary key(variable_id)
);

/*
 * accepted variable types: integer, float, string
 */

INSERT INTO config_tbl VALUES (null, 'default_theme', 'default', 'string');

create table if not exists modules_tbl(
    module_id int not null auto_increment,
    module_name varchar(63) unique not null,
    module_version varchar(15) not null,
    primary key(module_id)
);