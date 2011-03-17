-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `ifReflections` varchar(4) NOT NULL default 'none',
  `ifReflectionP` varchar(3) NOT NULL default '0.5',
  `ifImageFocusMax` int(1) NOT NULL default '4',
  `ifStartID` int(3) NOT NULL default '1',
  `ifBgColor` varchar(6) NOT NULL default '',
  `ifParameters` blob NULL,
  `ifConfigBlob` blob NULL,
  `ifLicense` char(1) NOT NULL default '',
  `ifAnimationSpeed` int(6) NOT NULL default '50'
  `ifAddSlideShow` char(1) NOT NULL default '',
  `ifSlideShowSpeed` int(5) NOT NULL default '1500',
  `ifSlideShowAutoPlay` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

