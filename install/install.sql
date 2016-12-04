-- create database example_cms_db;
create table if not exists config_tbl(
    variable_id int not null auto_increment,
    variable_name varchar(63) unique not null,
    variable_value varchar(63) not null,
    primary key(variable_id)
);

INSERT INTO config_tbl VALUES (null, 'default_theme', 'default');

create table if not exists modules_tbl(
    module_id int not null auto_increment,
    module_name varchar(63) unique not null,
    module_version varchar(15) not null,
    primary key(module_id)
);