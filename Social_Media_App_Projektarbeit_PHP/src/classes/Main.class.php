<?php

class Page {
    public $name;
    public $file;

    public function __construct($name, $file) {
        $this->name = $name;
        $this->file = $file;
    }
}

class Main {
    /**
     * load requested page in $_GET
     * 
     * @param array $pages, all pages to load  
     * */
    public static function showPage($pages) {
        $ini = parse_ini_file('config/config.ini');
        $db = new DB($ini["db_host"], $ini["db_username"], $ini["db_password"], $ini["db_name"]);

        $pageLoaded = false;
        if (isset($_GET["page"])) {
            foreach ($pages as $page) {
                if ($page->name == $_GET["page"]) {
                    require $page->file;
                    $pageLoaded = true;
                    break;
                }
            }
    
            if (!$pageLoaded) { 
                require "templates/home.php";
            }
        } else {
            require "templates/home.php";
        }
        $db->disconnect();
    }
}
?>
