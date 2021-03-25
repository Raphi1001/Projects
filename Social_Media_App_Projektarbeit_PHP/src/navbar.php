<?php
require_once "src/classes/Navbar.class.php";

$currUser = "";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["currentUser"])) {
    $currUser = $_SESSION["currentUser"];
    if ($db->getAdminStatus($currUser)) {
        /*Admin Navbar*/
        $navitems = array(
            new Navitem("index.php?page=posts", "Alle Beitr&auml;ge"),
            new Navitem("index.php?page=administration", "User verwalten"),
            new Navitem("index.php?page=account", "Mein Profil"),
            new Navitem("index.php?page=editPosts", "Meine Beitr&auml;ge"),
            new Navitem("index.php?page=uploads", "Meine Uploads"),
            new Navitem("index.php?page=login&logout=true", "Logout")
        );
    }
    else {
        /*logged-in user Navbar*/
        $navitems = array(
            new Navitem("index.php?page=posts", "Alle Beitr&auml;ge"),
            new Navitem("index.php?page=account", "Mein Profil"),
            new Navitem("index.php?page=editPosts", "Meine Beitr&auml;ge"),
            new Navitem("index.php?page=uploads", "Meine Uploads"),
            new Navitem("index.php?page=login&logout=true", "Logout")
        );
    }
}
else {
    /*not logged-in Navbar*/
    $navitems = array(
        new Navitem("index.php?page=posts", "Alle Beitr&auml;ge"),
        new Navitem("index.php?page=register", "Registrieren"),
        new Navitem("index.php?page=login", "Login")
    );
}

Navbar::dynamicNav($navitems, $currUser);
?>