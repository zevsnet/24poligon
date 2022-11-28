CREATE TABLE IF NOT EXISTS `bm_idex_seo_pages` (
  `ID` int(13) NOT NULL auto_increment,
  `URL` varchar(255) NOT NULL,
  `QUERY` varchar(255) NOT NULL,
  `USE_GET` varchar(1) NOT NULL DEFAULT 'N',
  `USE_INHERITANCE` varchar(1) NOT NULL DEFAULT 'N',
  `ACTIVE` varchar(1) NOT NULL default 'Y',
  `TITLE` text NULL,
  `BROWSER_TITLE` text NULL,  
  `KEYWORDS` text NULL,
  `DESCRIPTION` text NULL, 
  `SEO_TEXT` text NULL,
  `SEO_TEXT_2` text NULL,
  PRIMARY KEY  (`ID`),
  KEY `URL` (`URL`)
);

CREATE TABLE IF NOT EXISTS `bm_idex_seo_blocks` (
  `ID` int(13) NOT NULL auto_increment,
  `PAGE_ID` int(11) NOT NULL,
  `HTML_ID` varchar(255) NOT NULL,
  `TEXT` text NOT NULL, 
  PRIMARY KEY  (`ID`),
  KEY `PAGE_ID` (`PAGE_ID`)  
);

ALTER TABLE `bm_idex_seo_pages` ADD `DOMAIN` VARCHAR(255);