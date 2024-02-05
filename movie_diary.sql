-- Progettazione Web 
DROP DATABASE if exists movie_diary; 
CREATE DATABASE movie_diary; 
USE movie_diary; 
-- MySQL dump 10.13  Distrib 5.6.20, for Win32 (x86)
--
-- Host: localhost    Database: movie_diary
-- ------------------------------------------------------
-- Server version	5.6.20

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
-- Table structure for table `movie`
--

DROP TABLE IF EXISTS `movie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `image` text NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movie`
--

LOCK TABLES `movie` WRITE;
/*!40000 ALTER TABLE `movie` DISABLE KEYS */;
INSERT INTO `movie` VALUES (2,'Tolo Tolo','Checco Zalone si mantiene in equilibrio sul crinale della correttezza politica, colpendo a 360?.','https://www.amica.it/wp-content/uploads/2019/12/tolo-tolo-checco-zalone-manifesto.jpg','2019-12-30 20:32:47'),(4,'Terminator destino oscuro','Nell\'ora del trionfo delle macchine, terminator ha il merito inatteso di farci credere ancora un po\' nell\'uomo.','https://pad.mymovies.it/filmclub/2018/08/029/locandina.jpg','2020-01-15 11:58:59'),(5,'Hammamet','La discesa crepuscolare di un uomo dominato da pulsioni contrapposte. Un Craxi più vero del vero grazie a un gigantesco Favino','https://pad.mymovies.it/filmclub/2018/07/050/locandina.jpg','2020-01-15 12:16:56'),(6,'Leonardo - Le opere','Dalla Madonna Litta al Salvator Mundi da 450 milioni di dollari. La pittura di Leonardo è più viva che mai.','https://pad.mymovies.it/filmclub/2019/12/008/locandina.jpg','2020-01-15 12:40:54'),(7,'Richard Jewell','C\'è una bomba al Centennial Park. Avete solo trenta minuti di tempo. Il mondo viene così a conoscenza di Richard Jewell, una guardia di sicurezza che riferisce di aver trovato il dispositivo dell\'attentato dinamitardo di Atlanta del 1996. Il suo tempestivo intervento salva numerose vite, rendendolo un eroe. Ma in pochi giorni, l\'aspirante alle forze dell\'ordine diventa il sospettato numero uno dell\'FBI...','https://pad.mymovies.it/filmclub/2019/06/176/locandina.jpg','2020-01-16 14:07:47'),(8,'Jojo Rabbit','Una favola nera che misura l\'impatto della guerra e dei fascismi sugli spiriti innocenti.','https://pad.mymovies.it/filmclub/2018/06/045/locandina.jpg','2020-01-16 14:09:00'),(10,'Star Wars l\'ascesa di sky walker','Gli ultimi membri sopravvissuti della Resistenza affrontano il Primo Ordine mentre Rey, Finn e Poe Dameron continuano il proprio viaggio. La grande battaglia conclusiva ha finalmente inizio.\r\n','https://www.nocturno.it/wp-content/uploads/2019/12/Star-Wars-L%E2%80%99Ascesa-di-Skywalker-poster.jpg','2020-02-18 01:50:48'),(11,'Jumanji - The next level','Spencer decide di riparare il gioco di Jumanji e finisce ancora una volta risucchiato nel mondo del videogame. Per salvare il loro amico, Bethany, Fridge e Martha si rituffano dentro Jumanji.','https://pad.mymovies.it/filmclub/2019/07/008/locandina.jpg','2020-02-18 01:51:37');
/*!40000 ALTER TABLE `movie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movie_comments`
--

DROP TABLE IF EXISTS `movie_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movie_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movie_id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pub_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `movie_id` (`movie_id`),
  KEY `user` (`user`),
  CONSTRAINT `movie_comments_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `movie_comments_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movie_comments`
--

LOCK TABLES `movie_comments` WRITE;
/*!40000 ALTER TABLE `movie_comments` DISABLE KEYS */;
INSERT INTO `movie_comments` VALUES (2,11,'root','Questo film è fantastico!','2020-02-20 09:48:12'),(6,11,'test','Non vedo l\'ora di guardarlo!','2020-02-22 15:20:00');
/*!40000 ALTER TABLE `movie_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`),
  UNIQUE KEY `username` (`username`),
  KEY `reg_date` (`reg_date`),
  KEY `reg_date_2` (`reg_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('root','$2y$10$z8gpUyW8V1YU9Gcl/TKw5ub8P0NRmGI7zXvqKeVAw/qLs9y.o1jz2','admin@mysite.it','2019-12-26 17:21:20','1'),('test','$2y$10$V8XhzWZghFT039SOS0CWI.u34P1v9y5j5VNAQVwW7IfaGHMM9SxYO','test@user.it','2020-02-22 14:18:14','0');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_movies`
--

DROP TABLE IF EXISTS `user_movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_movies` (
  `user` varchar(255) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `watch_later` enum('0','1') NOT NULL DEFAULT '0',
  `watched` enum('0','1') NOT NULL DEFAULT '0',
  `favorite` enum('0','1') NOT NULL DEFAULT '0',
  `vote` int(11) NOT NULL DEFAULT '0',
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user`,`movie_id`),
  KEY `user_movies_ibfk_2` (`movie_id`),
  CONSTRAINT `user_movies_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_movies_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_movies`
--

LOCK TABLES `user_movies` WRITE;
/*!40000 ALTER TABLE `user_movies` DISABLE KEYS */;
INSERT INTO `user_movies` VALUES ('root',2,'0','0','1',4,'2020-01-17 16:08:13'),('root',4,'1','0','0',0,'2020-02-15 17:18:27'),('root',5,'1','0','0',3,'2020-02-15 16:11:16'),('root',6,'0','0','0',0,'2020-02-12 17:59:30'),('root',7,'0','0','0',0,'2020-02-12 18:22:57'),('root',8,'1','0','0',1,'2020-01-17 16:04:31'),('root',10,'0','0','1',3,'2020-02-22 15:25:34'),('root',11,'1','1','0',3,'2020-02-20 09:47:57'),('test',5,'0','1','1',0,'2020-02-22 15:20:48'),('test',11,'1','0','0',0,'2020-02-22 15:19:36');
/*!40000 ALTER TABLE `user_movies` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-22 15:34:23
