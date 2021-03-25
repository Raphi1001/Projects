<?php
class User
{
    protected $id;
    protected $gender;
    protected $fname;
    protected $lname;
    protected $email;
    protected $uname;
    protected $pwd;
    protected $isAdmin;
    protected $isActive;
    protected $userPicObj;

    /**
     * Validates and sets properties of User from register form
     * 
     * @param string $gender Gender
     * @param string $fname First name
     * @param string $lname Last name
     * @param string $email e-mail address
     * @param string $uname Username
     * @param string $pwd Password
     * @param string $pwdCheck Password Check
     * @param Img $userPicObj Img Object of Profile Picture
     * 
     * @return array Array of error messages
     */
    public function registerUser(
        $gender,
        $fname,
        $lname,
        $email,
        $uname,
        $pwd,
        $pwdCheck,
        $userPicObj = NULL
    ) {
        $errArray = array();
        $errArray["gender"] = $this->setGender($gender);
        $errArray["fname"] = $this->setVorname($fname);
        $errArray["lname"] = $this->setNachname($lname);
        $errArray["email"] = $this->setEmail($email);
        $errArray["uname"] = $this->setUsername($uname);
        $errArray["pwd"] = $this->setPwd($pwd);
        $errArray["pwdCheck"] = $this->checkPassword($pwdCheck);

        $this->userPicObj = $userPicObj;
        $this->encryptPwd();

        $this->isActive = true;
        $this->isAdmin = false;

        return $errArray;
    }

    /**
     * Validates and sets properties of User from account update form
     * 
     * @param string $gender Gender
     * @param string $fname First name
     * @param string $lname Last name
     * @param string $email e-mail address
     * @param string $uname Username
     * @param Img $userPicObj Profile Picture
     * 
     * @return array Array of error messages
     */
    public function updateUser(
        $gender,
        $fname,
        $lname,
        $email,
        $uname,
        $userPicObj = NULL
    ) {
        $errArray = array();
        $errArray["gender"] = $this->setGender($gender);
        $errArray["fname"] = $this->setVorname($fname);
        $errArray["lname"] = $this->setNachname($lname);
        $errArray["email"] = $this->setEmail($email);
        $errArray["uname"] = $this->setUsername($uname);

        $this->userPicObj = $userPicObj;

        return $errArray;
    }

    /**
     * Validates and sets properties of User from account update form
     * 
     * @param Img $userPicObj Profile Picture
     */
    public function setUserPicObj($userPicObj)
    {
        $this->userPicObj = $userPicObj;
    }

    /**
     * Validates and sets properties of User from account update form
     * 
     * @param string $pwd New Password
     * @param string $pwdCheck Check for new Password
     * 
     * @return array Array of error messages
     */
    public function updatePassword($pwd, $pwdCheck)
    {
        $errArray = array();
        $errArray["pwd"] = $this->setPwd($pwd);
        $errArray["pwdCheck"] = $this->checkPassword($pwdCheck);
        $this->encryptPwd();

        return $errArray;
    }

    public function updateIsActive($isActive)
    {
        if ($isActive == "false") {
            $this->isActive = false;
        } else {
            $this->isActive = true;
        }
    }

    /** 
     * Sets properties of User from database query
     * 
     * @param int $id User_ID
     * @param string $gender Gender
     * @param string $fname First name
     * @param string $lname Last name
     * @param string $email e-mail address
     * @param string $uname Username
     * @param string $pwdHash Password
     * @param bool $isAdmin Admin status
     * @param bool $isActive Active user
     * @param string $userPicObj Reference to profile picture
     */
    public function loadDatabaseUser(
        $id,
        $gender,
        $fname,
        $lname,
        $email,
        $uname,
        $pwdHash,
        $isAdmin,
        $isActive,
        $userPicObj
    ) {
        $this->id = $id;
        $this->gender = $gender;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->uname = $uname;
        $this->pwd = $pwdHash;
        $this->isAdmin = $isAdmin;
        $this->isActive = $isActive;
        $this->userPicObj = $userPicObj;
    }

    /**
     * Gets all properties
     * 
     * @return array Array of all properties
     */
    public function getAllVars()
    {
        return get_object_vars($this);
    }

    /**
     * Returns property if name exists
     * 
     * @param string $propName Name of the property
     * @return mixed Value of the property, NULL if not exists
     */
    public function getProperty($propName)
    {
        if (property_exists($this, $propName)) {
            return $this->$propName;
        }
        return NULL;
    }

