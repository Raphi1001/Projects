<!--Account info-->
<div class="container">
    <div class="row">
        <div class="col-md-6 bg-light">
            <div class="table-responsive">
                <table class="table table-borderless table-sm">
                    <thead>
                        <tr>
                            <th scope="col" colspan="2">
                                <div class="h3 mt-3">Pers&ouml;nliche Daten
                                    <div class="btn-group" role="group" aria-label="edit">
                                        <a class="btn btn-dark btn-sm" href="index.php?page=account&update=user" role="button"><i class="fa fa-pencil"></i></a>
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
                    <div class="btn-group m-2" role="group" aria-label="NewPassword">
                        <div class="row text-center">
                            <div class="col m-2">
                                <a href="index.php?page=account&update=password"><button class="btn btn-dark" type="button">Passwort &auml;ndern</button></a>
                            </div>
                            <div class="col m-2">
                                <a href="index.php?page=account&update=posts"><button class="btn btn-dark" type="button">Beiträge anzeigen</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col flex-center">
                    <form action="index.php?page=account" method="post" onsubmit="return confirm('Möchten Sie Ihren Account unwiderruflich löschen?');">
                        <button type="submit" name="deleteUser" value="<?= $placeholder["uname"] ?>" class="btn btn-danger mb-4" type="button">Account löschen</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="container px-5 d-flex flex-column align-items-center">
                <h4>Profilbild</h4>
                <?php if ($userPic = $currentUser->getProperty("userPicObj")) { ?>
                    <a data-fancybox="gallery" href="<?= $userPic->getProperty("path") ?>"><img class="card-img-top upload-img rounded-circle my-3" src="<?= $userPic->getProperty("thumbPath") ?>" alt="Profilbild"></a>
                    <div class="btn-group" role="group">
                        <form action="index.php?page=account" method="post">
                            <button type="submit" name="removePp" value="true" class="btn btn-danger btn-sm fit-content m-2" type="button"><i class="fa fa-remove"></i></button>
                        </form>
                        <a class="btn btn-outline-dark btn-sm fit-content m-2" href="index.php?page=uploads" role="button">Profilbild ändern</a>
                    </div>
                <?php } else { ?>
                    <span class="text-muted">Sie haben kein Profilbild</span>
                    <div class="btn-group" role="group">
                        <a class="btn btn-outline-dark btn-sm fit-content m-2" href="index.php?page=uploads" role="button">Profilbild hinzufügen</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>