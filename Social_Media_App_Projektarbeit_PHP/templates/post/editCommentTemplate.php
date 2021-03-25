<!-- template for editing the comment -->
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <a data-fancybox="gallery" href="<?= $commentUserProfilePic ?>"><img src="<?= $commentUserThumbPic ?>" alt="Profilbild" height="30" width="30" class="rounded-circle"></a>
            <h5 class="mx-2 my-0"><?= $commentUname ?></h5>
        </div>
    </div>
    <div class="card-body">
        <form action="#" method="POST">
            <div class=form-group>
                <label for="message-edit">Kommentar bearbeiten:</label>
                <input type="text" class="form-control" value="<?= $commentMessage ?>" name="message-edit" rows="2" maxlength="500" pattern="^[a-zA-Z0-9-,.!?:() ÄäÖöÜüß]*$" required>
                <span> <?php if (isset($commentErr)) Alert::inlineAlert($commentErr) ?> </span>
            </div>
            <a href="index.php?page=singlePost&postid=<?= $commentPostId ?>"><button class="btn btn-dark mt-3" type="button">Zur&uuml;ck</button></a>
            <button class="btn btn-dark mt-3" type="submit"><i class="fa fa-comment"></i></button>
        </form>
    </div>
</div>