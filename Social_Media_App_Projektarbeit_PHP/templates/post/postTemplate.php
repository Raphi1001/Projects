<!-- template for posts in overview page -->
<div class="col-sm-12 col-md-6 flex mx-auto px-0 my-3 justify-content-center">
    <div class="card post-card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <a data-fancybox="gallery" href="<?= $pathProfilePic ?>"><img class="rounded-circle profilePic" src="<?= $thumbProfilePic ?>" alt="Profilbild"></a>
                <h5 class="mx-3 my-0"><?= $username ?></h5>
                <!-- only owner or admin can see edit button -->
                <?php if ((isset($_SESSION["currentUserId"]) && $userid == $_SESSION["currentUserId"]) || (isset($_SESSION["currentUser"]) && $db->getAdminStatus($_SESSION["currentUser"]))) { ?>
                    <div class="ml-auto btn-group" role="group" aria-label="edit">
                        <a href="index.php?page=editPosts&postid=<?=$postid?>"><button class="btn btn-dark btn-sm" type="button"><i class="fa fa-pencil"></button></i></a>
                        <form action="" onsubmit="return confirm('Möchten Sie den Post löschen?');" method="get">
                            <button class="btn btn-danger btn-sm mx-1" type="submit" name="action" value="deletePost"><i class="fa fa-remove"></i></a>
                            <input type="hidden" name="postid-del" value="<?= $postid ?>">
                            <?php foreach($_GET as $key => $value) echo "<input type=\"hidden\" name=\"$key\" value=\"$value\">" ?>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- show post pic only if it exists -->
        <?php if (is_file($pathPostPic) && file_exists($pathPostPic) && is_file($thumbPostPic) && file_exists($thumbPostPic)) { ?>
            <a data-fancybox="gallery" href="<?= $pathPostPic ?>"><img src="<?= $thumbPostPic ?>" class="img-fluid post-img"></a>
        <?php } ?>
        <div class="card-body">
            <p class="card-text"><?= $shortDesc; ?></p>
            <div class="d-flex align-items-center justify-content-between">
                <!-- like buttons -->
                <form action="" method="GET">
                    <?php require "templates/post/likebuttons.php" ?>
                    <input type="hidden" name="postId_likes" value="<?= $postid ?>">
                    <input type="hidden" name="userId_likes" value="<?php if (isset($_SESSION["currentUserId"])) echo $_SESSION["currentUserId"] ?>">
                    <!-- submit form with current $_GET parameters -->
                    <?php foreach($_GET as $key => $value) echo "<input type=\"hidden\" name=\"$key\" value=\"$value\">" ?>
                </form>
                <small class="text-muted">vor <?= $time ?></small>
            </div>
        </div>
        <div class="card-body">
            <a href="index.php?page=singlePost&postid=<?= $postid ?>"><button class="btn mx-1 btn-outline-dark" type="button">Ganzen Post anzeigen</button></a>
        </div>
    </div>
</div>