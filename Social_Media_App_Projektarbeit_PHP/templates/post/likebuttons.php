<?php 
$class_like = "";
$class_dislike = "";
$action_like = "";
$action_dislike = "";

//buttons have different values depending on like status / dislike status
if (isset($_SESSION["currentUserId"])) {
    if ($db->getlike($postid, $_SESSION["currentUserId"])) {
        $class_like = "active";
        $action_like = "removelike";
    } else {
        $class_like = "";
        $action_like = "like";
    }
    
    if ($db->getDislike($postid, $_SESSION["currentUserId"])) {
        $class_dislike = "active";
        $action_dislike = "removedislike";
    } else {
        $class_dislike = "";
        $action_dislike = "dislike";
    }
} 
?>
<div class="btn-group">
    <button type="submit" name="action" class="btn my-1 btn-outline-dark <?=$class_like?>" value="<?=$action_like?>">
        <i class="fa fa-heart"></i>
        <?= $likes ?>
    </button>
    <button type="submit" name="action" class="btn mx-1 my-1 btn-outline-dark <?=$class_dislike?>" value="<?=$action_dislike?>">
        <i class="fa fa-thumbs-down"></i>
        <?= $dislikes ?>
    </button>
</div>