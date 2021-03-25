/*User*/
CREATE PROCEDURE getUserList()
BEGIN
    SELECT * FROM user;
END;

CREATE PROCEDURE getUser(IN uname VARCHAR(255))
BEGIN
    SELECT * FROM user WHERE user.Username=uname;
END;

CREATE PROCEDURE getUserById(IN userId INT(10))
BEGIN
    SELECT * FROM user WHERE user.User_ID=userId;
END;

CREATE PROCEDURE getUsername(IN userid INT(10))
BEGIN
    SELECT Username FROM user WHERE user.User_ID=userid;
END;

CREATE PROCEDURE insertUser(
    IN gender VARCHAR(10),
    IN fname VARCHAR(255),
    IN lname VARCHAR(255),
    IN email VARCHAR(255),
    IN uname VARCHAR(255),
    IN pwd VARCHAR(255),
    IN userPicRef VARCHAR(255)
)
BEGIN
    INSERT INTO user (Anrede, Vorname, Nachname, Emailadresse, Username, Passwort, ist_admin,
                ist_aktiv, Benutzerbildreferenz)
                VALUES (gender, fname, lname, email, uname, pwd, FALSE, TRUE, userPicRef);
END;

CREATE PROCEDURE updateUser(
    IN gender VARCHAR(10),
    IN fname VARCHAR(255),
    IN lname VARCHAR(255),
    IN email VARCHAR(255),
    IN uname VARCHAR(255),
    IN userPicRef VARCHAR(255),
    IN oldUname VARCHAR(255)
)
BEGIN
    UPDATE user
    SET
        Anrede = gender,
        Vorname = fname,
        Nachname = lname,
        Emailadresse = email,
        Username = uname,
        Benutzerbildreferenz = userPicRef
    WHERE user.Username = oldUname;
END;

CREATE PROCEDURE updatePassword(
    IN pwd VARCHAR(255),
    IN uname VARCHAR(255)
)
BEGIN
    UPDATE user SET Passwort=pwd WHERE user.Username=uname;
END;

CREATE PROCEDURE deleteUser(
    IN id INT(10)
)
BEGIN
    DELETE FROM user WHERE user.User_ID = id;
END;

CREATE PROCEDURE getEmail(
    IN email VARCHAR(255)
)
BEGIN
    SELECT Emailadresse FROM user WHERE user.Emailadresse = email;
END;

CREATE PROCEDURE getAdmin(
    IN uname VARCHAR(255)
)
BEGIN
    SELECT ist_admin FROM user WHERE user.Username = uname;
END;

CREATE PROCEDURE changeActiveStatus(IN isActive BOOLEAN, IN userid INT(10))
BEGIN
    UPDATE user SET ist_aktiv = isActive WHERE User_ID = userid;
END;

/*Post*/

CREATE PROCEDURE insertPost(
    IN userId INT(10),
    IN imgPath VARCHAR(255),
    IN description VARCHAR(500),
    IN privacy BOOLEAN
)
BEGIN
    SELECT AUTO_INCREMENT AS PostId
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = 'webtech_projekt' AND TABLE_NAME = 'beitraege';
    INSERT INTO beitraege (User_ID, Bildreferenz, Beschreibung, Privat)
                VALUES (userId, imgPath, description, privacy);
END;

CREATE PROCEDURE insertPostTag(IN postId INT(10), IN tag_name VARCHAR(255))
BEGIN
    INSERT INTO tags(Tagname) SELECT tag_name WHERE NOT EXISTS (SELECT * FROM tags WHERE Tagname = tag_name);
    INSERT INTO hat_tags (Beitrags_ID, Tag_ID) VALUES (postId, (SELECT Tag_ID FROM tags WHERE Tagname = tag_name));
END;

CREATE PROCEDURE getLike (IN postId INT(10), IN userId INT(10))
BEGIN
    SELECT * FROM likes WHERE Beitrags_ID = postId AND User_ID = userid;
END;

