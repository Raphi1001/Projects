<!--Update Personal Data-->
<form class="container" action="#" method="POST">
    <label class="col-form-label font-weight-bold" for="gender">Anrede:</label>
    <input type="hidden" name="gender" value="" required>
    <div class="form-group">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-dark <?php if ($placeholder["gender"] == "Herr") echo "active" ?>">
                <input type="radio" name="gender" id="inputRadioMale" value="Herr" autocomplete="off" <?php if ($placeholder["gender"]  == "Herr") echo "checked" ?>> Herr
            </label>
            <label class="btn btn-dark <?php if ($placeholder["gender"] == "Frau") echo "active" ?>">
                <input type="radio" name="gender" id="inputRadioFemale" value="Frau" autocomplete="off" <?php if ($placeholder["gender"]  == "Frau") echo "checked" ?>> Frau
            </label>
            <label class="btn btn-dark <?php if ($placeholder["gender"] == "Andere") echo "active" ?>">
                <input type="radio" name="gender" id="inputRadioOther" value="Andere" autocomplete="off" <?php if ($placeholder["gender"]  == "Andere") echo "checked" ?>> Andere
            </label>
        </div>
        <?php if (!empty($errArray["gender"])) Alert::inlineAlert($errArray["gender"]) ?>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="col-form-label font-weight-bold" for="inputFname">Vorname:</label>
            <input type="text" class="form-control" id="inputFname" name="fname" placeholder="Max" value="<?= $placeholder["fname"] ?>" maxlength="254" pattern="^[A-Za-z-' ÄäÖöÜüß]*$" required>
            <?php if (!empty($errArray["fname"])) Alert::inlineAlert($errArray["fname"]) ?>
        </div>

        <div class="form-group col-md-6">
            <label class="col-form-label font-weight-bold" for="inputLname">Nachname:</label>
            <input type="text" class="form-control" id="inputLname" name="lname" placeholder="Mustermann" value="<?= $placeholder["lname"] ?>" maxlength="254" pattern="^[A-Za-z-' ÄäÖöÜüß]*$" required>
            <?php if (!empty($errArray["lname"])) Alert::inlineAlert($errArray["lname"]) ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-form-label font-weight-bold" for="inputEmail">Email Adresse:</label>
        <input type="email" class="form-control" id="inputEmail" name="email" placeholder="max@musterman.at" value="<?= $placeholder["email"] ?>" maxlength="254" required>
        <?php if (!empty($errArray["email"])) Alert::inlineAlert($errArray["email"]) ?>
    </div>

    <div class="form-group">
        <label class="col-form-label font-weight-bold" for="inputUname">Username:</label>
        <input type="text" class="form-control" id="inputUname" name="uname" placeholder="max_1997" value="<?= $placeholder["uname"] ?>" maxlength="254" pattern="^[A-Za-z0-9ÄäÖöÜüß]*$" required>
        <?php if (!empty($errArray["uname"])) Alert::inlineAlert($errArray["uname"]) ?>
    </div>

    <?php
    if ($_GET["page"] == "updateUser" && $db->getAdminStatus($_SESSION["currentUser"])) {
    ?>
        <div class="form-group">
            <label class=" col-form-label font-weight-bold" for="isActive">Account sperren:</label>
            <input type="checkbox" id="isActive" name="isActive" value="false" data-toggle="toggle" data-on="Gesperrt" data-off="Aktiv" data-onstyle="primary" data-offstyle="dark" <?php if (!$db->getUser($_SESSION["userToUpdate"])->getProperty("isActive")) echo "checked" ?>>
            <?php if (!empty($errArray["isActive"])) Alert::inlineAlert($errArray["isActive"]) ?>
        </div>
    <?php } ?>

    <div class="form-row ">
        <div class="form-group m-1">
            <button class="btn btn-dark" type="submit">Daten aktualisieren</button>
        </div>
        <div class="form-group m-1">
            <a href="index.php?page=<?= $_GET["page"] ?>" class="btn btn-dark" role="button">Abbrechen und zur&uuml;ck</a>
        </div>
    </div>
</form>