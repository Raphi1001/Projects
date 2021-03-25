<?php
if ($_SESSION["loggedin"] && $db->getAdminStatus($_SESSION["currentUser"])) {

    if (isset($_GET["delErr"]) && $_GET["delErr"] == "admin") {
        Alert::echoAlert("Ein Fehler ist aufgetreten: Sie können keinen Admin Account löschen!", false);
    } else if (isset($_GET["delErr"]) && $_GET["delErr"] == "true") {
        Alert::echoAlert("Ein Fehler ist aufgetreten: Der Account wurde nicht vollständig gelöscht! Bitte stellen Sie sicher, dass der User aus Datenbank und Filesystem entfernt wurde.", false);
    } else if (isset($_GET["delErr"]) && $_GET["delErr"] == "false") {
        Alert::echoAlert("Der User wurde erfolgreich gelöscht", true);
    }
?>
    <div class="container-fluid text-center">
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Anrede</th>
                        <th scope="col">Vorname</th>
                        <th scope="col">Nachname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Username</th>
                        <th scope="col">Profilbild</th>
                        <th scope="col">Admin</th>
                        <th scope="col">Deaktiviert</th>
                        <th scope="col">User verwalten</th>
                        <th scope="col">User löschen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $userList = $db->getUserList();
                    //print stats for all users 
                    foreach ($userList as $userToPrint) {
                    ?>
                        <tr>
                            <th scope="row">
                                <?= $userToPrint->getProperty("id"); ?>
                                <?php if ($userToPrint->getProperty("uname") == $_SESSION["currentUser"]) echo "(Sie)" ?>
                            </th>
                            <td><?= $userToPrint->getProperty("gender"); ?></td>
                            <td><?= $userToPrint->getProperty("fname"); ?></td>
                            <td><?= $userToPrint->getProperty("lname"); ?></td>
                            <td><?= $userToPrint->getProperty("email"); ?></td>
                            <td><?= $userToPrint->getProperty("uname"); ?></td>
                            <td><?= $userToPrint->getProperty("userPicObj") !== null ? $userToPrint->getProperty("userPicObj")->getProperty("fileName") : "" ?></td>
                            <td><?php if ($userToPrint->getProperty("isAdmin") == true) echo '<i class="fa fa-check fa-2x"></i>' ?></td>
                            <td><?php if ($userToPrint->getProperty("isActive") == false) echo '<i class="fa fa-check fa-2x"></i>' ?></td>
                            <td>
                                <a class="btn btn-dark" href="index.php?page=updateUser&userToUpdate=<?= $userToPrint->getProperty("uname"); ?>" role="button"><i class="fa fa-pencil"></i></a>
                            </td>
                            <td>
                                <form action="index.php?page=updateUser&&delete=true" onsubmit="return confirm('Möchten Sie den User löschen?');" method="GET">
                                    <button class="btn btn-danger" type="submit"><i class="fa fa-remove"></i></button>
                                    <input type="hidden" name="page" value="updateUser">
                                    <input type="hidden" name="delete" value="true">
                                    <input type="hidden" name="userToUpdate" value="<?= $userToPrint->getProperty("uname") ?>">
                                </form>
                            </td>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>

        <a href="index.php?page=register"> <button type="submit" class="btn btn-dark">Neuen User erstellen</button></a>
    </div>
<?php
} else {
?>
    <script type="text/javascript">
        window.location.href = 'index.php';
    </script>
<?php
}
?>