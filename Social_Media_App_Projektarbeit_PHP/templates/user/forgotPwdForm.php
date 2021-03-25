<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4 col-md-offset-4 text-center">
      <h3><i class="fa fa-lock fa-4x"></i></h3>
      <h2 class="text-center">Passwort vergessen?</h2>
      <p>Hier können Sie ein neues Passwort anfordern!</p>
        <form action="index.php?page=resetPwd" onsubmit="return confirm('Passwort zur&uuml;cksetzen?');" role="form" autocomplete="off" class="form" method="post">
          <div class="form-group">
              <input type="text" name="username" placeholder="Username" class="form-control">
          </div>
          <div class="form-group">
            <input name="recover-submit" class="btn btn-dark btn-block" value="Neues Passwort anfordern" type="submit">
          </div>
        </form>
    </div>
  </div>
</div>