    /**
     * Validates and sets gender
     * 
     * @param string $value Gender
     * @return string Error message
     */
    public function setGender($value)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Dieses Feld darf nicht leer sein";
        } else {
            $value = $this->checkInput($value);

            if ($value != "Frau" && $value != "Herr" && $value != "Andere") {
                $err = "Die eingegebenen Daten sind ungültig";
            } else if ($this->letterOnlyCheck($value) != true) {
                $err = "Bitte geben Sie nur Buchstaben oder Leerzeichen ein";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Die eingegebenen Daten sind zu lang";
            } else {
                $this->gender = $value;
            }
        }

        return $err;
    }

    /**
     * Validates and sets first name
     * 
     * @param string $value First name
     * @return string Error message
     */
    public function setVorname($value)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Dieses Feld darf nicht leer sein";
        } else {
            $value = $this->checkInput($value);

            if ($this->letterOnlyCheck($value) != true) {
                $err = "Bitte geben Sie nur Buchstaben oder Leerzeichen ein";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Die eingegebenen Daten sind zu lang";
            } else {
                $this->fname = $value;
            }
        }
        return $err;
    }

    /**
     * Validates and sets last name
     * 
     * @param string $value Last name
     * @return string Error message
     */
    public function setNachname($value)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Dieses Feld darf nicht leer sein";
        } else {
            $value = $this->checkInput($value);

            if ($this->letterOnlyCheck($value) != true) {
                $err = "Bitte geben Sie nur Buchstaben oder Leerzeichen ein";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Die eingegebenen Daten sind zu lang";
            } else {
                $this->lname = $value;
            }
        }
        return $err;
    }

    /**
     * Validates and sets username
     * 
     * @param string $value Username
     * @return string Error message
     */
    public function setUsername($value)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Dieses Feld darf nicht leer sein";
        } else {
            $value = $this->checkInput($value);

            if (!ctype_alnum($value)) {
                $err = "Bitte geben Sie nur Buchstaben oder Zahlen ein";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Die eingegebenen Daten sind zu lang";
            } else {
                $this->uname = $value;
            }
        }
        return $err;
    }


    /**
     * Validates and sets password
     * 
     * @param string $value Password
     * @return string Error message
     */
    public function setPwd($value)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Dieses Feld darf nicht leer sein";
        } else {
            $value = $this->checkInput($value);

            if (!ctype_alnum($value)) {
                $err = "Bitte geben Sie nur Buchstaben oder Zahlen ein";
            } else if ($this->minPwdLen($value) != true) {
                $err = "Das Passwort muss mindestens 8 Zeichen haben.";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Die eingegebenen Daten sind zu lang";
            } else {
                $this->pwd = $value;
            }
        }
        return $err;
    }

    /**
     * Confirms if passowrd check matches with password
     * 
     * @param string $value Password Check
     * @return string Error message
     */
    public function checkPassword($value)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Dieses Feld darf nicht leer sein";
        } else {
            $value = $this->checkInput($value);

            if (!ctype_alnum($value)) {
                $err = "Bitte geben Sie nur Buchstaben oder Zahlen ein";
            } else if ($this->minPwdLen($value) != true) {
                $err = "Das Passwort muss mindestens 8 Zeichen haben.";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Die eingegebenen Daten sind zu lang";
            } else if ($value != $this->pwd) {
                $err =  "Die Passwörter stimmen nicht überein";
            }
        }
        return $err;
    }

    /**
     * Validates and sets email
     * 
     * @param string $value Email
     * @return string Error message
     */
    public function setEmail($value)
    {
        if (empty($value)) {
            $err = "Dieses Feld darf nicht leer sein";
        } else {
            $err = NULL;
            $value = $this->checkInput($value);

            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $err = "Ungültige Email-Adresse";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Die eingegebenen Daten sind zu lang";
            } else {
                $this->email = $value;
            }
        }
        return $err;
    }

    /**
     * Encrypts password
     * @return string encrypted password
     * */
    private function encryptPwd()
    {
        $this->pwd = password_hash($this->pwd, PASSWORD_DEFAULT);
    }

    /**
     * Protects against Sql-injections
     * @param string $input string to validate 
     * @return bool true if succes else false
     * */
    private function checkInput($input)
    {
        return htmlspecialchars(stripslashes(trim($input)));
    }

    /**
     * Checks if only letters and white spaces are used
     * @param string $input string to validate 
     * @return bool true if succes else false
     * */
    private function letterOnlyCheck($input)
    {
        return preg_match("/^^[a-zA-Z-' ÄäÖöÜüß]*$/", $input);
    }

    /**
     * Checks max length of description
     * 
     * @param string $input description
     * @return bool true if < 255 else false
     */
    private function sizeCheck($input)
    {
        return strlen($input) < 255;
    }

    /**
     * Checks min length of password
     * 
     * @param string $input password
     * @return bool true if > 8 else false
     */
    private function minPwdLen($input)
    {
        return strlen($input) >= 8;
    }

    /**
     * Create user diretory if it doesn't exist yet
     * @param DB $db database
     * */
    function createUserDir($db)
    {
        if (!file_exists("resources/userUploads/$this->id")) mkdir("resources/userUploads/$this->id");
        if (!file_exists("resources/userUploads/$this->id/uploads")) mkdir("resources/userUploads/$this->id/uploads");
        if (!file_exists("resources/userUploads/$this->id/thumbnails")) mkdir("resources/userUploads/$this->id/thumbnails");

        //remove user img from database if it doesn't exist in directory

        if ($this->userPicObj) {
            $imgPath = $this->userPicObj->getProperty("path");
            $thumbPath = $this->userPicObj->getProperty("thumbPath");
            if (!file_exists($imgPath) || !file_exists($thumbPath)) {
                $this->setUserPicObj(NULL);
                $db->updateUser($this, $this->uname);
            }
        }
    }

    /**
     * delete user diretory
     * @param string $dirToDelete, dir to delete
     * @return bool true if success else false
     * */
    function deleteUserDir($dirToDelete = NULL)
    {
        if ($dirToDelete == NULL) {
            $dirToDelete = "resources/userUploads/$this->id";
        }

        if (is_dir($dirToDelete)) {
            $allFiles = scandir($dirToDelete);
            foreach ($allFiles as $fileToDelete) {
                if ($fileToDelete != "." && $fileToDelete != "..") {
                    $filePath = $dirToDelete . "/" . $fileToDelete;
                    if (is_dir($filePath)) {
                        $this->deleteUserDir($filePath);
                    } else if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            if (rmdir($dirToDelete)) {
                return true;
            } else {
                return false;
            }
        } else {
            if (file_exists($dirToDelete)) {
                unlink($dirToDelete);
                return true;
            } else {
                return false;
            }
        }
    }
}
