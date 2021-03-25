<!-- Admin can reset password -->
<div class="container col-md-8">
    <form action="#" onsubmit="return confirm('Das Passwort dieses Users &auml;ndern?');" method="POST">
        <div class="form-group">
            <label class="col-form-label font-weight-bold" for="newPwd">Reset Passwort:</label>
            <input type="password" class="form-control" id="newPwd" name="newPwd" placeholder="Neues Passwort für User festlegen." maxlength="254" pattern="[A-Za-z0-9ÄäÖöÜüß]*$" required>
            <?php if (isset($_POST["newPwd"]) && (strlen($_POST["newPwd"]) < 8)) Alert::inlineAlert("Das Passwort muss mindestens 8 Zeichen beinhalten!") ?>
        </div>

        <div class="form-row">
            <div class="form-goup">
                <button class="btn btn-dark mb-2 mr-2 ml-1" type="submit">Passwort aktualisieren und dem User per Email zusenden</button>
            </div>
            <div class="form-group">
                <a href="index.php?page=<?= $_GET["page"] ?>" class="btn btn-dark ml-1" role="button">Abbrechen und zur&uuml;ck</a>
            </div>
        </div>
    </form>
</div>