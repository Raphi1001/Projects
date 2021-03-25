<?php if (isset($_SESSION["currentUser"]) && $db->getAdminStatus($_SESSION["currentUser"])) { ?>
    <button type="submit" name="action" class="btn my-1 btn-outline-danger" value="rmAllLikes">
        <i class="fa fa-heart"></i> entfernen
    </button>
    <button type="submit" name="action" class="btn my-1 btn-outline-danger" value="rmAllDislikes">
        <i class="fa fa-thumbs-down"></i> entfernen
    </button>
<?php }?>