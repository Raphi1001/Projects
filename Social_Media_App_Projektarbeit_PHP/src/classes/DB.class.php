<?php
class DB
{
    protected $host;
    protected $username;
    protected $passwd;
    protected $dbname;
    protected $db;

    public function __construct($host, $username, $passwd, $dbname)
    {
        $this->host = $host;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->dbname = $dbname;
        $this->db = new mysqli();
        $this->connect();
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
     * Open a database connection 
     */
    public function connect()
    {
        if ($this->db->connect($this->host, $this->username, $this->passwd, $this->dbname) === false) {
            Alert::echoAlert("Ein Fehler ist aufgetreten: Bitte überprüfen Sie Ihre Datenbank anbindung!", false);
            exit;
        }
    }

    /**
     * Closes a database connection
     */
    public function disconnect()
    {
        $this->db->close();
    }

    /**
     * Returns a list of all Users as an array of User objects
     * 
     * @return array Array of User objects
     */
    public function getUserList()
    {
        $userArray = array();
        if ($this->db->connect_errno == 0) {
            $stmt = $this->db->prepare("CALL getUserList");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows) {
                while ($row = $result->fetch_object()) {
                    array_push($userArray, $this->convertRowToUser($row));
                }
            }
            $result->free_result();
        }
        return $userArray;
    }

