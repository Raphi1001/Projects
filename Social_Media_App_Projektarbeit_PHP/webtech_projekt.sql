-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 25. Jan 2021 um 12:31
-- Server-Version: 10.4.14-MariaDB
-- PHP-Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `webtech_projekt`
--
CREATE DATABASE IF NOT EXISTS `webtech_projekt` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `webtech_projekt`;

DELIMITER $$
--
-- Prozeduren
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `changeActiveStatus` (IN `isActive` BOOLEAN, IN `userid` INT(10))  BEGIN
    UPDATE user SET ist_aktiv = isActive WHERE User_ID = userid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteAllPostTags` (IN `postId` INT(10))  BEGIN
    DELETE FROM hat_tags WHERE Beitrags_ID = postId;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteComment` (IN `commentId` INT(10))  BEGIN
    DELETE FROM kommentare WHERE Kommentar_ID = commentId;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deletePost` (IN `postId` INT(10))  BEGIN
    DELETE FROM beitraege WHERE Beitrags_ID = postId;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteUser` (IN `id` INT(10))  BEGIN
    DELETE FROM user WHERE user.User_ID = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `dislikePost` (IN `postId` INT(10), IN `userId` INT(10))  BEGIN
    DELETE FROM likes WHERE (likes.Beitrags_ID = postId AND likes.User_ID = userId);
    INSERT INTO dislikes (beitrags_id, user_id) VALUES (postId, userId);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAdmin` (IN `uname` VARCHAR(255))  BEGIN
    SELECT ist_admin FROM user WHERE user.Username = uname;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllPosts` (IN `sortby` VARCHAR(10), IN `publicOnly` BOOLEAN)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCommentById` (IN `commentId` INT(10))  BEGIN
    SELECT * FROM kommentare WHERE Kommentar_ID = commentId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getComments` (IN `postId` INT(10))  BEGIN
    SELECT * FROM kommentare WHERE Beitrags_ID = postId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getDislike` (IN `postId` INT(10), IN `userId` INT(10))  BEGIN
    SELECT * FROM dislikes WHERE Beitrags_ID = postId AND User_ID = userid;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEmail` (IN `email` VARCHAR(255))  BEGIN
    SELECT Emailadresse FROM user WHERE user.Emailadresse = email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getLike` (IN `postId` INT(10), IN `userId` INT(10))  BEGIN
    SELECT * FROM likes WHERE Beitrags_ID = postId AND User_ID = userid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getPostById` (IN `postId` INT(10))  BEGIN
    SELECT * FROM beitraege_view WHERE Beitrags_ID = postId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getPostTags` (IN `postId` INT(10))  BEGIN
    SELECT tags.Tagname
    FROM tags JOIN hat_tags ON tags.Tag_ID = hat_tags.Tag_ID
    WHERE Beitrags_ID = postId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUser` (IN `uname` VARCHAR(255))  BEGIN
    SELECT * FROM user WHERE user.Username=uname;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserById` (IN `userId` INT(10))  BEGIN
    SELECT * FROM user WHERE user.User_ID=userId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserList` ()  BEGIN
    SELECT * FROM user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUsername` (IN `userid` INT(10))  BEGIN
    SELECT Username FROM user WHERE user.User_ID=userid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserPosts` (IN `userId` INT(10), IN `sortby` VARCHAR(10), IN `publicOnly` BOOLEAN)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertComment` (IN `userId` INT(10), IN `postId` INT(10), IN `message` VARCHAR(500))  BEGIN
    INSERT INTO kommentare (User_ID, Beitrags_ID, Inhalt)
                VALUES (userId, postId, message);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertPost` (IN `userId` INT(10), IN `imgPath` VARCHAR(255), IN `description` VARCHAR(500), IN `privacy` BOOLEAN)  BEGIN
    SELECT AUTO_INCREMENT AS PostId
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = 'webtech_projekt' AND TABLE_NAME = 'beitraege';
    INSERT INTO beitraege (User_ID, Bildreferenz, Beschreibung, Privat)
                VALUES (userId, imgPath, description, privacy);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertPostTag` (IN `postId` INT(10), IN `tag_name` VARCHAR(255))  BEGIN
    INSERT INTO tags(Tagname) SELECT tag_name WHERE NOT EXISTS (SELECT * FROM tags WHERE Tagname = tag_name);
    INSERT INTO hat_tags (Beitrags_ID, Tag_ID) VALUES (postId, (SELECT Tag_ID FROM tags WHERE Tagname = tag_name));
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertUser` (IN `gender` VARCHAR(10), IN `fname` VARCHAR(255), IN `lname` VARCHAR(255), IN `email` VARCHAR(255), IN `uname` VARCHAR(255), IN `pwd` VARCHAR(255), IN `userPicRef` VARCHAR(255))  BEGIN
    INSERT INTO user (Anrede, Vorname, Nachname, Emailadresse, Username, Passwort, ist_admin,
                ist_aktiv, Benutzerbildreferenz)
                VALUES (gender, fname, lname, email, uname, pwd, FALSE, TRUE, userPicRef);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `likePost` (IN `postId` INT(10), IN `userId` INT(10))  BEGIN
    DELETE FROM dislikes WHERE (dislikes.Beitrags_ID = postId AND dislikes.User_ID = userId);
    INSERT INTO likes (beitrags_id, user_id) VALUES (postId, userId);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `removeAllDislikes` (IN `postid` INT(10))  BEGIN
    DELETE FROM dislikes WHERE Beitrags_ID = postid;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `removeAllLikes` (IN `postid` INT(10))  BEGIN
    DELETE FROM likes WHERE Beitrags_ID = postid;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `removeDislike` (IN `postId` INT(10), IN `userId` INT(10))  BEGIN
    DELETE FROM dislikes WHERE (dislikes.Beitrags_ID = postId AND dislikes.User_ID = userId);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `removeLike` (IN `postId` INT(10), IN `userId` INT(10))  BEGIN
    DELETE FROM likes WHERE (likes.Beitrags_ID = postId AND likes.User_ID = userId);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `searchComments` (IN `searchTerm` VARCHAR(500), IN `publicOnly` BOOLEAN)  BEGIN
    IF (publicOnly = true) THEN
        SELECT k.* FROM kommentare k JOIN beitraege b USING(Beitrags_ID)
        WHERE k.Inhalt LIKE CONCAT('%', searchTerm, '%') AND b.Privat = false;
    ELSE
        SELECT * FROM kommentare WHERE Inhalt LIKE CONCAT('%', searchTerm, '%');
    end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `searchImg` (IN `searchTerm` VARCHAR(500), IN `sortby` VARCHAR(10), IN `publicOnly` BOOLEAN)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `searchPosts` (IN `searchTerm` VARCHAR(500), IN `sortby` VARCHAR(10), IN `publicOnly` BOOLEAN)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `searchTags` (IN `tagString` VARCHAR(100), IN `sortby` VARCHAR(10), IN `publicOnly` BOOLEAN)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `searchUser` (IN `searchTerm` VARCHAR(255))  BEGIN
    SELECT * FROM user WHERE Username LIKE CONCAT('%', searchTerm, '%') ORDER BY Username ASC;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateComment` (IN `commentId` INT(10), IN `message` VARCHAR(500))  BEGIN
    UPDATE kommentare
    SET
        Inhalt = message
    WHERE Kommentar_ID = commentId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updatePassword` (IN `pwd` VARCHAR(255), IN `uname` VARCHAR(255))  BEGIN
    UPDATE user SET Passwort=pwd WHERE user.Username=uname;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updatePost` (IN `postId` INT(10), IN `imgPath` VARCHAR(255), IN `description` VARCHAR(500), IN `privacy` BOOLEAN)  BEGIN
    UPDATE beitraege
    SET
        beitraege.Bildreferenz = imgPath,
        beitraege.Beschreibung = description,
        beitraege.Privat = privacy
    WHERE beitraege.Beitrags_ID = postId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUser` (IN `gender` VARCHAR(10), IN `fname` VARCHAR(255), IN `lname` VARCHAR(255), IN `email` VARCHAR(255), IN `uname` VARCHAR(255), IN `userPicRef` VARCHAR(255), IN `oldUname` VARCHAR(255))  BEGIN
    UPDATE user
    SET
        Anrede = gender,
        Vorname = fname,
        Nachname = lname,
        Emailadresse = email,
        Username = uname,
        Benutzerbildreferenz = userPicRef
    WHERE user.Username = oldUname;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `beitraege`
