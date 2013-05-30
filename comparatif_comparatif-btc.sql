SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `comparatif_comparatif-btc`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(64) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `date` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `code_val` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`,`pass`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment_product`
--

CREATE TABLE IF NOT EXISTS `comment_product` (
  `unique_identifier_product` varchar(64) NOT NULL,
  `id_account` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` int(11) NOT NULL,
  KEY `id_product` (`unique_identifier_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comment_shop`
--

CREATE TABLE IF NOT EXISTS `comment_shop` (
  `id_shop` int(11) NOT NULL,
  `id_account` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id_shop`),
  UNIQUE KEY `id_shop` (`id_shop`,`id_account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `information_extra_cases`
--

CREATE TABLE IF NOT EXISTS `information_extra_cases` (
  `unique_identifier_product` varchar(64) NOT NULL,
  `format` text NOT NULL,
  `matiere` text NOT NULL,
  `color` text NOT NULL,
  `dimension` text NOT NULL,
  `weight` decimal(3,1) NOT NULL,
  PRIMARY KEY (`unique_identifier_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `information_extra_graphiccard`
--

CREATE TABLE IF NOT EXISTS `information_extra_graphiccard` (
  `unique_identifier_product` varchar(64) NOT NULL,
  `familly` text NOT NULL,
  `memory_type` text NOT NULL,
  `memory` int(11) NOT NULL,
  `bus` text NOT NULL,
  `cooling_type` text NOT NULL,
  `frequency_gpu` int(11) NOT NULL,
  `frequency_memory` int(11) NOT NULL,
  `output_video` text NOT NULL,
  `DirectX` text NOT NULL,
  `OpenGL` text NOT NULL,
  PRIMARY KEY (`unique_identifier_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `information_extra_memory`
--

CREATE TABLE IF NOT EXISTS `information_extra_memory` (
  `unique_identifier_product` varchar(64) NOT NULL,
  `memory_type` text NOT NULL,
  `frequency` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `ecc` tinyint(1) NOT NULL,
  `format` text NOT NULL,
  `kit` int(11) NOT NULL,
  `cas` int(1) NOT NULL,
  `voltage` decimal(5,2) NOT NULL,
  PRIMARY KEY (`unique_identifier_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `information_extra_motherboard`
--

CREATE TABLE IF NOT EXISTS `information_extra_motherboard` (
  `unique_identifier_product` varchar(64) NOT NULL,
  `socket` text NOT NULL,
  `memory_type` text NOT NULL,
  `format` text NOT NULL,
  `northbridge` text NOT NULL,
  `port_memoire` text NOT NULL,
  `PCIe_x16` text NOT NULL,
  `PCIe_x4` text NOT NULL,
  `PCIe_x1` text NOT NULL,
  `PCI` text NOT NULL,
  `IDE` text NOT NULL,
  `SATA` text NOT NULL,
  `eSATA` text NOT NULL,
  `SCSI` text NOT NULL,
  `RAID` text NOT NULL,
  `USB` text NOT NULL,
  `firewire` text NOT NULL,
  `sound` text NOT NULL,
  `sound_codec` text NOT NULL,
  `network` text NOT NULL,
  `UEFI` enum('no','yes') NOT NULL,
  PRIMARY KEY (`unique_identifier_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `information_extra_powersupply`
--

CREATE TABLE IF NOT EXISTS `information_extra_powersupply` (
  `unique_identifier_product` varchar(64) NOT NULL,
  `power` text NOT NULL,
  `modulaire` text NOT NULL,
  `certification` text NOT NULL,
  PRIMARY KEY (`unique_identifier_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `information_extra_processor`
--

CREATE TABLE IF NOT EXISTS `information_extra_processor` (
  `unique_identifier_product` varchar(64) NOT NULL,
  `frequency` int(11) NOT NULL,
  `socket` text NOT NULL,
  `nm` int(11) NOT NULL,
  `nbr_core` int(11) NOT NULL,
  `TDP` int(11) NOT NULL,
  `L2` int(11) NOT NULL,
  `L3` int(11) NOT NULL,
  PRIMARY KEY (`unique_identifier_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ipban`
--

CREATE TABLE IF NOT EXISTS `ipban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL,
  `failcount` int(11) NOT NULL,
  `lastfail` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `output_product`
--

CREATE TABLE IF NOT EXISTS `output_product` (
  `unique_identifier_product` varchar(64) NOT NULL,
  `id_shop` int(11) NOT NULL,
  `categorie` varchar(32) NOT NULL,
  `sub_categorie` varchar(32) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `last_time_not_used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`unique_identifier_product`,`id_shop`,`timestamp`),
  KEY `id_shop` (`id_shop`,`last_time_not_used`),
  KEY `categorie` (`categorie`,`last_time_not_used`),
  KEY `sub_categorie` (`sub_categorie`,`last_time_not_used`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_identifier_product` varchar(64) NOT NULL,
  `id_shop` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `url` varchar(255) NOT NULL,
  `price_port` decimal(10,2) NOT NULL,
  `delivery` text NOT NULL,
  `date` int(11) NOT NULL,
  `url_thumb` text NOT NULL,
  `url_technical_details` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_product_2` (`unique_identifier_product`,`id_shop`),
  UNIQUE KEY `url` (`url`),
  KEY `id_shop` (`id_shop`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_base_information`
--

CREATE TABLE IF NOT EXISTS `product_base_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_product` varchar(32) NOT NULL,
  `title` varchar(128) NOT NULL,
  `url_alias_for_seo` varchar(64) NOT NULL,
  `mark` text NOT NULL,
  `date` int(11) NOT NULL,
  `boosted` tinyint(1) NOT NULL,
  `ean` varchar(16) NOT NULL,
  `last_time_not_used` int(11) NOT NULL DEFAULT '0',
  `product_code` varchar(64) NOT NULL,
  `unique_identifier` varchar(64) NOT NULL,
  `extra_product_code` text NOT NULL,
  `rewriten` tinyint(1) NOT NULL,
  `have_thumb` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `boosted` (`boosted`),
  KEY `table_product` (`table_product`),
  KEY `last_time_not_used` (`last_time_not_used`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=997 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_base_information_given`
--

CREATE TABLE IF NOT EXISTS `product_base_information_given` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `unique_identifier` varchar(64) NOT NULL,
  `shop_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1004 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_base_thumbs`
--

CREATE TABLE IF NOT EXISTS `product_base_thumbs` (
  `unique_identifier_product` varchar(64) NOT NULL,
  `thumb_overwrite` text NOT NULL,
  PRIMARY KEY (`unique_identifier_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `url_alias_for_seo` text NOT NULL,
  `url_product_pool` text NOT NULL,
  `site` text NOT NULL,
  `payment` text NOT NULL,
  `delivery_zones` text NOT NULL,
  `insurance_safety` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10001 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
