<form class="container" action="index.php?page=login" method="POST">
    <div class="form-row">
        <div class="form-group login-field">
            <label class="col-form-label font-weight-bold" for="inputUname">Username:</label>
            <input type="text" class="form-control" id="inputUname" name="inputUname" placeholder="Geben Sie Ihren Usernamen ein." maxlength="254" pattern="^[A-Za-z0-9ÄäÖöÜüß]*$" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group login-field">
            <label class="col-form-label font-weight-bold" for="inputPw">Passwort:</label>
            <input type="password" class="form-control" id="inputPw" name="inputPw" placeholder="Schützen Sie Ihr Passwort vor anderen." maxlength="254" pattern="^[A-Za-z0-9ÄäÖöÜüß]*$" required>
            <a href="index.php?page=register" class="mr-3"><small>Registrieren</small></a>
            <a href="index.php?page=forgotPwd"><small>Passwort vergessen?</small></a>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group mt-3 login-field">
            <button class="btn btn-dark" type="submit">Login</button>
        </div>
    </div>
</form>