    /**
     * Returns the Username
     * 
     * @param int $userid UserId
     * @return string Username
     */
    public function getUsername($userid)
    {
        $uname = "";
        if ($this->db->connect_errno == 0 && $stmt = $this->db->prepare("CALL getUsername(?)")) {
            $stmt->bind_param("i", $userid);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();

                if ($result && $result->num_rows) {
                    $uname = $result->fetch_object()->Username;
                }
                $result->free_result();
            }
        }
        return $uname;
    }

    /**
     * Returns User object of given username
     * 
     * @param string $username Username
     * @return mixed User object if user exists, else NULL
     */
    public function getUser($username)
    {
        $userObject = NULL;
        if ($this->db->errno == 0) {
            //escape special chars to prevent SQL injections
            $username = $this->db->escape_string($username);
            if ($stmt = $this->db->prepare("CALL getUser(?)")) {
                $stmt->bind_param("s", $username);
                if ($stmt->execute() && $result = $stmt->get_result()) {
                    if ($row = $result->fetch_object()) {
                        $userObject = $this->convertRowToUser($row);
                    }
                }
                $result->free_result();
            }
        }
        return $userObject;
    }

    /**
     * Returns User object of given userid
     * 
     * @param int $userid User_ID
     * @return mixed User object if user exists, else NULL
     */
    public function getUserById($userid)
    {
        $userObject = NULL;
        if ($this->db->errno == 0) {
            //escape special chars to prevent SQL injections
            $userid = $this->db->escape_string($userid);
            if ($stmt = $this->db->prepare("CALL getUserById(?)")) {
                $stmt->bind_param("i", $userid);
                if ($stmt->execute() && $result = $stmt->get_result()) {
                    if ($row = $result->fetch_object()) {
                        $userObject = $this->convertRowToUser($row);
                    }
                }
                $result->free_result();
            }
        }
        return $userObject;
    }

    /**
     * Checks if email already exists
     * 
     * @param string $emailaddr e-mail address
     * @return bool TRUE if exists, FALSE if not
     */
    public function getEmail($emailaddr)
    {
        if ($this->db->errno == 0) {
            $emailaddr = $this->db->escape_string($emailaddr);
            if ($stmt = $this->db->prepare("CALL getEmail(?)")) {
                $stmt->bind_param("s", $emailaddr);
                if ($stmt->execute() && $result = $stmt->get_result()) {
                    if ($result->num_rows) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Returns admin status of user
     * 
     * @param string $username Username
     * @return bool Admin status
     */
    public function getAdminStatus($username)
    {
        if ($this->db->errno == 0) {
            $username = $this->db->escape_string($username);
            if ($stmt = $this->db->prepare("CALL getAdmin(?)")) {
                $stmt->bind_param("s", $username);
                if ($stmt->execute() && $result = $stmt->get_result()) {
                    if ($row = $result->fetch_array()) {
                        if ($row[0]) return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Returns active status of user
     * 
     * @param string $username Username
     * @return bool Active status
     */
    public function getActiveStatus($username)
    {
        $user = $this->getUser($username);
        return $user->getProperty("isActive");
    }

    /**
     * Inserts User into the database
     * 
     * @param User $user User object
     * @return bool TRUE if successful, FALSE if not
     */
    public function insertUser($user)
    {

        if ($this->db->errno == 0) {
            $userArray = $user->getAllVars();
            $userArray = $this->escapeAllVars($userArray);
            $stmt = $this->db->prepare("CALL insertUser(?, ?, ?, ?, ?, ?, ?)");

            $gender = $userArray['gender'];
            $fname = $userArray['fname'];
            $lname = $userArray['lname'];
            $email = $userArray['email'];
            $uname = $userArray['uname'];
            $pwd = $userArray['pwd'];
            $userPicObj = $userArray['userPicObj'] ? $userArray['userPicObj']->getProperty('fileName') : NULL;

            if ($stmt) {
                $stmt->bind_param(
                    "sssssss",
                    $gender,
                    $fname,
                    $lname,
                    $email,
                    $uname,
                    $pwd,
                    $userPicObj
                );
                $stmt->execute();
                if ($stmt->errno == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Updates password of a user
     * 
     * @param string $username Username
     * @param string $newPassword Hash of the new Password
     * @return bool TRUE is successful, FALSE is not
     */
    public function updatePassword($username, $newPassword)
    {
        if ($this->db->errno == 0) {
            $username = $this->db->escape_string($username);
            $newPassword = $this->db->escape_string($newPassword);
            $stmt = $this->db->prepare("CALL updatePassword(?, ?)");

            if ($stmt) {
                $stmt->bind_param("ss", $newPassword, $username);
                $stmt->execute();

                if ($stmt->errno == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Updates User data
     * 
     * @param User $user User object
     * @param string $oldUsername (Old) Username
     * @return bool TRUE is successful, FALSE is not
     */
    public function updateUser($user, $oldUsername)
    {
        if ($this->db->errno == 0) {
            $userArray = $user->getAllVars();
            $userArray = $this->escapeAllVars($userArray);
            $stmt = $this->db->prepare("CALL updateUser(?, ?, ?, ?, ?, ?, ?)");

            $gender = $userArray['gender'];
            $fname = $userArray['fname'];
            $lname = $userArray['lname'];
            $email = $userArray['email'];
            $uname = $userArray['uname'];
            $userPicObj = $userArray['userPicObj'] ? $userArray['userPicObj']->getProperty("fileName") : NULL;

            if ($stmt) {
                $stmt->bind_param(
                    "sssssss",
                    $gender,
                    $fname,
                    $lname,
                    $email,
                    $uname,
                    $userPicObj,
                    $oldUsername,
                );
                $stmt->execute();

                if ($stmt->errno == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Deletes User from database
     * 
     * @param int $id ID of the User
     * @return bool TRUE is successful, FALSE is not
     */
    public function deleteUser($id)
    {
        $id = $this->db->escape_string($id);

        if (is_numeric($id) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL deleteUser(?)")) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verifies username and password
     * 
     * @param string $username Username
     * @param string $password Password
     * @return bool TRUE is successful, FALSE is not
     */
    public function loginUser($username, $password)
    {
        $user = $this->getUser($username);
        if ($user) {
            $pwdHash = $user->getProperty('pwd');
            return password_verify($password, $pwdHash);
        }
        return false;
    }

    /**
     * Changes isActive status of User
     * 
     * @param bool $isActive Is active status
     * @param int $userid ID of the User
     * @return bool TRUE is successful, FALSE is not
     */
    public function changeActiveStatus($isActive, $userid)
    {
        $userid = $this->db->escape_string($userid);

        if (is_numeric($userid) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL changeActiveStatus(?, ?)")) {
            $stmt->bind_param("ii", $isActive, $userid);
            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Escapes special chars in every property of user to prevent SQL injections
     */
    private function escapeAllVars($array)
    {
        foreach ($array as $value) {
            if (is_string($value)) $value = $this->db->escape_string($value);
            else if (is_array($value)) {
                foreach ($value as $arrElement) {
                    $arrElement = $this->db->escape_string($arrElement);
                }
            }
        }
        return $array;
    }

    /**
     * Converts mysqli_fetch_object result into User object
     * 
     * @param object $row Fetched database row
     * @return User User object
     */
    private function convertRowToUser($row)
    {
        $newUser = new User();
        if ($row->Benutzerbildreferenz !== null) {
            $newImg = new Img();
            $newImg->loadImage($row->Benutzerbildreferenz, $row->User_ID);
        } else {
            $newImg = null;
        }

        $newUser->loadDatabaseUser(
            $row->User_ID,
            $row->Anrede,
            $row->Vorname,
            $row->Nachname,
            $row->Emailadresse,
            $row->Username,
            $row->Passwort,
            $row->ist_admin,
            $row->ist_aktiv,
            $newImg
        );

        return $newUser;
    }

    /**
     * Inserts Comment into the database
     * 
     * @param Comment $newComment Comment object
     * @return bool TRUE if successful, FALSE if not
     */
    public function insertComment($newComment)
    {
        if ($this->db->errno == 0) {
            $stmt = $this->db->prepare("CALL insertComment(?, ?, ?)");
            $commentArray = $newComment->getAllVars();
            $commentArray = $this->escapeAllVars($commentArray);

            $userId = $commentArray['userId'];
            $postId = $commentArray['postId'];
            $message = $commentArray['message'];

            if ($stmt) {
                $stmt->bind_param("iis", $userId, $postId, $message);
                $stmt->execute();
                if ($stmt->errno == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Returns an array of all Comments of a Post
     * 
     * @param int $postId Post_ID
     * @return array Array of Comment objects
     */
    public function getComments($postId)
    {
        $commentArray = array();
        if ($this->db->connect_errno == 0) {
            if ($stmt = $this->db->prepare("CALL getComments(?)")) {
                $stmt->bind_param("i", $postId);

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows) {
                    while ($row = $result->fetch_object()) {
                        $temp = new Comment();
                        $temp->createComment($row->User_ID, $postId, $row->Inhalt, $row->Kommentar_ID);
                        array_push($commentArray, $temp);
                    }
                }
                $result->free_result();
            }
        }
        return $commentArray;
    }

     /**
     * Returns an array of all Comments of a Post
     * 
     * @param int $postId Post_ID
     * @return array Array of Comment objects
     */
    public function getCommentById($commentId)
    {
        $comment = NULL;
        if ($this->db->connect_errno == 0) {
            if ($stmt = $this->db->prepare("CALL getCommentById(?)")) {
                $stmt->bind_param("i", $commentId);

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows) {
                    if ($row = $result->fetch_object()) {
                        $comment = new Comment();
                        $comment->createComment($row->User_ID, $row->Beitrags_ID, $row->Inhalt, $row->Kommentar_ID);
                    }
                }
                $result->free_result();
            }
        }
        return $comment;
    }

    /**
     * Updates Comment in database
     * 
     * @param Comment $newComment Comment object
     * @return bool TRUE if successful, FALSE if not
     */
    public function updateComment($newComment)
    {
        if ($this->db->errno == 0) {
            $stmt = $this->db->prepare("CALL updateComment(?, ?)");
            $commentArray = $newComment->getAllVars();
            $commentArray = $this->escapeAllVars($commentArray);

            $commentId = $commentArray['commentId'];
            $message = $commentArray['message'];

            if ($stmt) {
                $stmt->bind_param("is", $commentId, $message);
                $stmt->execute();
                if ($stmt->errno == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Deletes a Comment from database
     * 
     * @param int $commentId Comment ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function deleteComment($commentId)
    {
        if ($this->db->errno == 0 && $stmt = $this->db->prepare("CALL deleteComment(?)")) {
            $commentId = $this->db->escape_string($commentId);

            $stmt->bind_param("i", $commentId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns a list of all Posts as an array of Post objects
     * 
     * @param string $sortby name of the sorting key ('Uploadzeit', 'Likes', or 'Dislikes')
     * @param bool $publicOnly show only public posts
     * @return array Array of Post objects
     */
    public function getAllPosts($sortby = "", $publicOnly = true)
    {
        $postArray = array();
        if ($this->db->connect_errno == 0 && $stmt = $this->db->prepare("CALL getAllPosts(?, ?)")) {
            $sortby = $this->db->escape_string($sortby);
            $stmt->bind_param("si", $sortby, $publicOnly);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                $stmt->close();

                if ($result && $result->num_rows) {
                    while ($row = $result->fetch_object()) {
                        $postId = $row->Beitrags_ID;
                        $tags = $this->getPostTags($postId);

                        array_push($postArray, $this->convertRowToPost($row, $tags));
                    }
                }
                $result->free_result();
            }
        }
        return $postArray;
    }

    /**
     * Returns a Post by postId
     * 
     * @param int $postId Beitrags_ID
     * @return Post Post object
     */
    public function getPostbyId($postId)
    {
        $post = NULL;
        if ($this->db->connect_errno == 0 && $stmt = $this->db->prepare("CALL getPostById(?)")) {
            $postId = $this->db->escape_string($postId);
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                $stmt->close();

                if ($result && $result->num_rows) {
                    if ($row = $result->fetch_object()) {
                        $tags = $this->getPostTags($postId);
                        $post = $this->convertRowToPost($row, $tags);
                    }
                }
                $result->free_result();
            }
        }
        return $post;
    }

    /**
     * Returns a list of all Tags from a Post
     * 
     * @return array Array of Tags
     */
    public function getPostTags($postId)
    {
        $tags = array();
        if ($this->db->connect_errno == 0 && $stmt = $this->db->prepare("CALL getPostTags(?)")) {
            $postId = $this->db->escape_string($postId);
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows) {
                while ($row = $result->fetch_object())
                    array_push($tags, $row->Tagname);
            }
            $result->free_result();
        }
        return $tags;
    }

    /**
     * Returns a list of all Posts as an array of Post objects
     * 
     * @param string $sortby name of the sorting key ('Uploadzeit', 'Likes', or 'Dislikes')
     * @param bool $publicOnly show only public posts
     * @return array Array of Post objects
     */
    public function getUserPosts($userId, $sortby = "", $publicOnly = true)
    {
        $postArray = array();
        if ($this->db->connect_errno == 0 && $stmt = $this->db->prepare("CALL getUserPosts(?, ?, ?)")) {
            $userId = $this->db->escape_string($userId);
            $sortby = $this->db->escape_string($sortby);
            $stmt->bind_param("isi", $userId, $sortby, $publicOnly);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                $stmt->close();

                if ($result && $result->num_rows) {
                    while ($row = $result->fetch_object()) {
                        $postId = $row->Beitrags_ID;
                        $tags = $this->getPostTags($postId);

                        array_push($postArray, $this->convertRowToPost($row, $tags));
                    }
                }
                $result->free_result();
            }
        }
        return $postArray;
    }

    /**
     * Converts mysqli_fetch_object result into Post object
     * 
     * @param object $row Fetched database row
     * @param array $tags tag array
     * @return Post Post object
     */
    private function convertRowToPost($row, $tags)
    {
        $newPost = new Post();

        if ($row->Bildreferenz !== null) {
            $newImg = new Img();
            $newImg->loadImage($row->Bildreferenz, $row->User_ID);
        } else {
            $newImg = null;
        }

        $newPost->setImg($newImg);

        $newPost->createPost(
            $row->User_ID,
            $row->Beschreibung,
            $row->Privat,
            $row->Beitrags_ID,
            $row->Uploadzeit,
            $row->Likes,
            $row->Dislikes
        );
        $newPost->setTags($tags);
        return $newPost;
    }

    /**
     * Inserts Post into the database
     * 
     * @param Post $post Post object
     * @return bool TRUE if successful, FALSE if not
     */
    public function insertPost($post)
    {
        $success = false;


        if ($this->db->errno == 0) {
            $stmt = $this->db->prepare("CALL insertPost(?, ?, ?, ?)");

            $postArray = $post->getAllVars();
            $postArray = $this->escapeAllVars($postArray);
            $userid = $postArray['userId'];
            $imgName = $postArray['imgObject'] ? $postArray['imgObject']->getProperty("fileName") : NULL;
            $description = $postArray['description'];
            $privacy = $postArray['privacy'];

            if ($stmt) {
                $stmt->bind_param(
                    "issi",
                    $userid,
                    $imgName,
                    $description,
                    $privacy
                );
                $stmt->execute();
                if ($stmt->errno == 0) {
                    $result = $stmt->get_result();
                    $stmt->close();
                    if ($result && $result->num_rows) {
                        //insert tags
                        $postId = $result->fetch_object()->PostId;
                        $success = $this->insertPostTags($postId, $postArray["tags"]);
                    }
                    $result->free_result();
                }
            }
        }
        return $success;
    }

    /**
     * Inserts Tags for a Post into the database
     * 
     * @param int $postId Post_ID
     * @param array $tags Array of tags
     * @return bool TRUE if successful, FALSE if not
     */
    public function insertPostTags($postId, $tags)
    {
        $postId = $this->db->escape_string($postId);
        if (is_numeric($postId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL insertPostTag(?, ?)")) {
            foreach ($tags as $tag) {
                $tag = $this->db->escape_string($tag);
                $stmt->bind_param("is", $postId, $tag);
                $stmt->execute();
                if ($stmt->errno != 0) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Gets like from database
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function getLike($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL getLike(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows != 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Gets dislike from database
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function getDislike($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL getDislike(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows != 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Removes dislike and adds like
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function likePost($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL likePost(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes like and adds dislike
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function dislikePost($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL dislikePost(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes like
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function removeLike($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL removeLike(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

      /**
     * Removes dislike
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function removeDislike($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL removeDislike(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

     /**
     * Removes all likes from a post
     * 
     * @param int $postId Beitrags_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function removeAllLikes($postId)
    {
        $postId = $this->db->escape_string($postId);
        if (is_numeric($postId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL removeAllLikes(?)")) {
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes all dislikes from a post
     * 
     * @param int $postId Beitrags_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function removeAllDislikes($postId)
    {
        $postId = $this->db->escape_string($postId);
        if (is_numeric($postId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL removeAllDislikes(?)")) {
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Deletes Tags of a Post in database
     * 
     * @param int $postId Post_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function deleteAllPostTags($postId)
    {
        $postId = $this->db->escape_string($postId);
        if (is_numeric($postId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL deleteAllPostTags(?)")) {
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Updates Post in database
     * 
     * @param Post $post Post object
     * @return bool TRUE if successful, FALSE if not
     */
    public function updatePost($post)
    {
        if ($this->db->errno == 0) {
            $stmt = $this->db->prepare("CALL updatePost(?, ?, ?, ?)");

            $postArray = $post->getAllVars();
            $postArray = $this->escapeAllVars($postArray);
            $postId = $postArray['postId'];
            $imgName = $postArray['imgObject'] ? $postArray['imgObject']->getProperty("fileName") : NULL;
            $description = $postArray['description'];
            $privacy = $postArray['privacy'];

            if ($stmt) {
                $stmt->bind_param(
                    "issi",
                    $postId,
                    $imgName,
                    $description,
                    $privacy
                );
                $stmt->execute();
                if ($stmt->errno == 0) {
                    $stmt->close();
                    return $this->deleteAllPostTags($postId) && $this->insertPostTags($postId, $postArray['tags']);
                }
            }
        }
        return false;
    }

    /**
     * Deletes Post from database
     * 
     * @param int $postId Post_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function deletePost($postId)
    {
        if ($this->db->errno == 0 && $stmt = $this->db->prepare("CALL deletePost(?)")) {
            $postId = $this->db->escape_string($postId);

            $stmt->bind_param("i", $postId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Searches for posts in the database
     * 
     * @param string $searchterm search term
     * @param string $sortby name of the sorting key ('Uploadzeit', 'Likes', or 'Dislikes')
     * @param bool $publicOnly show only public posts
     * @return array Array of Post objects
     */
    public function searchPosts($searchTerm, $sortby = "", $publicOnly = true)
    {
        $postArray = array();

        if ($this->db->errno == 0 && $stmt = $this->db->prepare("CALL searchPosts(?, ?, ?)")) {
            $searchTerm = $this->db->escape_string($searchTerm);
            $sortby = $this->db->escape_string($sortby);

            $stmt->bind_param("ssi", $searchTerm, $sortby, $publicOnly);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                $stmt->close();

                if ($result && $result->num_rows) {
                    while ($row = $result->fetch_object()) {
                        $postId = $row->Beitrags_ID;
                        $tags = $this->getPostTags($postId);

                        array_push($postArray, $this->convertRowToPost($row, $tags));
                    }
                }
                $result->free_result();
            }
        }
        return $postArray;
    }

    /**
     * Searches for posts by tags in the database
     * 
     * @param array $tagArray tag array
     * @param string $sortby name of the sorting key ('Uploadzeit', 'Likes', or 'Dislikes')
     * @param bool $publicOnly show only public posts
     * @return array Array of Post objects
     */
    public function searchTags($tagArray, $sortby = "", $publicOnly = true)
    {
        $postArray = array();

        if ($this->db->errno == 0 && $stmt = $this->db->prepare("CALL searchTags(?, ?, ?)")) {
            $tagString = implode(",", $tagArray);
            $tagString = $this->db->escape_string($tagString);
            $sortby = $this->db->escape_string($sortby);

            $stmt->bind_param("ssi", $tagString, $sortby, $publicOnly);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                $stmt->close();

                if ($result && $result->num_rows) {
                    while ($row = $result->fetch_object()) {
                        $postId = $row->Beitrags_ID;
                        $tags = $this->getPostTags($postId);

                        array_push($postArray, $this->convertRowToPost($row, $tags));
                    }
                }
                $result->free_result();
            }
        }
        return $postArray;
    }

    /**
     * Searches for Comments
     * 
     * @param string $searchTerm Search term
     * @param bool $publicOnly show only comments from public posts
     * @return array Array of Comment objects
     */
    public function searchComments($searchTerm, $publicOnly = true)
    {
        $commentArray = array();
        if ($this->db->connect_errno == 0) {
            if ($stmt = $this->db->prepare("CALL searchComments(?, ?)")) {
                $stmt->bind_param("si", $searchTerm, $publicOnly);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows) {
                        while ($row = $result->fetch_object()) {
                            $temp = new Comment();
                            $temp->createComment($row->User_ID, $row->Beitrags_ID, $row->Inhalt, $row->Kommentar_ID);
                            array_push($commentArray, $temp);
                        }
                    }
                    $result->free_result();
                }
            }
        }
        return $commentArray;
    }

    /**
     * Searches for image name in all posts in the database
     * 
     * @param string $searchterm search term
     * @param string $sortby name of the sorting key ('Uploadzeit', 'Likes', or 'Dislikes')
     * @param bool $publicOnly show only public posts
     * @return array Array of Post objects
     */
    public function searchImg($searchTerm, $sortby = "", $publicOnly = true)
    {
        $postArray = array();

        if ($this->db->errno == 0 && $stmt = $this->db->prepare("CALL searchImg(?, ?, ?)")) {
            $searchTerm = $this->db->escape_string($searchTerm);
            $sortby = $this->db->escape_string($sortby);

            $stmt->bind_param("ssi", $searchTerm, $sortby, $publicOnly);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                $stmt->close();

                if ($result && $result->num_rows) {
                    while ($row = $result->fetch_object()) {
                        $postId = $row->Beitrags_ID;
                        $tags = $this->getPostTags($postId);

                        array_push($postArray, $this->convertRowToPost($row, $tags));
                    }
                }
                $result->free_result();
            }
        }
        return $postArray;
    }

    /**
     * Searches users, returns an array of User objects
     * 
     * @return array Array of User objects
     */
    public function searchUser($searchTerm)
    {
        $userArray = array();
        if ($this->db->connect_errno == 0) {
            $stmt = $this->db->prepare("CALL searchUser(?)");
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();

                if ($result && $result->num_rows) {
                    while ($row = $result->fetch_object()) {
                        array_push($userArray, $this->convertRowToUser($row));
                    }
                }
                $result->free_result();
            }
        }
        return $userArray;
    }
}