--

CREATE TABLE `beitraege` (
  `Beitrags_ID` int(10) UNSIGNED NOT NULL,
  `User_ID` int(10) UNSIGNED NOT NULL,
  `Bildreferenz` varchar(255) DEFAULT NULL,
  `Uploadzeit` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Beschreibung` varchar(500) DEFAULT NULL,
  `Privat` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `beitraege`
--

INSERT INTO `beitraege` (`Beitrags_ID`, `User_ID`, `Bildreferenz`, `Uploadzeit`, `Beschreibung`, `Privat`) VALUES
(1, 1, NULL, '2021-01-25 11:21:59', 'Hallo, ich bin euer Admin!', 1),
(2, 4, NULL, '2021-01-25 10:55:48', 'hello, world!', 0),
(3, 2, 'website.PNG', '2021-01-25 11:06:15', 'Wie findet ihr unsere Website?', 0),
(4, 3, 'amongus.png', '2021-01-25 11:20:05', 'Nur eingeloggte User können diesen Post sehen... hihihi', 1);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `beitraege_view`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE `beitraege_view` (
`Beitrags_ID` int(10) unsigned
,`User_ID` int(10) unsigned
,`Bildreferenz` varchar(255)
,`Uploadzeit` timestamp
,`Beschreibung` varchar(500)
,`Likes` bigint(21)
,`Dislikes` bigint(21)
,`Privat` tinyint(1)
);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dislikes`
--

CREATE TABLE `dislikes` (
  `Beitrags_ID` int(10) UNSIGNED NOT NULL,
  `User_ID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `dislikes`
--

INSERT INTO `dislikes` (`Beitrags_ID`, `User_ID`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hat_tags`
--

CREATE TABLE `hat_tags` (
  `Beitrags_ID` int(10) UNSIGNED NOT NULL,
  `Tag_ID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `hat_tags`
--

INSERT INTO `hat_tags` (`Beitrags_ID`, `Tag_ID`) VALUES
(1, 5),
(2, 1),
(3, 6),
(3, 7),
(4, 2),
(4, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommentare`
--

CREATE TABLE `kommentare` (
  `Kommentar_ID` int(10) UNSIGNED NOT NULL,
  `User_ID` int(10) UNSIGNED NOT NULL,
  `Beitrags_ID` int(10) UNSIGNED NOT NULL,
  `Inhalt` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `kommentare`
--

INSERT INTO `kommentare` (`Kommentar_ID`, `User_ID`, `Beitrags_ID`, `Inhalt`) VALUES
(1, 3, 3, 'cool!'),
(2, 4, 3, 'awesome!'),
(3, 4, 4, 'magic...');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `likes`
--

CREATE TABLE `likes` (
  `Beitrags_ID` int(10) UNSIGNED NOT NULL,
  `User_ID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `likes`
--

INSERT INTO `likes` (`Beitrags_ID`, `User_ID`) VALUES
(2, 4),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(4, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tags`
--

CREATE TABLE `tags` (
  `Tag_ID` int(10) UNSIGNED NOT NULL,
  `Tagname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tags`
--

INSERT INTO `tags` (`Tag_ID`, `Tagname`) VALUES
(7, 'awesomeCrew'),
(1, 'first'),
(6, 'firstWebsite'),
(5, 'important'),
(4, 'orangeIsTheImposter'),
(2, 'private'),
(3, 'topsecret');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `User_ID` int(10) UNSIGNED NOT NULL,
  `Anrede` varchar(10) NOT NULL,
  `Vorname` varchar(255) NOT NULL,
  `Nachname` varchar(255) NOT NULL,
  `Emailadresse` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Passwort` varchar(255) NOT NULL,
  `ist_admin` tinyint(1) NOT NULL,
  `ist_aktiv` tinyint(1) NOT NULL,
  `Benutzerbildreferenz` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`User_ID`, `Anrede`, `Vorname`, `Nachname`, `Emailadresse`, `Username`, `Passwort`, `ist_admin`, `ist_aktiv`, `Benutzerbildreferenz`) VALUES
(1, 'Herr', 'Admin', 'Admin', 'admin@admin.admin', 'admin', '$2y$10$j8ybKgfrEyuGHdSibjJJ7e9woPSzhHa7FBlPb5p3UtqU8pzPcm8Ae', 1, 1, 'spiderman.jpg'),
(2, 'Herr', 'Christoph', 'Kleindienst', 'if20b266@technikum-wien.at', 'chris', '$2y$10$4NFJHZiB0dNvb393nHhoJuhdshQJ7d6jaKrLLC2LTivwxoolYEslG', 0, 1, 'Christoph.jpg'),
(3, 'Herr', 'Raphael', 'Wertitsch', 'if20b047@technikum-wien.at', 'raphi', '$2y$10$xqA7Sq4DzvxGfRIyflU3aeXoxwU6vdS381P4f8wLLyWJDtk2wGl9O', 0, 1, 'Raphael.jpg'),
(4, 'Herr', 'Willy', 'Chhay', 'if20b267@technikum-wien.at', 'will', '$2y$10$jAmTSgkKRTl11EsLqOOj1eQqImnyw8z1wfzi5yIuFy8DwqJnDLf1i', 0, 1, 'willy.jpg');

-- --------------------------------------------------------

--
-- Struktur des Views `beitraege_view`
--
DROP TABLE IF EXISTS `beitraege_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `beitraege_view`  AS  select `b`.`Beitrags_ID` AS `Beitrags_ID`,`b`.`User_ID` AS `User_ID`,`b`.`Bildreferenz` AS `Bildreferenz`,`b`.`Uploadzeit` AS `Uploadzeit`,`b`.`Beschreibung` AS `Beschreibung`,(select count(0) from `likes` `l` where `l`.`Beitrags_ID` = `b`.`Beitrags_ID`) AS `Likes`,(select count(0) from `dislikes` `d` where `d`.`Beitrags_ID` = `b`.`Beitrags_ID`) AS `Dislikes`,`b`.`Privat` AS `Privat` from `beitraege` `b` ;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `beitraege`
--
ALTER TABLE `beitraege`
  ADD PRIMARY KEY (`Beitrags_ID`),
  ADD KEY `fk_user_id_beitraege` (`User_ID`);

--
-- Indizes für die Tabelle `dislikes`
--
ALTER TABLE `dislikes`
  ADD PRIMARY KEY (`Beitrags_ID`,`User_ID`),
  ADD KEY `fk_user_id_dislikes` (`User_ID`);

--
-- Indizes für die Tabelle `hat_tags`
--
ALTER TABLE `hat_tags`
  ADD PRIMARY KEY (`Beitrags_ID`,`Tag_ID`),
  ADD KEY `fk_tag_id_hat_tags` (`Tag_ID`);

--
-- Indizes für die Tabelle `kommentare`
--
ALTER TABLE `kommentare`
  ADD PRIMARY KEY (`Kommentar_ID`),
  ADD KEY `fk_user_id_kommentare` (`User_ID`),
  ADD KEY `fk_beitrags_id_kommentare` (`Beitrags_ID`);

--
-- Indizes für die Tabelle `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`Beitrags_ID`,`User_ID`),
  ADD KEY `fk_user_id_likes` (`User_ID`);

--
-- Indizes für die Tabelle `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`Tag_ID`),
  ADD UNIQUE KEY `unique_tagname` (`Tagname`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `Emailadresse` (`Emailadresse`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `beitraege`
--
ALTER TABLE `beitraege`
  MODIFY `Beitrags_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `kommentare`
--
ALTER TABLE `kommentare`
  MODIFY `Kommentar_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `tags`
--
ALTER TABLE `tags`
  MODIFY `Tag_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `User_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `beitraege`
--
ALTER TABLE `beitraege`
  ADD CONSTRAINT `fk_user_id_beitraege` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `dislikes`
--
ALTER TABLE `dislikes`
  ADD CONSTRAINT `fk_beitrags_id_dislikes` FOREIGN KEY (`Beitrags_ID`) REFERENCES `beitraege` (`Beitrags_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id_dislikes` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `hat_tags`
--
ALTER TABLE `hat_tags`
  ADD CONSTRAINT `fk_beitrags_id_hat_tags` FOREIGN KEY (`Beitrags_ID`) REFERENCES `beitraege` (`Beitrags_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tag_id_hat_tags` FOREIGN KEY (`Tag_ID`) REFERENCES `tags` (`Tag_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kommentare`
--
ALTER TABLE `kommentare`
  ADD CONSTRAINT `fk_beitrags_id_kommentare` FOREIGN KEY (`Beitrags_ID`) REFERENCES `beitraege` (`Beitrags_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id_kommentare` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_beitrags_id_likes` FOREIGN KEY (`Beitrags_ID`) REFERENCES `beitraege` (`Beitrags_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id_likes` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
