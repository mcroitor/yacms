SET NAMES utf8 COLLATE utf8_unicode_ci;

-- ------------------------------------
-- user_faculties
-- ------------------------------------
CREATE TABLE IF NOT EXISTS users_ext_faculties(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    faculty_id INT NOT NULL,
    PRIMARY KEY(id)
);

-- ------------------------------------
-- days
-- ------------------------------------
CREATE TABLE IF NOT EXISTS data_days (
    day_id INT NOT NULL AUTO_INCREMENT,
    day_name VARCHAR(63) NOT NULL,
    PRIMARY KEY(day_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

-- INSERT INTO data_days VALUES 
--     (null, 'monday'), 
--     (null, 'tuesday'), 
--     (null, 'wednesday'), 
--     (null, 'thursday'), 
--     (null, 'friday'), 
--     (null, 'saturday'), 
--     (null, 'sunday');

INSERT INTO data_days VALUES 
    (null, 'luni'), 
    (null, 'marti'), 
    (null, 'miercuri'), 
    (null, 'joi'), 
    (null, 'vineri'), 
    (null, 'sambata'), 
    (null, 'duminica');

-- ------------------------------------
-- lesson types
-- ------------------------------------
CREATE TABLE IF NOT EXISTS lesson_type (
    lt_id INT NOT NULL AUTO_INCREMENT,
    lt_name VARCHAR(63) NOT NULL,
    PRIMARY KEY (lt_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO lesson_type VALUES 
    (null, 'curs'), 
    (null, 'seminar'), 
    (null, 'laborator');

-- ------------------------------------
-- timing periods
-- ------------------------------------
CREATE TABLE IF NOT EXISTS time_scale (
    ts_id INT NOT NULL AUTO_INCREMENT,
    ts_name VARCHAR(63) NOT NULL,
    PRIMARY KEY (ts_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO time_scale VALUES 
    (null, '8:00-9:30'), 
    (null, '9:45-11:15'), 
    (null, '11:30-13:00'),
    (null, '13:30-15:00'), 
    (null, '15:15-16:45'), 
    (null, '17:00-18:30'),
    (null, '18:45-20:15');

-- ------------------------------------
-- blocks
-- ------------------------------------
CREATE TABLE IF NOT EXISTS blocks (
    block_id INT NOT NULL AUTO_INCREMENT,
    block_name VARCHAR(63) NOT NULL,
    PRIMARY KEY (block_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO blocks VALUES 
(NULL, 'I'),
(NULL, 'II'),
(NULL, 'IIa'),
(NULL, 'III'),
(NULL, 'IV'),
(NULL, 'IVa'),
(NULL, 'V'),
(NULL, 'VI'),
(NULL, 'central'),
(NULL, 'Palatul Sportului');

-- ------------------------------------
-- rooms
-- ------------------------------------
CREATE TABLE IF NOT EXISTS rooms (
    room_id INT NOT NULL AUTO_INCREMENT,
    block_id INT NOT NULL,
    room_name VARCHAR(63) NOT NULL,
    PRIMARY KEY (room_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ------------------------------------
-- faculties
-- ------------------------------------
CREATE TABLE IF NOT EXISTS faculties (
    faculty_id INT NOT NULL AUTO_INCREMENT,
    faculty_name VARCHAR(255) NOT NULL,
    faculty_abbr VARCHAR(15) NOT NULL,
    PRIMARY KEY(faculty_id),
    UNIQUE KEY(faculty_name)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO faculties VALUES
(NULL, 'Biologie și Pedologie', 'BP'),
(NULL, 'Chimie și Tehnologie Chimică', 'CTC'),
(NULL, 'Drept', 'Drept'),
(NULL, 'Fizică şi Inginerie', 'FI'),
(NULL, 'Istorie şi Filosofie', 'IF'),
(NULL, 'Jurnalism și Știinţe ale Comunicării', 'JSC'),
(NULL, 'Limbi și Literaturi Străine', 'LLS'),
(NULL, 'Litere', 'Litere'),
(NULL, 'Matematică şi Informatică', 'FMI'),
(NULL, 'Psihologie și Știinţe ale Educaţiei', 'PSE'),
(NULL, 'Relaţii Internaţionale, Știinţe Politice și Administrative', 'FRISPA'),
(NULL, 'Sociologie și Asistenţă Socială', 'SAS'),
(NULL, 'Știinţe Economice', 'FSE');

-- ------------------------------------
-- groups
-- ------------------------------------
CREATE TABLE IF NOT EXISTS groups (
    group_id INT NOT NULL AUTO_INCREMENT,
    faculty_id INT NOT NULL,
    group_name VARCHAR(127) NOT NULL,
    PRIMARY KEY(group_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ------------------------------------
-- courses
-- ------------------------------------
CREATE TABLE IF NOT EXISTS courses (
    course_id INT NOT NULL AUTO_INCREMENT,
    course_name VARCHAR(127) NOT NULL,
    program_id INT,
    PRIMARY KEY(course_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ------------------------------------
-- course programs
-- ------------------------------------
CREATE TABLE IF NOT EXISTS course_programs (
    program_id INT NOT NULL AUTO_INCREMENT,
    program_name VARCHAR(127) NOT NULL,
    program_date DATETIME(4),
    faculty_id INT NOT NULL,
    PRIMARY KEY(program_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ------------------------------------
-- instructors
-- ------------------------------------
CREATE TABLE IF NOT EXISTS instructors (
    instructor_id INT NOT NULL AUTO_INCREMENT,
    instructor_firstname VARCHAR(31) NOT NULL,
    instructor_lastname VARCHAR(31) NOT NULL,
    PRIMARY KEY(instructor_id),
    UNIQUE KEY(instructor_firstname, instructor_lastname)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ------------------------------------
-- schedule
-- ------------------------------------
CREATE TABLE IF NOT EXISTS schedule_tbl (
    id INT NOT NULL AUTO_INCREMENT,
    day_id INT NOT NULL,
    ts_id INT NOT NULL,
    group_id INT NOT NULL,
    course_id INT NOT NULL,
    lt_id INT NOT NULL,
    block_id INT NOT NULL,
    room_id INT NOT NULL,
    instructor_id INT NOT NULL,
    parity VARCHAR(8) NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO modules_tbl VALUES(NULL, 'Schedule', '20170103');