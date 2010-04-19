CREATE TABLE IF NOT EXISTS `ipicelandic_cache` (
    `ip`        TINYBLOB   NOT NULL,
    `icelandic` TINYINT(1) NOT NULL,
    `when`      TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY  (`ip`(16)),
    KEY `ip_icelandic` (`ip`(16),`icelandic`),
    KEY `when` (`when`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
