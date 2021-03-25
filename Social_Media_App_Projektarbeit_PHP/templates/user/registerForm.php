<form class="container" action="index.php?page=register" method="post">
    <label class="col-form-label font-weight-bold" for="gender">Anrede:</label>
    <input type="hidden" name="gender" value="" required>
    <div class="form-group">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-dark <?php if ($placeholder["gender"] == "Herr") echo "active" ?>">
                <input type="radio" name="gender" id="inputRadioMale" value="Herr" autocomplete="off" <?php if ($placeholder["gender"]  == "Herr") echo "checked" ?> required> Herr
            </label>
            <label class="btn btn-dark <?php if ($placeholder["gender"] == "Frau") echo "active" ?>">
                <input type="radio" name="gender" id="inputRadioFemale" value="Frau" autocomplete="off" <?php if ($placeholder["gender"]  == "Frau") echo "checked" ?> required> Frau
            </label>
            <label class="btn btn-dark <?php if ($placeholder["gender"] == "Andere") echo "active" ?>">
                <input type="radio" name="gender" id="inputRadioOther" value="Andere" autocomplete="off" <?php if ($placeholder["gender"]  == "Andere") echo "checked" ?> required> Andere
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
        <input type="text" class="form-control" id="inputUname" name="uname" placeholder="W&auml;hlen Sie einen Usernamen." value="<?= $placeholder["uname"] ?>" maxlength="254" pattern="^[A-Za-z0-9ÄäÖöÜüß]*$" required>
        <?php if (!empty($errArray["uname"])) Alert::inlineAlert($errArray["uname"]) ?>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="col-form-label font-weight-bold" for="inputPwd">Passwort:</label>
            <input type="password" class="form-control" id="inputPwd" name="pwd" placeholder="Mindestens 8 Zeichen. Erlaubt sind Zahlen und Buchstaben." maxlength="254" pattern="^[A-Za-z0-9ÄäÖöÜüß]*$" title="Mindestens 8 alphanumerische Zeichen." required>
            <?php if (!empty($errArray["pwd"])) Alert::inlineAlert($errArray["pwd"]) ?>
        </div>

        <div class="form-group col-md-6">
            <label class="col-form-label font-weight-bold" for="inputPwdCheck">Passwort Best&auml;tigung:</label>
            <input type="password" class="form-control" id="inputPwdCheck" name="pwdCheck" placeholder="Bitte wiederholen Sie das Passwort." maxlength="254" pattern="^[A-Za-z0-9ÄäÖöÜüß]*$" title="Mindestens 8 alphanumerische Zeichen." required>
            <?php if (!empty($errArray["pwdCheck"])) Alert::inlineAlert($errArray["pwdCheck"]) ?>
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-dark" type="submit">Registrieren</button>
    </div>
</form>