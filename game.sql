-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: game
-- ------------------------------------------------------
-- Server version	5.7.23-0ubuntu0.18.04.1

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
-- Table structure for table `game_bases`
--

DROP TABLE IF EXISTS `game_bases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_bases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT 'base',
  `player` varchar(255) DEFAULT NULL,
  `playerId` int(11) DEFAULT NULL,
  `HP` int(11) NOT NULL DEFAULT '100',
  `main` tinyint(1) NOT NULL DEFAULT '0',
  `pos` json DEFAULT NULL,
  `workerSpace` int(11) NOT NULL DEFAULT '9',
  `soldierSpace` int(11) NOT NULL DEFAULT '9',
  `workers` int(11) NOT NULL DEFAULT '1',
  `soldiers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_bases`
--

LOCK TABLES `game_bases` WRITE;
/*!40000 ALTER TABLE `game_bases` DISABLE KEYS */;
INSERT INTO `game_bases` VALUES (69,'base','test',26,500,1,'[166.603271484375, 99.8510520038982]',9,9,8,0);
/*!40000 ALTER TABLE `game_bases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_lastScore`
--

DROP TABLE IF EXISTS `game_lastScore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_lastScore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place` varchar(255) DEFAULT NULL,
  `playerId` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_lastScore`
--

LOCK TABLES `game_lastScore` WRITE;
/*!40000 ALTER TABLE `game_lastScore` DISABLE KEYS */;
INSERT INTO `game_lastScore` VALUES (1,'first',26,0),(2,'second',27,0),(3,'third',28,0);
/*!40000 ALTER TABLE `game_lastScore` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_mines`
--

DROP TABLE IF EXISTS `game_mines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_mines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT 'mine',
  `player` varchar(255) NOT NULL,
  `playerId` int(11) NOT NULL,
  `HP` int(11) NOT NULL DEFAULT '100',
  `pos` json NOT NULL,
  `workerSpace` int(11) NOT NULL DEFAULT '4',
  `soldierSpace` int(11) NOT NULL DEFAULT '4',
  `workers` int(11) NOT NULL DEFAULT '1',
  `soldiers` int(11) NOT NULL DEFAULT '0',
  `metalNodes` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_mines`
--

LOCK TABLES `game_mines` WRITE;
/*!40000 ALTER TABLE `game_mines` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_mines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_tasks`
--

DROP TABLE IF EXISTS `game_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `startOrigin` varchar(255) DEFAULT NULL,
  `startPos` varchar(255) DEFAULT NULL,
  `targetOrigin` varchar(255) DEFAULT NULL,
  `targetPos` varchar(255) DEFAULT NULL,
  `startTime` int(11) NOT NULL DEFAULT '0',
  `endTime` int(11) DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=943 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_tasks`
--

LOCK TABLES `game_tasks` WRITE;
/*!40000 ALTER TABLE `game_tasks` DISABLE KEYS */;
INSERT INTO `game_tasks` VALUES (939,'buy','soldier','base,69','[166.60327148438,99.851052003898]',NULL,NULL,1539614352,1539616152,26),(940,'buy','soldier','base,69','[166.60327148438,99.851052003898]',NULL,NULL,1539614357,1539616157,26),(941,'move','worker,1','base,69','[166.603271484375, 99.8510520038982]','','[162.97778320312,102.5352435535]',1539616428,1539616438,26),(942,'build','mine','base,69','[166.60327148438,99.851052003898]',NULL,'[162.977783203125,102.53524355349668]',1539616438,1539620038,26);
/*!40000 ALTER TABLE `game_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_users`
--

DROP TABLE IF EXISTS `game_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `newUser` tinyint(1) NOT NULL DEFAULT '1',
  `token` varchar(255) DEFAULT NULL,
  `token_exp` datetime DEFAULT NULL,
  `score` mediumint(9) NOT NULL DEFAULT '0',
  `metal` int(11) NOT NULL DEFAULT '2500',
  `bestScore` int(11) DEFAULT '0',
  `totalScore` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_users`
--

LOCK TABLES `game_users` WRITE;
/*!40000 ALTER TABLE `game_users` DISABLE KEYS */;
INSERT INTO `game_users` VALUES (26,'test','ssetsetes@fdsfsd.fdsfsd','$2y$10$9tPYnEmiWj1HLUaGRnFiDeFAwrQkCsLurNZrgOfAjK1cZgRbbOz/m',0,NULL,NULL,0,2150,1051,2727),(27,'test2','xvxcvxvx@dsfds.cxs','$2y$10$N9v5fHk/dnk7wnLK47effuMtlRQFvmdU1rXJYWiN4tSRG4us4dqB2',1,NULL,NULL,0,2500,100,100),(28,'test3','fsdsdf@dksl.fds','$2y$10$vbQy9RTAGOuq3S0wfeptWeodIp7w46EDzTcG4HWKCdhVok3da10x.',1,NULL,NULL,0,2500,1000,0),(29,'test4','sfdsfsdf@fdsfdf.con','$2y$10$lM9XdJk/MTke/axceQWNH.Us36Qu5lGuLmLVq9b0VbGzID2fgns8.',1,NULL,NULL,0,2500,653,653),(30,'test5','sfsdfds@fdss.dsahoi','$2y$10$xW0Z6rQQLCyN9P3OvSBStuK7.e7Pc6Sfcb7vacBYjLK0VRhAHb6Cm',1,NULL,NULL,0,2500,0,0),(31,'test444','Snowkraft02@gmail.com','$2y$10$hZZ8FBGSH3MiVWZWngdLD.QWut8bJ7gSOSbckMi9edo2CbRs2kS2S',1,NULL,NULL,0,2500,0,0);
/*!40000 ALTER TABLE `game_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-17 14:22:53
