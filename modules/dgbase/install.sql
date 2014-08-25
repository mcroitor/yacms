DROP TABLE IF EXISTS problem_base;

CREATE TABLE problem_base (
    problem_id INT NOT NULL AUTO_INCREMENT,
    problem_fen VARCHAR(255),
    problem_stipulation VARCHAR(64),
    problem_solution VARCHAR(255),
    PRIMARY KEY(problem_id)
);

INSERT INTO menu_links VALUES (null, "problems", "./?path=problems/show", "primary", 20, 0);