CREATE PROCEDURE getDislike (IN postId INT(10), IN userId INT(10))
BEGIN
    SELECT * FROM dislikes WHERE Beitrags_ID = postId AND User_ID = userid;
end;

CREATE PROCEDURE likePost(IN postId INT(10), IN userId INT(10))
BEGIN
    DELETE FROM dislikes WHERE (dislikes.Beitrags_ID = postId AND dislikes.User_ID = userId);
    INSERT INTO likes (beitrags_id, user_id) VALUES (postId, userId);
end;

CREATE PROCEDURE dislikePost(IN postId INT(10), IN userId INT(10))
BEGIN
    DELETE FROM likes WHERE (likes.Beitrags_ID = postId AND likes.User_ID = userId);
    INSERT INTO dislikes (beitrags_id, user_id) VALUES (postId, userId);
end;

CREATE PROCEDURE removeLike(IN postId INT(10), IN userId INT(10))
BEGIN
    DELETE FROM likes WHERE (likes.Beitrags_ID = postId AND likes.User_ID = userId);
end;

CREATE PROCEDURE removeDislike(IN postId INT(10), IN userId INT(10))
BEGIN
    DELETE FROM dislikes WHERE (dislikes.Beitrags_ID = postId AND dislikes.User_ID = userId);
end;

CREATE PROCEDURE removeAllLikes (IN postid INT (10))
BEGIN
    DELETE FROM likes WHERE Beitrags_ID = postid;
end;

CREATE PROCEDURE removeAllDislikes (IN postid INT (10))
BEGIN
    DELETE FROM dislikes WHERE Beitrags_ID = postid;
end;

CREATE PROCEDURE getAllPosts(IN sortby VARCHAR(10), IN publicOnly BOOLEAN)
BEGIN
    IF (publicOnly = true) THEN
        SELECT * FROM beitraege_view
        WHERE Privat = false
        ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    ELSE
        SELECT * FROM beitraege_view
        ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    END IF;
END;

CREATE PROCEDURE getPostById(IN postId INT(10))
BEGIN
    SELECT * FROM beitraege_view WHERE Beitrags_ID = postId;
END;

CREATE PROCEDURE getPostTags(IN postId INT(10))
BEGIN
    SELECT tags.Tagname
    FROM tags JOIN hat_tags ON tags.Tag_ID = hat_tags.Tag_ID
    WHERE Beitrags_ID = postId;
END;

CREATE PROCEDURE getUserPosts(IN userId INT(10), IN sortby VARCHAR(10), IN publicOnly BOOLEAN)
BEGIN
    IF (publicOnly = true) THEN
        SELECT * FROM beitraege_view WHERE User_ID = userId AND Privat = false ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    ELSE
        SELECT * FROM beitraege_view WHERE User_ID = userId ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    END IF;
END;

CREATE PROCEDURE updatePost(
    IN postId INT(10),
    IN imgPath VARCHAR(255),
    IN description VARCHAR(500),
    IN privacy BOOLEAN
)
BEGIN
    UPDATE beitraege
    SET
        beitraege.Bildreferenz = imgPath,
        beitraege.Beschreibung = description,
        beitraege.Privat = privacy
    WHERE beitraege.Beitrags_ID = postId;
END;

CREATE PROCEDURE deletePost(IN postId INT(10))
BEGIN
    DELETE FROM beitraege WHERE Beitrags_ID = postId;
end;

CREATE PROCEDURE deleteAllPostTags (IN postId INT(10))
BEGIN
    DELETE FROM hat_tags WHERE Beitrags_ID = postId;
end;

/*Comments*/
CREATE PROCEDURE insertComment(
    IN userId INT(10),
    IN postId INT(10),
    IN message VARCHAR(500)
)
BEGIN
    INSERT INTO kommentare (User_ID, Beitrags_ID, Inhalt)
        VALUES (userId, postId, message);
END;

CREATE PROCEDURE getComments(
    IN postId INT(10)
)
BEGIN
    SELECT * FROM kommentare WHERE Beitrags_ID = postId;
