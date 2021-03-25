<?php
class Post
{
    protected $postId;
    protected $userId;
    protected $imgObject;
    protected $uploadTime;
    protected $description;
    protected $tags = array();
    protected $likes;
    protected $dislikes;
    protected $privacy;

    /** 
     * creates post with given values
     * @param int $userId, User id
     * @param string $description, post description
     * @param bool $privacy, post privacy
     * @param int $postId, Post Id
     * @param string $uploadTime, time of upload
     * @param int $likes, likes
     * @param int $dislikes, dislikes
     * @return array $errArray, array of error messages
     */
    public function createPost(
        $userId,
        $description,
        $privacy,
        $postId = NULL,
        $uploadTime = NULL,
        $likes = 0,
        $dislikes = 0
    ) {
        if ($postId !== NULL) $this->postId = $postId;
        $this->userId = $userId;
        $this->uploadTime = $uploadTime;
        $this->likes = $likes;
        $this->dislikes = $dislikes;

        $errArray = array();
        $errArray["postDescription"] = $this->setDescription($description);
        $this->setPrivacy($privacy);

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
     * Validates and sets description
     * 
     * @param string $value description
     * @return string Error message
     */
    public function setDescription($value)
    {
        $err = NULL;
        if ($value != NULL) {
            $value = $this->checkInput($value);

            if ($this->noSpecialCharactersCheck($value) != true) {
                $err = "Bitte geben Sie keine Sonderzeichen ein";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Die eingebenen Daten sind zu lang";
            } else {
                $this->description = $value;
            }
        } else {
            $this->description = $value;
        }
        return $err;
    }

    public function setPrivacy($value)
    {
        if ($value == true) {
            $this->privacy = true;
        } else {
            $this->privacy = false;
        }
    }

    /**
     * Validates and adds a tag to array
     * 
     * @param string $value tag
     * @return string Error message
     */
    public function addTag($value)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Dieses Feld darf nicht leer sein";
        } else {
            $value = $this->checkInput($value);

            if ($this->noSpecialCharactersCheck($value) != true) {
                $err = "Bitte geben Sie keine Sonderzeichen ein";
            } else if ($this->sizeCheckTags($value) != true) {
                $err = "Die eingebenen Daten sind zu lang";
            } else {
                foreach ($this->tags as $tag) {
                    if ($tag == $value) {
                        $err = "Dieser Tag wurde bereits hinzugefügt";
                        break;
                    }
                }
            }
        }
        if ($err == NULL) {
            array_push($this->tags, $value);
        }
        return $err;
    }

    /**
     * deletes a single tag from Tags array
     * @param string $value name of the tag
     * 
     * @return string Error message
     */
    public function deleteTag($value)
    {
        $err = "Es wurde kein passender Tag gefunden";
        foreach ($this->tags as $tag) {
            if ($tag == $value) {
                $this->tags = array_diff($this->tags, [$value]);
                $err = NULL;
                break;
            }
        }
        return $err;
    }

    /**
     * Sets tag array
     * 
     * @param array $valueArray tag array
     * @return string Error message
     */
    public function setTags($valueArray)
    {
        $err = NULL;
        foreach ($valueArray as $value) {
            if (empty($value)) {
                $err = "Dieses Feld darf nicht leer sein";
            } else {
                $value = $this->checkInput($value);

                if ($this->noSpecialCharactersCheck($value) != true) {
                    $err = "Bitte geben Sie keine Sonderzeichen ein";
                } else if ($this->sizeCheckTags($value) != true) {
                    $err = "Die eingebenen Daten sind zu lang";
                }
            }
        }
        if ($err == NULL) {
            $this->tags = $valueArray;
        }

        return $err;
    }

    /**
     * Returns the time difference between upload time and current time. 
     * Shows max. two time units.
     * 
     * @return string time intervall string
     */
    public function getTimeDifference()
    {
        $now = new DateTime();
        $date = new DateTime($this->uploadTime);
        $interval = $now->diff($date);
        $intervalStr = "";
        $timeCnt = 0;
        $timeUnits = array(
            'y' => 'y',
            'm' => 'm',
            'd' => 't',
            'h' => 'h',
            'i' => 'min',
            's' => 's'
        );
        
        foreach ($timeUnits as $timeunit => $name) {
            //if current time unit != 0
            if ($interval->$timeunit) {
                $timeCnt++;
                $intervalStr = $intervalStr . $interval->$timeunit . $name;
                //break if $timeunit has 2 or more digits or if 2 time units has been added to $intervalStr
                if ($interval->$timeunit >= 10 || $timeCnt >= 2) {
                    break;
                }
                $intervalStr = $intervalStr . " ";
            }
        }
        return $intervalStr;
    }

    public function setImg($imgObject)
    {
        $this->imgObject = $imgObject;
    }

    /**
     * displays a post overview of passed postArray
     * 
     * @param array $postArray 
     * @param object $db database connection
     * @param string $sortBy sort on uploadtime, likes/dislikes
     * 
     */
    public static function printPostArray($postArray, $db, $sortBy) 
    { ?>
        <div class="container px-0">
        <div class="d-flex justify-content-center m-3">
            <a href="index.php?page=insertPost&subpage=reset"><button type="button" class="btn btn-dark">Neuen Beitrag erstellen</button></a>
        </div>
        <?php if (!empty($postArray)) { ?>
                <!-- Sort Bar -->
                <form action="" method="GET">
                    <div class="sort-bar mx-auto my-2 d-flex align-items-baseline justify-content-center">
                        <label class="" for="sort">Sortieren nach:</label>
                        <select class="mx-2 overflow-auto form-control" name="sort" id="sort">
                            <option value="time" <?php if (isset($sortBy) && $sortBy == 'Uploadzeit') echo 'selected' ?>>Zuletzt bearbeitet</option>
                            <option value="likes" <?php if (isset($sortBy) && $sortBy == 'Likes') echo 'selected' ?>>Likes</option>
                            <option value="dislikes" <?php if (isset($sortBy) && $sortBy == 'Dislikes') echo 'selected' ?>>Dislikes</option>
                        </select>
                        <button class="btn btn-dark" type="submit" value="true">Anwenden</button>
                        <?php foreach ($_GET as $key => $value) echo "<input type=\"hidden\" name=\"$key\" value=\"$value\">" ?>
                    </div>
                </form>
                <?php
                foreach ($postArray as $post) {
                    // verkürzte Anzeige der Beitragsbeschreibung
                    $description = $post->getProperty("description");
                    $shortDesc = strlen($description) > 100 ? substr($description, 0, 100) . ' ...' : $description;

                    // getting all properties for a post
                    $userid = $post->getProperty("userId");
                    $user = $db->getUserById($userid);
                    $username = $user->getProperty("uname");

                    //default profile pic if does not exist
                    if ($userPic = $user->getProperty("userPicObj")) {
                        $thumbProfilePic =  $userPic->getProperty("thumbPath");
                        $pathProfilePic =  $userPic->getProperty("path");
                    } else {
                        $thumbProfilePic =  "resources/default/profilePic.png";
                        $pathProfilePic =  "resources/default/profilePic.png";
                    }

                    //no post pic if does not exist
                    if ($postPic = $post->getProperty("imgObject")) {
                        $thumbPostPic = $postPic->getProperty("thumbPath");
                        $pathPostPic = $postPic->getProperty("path");
                    } else {
                        $thumbPostPic = ""; 
                        $pathPostPic = "";
                    }
                    $likes = $post->getProperty("likes");
                    $dislikes = $post->getProperty("dislikes");
                    $time = $post->getTimeDifference();
                    $postid = $post->getProperty("postId");

                    include "templates/post/postTemplate.php";
                }
            } else { ?>
            <p class="text-center">Es wurden noch keine Beitr&auml;ge erstellt.</p><?php 
            } ?>
        </div><?php 
    }

     /** 
     * protect against sql-injections 
     * @param string $input, string to validate
     * @return string without harmfull characters  
    */
    private function checkInput($input)
    {
        return htmlspecialchars(stripslashes(trim($input)));
    }

    
    /** 
     * check if only letters and white spaces are used
     * @param string $input, string to validate
     * @return bool true if successfull else false 
    */
    private function noSpecialCharactersCheck($input)
    {
        return preg_match("/^[-a-zA-Z0-9-' _:().,?!ÄäÖöÜüß\n]+$/", $input);
    }

    /**
     * Checks max length of description
     * 
     * @param string $input description
     * @return bool true if < 500 else false
     */
    private function sizeCheck($input)
    {
        return strlen($input) < 500;
    }

    /** 
     * Check max lenght of tag
     * @param string $input, tag to check
     * @return bool true if success else false   
     */ 
     
    private function sizeCheckTags($input)
    {
        return strlen($input) < 60;
    }
}
