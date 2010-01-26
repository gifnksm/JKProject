-- MySQL dump 10.11
--
-- Host: localhost    Database: login_db
-- ------------------------------------------------------
-- Server version	5.0.85

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
-- Table structure for table `credentials`
--

DROP TABLE IF EXISTS `credentials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credentials` (
  `username` char(64) character set ucs2 NOT NULL,
  `password` char(64) NOT NULL,
  `email` char(128) NOT NULL,
  `mobilemail` char(128) default NULL,
  `gender` enum('M','F','N') NOT NULL,
  `birthdate` date default NULL,
  `prefecture` char(64) default NULL,
  `city` char(64) default NULL,
  `rank1` int(11) default NULL,
  `rank2` int(11) default NULL,
  `elevator` enum('large-ev','normal-ev','without-ev') default NULL,
  `step` enum('with-banister','cannot-climb') default NULL,
  `toilet` enum('disabled-toilet','normal-toilet','without-toilet') default NULL,
  `baby` enum('with-baby','without-baby') default NULL,
  `id` int(11) NOT NULL auto_increment,
  `slidedoor` int(11) default NULL,
  `doubledoor` int(11) default NULL,
  `autodoor` int(11) default NULL,
  `width` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credentials`
--

LOCK TABLES `credentials` WRITE;
/*!40000 ALTER TABLE `credentials` DISABLE KEYS */;
INSERT INTO `credentials` VALUES ('å±±ç”°','yamada','yamada@email',NULL,'M',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),('ç”°ä¸­','tanaka','tanaka@email',NULL,'M',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL),('scsc','cs','fda',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL),('reer','dfdf','fafafa',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL),('reerdssfda','dfdf','fafafa',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL),('','','',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL),('asdf','','asdf',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,NULL),('é ˆç”°','','suda@email',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,NULL,NULL,NULL,NULL),('é ˆç”°','','suda@email',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,NULL,NULL,NULL,NULL),('suda','','suda',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,NULL,NULL,NULL,NULL),('ç”°ä¸­','','d','mobilemail','','0000-00-00','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,NULL,NULL,NULL,NULL),('asdfdasdf','','d','','','0000-00-00','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,12,NULL,NULL,NULL,NULL),('asdfdasdf','','d','','','0000-00-00','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,NULL,NULL,NULL,NULL),('aaa','','aaaaa','','','0000-00-00','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,14,NULL,NULL,NULL,NULL),('ã‚ã‚','','aa','','','0000-00-00','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,15,NULL,NULL,NULL,NULL),('ã‚ã‚','','aa','','','0000-00-00','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,16,NULL,NULL,NULL,NULL),('momo','','aa','','','0000-00-00','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,17,NULL,NULL,NULL,NULL),('132','','ssss','','','0000-00-00','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,18,NULL,NULL,NULL,NULL),('132','','ssss','','','0000-00-00','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,19,NULL,NULL,NULL,NULL),('sss','','ss','','','0000-00-00','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,20,NULL,NULL,NULL,NULL),('sss','','sss','','','0000-00-00','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,21,NULL,NULL,NULL,NULL),('qw','','q','q','','0000-00-00','3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,22,NULL,NULL,NULL,NULL),('qw','','q','q','','0000-00-00','3',NULL,12,12,'','','','',23,0,0,0,123),('ã»ã’','','hoge','foo','','0000-00-00','3',NULL,1234,12,'','','','',24,0,0,0,123),('sss','','sss','','','0000-00-00','0',NULL,0,0,'','','','',25,0,0,0,0),('sss','','sss','','','0000-00-00','0',NULL,0,0,'','','','',26,0,0,0,0),('sss','','sss','','','0000-00-00','0',NULL,0,0,'','','','',27,0,0,0,0),('sss','','sss','','','0000-00-00','0',NULL,0,0,'','','','',28,0,0,0,0),('suda','','suda','','','0000-00-00','0',NULL,0,0,'','','','',29,0,0,0,0),('suda','','suda','','','0000-00-00','0',NULL,0,0,'','','','',30,0,0,0,0),('suda','','suda','','','0000-00-00','0',NULL,0,0,'','','','',31,0,0,0,0),('suda','','suda','','','0000-00-00','0',NULL,0,0,'','','','',32,0,0,0,0),('éˆ´æœ¨','suzuki','suzuki','','','0000-00-00','0',NULL,12,12,'','','','',33,0,0,0,12),('æœ¨æ‘','','kimuramail','yoda','','0000-00-00','2',NULL,12,11,'','','','',34,0,0,0,123),('æœ¨æ‘','','kimuramail','yoda','','0000-00-00','2',NULL,12,11,'','','','',35,0,0,0,123),('æœ¨æ‘','','kimuramail','yoda','','0000-00-00','2',NULL,12,11,'','','','',36,0,0,0,123),('æœ¨æ‘','','kimuramail','yoda','','0000-00-00','2',NULL,12,11,'','','','',37,0,0,0,123),('æœ¨æ‘','','kimuramail','','','0000-00-00','2',NULL,0,0,'','','','',38,0,0,0,0),('æœ¨æ‘','','kimuramail','','','0000-00-00','2',NULL,0,0,'','','','',39,0,0,0,0),('æœ¨æ‘','','kimuramail','','','0000-00-00','2',NULL,0,0,'','','','',40,0,0,0,0),('æœ¨æ‘','','kimuramail','','','0000-00-00','2',NULL,0,0,'','','','',41,0,0,0,0),('çŸ³äº•','','ishii@','ishiim@','','0000-00-00','3',NULL,12,1,'','','','',42,0,0,0,123),('çŸ³äº•','','ishii@','ishiim@','','0000-00-00','3',NULL,12,1,'','','','',43,0,0,0,123),('çŸ³äº•','','ishii@','ishiim@','','0000-00-00','3',NULL,12,1,'','','','',44,0,0,0,123),('qq','','qq','','','0000-00-00','0',NULL,0,0,'','','','',45,0,0,0,0),('undo','','undo','','','0000-00-00','0',NULL,0,0,'','','','',46,0,0,0,0),('undo','q','und','','','0000-00-00','0',NULL,0,0,'','','','',47,0,0,0,0),('undo','','undaaaaaa','','','0000-00-00','0',NULL,0,0,'','','','',48,0,0,0,0),('undo','sss','undaaaaaa','','','0000-00-00','0',NULL,0,0,'','','','',49,0,0,0,0),('ddd','','d','','','0000-00-00','0',NULL,0,0,'','','','',50,0,0,0,0),('ddd','','d','','','0000-00-00','0',NULL,0,0,'','','','',51,0,0,0,0),('sss','ssss','tttsss','','','0000-00-00','0',NULL,0,0,'','','','',52,0,0,0,0),('ddd','','dd','','','0000-00-00','0',NULL,0,0,'','','','',53,0,0,0,0),('ss','','s','ss','','0000-00-00','0',NULL,0,0,'','','','',54,0,0,0,123),('ss','','s','ss','','0000-00-00','0',NULL,0,0,'','','','',55,0,0,0,123),('ss','','s','ss','','0000-00-00','0',NULL,0,0,'','','','',56,0,0,0,123),('ss','','s','ss','','0000-00-00','0',NULL,0,0,'','','','',57,0,0,0,123),('ss','','s','ss','','0000-00-00','0',NULL,0,0,'','','','',58,0,0,0,123),('ss','','s','ss','','0000-00-00','0',NULL,0,0,'','','','',59,0,0,0,123),('ss','','s','ss','','0000-00-00','0',NULL,0,0,'','','','',60,0,0,0,123),('ss','','s','ss','','0000-00-00','0',NULL,0,0,'','','','',61,0,0,0,123),('ss','','s','ss','','0000-00-00','0',NULL,0,0,'','','','',62,0,0,0,123),('aa','','a','','','0000-00-00','0',NULL,0,0,'','','','',63,0,0,0,0),('aa','','a','','','0000-00-00','0',NULL,0,0,'','','','',64,0,0,0,0),('aa','','a','','','0000-00-00','0',NULL,0,0,'','','','',65,0,0,0,0),('aa','','a','','','0000-00-00','0',NULL,0,0,'','','','',66,0,0,0,0),('aa','','a','','','0000-00-00','0',NULL,0,0,'','','','',67,0,0,0,0),('aa','','a','','','0000-00-00','0',NULL,0,0,'','','','',68,0,0,0,0),('aa','','a','','','0000-00-00','0',NULL,0,0,'','','','',69,0,0,0,0),('aa','','a','','','0000-00-00','0',NULL,0,0,'','','','',70,0,0,0,0),('aa','','a','','M','0000-00-00','0',NULL,0,0,'','','','',71,0,0,0,0),('test','','a','ss','M','0000-00-00','0',NULL,1,1,'','with-banister','disabled-toilet','with-baby',72,1,1,1,12),('test','','a','ss','M','0000-00-00','0',NULL,1,1,'','with-banister','disabled-toilet','with-baby',73,1,1,1,12),('test','','test','test','M','0000-00-00','0',NULL,11,11,'large-ev','with-banister','disabled-toilet','with-baby',74,1,1,1,11),('test','','test','test','M','0000-00-00','0',NULL,11,11,'large-ev','with-banister','disabled-toilet','with-baby',75,1,1,1,11),('test','','test','test','M','0000-00-00','0',NULL,11,11,'large-ev','with-banister','disabled-toilet','with-baby',76,1,1,1,11),('te','te','te','e','N','0000-00-00','0',NULL,123,123,'large-ev','with-banister','disabled-toilet','with-baby',77,1,0,0,123),('d','','d','d','','0000-00-00','0',NULL,0,0,'','','','',78,0,0,0,0),('d','','d','d','','0000-00-00','0',NULL,0,0,'','','','',79,0,0,0,0),('te','','te','te','M','0000-00-00','0',NULL,0,0,'','','','',80,0,0,0,0),('qq','','qq','','','0000-00-00','0',NULL,0,0,'','','','',81,0,0,0,0),('d','','d','','','0000-00-00','0',NULL,0,0,'','','','',82,0,0,0,0),('d','','d','','','0000-00-00','0',NULL,0,0,'','','','',83,0,0,0,0),('d','','d','','','0000-00-00','0',NULL,0,0,'','','','',84,0,0,0,0),('te','','te','','M','0000-00-00','0',NULL,0,0,'','','','',85,0,0,0,0),('te','','te','','M','0000-00-00','0',NULL,0,0,'','','','',86,0,0,0,0),('te','','te','','M','0000-00-00','0',NULL,0,0,'','','','',87,0,0,0,0),('te','','te','','M','0000-00-00','0',NULL,0,0,'','','','',88,0,0,0,0),('te','s','te','','M','0000-00-00','0',NULL,0,0,'','','','',89,0,0,0,0),('te','s','te','','M','0000-00-00','0',NULL,0,0,'','','','',90,0,0,0,0),('te','s','te','','M','0000-00-00','0',NULL,0,0,'','','','',91,0,0,0,0),('te','s','te','','M','0000-00-00','0',NULL,0,0,'','','','',92,0,0,0,0),('te','s','te','','M','0000-00-00','0',NULL,0,0,'','','','',93,0,0,0,0),('te','s','te','','M','0000-00-00','0',NULL,0,0,'','','','',94,0,0,0,0),('te','s','te','','M','0000-00-00','0',NULL,0,0,'','','','',95,0,0,0,0),('te','s','te','','M','2001-01-01','0',NULL,0,0,'','','','',96,0,0,0,0),('te','s','te','','M','2001-01-01','0',NULL,0,0,'','','','',97,0,0,0,0),('ee','','ee','','','2001-01-01','0',NULL,0,0,'','','','',98,0,0,0,0),('ee','','ee','','','2001-01-01','0',NULL,0,0,'','','','',99,0,0,0,0),('ss','','s','','','2001-01-01','0',NULL,0,0,'','','','',100,0,0,0,0),('ss','','s','','','2001-01-01','0',NULL,0,0,'','','','',101,0,0,0,0),('ss','','s','','','2001-01-01','0',NULL,0,0,'','','','',102,0,0,0,0),('ss','','s','','','2001-01-01','0',NULL,0,0,'','','','',103,0,0,0,0),('ss','','s','','','2001-01-01','0',NULL,0,0,'','','','',104,0,0,0,0),('ss','','s','','','2001-01-01','0',NULL,0,0,'','','','',105,0,0,0,0),('te','','te','','','2001-01-01','0',NULL,0,0,'','','','',106,0,0,0,0),('ã™ã‚ã†','','e','','','2001-01-01','0',NULL,0,0,'','','','',107,0,0,0,0),('s','s','s','s','M','2001-01-01','0',NULL,0,0,'','','','',108,0,0,0,0),('ç›¸æ’²','','s','','','2001-01-01','0',NULL,0,0,'','','','',109,0,0,0,0),('s','sd','s','','','2001-01-01','0',NULL,0,0,'','','','',110,0,0,0,0),('ãŸã‚','tame','e','','','2001-01-01','0',NULL,0,0,'','','','',111,0,0,0,0),('ss','ss','ss','','M','2001-01-01','0',NULL,0,0,'large-ev','with-banister','disabled-toilet','with-baby',112,1,1,0,0),('ss','ss','ss','','M','2001-01-01','0',NULL,0,0,'large-ev','with-banister','disabled-toilet','with-baby',113,1,1,0,0);
/*!40000 ALTER TABLE `credentials` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-01-22 18:51:55
