<div class="container">
    <div class="row">
        <div class="col-12">
            <?php
            require "src/post/sort.php";
            //check if user is logged in 
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
                $publicOnly = false;
                require "src/post/likes.php";
                require "src/post/deletePost.php";
            } else {
                $publicOnly = true;
            }
            include "templates/searchTemplate.php";

            //validates search form
            if (isset($_GET["submit-search"])) {
                if (isset($_GET["searchFor"])) {
                    if (preg_match("/^[-a-zA-Z0-9-' _:().,?!ÄäÖöÜüß\n]+$/", $_GET["searchFor"])) {
                        if (strlen($_GET["searchFor"]) < 499) {
                            $searchFor = $_GET["searchFor"];
                            echo "<h3 class=\"h3 text-center\">Suchergebnisse für $searchFor:</h3>";
                        } else {
                        echo "<div class='d-flex justify-content-center'>";
                        Alert::inlineAlert("Ihre Sucheingabe ist zu lang!");
                        echo "</div>";
                        }
                    } else {
                        echo "<div class='d-flex justify-content-center'>";
                        Alert::inlineAlert("Ihre Suche enthält ein gesperrtes Zeichen!");
                        echo "</div>";
                    }
                
                    if (isset($_GET["filter-search"])) {
                        $searchFilter = $_GET["filter-search"];
                    }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="container">
            <?php   
                    if (isset($searchFor)) {
                        // displays all matching posts
                        if (!isset($searchFilter) || (isset($searchFilter) && $searchFilter == 'Beitrag')) {
                            $searchResults = $db->searchPosts($searchFor, $sortBy, $publicOnly);

                            if ($searchResults) {
                                Post::printPostArray($searchResults, $db, $sortBy);
                            } else {
                                Alert::echoAlert("Ihre Suche ergab keine Treffer!", false);
                            }
                        } 
                        
                        // displays all matching picture names
                        else if (isset($searchFilter) && $searchFilter == 'Bildname') {
                            $searchResults = $db->searchImg($searchFor, $sortBy, $publicOnly);

                            if ($searchResults) {
                                Post::printPostArray($searchResults, $db, $sortBy);
                            } else {
                                Alert::echoAlert("Ihre Suche ergab keine Treffer!", false);
                            }
                        } 
                        
                        // displays all matching tags
                        else if ((isset($searchFilter) && $searchFilter == 'Tag')) {
                            $searchFor = str_replace(",", " ", $searchFor);
                            $searchForArray = explode(" ", $searchFor);
                            $emptyArr = array("");
                            $searchForArray = array_diff($searchForArray, $emptyArr);
                            $searchResults = $db->searchTags($searchForArray, $sortBy, $publicOnly);

                            if ($searchResults) {
                                Post::printPostArray($searchResults, $db, $sortBy);
                            } else {
                                Alert::echoAlert("Ihre Suche ergab keine Treffer!", false);
                            }
                        }
                        
                        // displays all matching comments
                        else if (isset($searchFilter) && $searchFilter == 'Kommentar') {
                            $searchResults = $db->searchComments($searchFor, $publicOnly);

                            if ($searchResults) {
                                echo "<div class='row'>";
                                foreach ($searchResults as $searchResult) {

                                    $userid = $searchResult->getProperty("userId");
                                    $username = $db->getUsername($userid);
                                    $user = $db->getUserById($userid);
                                    if ($commentProfilePic = $user->getProperty("userPicObj")) {
                                        $commentUserProfilePic =  $commentProfilePic->getProperty("path");
                                        $commentThumbProfilePic = $commentProfilePic->getProperty("thumbPath");
                                    } else {
                                        $commentUserProfilePic = "resources/default/profilePic.png";
                                        $commentThumbProfilePic = "resources/default/profilePic.png";
                                    }
                                    $postid = $searchResult->getProperty("postId");
                                    $commentid = $searchResult->getProperty("commentId");
                                    $message = $searchResult->getProperty("message");
                                    $shortMessage = strlen($message) > 20 ? substr($message, 0, 20) . ' ...' : $message;
                                    $commentPost = $db->getPostById($postid);
                                    $time = $commentPost->getTimeDifference();
                                    $likes = $commentPost->getProperty("likes");
                                    $dislikes = $commentPost->getProperty("dislikes");
                
                                    include "templates/searchedCommentsTemplate.php";
                                }
                                echo "</div>";
                            } else {
                                Alert::echoAlert("Ihre Suche ergab keine Treffer!", false);
                            }
                        } 
                        
                        // displays all matching users
                        else if (isset($searchFilter) && $searchFilter == 'User') {
                            if (isset($_GET['show-user'])) {
                                $userid = $db->getUser($_GET['show-user'])->getProperty('id');
                                $postArray = $db->getUserPosts($userid, $sortBy, $publicOnly);
                                Post::printPostArray($postArray, $db, $sortBy);
                            } else {
                                $userArray = $db->searchUser($searchFor);
                                foreach ($userArray as $user) {
                                    $username = $user->getProperty('uname');
                                    if ($userPic = $user->getProperty("userPicObj")) {
                                        $thumbProfilePic =  $userPic->getProperty("thumbPath");
                                        $pathProfilePic =  $userPic->getProperty("path");
                                    } else {
                                        $thumbProfilePic =  "resources/default/profilePic.png";
                                        $pathProfilePic =  "resources/default/profilePic.png";
                                    }
                                    include "templates/user/userCard.php";
                                }
                            }
                        }
                    } 
                } 
                //If $_GET is emty
                else {
                    echo "<div class='d-flex justify-content-center'>";
                    Alert::inlineAlert("Bitte geben Sie einen Suchbegriff ein!");
                    echo "</div>";
                }
            } ?>         
        </div>
    </div>
</div>