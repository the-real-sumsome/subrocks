SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `commentlikes` (
  `id` int NOT NULL,
  `sender` varchar(255) NOT NULL,
  `reciever` varchar(255) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'l'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `toid` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `history` (
  `id` int NOT NULL,
  `video` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `likes` (
  `id` int NOT NULL,
  `sender` varchar(255) NOT NULL,
  `reciever` varchar(255) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'l'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `playlists` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `rid` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `videos` varchar(10000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `profilecomments` (
  `id` int NOT NULL,
  `toid` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `subscribers` (
  `id` int NOT NULL,
  `sender` varchar(255) NOT NULL,
  `reciever` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bio` varchar(2000) NOT NULL DEFAULT '',
  `pfp` varchar(255) NOT NULL DEFAULT 'default.png',
  `banner` varchar(255) NOT NULL DEFAULT 'banner.gif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `videolikes` (
  `id` int NOT NULL,
  `toid` int NOT NULL,
  `type` varchar(1) NOT NULL,
  `owner` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `videos` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `publish` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(1024) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `rid` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL DEFAULT '0',
  `visibility` varchar(1) NOT NULL DEFAULT 'n',
  `commenting` varchar(1) NOT NULL DEFAULT 'a'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `views` (
  `id` int NOT NULL,
  `viewer` varchar(255) NOT NULL,
  `videoid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `commentlikes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `profilecomments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `videolikes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `views`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `commentlikes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `playlists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `profilecomments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `subscribers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `videolikes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `videos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `views`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
