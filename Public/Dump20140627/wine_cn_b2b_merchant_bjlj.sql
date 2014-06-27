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
-- Table structure for table `b2b_merchant_bjlj`
--

DROP TABLE IF EXISTS `b2b_merchant_bjlj`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `b2b_merchant_bjlj` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `std_id` int(10) DEFAULT '0' COMMENT '白酒库/烈酒库的酒id,即标准酒ID',
  `brand` varchar(90) DEFAULT NULL COMMENT '品牌',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '区分白酒和洋酒的类型：白酒为1，洋(烈)酒为2',
  `ym_id` int(10) DEFAULT '0' COMMENT '商家ID',
  `region` varchar(60) DEFAULT NULL COMMENT '产地',
  `flavor_id` int(10) NOT NULL DEFAULT '0' COMMENT '香型id',
  `flavor` varchar(100) DEFAULT NULL COMMENT '香型类型',
  `barcode` int(11) NOT NULL DEFAULT '0' COMMENT '条形码',
  `cname` varchar(100) NOT NULL DEFAULT '' COMMENT '中文名',
  `ename` varchar(100) DEFAULT '' COMMENT '英文名',
  `title` varchar(100) DEFAULT '' COMMENT '商家自定标题',
  `alcohol_degree` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '酒精度（°C），0为无效',
  `capacity` varchar(10) NOT NULL COMMENT '容量（ml）',
  `specificat` varchar(50) DEFAULT '' COMMENT '包装规格',
  `wholesale_price` varchar(50) NOT NULL DEFAULT '面议' COMMENT '批发价',
  `brew_house` varchar(50) DEFAULT '' COMMENT '酒厂',
  `raw_material` varchar(200) DEFAULT '' COMMENT '原料',
  `product_features` text COMMENT '商品特点',
  `brew_process` text COMMENT '酿造工艺',
  `honor_awards` text COMMENT '荣誉奖项',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='商家白酒及烈酒酒款表';
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
