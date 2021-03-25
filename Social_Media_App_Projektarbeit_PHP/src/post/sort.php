<?php
    //search by upload time by default
    $sortBy = 'Uploadzeit';
    if (isset($_GET["sort"])) {
        switch ($_GET["sort"]) {
            case "likes":
                $sortBy = 'Likes';
                break;
            case "dislikes":
                $sortBy = 'Dislikes';
                break;
            case "time":
                $sortBy = 'Uploadzeit';
                break;
        }
        unset($_GET["sort"]);
    }
?>