<?php
session_start();

require_once "src/classes/Alert.class.php";
require_once "src/classes/User.class.php";
require_once "src/classes/DB.class.php";
require_once "src/classes/Post.class.php";
require_once "src/classes/Img.class.php";
require_once "src/classes/Main.class.php";
require_once "src/classes/Comment.class.php";

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crew</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">    
    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <!-- toggle buttons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css">  
    <!--Fancybox/Lightbox-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />   
    <!-- global reset -->
    <link rel="stylesheet" href="public/css/globalReset.css">   
    <!-- stylesheet -->
    <link rel="stylesheet" href="public/css/style.css">
    <!-- bootstrap js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- toggle button -->
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <!-- Fancybox/Lightbox -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
</head>

<body>
    <div class="full-screen-height d-flex mx-auto p-0 flex-column justify-content-between">
        <header class="bg-light">
            <?php
            $ini = parse_ini_file('config/config.ini');
            //connection to database
            $db = new DB($ini["db_host"], $ini["db_username"], $ini["db_password"], $ini["db_name"]);

            require_once "src/login.php";
            require_once "src/navbar.php";

            $isAdmin = $_SESSION["loggedin"] ? $db->getAdminStatus($_SESSION["currentUser"]) : false;

            $db->disconnect();
            ?>
        </header>
        <main class="main-height flex-center p-3">
            <?php
            //allow pages depending on user status
            if ($_SESSION["loggedin"] && $isAdmin) {
                $adminPages = array(
                    new Page("register", "src/user/register.php"),
                    new Page("impressum", "templates/impressum.php"),
                    new Page("aboutUs", "templates/aboutUs.php"),
                    new Page("help", "templates/help.php"),
                    new Page("search", "src/search/search.php"),
                    new Page("newPost", "src/post/newPost.php"),
                    new Page("insertPost", "src/post/insertPost.php"),
                    new Page("posts", "src/post/postsOverview.php"),
                    new Page("account", "src/user/accUpdate.php"),
                    new Page("administration", "templates/admin/administrationPage.php"),
                    new Page("updateUser", "src/admin/adminUpdateUser.php"),
                    new Page("singlePost", "src/post/singlePost.php"),
                    new Page("editPosts", "src/user/editPosts.php"),
                    new Page("uploads", "src/user/uploads.php")
                );
                Main::showPage($adminPages);
            } else if ($_SESSION["loggedin"]) {
                $userPages = array(
                    new Page("impressum", "templates/impressum.php"),
                    new Page("aboutUs", "templates/aboutUs.php"),
                    new Page("help", "templates/help.php"),
                    new Page("search", "src/search/search.php"),
                    new Page("newPost", "src/post/newPost.php"),
                    new Page("insertPost", "src/post/insertPost.php"),
                    new Page("posts", "src/post/postsOverview.php"),
                    new Page("account", "src/user/accUpdate.php"),
                    new Page("singlePost", "src/post/singlePost.php"),
                    new Page("editPosts", "src/user/editPosts.php"),
                    new Page("uploads", "src/user/uploads.php")
                );
                Main::showPage($userPages);
            } else {
                $publicPages = array(
                    new Page("register", "src/user/register.php"),
                    new Page("login", "templates/user/loginForm.php"),
                    new Page("impressum", "templates/impressum.php"),
                    new Page("aboutUs", "templates/aboutUs.php"),
                    new Page("help", "templates/help.php"),
                    new Page("search", "src/search/search.php"),
                    new Page("newPost", "src/post/newPost.php"),
                    new Page("insertPost", "src/post/newPost.php"),
                    new Page("posts", "src/post/postsOverview.php"),
                    new Page("singlePost", "src/post/singlePost.php"),
                    new Page("uploads", "templates/loginToContinue.php"),
                    new Page("forgotPwd", "templates/user/forgotPwdForm.php"),
                    new Page("resetPwd", "src/user/forgotPwd.php"),
                    new Page("account", "templates/loginToContinue.php"),
                );
                Main::showPage($publicPages);
            }

            if (isset($_GET["alert"]) && $_GET["alert"] == "error") {
                Alert::echoAlert("Ein Fehler ist aufgetreten!", false);
            }
            ?>
        </main>
        <footer class="footer">
            <ul class="footer-links my-3">
                <li>
                    <a class="m-3 footer-link" href="index.php">Home</a>
                </li>
                <li>
                    <a class="m-3 footer-link" href="index.php?page=impressum">Impressum</a>
                </li>
                <li>
                    <a class="m-3 footer-link" href="index.php?page=aboutUs">&Uuml;ber uns</a>
                </li>
                <li>
                    <a class="m-3 footer-link" href="index.php?page=help">Hilfe</a>
                </li>
                <li>
                    <a class="m-3 footer-link" href="index.php?page=search">Suche</a>
                </li>
            </ul>
            <p class="m-3">&#169; 2020 CREW</p>
        </footer>
    </div>
</body>

</html>