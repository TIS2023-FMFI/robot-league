-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: lltest
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignments` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `id_group` int(12) NOT NULL,
  `id_user` int(12) NOT NULL,
  `sk_title` text COLLATE utf8_slovak_ci NOT NULL,
  `en_title` text COLLATE utf8_slovak_ci,
  `de_title` text CHARACTER SET utf8 COLLATE utf8_german2_ci,
  `sk_description` text COLLATE utf8_slovak_ci NOT NULL,
  `en_description` text COLLATE utf8_slovak_ci,
  `de_description` text CHARACTER SET utf8 COLLATE utf8_german2_ci,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assignments_group`
--

DROP TABLE IF EXISTS `assignments_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignments_group` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `year` year(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `banned`
--

DROP TABLE IF EXISTS `banned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banned` (
  `id_user` int(12) NOT NULL,
  KEY `id_user` (`id_user`),
  CONSTRAINT `remove banned user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `id_solution` int(12) NOT NULL,
  `id_user` int(12) NOT NULL,
  `text` text COLLATE utf8_slovak_ci NOT NULL,
  `points` float NOT NULL,
  `internal_comment` text COLLATE utf8_slovak_ci,
  PRIMARY KEY (`id`),
  KEY `id_solution` (`id_solution`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3156 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `google_login`
--

DROP TABLE IF EXISTS `google_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `google_login` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `password_hint` int(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `images_assignment`
--

DROP TABLE IF EXISTS `images_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images_assignment` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `id_assignment` int(12) NOT NULL,
  `extension` varchar(4) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_assignment` (`id_assignment`)
) ENGINE=InnoDB AUTO_INCREMENT=189 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `images_solution`
--

DROP TABLE IF EXISTS `images_solution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images_solution` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `id_solution` int(12) NOT NULL,
  `extension` varchar(4) COLLATE utf8_slovak_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solution` (`id_solution`),
  KEY `extension` (`extension`)
) ENGINE=InnoDB AUTO_INCREMENT=5039 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programs` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `id_solution` int(12) NOT NULL,
  `extension` varchar(4) COLLATE utf8_slovak_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8_slovak_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solution` (`id_solution`)
) ENGINE=InnoDB AUTO_INCREMENT=1228 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solutions`
--

DROP TABLE IF EXISTS `solutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solutions` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `id_assignment` int(12) NOT NULL,
  `id_user` int(12) NOT NULL,
  `text` text COLLATE utf8_slovak_ci NOT NULL,
  `best` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_assignment` (`id_assignment`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=888 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id_user` int(12) NOT NULL,
  `name` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `description` text COLLATE utf8_slovak_ci,
  `sk_league` tinyint(1) NOT NULL DEFAULT '1',
  `city` varchar(255) COLLATE utf8_slovak_ci,
  `street_name` varchar(255) COLLATE utf8_slovak_ci,
  `zip` varchar(15) COLLATE utf8_slovak_ci,
  `category` varchar(255) COLLATE utf8_slovak_ci,
  UNIQUE KEY `id_user_2` (`id_user`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `remove team` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_slovak_ci NOT NULL,
  `last_logged` datetime DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `jury` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `context` int(1) NOT NULL,
  `id_context` int(12) NOT NULL,
  `link` text COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solution` (`id_context`)
) ENGINE=InnoDB AUTO_INCREMENT=1145 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'lltest'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-08-26  1:41:52
