
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `ipicelandic`
--

CREATE TABLE `ipicelandic` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(15) NOT NULL,
  `expire` bigint(20) NOT NULL,
  `icelandic` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3765 ;
