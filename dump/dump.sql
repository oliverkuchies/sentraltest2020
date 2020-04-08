-- MySQL dump 10.13  Distrib 5.7.14, for Win64 (x86_64)
--
-- Host: 192.168.1.202    Database: sentral
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
-- Table structure for table `event_category`
--

DROP TABLE IF EXISTS `event_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_category`
--

LOCK TABLES `event_category` WRITE;
/*!40000 ALTER TABLE `event_category` DISABLE KEYS */;
INSERT INTO `event_category` VALUES (1,'Parents'),(2,'Students'),(3,'Outing'),(4,'Anniversary'),(5,'Celebration');
/*!40000 ALTER TABLE `event_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_locations`
--

DROP TABLE IF EXISTS `event_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(100) DEFAULT NULL,
  `location_coords` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_locations`
--

LOCK TABLES `event_locations` WRITE;
/*!40000 ALTER TABLE `event_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_organiser_info`
--

DROP TABLE IF EXISTS `event_organiser_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_organiser_info` (
  `organiser_id` int(11) NOT NULL AUTO_INCREMENT,
  `organiser_name` varchar(50) DEFAULT NULL,
  `organiser_school_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`organiser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_organiser_info`
--

LOCK TABLES `event_organiser_info` WRITE;
/*!40000 ALTER TABLE `event_organiser_info` DISABLE KEYS */;
INSERT INTO `event_organiser_info` VALUES (1,'Oliver Kucharzewski',1);
/*!40000 ALTER TABLE `event_organiser_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_organisers`
--

DROP TABLE IF EXISTS `event_organisers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_organisers` (
  `event_id` int(11) DEFAULT NULL,
  `organiser_id` int(11) DEFAULT NULL,
  KEY `event_organisers_event_organiser_info_organiser_id_fk` (`organiser_id`),
  KEY `event_organisers_events_event_id_fk` (`event_id`),
  CONSTRAINT `event_organisers_event_organiser_info_organiser_id_fk` FOREIGN KEY (`organiser_id`) REFERENCES `event_organiser_info` (`organiser_id`) ON DELETE CASCADE,
  CONSTRAINT `event_organisers_events_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_organisers`
--

LOCK TABLES `event_organisers` WRITE;
/*!40000 ALTER TABLE `event_organisers` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_organisers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_participant_info`
--

DROP TABLE IF EXISTS `event_participant_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_participant_info` (
  `participant_id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_fname` varchar(50) DEFAULT NULL,
  `participant_lname` varchar(50) DEFAULT NULL,
  `participant_type` varchar(100) DEFAULT NULL,
  `participant_email` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`participant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_participant_info`
--

LOCK TABLES `event_participant_info` WRITE;
/*!40000 ALTER TABLE `event_participant_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_participant_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_participants`
--

DROP TABLE IF EXISTS `event_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_participants` (
  `participant_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  KEY `event_participants_event_participant_info_participant_id_fk` (`participant_id`),
  CONSTRAINT `event_participants_event_participant_info_participant_id_fk` FOREIGN KEY (`participant_id`) REFERENCES `event_participant_info` (`participant_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_participants`
--

LOCK TABLES `event_participants` WRITE;
/*!40000 ALTER TABLE `event_participants` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_schools`
--

DROP TABLE IF EXISTS `event_schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_schools` (
  `school_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(200) DEFAULT NULL,
  `school_coords` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_schools`
--

LOCK TABLES `event_schools` WRITE;
/*!40000 ALTER TABLE `event_schools` DISABLE KEYS */;
INSERT INTO `event_schools` VALUES (1,'Cherrybrook Technology High School','-33.720096,151.0358283'),(2,'Epping Boys','-33.7695466,151.0963558'),(3,'Arden Anglican College','-33.7581971,151.0398371');
/*!40000 ALTER TABLE `event_schools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) DEFAULT NULL,
  `event_description` text,
  `event_category_id` int(11) DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_location_id` int(11) DEFAULT NULL,
  `event_creator_id` int(11) DEFAULT NULL,
  `event_school_id` int(11) DEFAULT NULL,
  `event_distance` varchar(40) DEFAULT NULL,
  `event_travel_time` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `events_event_organiser_info_organiser_id_fk` (`event_creator_id`),
  KEY `events_event_category_category_id_fk` (`event_category_id`),
  KEY `events_event_locations_location_id_fk` (`event_location_id`),
  KEY `events_event_schools_school_id_fk` (`event_school_id`),
  CONSTRAINT `events_event_category_category_id_fk` FOREIGN KEY (`event_category_id`) REFERENCES `event_category` (`category_id`),
  CONSTRAINT `events_event_locations_location_id_fk` FOREIGN KEY (`event_location_id`) REFERENCES `event_locations` (`location_id`),
  CONSTRAINT `events_event_organiser_info_organiser_id_fk` FOREIGN KEY (`event_creator_id`) REFERENCES `event_organiser_info` (`organiser_id`),
  CONSTRAINT `events_event_schools_school_id_fk` FOREIGN KEY (`event_school_id`) REFERENCES `event_schools` (`school_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-08 16:35:34
