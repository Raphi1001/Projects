<!-- template for single post -->
<div class="col-sm-12 col-md-6 col-lg-4 d-flex my-3 justify-content-center">
    <div class="card post-card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <a data-fancybox="gallery" href="<?= $pathProfilePic ?>"><img src="<?= $thumbProfilePic ?>" alt="Profilbild" class="rounded-circle profilePic"></a>
                <h5 class=" mx-3 my-0"><?= $username ?></h5>
                <!-- only owner or admin can see edit button -->
                <?php if ((isset($_SESSION["currentUserId"]) && $userid == $_SESSION["currentUserId"]) || (isset($_SESSION["currentUser"]) && $db->getAdminStatus($_SESSION["currentUser"]))) { ?>
                    <div class="ml-auto btn-group" role="group" aria-label="edit">
                    <a href="index.php?page=editPosts&postid=<?=$postid?>"><button class="btn btn-dark btn-sm"><i class="fa fa-pencil"></button></i></a>
                        <form action="" onsubmit="return confirm('Möchten Sie den Post löschen?');" method="GET">
                            <button class="btn btn-danger btn-sm mx-1" type="submit" name="action" value="deletePost"><i class="fa fa-remove"></i></a>
                                <input type="hidden" name="postid-del" value="<?= $postid ?>">
                                <?php foreach ($_GET as $key => $value) echo "<input type=\"hidden\" name=\"$key\" value=\"$value\">" ?>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- show post pic only if it exists -->
        <?php if (is_file($pathPostPic) && file_exists($pathPostPic)) { ?>
            <a data-fancybox="gallery" href="<?= $pathPostPic ?>"><img class="card-img-top post-img" src="<?= $pathPostPic ?>"></a>
        <?php } ?>
        <div class="card-body">
            <p class="card-text"><?= $description ?></p>
            <!-- show tags -->
            <?php if (!empty($tagArray)) { ?>
                <div class="d-flex flex-wrap justify-content-start">
                    <?php foreach ($tagArray as $tag) { ?>
                        <form action="index.php" method="GET">
                            <input type="hidden" name="page" value="search">
                            <input type="hidden" name="filter-search" value="Tag">
                            <input type="hidden" name="searchFor" value="<?= $tag ?>">
                            <button class="btn btn-outline-dark btn-sm mr-2 mb-2" name="submit-search" value="true">#<?= $tag ?></button>
                        </form>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="d-flex align-items-center justify-content-between">
                <!-- like buttons -->
                <form action="" method="GET">
                    <?php 
                        require "templates/post/likebuttons.php"; 
                        require "templates/admin/adminEditLikes.php";
                    ?>
                    <input type="hidden" name="postId_likes" value="<?= $postid ?>">
                    <input type="hidden" name="userId_likes" value="<?php if (isset($_SESSION["currentUserId"])) echo $_SESSION["currentUserId"] ?>">
                    <!-- submit form with current $_GET parameters -->
                    <?php foreach ($_GET as $key => $value) echo "<input type=\"hidden\" name=\"$key\" value=\"$value\">" ?>
                </form>
                <div class="float-right">
                    <small class="text-muted float-right text-nowrap">vor <?= $time ?></small>
                    <br><small class="text-muted float-right text-nowrap"><?php if ($privacyStatus == false) {
                                                        echo "Für alle sichtbar";
                                                    } else {
                                                        echo "Privat";
                                                    } ?></small>
                </div>
            </div>

        </div>
        <!-- comment input field for logged in users -->
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
            <div class="card-body">
                <span class="float-left">
                    <a data-fancybox="gallery" href="<?= $currUserProfilePic ?>"><img src="<?= $currUserThumbProfilePic ?>" alt="Profilbild" height="50" width="50" class="rounded-circle"></a>
                </span>
                <form action="#" method="POST">
                    <div class="p-2 d-flex justify-content-start">
                        <input type="text" class="mx-2 form-control comment" placeholder="Kommentieren..." name="message" rows="2" maxlength="500" pattern="^[a-zA-Z0-9-,.!?(): ÄäÖöÜüß]*$" required>
                        <button class="btn btn-dark" type="submit"><i class="fa fa-comment"></i></button>
                        <input type="hidden" name="newComment" value="true">
                    </div>
                    <!-- error message if comment fails validation -->
                    <?php if (isset($errArray) && !empty($errArray["message"])) {
                        echo "<br>";
                        Alert::inlineAlert($errArray["message"]);
                    } ?>
                </form>
            </div>
        <?php }
        $comments = $db->getComments($postid);

        foreach ($comments as $displayComment) {

            // getting all propreties for a comment
            $commentUserid = $displayComment->getProperty("userId");
            $commentUname = $db->getUsername($commentUserid);
            $commentUser = $db->getUserById($commentUserid);
            if ($commentProfilePic = $commentUser->getProperty("userPicObj")) {
                $commentUserThumbPic =  $commentProfilePic->getProperty("thumbPath");
                $commentUserProfilePic =  $commentProfilePic->getProperty("path");
            } else {
                $commentUserThumbPic = "resources/default/profilePic.png";
                $commentUserProfilePic = "resources/default/profilePic.png";
            }
            $commentMessage = $displayComment->getProperty("message");
            $commentId = $displayComment->getProperty("commentId");
        ?>
            <div class="border-top">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <a data-fancybox="gallery" href="<?= $commentUserProfilePic ?>"><img src="<?= $commentUserThumbPic ?>" alt="Profilbild" height="30" width="30" class="rounded-circle"></a>
                        <h5 class="mx-2 my-0"><?= $commentUname ?></h5>
                        <?php if ((isset($_SESSION["currentUserId"]) && $commentUserid == $_SESSION["currentUserId"]) || (isset($_SESSION["currentUser"]) && $db->getAdminStatus($_SESSION["currentUser"]))) { ?>
                            <div class="ml-auto btn-group" role="group" aria-label="edit">
                                <a href="index.php?page=singlePost&action=editComment&commentid-edit=<?=$commentId?>"><button class="btn btn-dark btn-sm" type="button"><i class="fa fa-pencil"></button></i></a>
                                <form action="" onsubmit="return confirm('Möchten Sie den Kommentar löschen?');" method="GET">
                                    <button class="btn btn-danger btn-sm mx-1" type="submit" name="action" value="deleteComment"><i class="fa fa-remove"></i></a>
                                        <input type="hidden" name="commentid-del" value="<?= $commentId ?>">
                                        <?php foreach ($_GET as $key => $value) echo "<input type=\"hidden\" name=\"$key\" value=\"$value\">" ?>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                    <p class="mt-3 mb-0"><?= $commentMessage ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>