CREATE DATABASE  IF NOT EXISTS `wine_cn` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `wine_cn`;
-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: wine_cn
-- ------------------------------------------------------
-- Server version	5.6.17

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
-- Table structure for table `b2b_merchant_wine`
--

DROP TABLE IF EXISTS `b2b_merchant_wine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `b2b_merchant_wine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ym_id` int(11) DEFAULT NULL COMMENT '商家id',
  `fname` varchar(120) NOT NULL COMMENT '英文名[标准]',
  `cname` varchar(120) NOT NULL COMMENT '中文名[标准]',
  `caname_id` int(11) DEFAULT '0' COMMENT '标准酒款ID[jiuku_wine_caname ID]',
  `brand` int(11) DEFAULT '0' COMMENT '品牌ID',
  `winetype` int(11) DEFAULT '0' COMMENT '酒的类型',
  `grape` varchar(50) DEFAULT '0' COMMENT '葡萄类型id',
  `country` int(11) DEFAULT '0' COMMENT '国家id',
  `region` varchar(50) DEFAULT '0' COMMENT '产区id,多级产区存储格式:父产区,子产区,孙产区',
  `title` varchar(120) DEFAULT NULL COMMENT '自定义商品标题',
  `year` varchar(50) DEFAULT '0' COMMENT '酒款年份,可以是多个年份:1983,1988,2002',
  `wholesale_price` varchar(50) DEFAULT '面议' COMMENT '批发价,可以是价格区间:99,188',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间，时间戳形式',
  `lmtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间last modify time',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-27 14:57:27
