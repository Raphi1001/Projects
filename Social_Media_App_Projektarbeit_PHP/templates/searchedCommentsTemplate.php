<!-- template for searching comments -->
<div class="border rounded mx-4 my-3">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <a data-fancybox="gallery" href="<?= $commentUserProfilePic ?>"><img src="<?= $commentThumbProfilePic ?>" alt="Profilbild" height="30" width="30" class="rounded-circle"></a>
            <h5 class="mx-2 my-0"><?= $username; ?></h5>
        </div>
        <p class="my-3"><?= $shortMessage; ?></p>
        <a href="index.php?page=singlePost&postid=<?= $postid ?>"><button class="btn mx-1 btn-outline-dark" type="button">Ganzen Post anzeigen</button></a>
    </div>
</div>