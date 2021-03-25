<?php

class Comment
{
    protected $commentId;
    protected $userId;
    protected $postId;
    protected $message;

    /**
     * set properties of comment
     * 
     * @param int $userId User_ID
     * @param int $postId Post_ID
     * @param string $message comment message
     * @param int $commentId Comment_ID
     * 
     * @return array Array of strings
     */
    public function createComment($userId, $postId, $message, $commentId = NULL)
    {
        $this->userId = $userId;
        $this->postId = $postId;
        $this->commentId = $commentId;
        
        $errArray = array();
        $errArray["message"] = $this->setComment($message);

        return $errArray;
    }
    /**
     * returns all vars
     * 
     * @return array properties
    */
    public function getAllVars()
    {
        return get_object_vars($this);
    }

    public function getProperty($propName)
    {
        if (property_exists($this, $propName)) {
            return $this->$propName;
        }
        return NULL;
    }
    /**
     * Validates and sets comment
     * 
     * @param string $value comment message
     * @return string Error message
     */
    public function setComment($value)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Das Kommentarfeld darf nicht leer sein!";
        } else {
            $value = $this->checkInput($value);

            if ($this->noSpecialCharactersCheck($value) != true) {
                $err = "Bitte geben Sie keine Sonderzeichen ein!";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Ihr Kommentar ist zu lang (max. 500 Zeichen)!";
            } else {
                $this->message = $value;
            }
        }
        return $err;
    }
    /**
     * check size of comment message 
     * 
     * @param string $input comment message
     * @return bool true if <= 500
     */
    public function sizeCheck($input)
    {
        return strlen($input) <= 500;
    }

    /**
     * Protects against Sql-injections
     * @param string $input string to validate
     * @return string removes potentially harmful characters
     * 
     * 
    */
    public function checkInput($input)
    {
        return htmlspecialchars(stripslashes(trim($input)));
    }

    /**
     * check if only letters, numbers and punctuation marks
     * 
     * @param string $input comment message
     * @return bool true if no special characters else false
     */
    public function noSpecialCharactersCheck($input)
    {
        return preg_match("/^[-a-zA-Z0-9:()' .,?!ÄäÖöÜüß]+$/", $input);
    }
}

?>