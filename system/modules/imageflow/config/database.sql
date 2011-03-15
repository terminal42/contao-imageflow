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
  `ifPreload` char(1) NOT NULL default '1',
  `ifReflections` char(1) NOT NULL default '1',
  `ifReflectionP` varchar(3) NOT NULL default '0.5',
  `ifReflectionPNG` char(1) NOT NULL default '',
  `ifImageFocusMax` int(1) NOT NULL default '4',
  `ifStartID` int(3) NOT NULL default '1',
  `ifStartAnimation` char(1) NOT NULL default '',
  `ifSlider` char(1) NOT NULL default '1',
  `ifButtons` char(1) NOT NULL default '1',
  `ifCaptions` char(1) NOT NULL default '1',
  `ifOpacity` char(1) NOT NULL default '',
  `ifBgColor` varchar(6) NOT NULL default '',
  `ifGetParameters` blob NULL,
  `ifParameters` blob NULL,
  `ifLicense` char(1) NOT NULL default '',
  `ifAnimationSpeed` int(3) NOT NULL default '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;