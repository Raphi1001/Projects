<!-- template for searching users -->
<div class="col-sm-12 col-md-6 flex mx-auto px-0 my-3 justify-content-center">
    <div class="card post-card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <a data-fancybox="gallery" href="<?= $pathProfilePic ?>"><img class="rounded-circle profilePic" src="<?= $thumbProfilePic ?>" alt="Profilbild"></a>
                <a href="index.php?page=search&filter-search=User&searchFor=<?=$searchFor?>&show-user=<?=$username?>&submit-search=true" class="mx-3 my-0"><?= $username ?></a>
            </div>
        </div>
    </div>
</div>
