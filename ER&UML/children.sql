CREATE DATABASE  IF NOT EXISTS `management` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `management`;
-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: management
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.16.04.1

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
-- Dumping data for table `daily_schedule`
--

LOCK TABLES `daily_schedule` WRITE;
/*!40000 ALTER TABLE `daily_schedule` DISABLE KEYS */;
INSERT INTO `daily_schedule` VALUES (1,0,'2017-07-10',1,3),(2,1,'2017-07-10',1,5),(3,0,'2017-07-11',1,3),(4,1,'2017-07-11',1,5),(5,0,'2017-07-12',1,3),(6,1,'2017-07-12',1,5),(7,0,'2017-07-12',1,9),(8,0,'2017-07-12',1,3),(9,0,'2017-07-12',1,5),(10,0,'2017-07-12',1,9),(11,0,'2017-07-12',1,3),(12,0,'2017-07-12',1,5),(13,0,'2017-07-12',1,9),(14,0,'2017-07-13',1,3),(15,0,'2017-07-13',1,5),(16,0,'2017-07-13',1,9),(17,0,'2017-07-16',1,3),(18,0,'2017-07-16',1,5),(19,0,'2017-07-16',1,9),(21,0,'2017-07-18',1,3),(22,0,'2017-07-18',1,5),(23,0,'2017-07-18',1,9),(25,0,'2017-07-18',1,20),(26,0,'2017-07-18',1,21),(27,0,'2017-07-18',1,23),(28,0,'2017-07-19',6,3),(29,0,'2017-07-19',6,5),(30,0,'2017-07-19',6,9),(32,0,'2017-07-19',6,20),(33,0,'2017-07-19',6,21),(34,0,'2017-07-19',6,23),(35,0,'2017-07-19',6,3),(36,0,'2017-07-19',6,5),(37,0,'2017-07-19',6,9),(39,0,'2017-07-19',6,20),(40,0,'2017-07-19',6,21),(42,0,'2017-07-19',6,3),(43,0,'2017-07-19',6,5),(44,0,'2017-07-19',6,9),(46,0,'2017-07-19',6,20),(47,0,'2017-07-19',6,21),(49,0,'2017-07-19',7,3),(50,0,'2017-07-19',7,5),(51,0,'2017-07-19',7,9),(53,0,'2017-07-19',7,20),(54,0,'2017-07-19',7,21),(55,0,'2017-07-19',7,23),(56,0,'2017-07-26',6,3),(57,0,'2017-07-26',7,3),(58,0,'2017-07-26',6,5),(59,0,'2017-07-26',7,5),(60,0,'2017-07-26',6,9),(61,0,'2017-07-26',7,9),(64,0,'2017-07-26',6,20),(65,0,'2017-07-26',7,20),(66,0,'2017-07-26',6,21),(67,0,'2017-07-26',7,21),(68,0,'2017-07-26',6,23),(69,0,'2017-07-26',7,23),(70,0,'2017-07-27',6,3),(71,0,'2017-07-27',7,3),(72,0,'2017-07-27',6,5),(73,0,'2017-07-27',7,5),(78,0,'2017-07-27',6,20),(79,0,'2017-07-27',7,20),(80,0,'2017-07-27',6,21),(81,0,'2017-07-27',7,9),(100,1,'2017-07-27',6,9),(101,1,'2017-07-30',6,23),(102,1,'2017-08-01',6,23),(104,1,'2017-08-07',6,9),(105,1,'2017-08-15',6,5),(106,1,'2017-08-16',6,5),(107,1,'2017-08-17',6,9),(116,1,'2017-08-28',6,9);
/*!40000 ALTER TABLE `daily_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `task`
--

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` VALUES (1,'football',1,2),(2,'task1',7,1),(3,'sport',7,10),(6,'fagr',10,0),(7,'help mother',10,2);
/*!40000 ALTER TABLE `task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'sara','elsyed','saraElsayed','123456',24,1,2),(2,'salma','mahmoud','salmaMahmoud','123456',4,3,NULL),(3,'esraa','hassan','esraaHassan','123456',23,3,NULL),(4,'aaa','aaaaaaaaaaaa','aaaaaaaaaaa','$2y$13$SxVvjS.2pywb0I1GdQdBuefpEZObD6vGNsYWMBDtIiuiBWRgAtVcS',5,2,2),(5,'menna','elsayed','mennaElsayed','$2y$13$zM.o3yGns80CNjzmjUWvFujlcycl0LN3VpWh76C0UQDbKPVt1oPqi',12,3,NULL),(7,'sara','elsayed','saraelskely','$2y$13$XH0fDKXdKSChJ3VLnXV8gu.O/vrIVlqrZp3.IP561ETYDwhPyBgt2',24,2,NULL),(8,'sally','ahmed','sallyahmed','$2y$13$e24oK5YNj22HVTUa2yz1xeZFhNTVjZgFOi1XqmenFJu4G8aQlbPsW',29,2,NULL),(9,'menna1','elsayed1','mennaElsayed1','$2y$13$zM.o3yGns80CNjzmjUWvFujlcycl0LN3VpWh76C0UQDbKPVt1oPqi',12,3,7),(10,'admin','admin','admin','$2y$13$oiN5dSDQZdYl8JtbbwUvheq3NXoRnDMC6iJtq236T6Le/8DG0i/2K',30,1,NULL),(20,'sara','sara','saraMohammed','$2y$13$1fAX8JV4.yECAb/ffxrB2eYpSUMI07X.QlTaO4Pr8H5F9W7tWef4O',22,3,7),(21,'mohamed','ahmed','Mohamed','$2y$13$BO7WvlyBlyLVAHtxuvlsB.q1ZYcWSMYGQBOEWi7nxNTRAX5tjvvUm',7,3,7),(23,'mahmoud','mahmoud','mahmoudSaad','$2y$13$GYBUFP11GeyBv6diJCiGPe6wKT1hpVKSUzHXaSQEChpn0Vtv6/5xu',5,3,7),(24,'mohammed','mohammed','mohammedelalem','$2y$13$SkmaFhZn9cgOTBkYsH7AYOuXabueHZI48hqkhao0VTqx6faK6vZDu',24,2,NULL),(25,'nancy','saad','nancysaad','$2y$13$Rycfj00jrB0yhkrqz4ZfYu5emZpvyK8TsmW7vnDog3oK7weRz8LW6',20,3,7);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-09-20 10:46:17
