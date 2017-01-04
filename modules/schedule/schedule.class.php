<?php

class Schedule {

    var $name = "Schedule";
    var $version = "20170103";

    function __construct() {
        // TODO ## : do here something
    }

    function user_login() {
        $result = sql_query("SELECT faculty_id FROM users_ext_faculties WHERE user_id = {$_SESSION['user_id']}");
        $_SESSION["faculty_id"] = count($result) > 0 ? $result[0]["faculty_id"] : 0;
    }

    function user_logout() {
        unset($_SESSION["faculty_id"]);
    }

    function schedule_management(){
        Page::$modules["Users"]->check_permissions(1);
        Page::$data["<!-- page_content -->"] = load_data(MODULE_PATH . $this->name . "/templates/schedule_mngmt.tpl.php");
    }
    // rooms and blocks
    function process_schedule_rooms_view() {
        // TODO 01: show all blocks and rooms
        Page::$modules["Users"]->check_permissions(1);
        $block_id = filter_input(INPUT_POST, "block_id", FILTER_VALIDATE_INT, ["options" => ["default" => 1]]);
        Page::$data["<!-- page_content -->"] = "";
        
        $template = load_data(MODULE_PATH . $this->name . "/templates/rooms.tpl.php");
        $rows = ["<!-- schedule-blocks -->" => "", "<!-- schedule-rooms -->" => ""];
        
        $result = sql_query("SELECT * FROM blocks");

        foreach ($result as $block) {
            $rows["<!-- schedule-blocks -->"] .= "<option value='{$block["block_id"]}'>{$block['block_name']}</option>";
        }
        
        $result = sql_query("SELECT * FROM rooms WHERE block_id = {$block_id}");

        foreach ($result as $room) {
            $rows["<!-- schedule-rooms -->"] .= "<tr><td>{$room['room_name']}</td></tr>";
        }
        Page::$data["<!-- page_content -->"] .= fill_template($template, $rows);
    }

    function process_schedule_room_add() {
        // TODO 02: add a room
    }

    function process_schedule_room_edit() {
        // TODO 03: edit a room
    }

    function process_schedule_room_delete() {
        // TODO 04: delete a room
    }

    //courses
    function process_schedule_courses_view() {
        // TODO 05: show all courses
    }

    function process_schedule_course_add() {
        // TODO 06: add a course
    }

    function process_schedule_course_edit() {
        // TODO 07: edit a course
    }

    function process_schedule_course_delete() {
        // TODO 08: delete a course
    }

    //programs
    function process_schedule_programs_view() {
        // TODO 09: show all programs
        Page::$modules["Users"]->check_permissions(1);
        Page::$data["<!-- page_content -->"] = "";
        $result = sql_query("SELECT * FROM course_programs "
                . "WHERE faculty_id = {$_SESSION["faculty_id"]}");

        $template = load_data(MODULE_PATH . $this->name . "/templates/programs.tpl.php");
        $rows = ["<!-- schedule-program -->" => ""];
        foreach ($result as $program) {
            $rows["<!-- schedule-program -->"] .= "<tr><td>{$program['program_name']}</td><td>{$program['program_date']}</td></tr>";
        }
        Page::$data["<!-- page_content -->"] .= fill_template($template, $rows);
    }

    function process_schedule_program_add() {
        // TODO 10: add a program
        Page::$modules["Users"]->check_permissions(1);
        $program_name = filter_input(INPUT_POST, "program_name");
        $program_date = filter_input(INPUT_POST, "program_date");

        sql_query("INSERT INTO course_programs VALUES (NULL, "
                . "'{$program_name}', "
                . "'{$program_date}', "
                . "{$_SESSION["faculty_id"]})", "program adding error: ", false);
        header("location:./?q=schedule/programs/view");
        exit();
    }

    function process_schedule_program_edit() {
        // TODO 11: edit a program
    }

    function process_schedule_program_delete() {
        // TODO 12: delete a program
    }

}
