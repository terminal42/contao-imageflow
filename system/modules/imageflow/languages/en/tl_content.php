<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2008
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    LGPL
 * @version    $Id$
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_content']['ifReflections']		= array('Reflection', 'Choose the image reflection type. <a href="http://finnrudolph.de/ImageFlow/Examples#No_reflections" onclick="window.open(this.href); return false">See here for an example</a>.');
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']		= array('Reflection height', 'Height of the reflection in % of the source image. <a href="http://finnrudolph.de/ImageFlow/Examples#Image_alignment" onclick="window.open(this.href); return false">See here for an example</a>.');
$GLOBALS['TL_LANG']['tl_content']['ifImageFocusMax']	= array('Images on each side', 'Maximum number of images on each side of the focussed one. <a href="http://finnrudolph.de/ImageFlow/Examples#imageFocusMax" onclick="window.open(this.href); return false">See here for an example</a>.');
$GLOBALS['TL_LANG']['tl_content']['ifStartID']			= array('Start image', 'Glide to this image number on startup. <a href="http://finnrudolph.de/ImageFlow/Examples#startID" onclick="window.open(this.href); return false">See here for an example</a>.');
$GLOBALS['TL_LANG']['tl_content']['ifBgColor']			= array('Reflection background color', 'Set the reflection background color to your website\'s background for best results.');
$GLOBALS['TL_LANG']['tl_content']['ifParameters']		= array('Parameters for ImageFlow-Script', 'You can pass additional parameters to the ImageFlow script. <a href="http://finnrudolph.de/ImageFlow/Documentation" onclick="window.open(this.href); return false;">See documentation for more info.</a>');
$GLOBALS['TL_LANG']['tl_content']['ifLicense']			= array('ImageFlow license', '');
$GLOBALS['TL_LANG']['tl_content']['ifAnimationSpeed']	= array('Animation speed', 'Enter the desired animation speed in ms.');
$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob']		= array('Settings', 'Choose the desired settings.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_content']['ifSettings_legend']		= 'ImageFlow Settings';
$GLOBALS['TL_LANG']['tl_content']['ifReflections_legend']	= 'Image reflections';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_content']['ifParameters']['key']				= 'Key';
$GLOBALS['TL_LANG']['tl_content']['ifParameters']['value']				= 'Value';
$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob']['preloadImages']		= 'Preload images';
$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob']['startAnimation']		= 'Enable startup animation';
$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob']['slider']				= 'Show slider';
$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob']['buttons']			= 'Show buttons';
$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob']['captions']			= 'Show image captions';
$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob']['opacity']			= 'Enable image opacity';
$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob']['circular']			= 'Enable circular rotation';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['0.1']				= '10%';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['0.2']				= '20%';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['0.3']				= '30%';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['0.4']				= '40%';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['0.5']				= '50%';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['0.6']				= '60%';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['0.7']				= '70%';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['0.8']				= '80%';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['0.9']				= '90%';
$GLOBALS['TL_LANG']['tl_content']['ifReflectionP']['1.0']				= '100%';
$GLOBALS['TL_LANG']['tl_content']['ifReflections']['none']				= 'No reflection';
$GLOBALS['TL_LANG']['tl_content']['ifReflections']['png']				= 'PNG reflection';
$GLOBALS['TL_LANG']['tl_content']['ifReflections']['jpeg']				= 'JPEG reflection';

