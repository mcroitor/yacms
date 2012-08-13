<?php

/**
 * Description of page
 *
 * @author Computer
 */
class Page {

    //put your code here
    var $header;
    var $body;
    var $theme;
    var $html;

    function Page($theme) {
        $this->theme = $theme;
        $this->header = array();
        $this->header["style"] = array();
        $this->header["script"] = array();
        $this->header["meta"] = array();

        $this->body["menu"] = array();
        $this->body["data"] = "";
        
        $this->html = "";
    }

    function loadTemplate($theme) {
        $template = loadData(THEME_PATH . $theme . "/index.tpl");
        $template = str_replace("<!-- theme-path -->", THEME_PATH . $theme, $template);
        return $template;
    }

    function renderMenu() {
        __hook("start_build_menu");

        $query = "SELECT * FROM menu_links WHERE menu_link_level<={$_SESSION['user_level']} AND menu_link_group='primary' ORDER BY menu_link_weight ASC";
        $links = sqlQuery($query);

        $this->body["menu"][-1] = "<div class='menu'><div class='group'>main menu:</div>";
        foreach ($links as $value) {
            $menu_link = $value["menu_link_link"];
            $menu_name = $value["menu_link_name"];

            __hook("build_menu");
            $this->body["menu"][$value["menu_link_weight"]] = "<a href='{$menu_link}' class='menu-button panel'>{$menu_name}</a>";
        }
        $this->body["menu"][] = "</div>";

        __hook("end_build_menu");
    }
    
    function renderHeader(){
        __hook("start_build_header");
        __hook("build_header");
        /*
         * TODO #2: fix header rendering
         */
        __hook("end_build_header");
    }

    function renderPage() {
        writeLog("start render page");
        writeLog("user access level: {$_SESSION['user_level']}");

        $this->html = $this->loadTemplate($this->theme);

        // meta
        __hook("preload_page");
        
        $this->renderHeader();
        $meta = implode("\n", $this->header["meta"]);
        $script = implode("\n", $this->header["script"]);
        $style = implode("\n", $this->header["style"]);

        $this->html = str_replace("<!-- meta -->", $meta, $this->html);
        $this->html = str_replace("<!-- script -->", $script, $this->html);
        $this->html = str_replace("<!-- style -->", $style, $this->html);

        $this->renderMenu();

        $menu = implode("\n", $this->body["menu"]);

        $this->html = str_replace("<!-- menu-site -->", $menu, $this->html);

        __hook("postload_page");
        return $this->html;
    }

    function processPath($path) {
        $hook_name = str_replace("/", "_", $path);
        __hook($hook_name);
    }

}

?>
