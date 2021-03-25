<?php

if (isset($_POST["recover-submit"])) {
    if ($user = $db->getUser($_POST["username"])) {
        $userEmail = $user->getProperty("email");

        // choosing a random password
        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $pass = array();
        $pwdCharacters = strlen($characters) - 1;
        //add 8 random letters from $characters to create new password
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $pwdCharacters); 
            array_push($pass, $characters[$n]);
        }
        $newPwd = implode("", $pass);
        $hashNewPwd = password_hash($newPwd, PASSWORD_DEFAULT);

        // updates password and sends email to user
        if ($db->updatePassword($_POST["username"], $hashNewPwd)) {
            $to = $userEmail;
            $subject = 'Passwort Reset "Crew.at"';
            $message = "<p>Sehr geehrter User!<br><br>Wir haben einen Antrag zur Zur&uuml;cksetzung Ihres Passworts erhalten.<br>Sie k&ouml;nnen sich nun mit Ihrem neuen Passwort wie gewohnt auf unserer Website einloggen.<br><br>Ihr neues Passwort lautet: <strong>" . $newPwd . "</strong><br><br>Wir freuen uns, bald wieder von Ihnen zu h&ouml;ren!<br>Falls ein Problem auftreten sollte, stehen wir Ihnen gerne jederzeit zur Verf&uuml;gung.<br><br>Ihr Crew-Team</p>";
            $headers = "From: Crew <crew3068@gmail.com>\r\n";
            $headers .= "Reply-To: crew3068@gmail.com\r\n";
            $headers .= "Content-type: text/html\r\n";

            mail($userEmail, $subject, $message, $headers);
            
            Alert::echoAlert("Ihr Passwort wurde erfolgreich ge&auml;ndert und an Ihre Email-Adresse gesendet!", true);

            include "templates/user/loginForm.php";
        }
    } else {
        Alert::echoAlert("Dieser Username existiert nicht!", false);
        require "templates/user/forgotPwdForm.php";
    }
}