END;

CREATE PROCEDURE getCommentById(
    IN commentId INT(10)
)
BEGIN
    SELECT * FROM kommentare WHERE Kommentar_ID = commentId;
END;

CREATE PROCEDURE updateComment(
    IN commentId INT(10),
    IN message VARCHAR(500)
)
BEGIN
    UPDATE kommentare
    SET
        Inhalt = message
    WHERE Kommentar_ID = commentId;
END;

CREATE PROCEDURE deleteComment(IN commentId INT(10))
BEGIN
    DELETE FROM kommentare WHERE Kommentar_ID = commentId;
end;

/*Search Functions*/
CREATE PROCEDURE searchPosts (IN searchTerm VARCHAR(500), IN sortby VARCHAR(10), IN publicOnly BOOLEAN)
BEGIN
    IF (publicOnly = true) THEN
        SELECT * FROM beitraege_view WHERE Beschreibung LIKE CONCAT('%', searchTerm, '%') AND Privat = false ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    ELSE
        SELECT * FROM beitraege_view WHERE Beschreibung LIKE CONCAT('%', searchTerm, '%') ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    END IF;
END;

CREATE PROCEDURE searchComments (IN searchTerm VARCHAR(500), IN publicOnly BOOLEAN)
BEGIN
    IF (publicOnly = true) THEN
        SELECT k.* FROM kommentare k JOIN beitraege b USING(Beitrags_ID)
        WHERE k.Inhalt LIKE CONCAT('%', searchTerm, '%') AND b.Privat = false;
    ELSE
        SELECT * FROM kommentare WHERE Inhalt LIKE CONCAT('%', searchTerm, '%');
    end if;
END;

/*Search Post by tag with a comma-separated tagString (no spaces)*/
CREATE PROCEDURE searchTags(IN tagString VARCHAR(100), IN sortby VARCHAR(10), IN publicOnly BOOLEAN)
BEGIN
    DECLARE tagCnt INT;
    SET tagCnt = CHAR_LENGTH(tagString) - CHAR_LENGTH(REPLACE(tagString, ',', '')) + 1;

    IF (publicOnly = true) THEN
        SELECT b.*
            FROM hat_tags h JOIN beitraege_view b USING(Beitrags_ID)
            WHERE h.Tag_ID IN (SELECT Tag_ID FROM tags WHERE FIND_IN_SET(Tagname, tagString)) AND b.Privat = false
            GROUP BY Beitrags_ID
            HAVING COUNT(h.Tag_ID) = tagCnt
        ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    ELSE
        SELECT b.*
            FROM hat_tags h JOIN beitraege_view b USING(Beitrags_ID)
            WHERE h.Tag_ID IN (SELECT Tag_ID FROM tags WHERE FIND_IN_SET(Tagname, tagString))
            GROUP BY Beitrags_ID
            HAVING COUNT(h.Tag_ID) = tagCnt
        ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    END IF;
END;

CREATE PROCEDURE searchImg (IN searchTerm VARCHAR(500), IN sortby VARCHAR(10), IN publicOnly BOOLEAN)
BEGIN
    IF (publicOnly = true) THEN
        SELECT * FROM beitraege_view WHERE Bildreferenz LIKE CONCAT('%', searchTerm, '%') AND Privat = false ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    ELSE
        SELECT * FROM beitraege_view WHERE Bildreferenz LIKE CONCAT('%', searchTerm, '%') ORDER BY
        CASE
            WHEN sortby = 'Uploadzeit' THEN Uploadzeit
            WHEN sortby = 'Likes' THEN Likes
            WHEN sortby = 'Dislikes' THEN Dislikes
            ELSE Uploadzeit
        END DESC, Uploadzeit DESC;
    END IF;
END;

CREATE PROCEDURE searchUser (IN searchTerm VARCHAR(255))
BEGIN
    SELECT * FROM user WHERE Username LIKE CONCAT('%', searchTerm, '%') ORDER BY Username ASC;
end;