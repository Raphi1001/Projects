<!--Update Password-->
<div class="container col-md-8">
    <form action="#" method="POST">
        <div class="form-group">
            <label class="col-form-label font-weight-bold" for="oldPwd">Aktuelles Passwort:</label>
            <input type="password" class="form-control" id="oldPwd" name="oldPwd" placeholder="Geben Sie das aktuelle Passwort ein." maxlength="254" pattern="^[A-Za-z0-9ÄäÖöÜüß]*$" required>
            <?php if (!empty($errArray["oldPwd"])) Alert::inlineAlert($errArray["oldPwd"]) ?>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label class="col-form-label font-weight-bold" for="inputPwd">Neues Passwort:</label>
                <input type="password" class="form-control" id="inputPwd" name="pwd" placeholder="Mindestens 8 Zeichen. Erlaubt sind Zahlen und Buchstaben." maxlength="254" pattern="^[A-Za-z0-9ÄäÖöÜüß]*$" title="Mindestens 8 alphanumerische Zeichen." required>
                <?php if (!empty($errArray["pwd"])) Alert::inlineAlert($errArray["pwd"]) ?>
            </div>

            <div class="form-group col-md-6">
                <label class="col-form-label font-weight-bold" for="inputPwdCheck">Neues Passwort Best&auml;tigung:</label>
                <input type="password" class="form-control" id="inputPwdCheck" name="pwdCheck" placeholder="Bitte wiederholen Sie das Passwort." maxlength="254" pattern="^[A-Za-z0-9ÄäÖöÜüß]*$" title="Mindestens 8 alphanumerische Zeichen." required>
                <?php if (!empty($errArray["pwdCheck"])) Alert::inlineAlert($errArray["pwdCheck"]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <button class="btn btn-dark mr-2 ml-1" type="submit">Passwort aktualisieren</button>
            </div>
            <div class="form-group">
                <a href="index.php?page=<?= $_GET["page"] ?>" class="btn btn-dark ml-1" role="button">Abbrechen und zur&uuml;ck</a>
            </div>
        </div>
    </form>
</div>