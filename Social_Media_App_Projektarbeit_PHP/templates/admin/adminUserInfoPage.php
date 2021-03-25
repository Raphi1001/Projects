<!--Account info-->

<div class="container">
    <a class="btn btn-outline-dark bt m-2" href="index.php?page=administration" role="button">Zurück</a>
    <div class="row">
        <div class="col-md-6 bg-light">
            <div class="table-responsive">
                <table class="table table-borderless table-sm">
                    <thead>
                        <tr>
                            <th scope="col" colspan="2">
                                <div class="h3 mt-3">Userdaten
                                    <div class="btn-group" role="group" aria-label="edit">
                                        <a class="btn btn-dark btn-sm" href="index.php?page=updateUser&update=user" role="button"><i class="fa fa-pencil"></i></a>
                                    </div>
                                </div>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Anrede</th>
                            <td><?= $placeholder["gender"] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Vorname</th>
                            <td><?= $placeholder["fname"] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Nachname</th>
                            <td><?= $placeholder["lname"] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">E-Mail-Adresse</th>
                            <td><?= $placeholder["email"] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Username</th>
                            <td><?= $placeholder["uname"] ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="col flex-center py-3">
                    <div class="btn-group" role="group" aria-label="User buttons">
                        <a href="index.php?page=updateUser&update=password" role="button" class="btn btn-dark">Passwort &auml;ndern</a>
                        <a href="index.php?page=updateUser&update=posts" role="button" class="btn btn-dark ml-4">Beiträge von <?= $placeholder["uname"] ?> anzeigen</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="container px-5 d-flex flex-column align-items-center">
                <h4>Profilbild</h4>
                <?php if ($userPic = $userToUpdate->getProperty("userPicObj")) { ?>
                    <a data-fancybox="gallery" href="<?= $userPic->getProperty("path") ?>"><img class="card-img-top upload-img rounded-circle my-3" src="<?= $userPic->getProperty("thumbPath") ?>" alt="Profilbild"></a>
                    <div class="btn-group" role="group">
                        <a class="btn btn-danger btn-sm fit-content m-2" href="index.php?page=updateUser&userToUpdate=<?= $userToUpdate->getProperty("uname") ?>&removePp=true" role="button"><i class="fa fa-remove"></i></a>
                    </div>
                <?php } else { ?>
                    <span class="text-muted">Kein Profilbild gefunden</span>
                <?php } ?>
            </div>
        </div>
    </div>
</div>