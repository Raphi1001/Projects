<?php
$placeholder = array(
    "img" => "",
    "description" => "",
    "privacy" => "",
);
//if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $continue = true;

    if (!isset($_POST["postDescription"])) $_POST["postDescription"] = NULL;
    if (!isset($_POST["postPrivacy"])) $_POST["postPrivacy"] = "";


    if ($_POST["postPrivacy"] == "Nur für eingeloggte User sichtbar") {
        $isPrivate = true;
    } else {
        $isPrivate = false;
    }
    //insert and validate post data 
    $errArray = $newPost->createPost($currentUserId, $_POST["postDescription"], $isPrivate);
    
    
    //check if any errors were found
    foreach ($errArray as $errMsg) {
        if ($errMsg) {
            $continue = false;
            break;
        }
    }
    /* Create Post */
    if ($continue) {
        $_SESSION["newPost"] = serialize($newPost);
?>
        <script type="text/javascript">
            window.location.href = 'index.php?page=newPost&subpage=preview';
        </script>
<?php
    } else {
        foreach (array_keys($placeholder) as $key) {
            if ($newPost->getProperty($key)) $placeholder[$key] = $newPost->getProperty($key);
        }
    }
}
