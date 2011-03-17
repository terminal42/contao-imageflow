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


$this->loadDataContainer('tl_style');
$GLOBALS['TL_CSS'][] = 'system/modules/imageflow/html/style_be.css';


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'ifReflections';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'ifAddSlideShow';
$GLOBALS['TL_DCA']['tl_content']['palettes']['imageflow'] = '{type_legend},type,headline;{license_legend},ifLicense;{source_legend},multiSRC,useHomeDir;{image_legend},sortBy,fullsize,ifImageFocusMax,ifStartID,ifAnimationSpeed,ifConfigBlob,ifParameters;{ifReflections_legend},ifReflections;{ifSlideShow_legend},ifAddSlideShow;{protected_legend:hide},protected;{expert_legend:hide},guests,align,space,cssID';


/**
 * Subpalettes
 */
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['ifReflections_none'] = '';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['ifReflections_png'] = 'ifReflectionP';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['ifReflections_jpeg'] = 'ifReflectionP,ifBgColor';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['ifAddSlideShow'] = 'ifSlideShowSpeed,ifSlideShowAutoPlay';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['ifReflections'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifReflections'],
	'exclude'			=> true,
	'default'			=> 'none',
	'inputType'			=> 'radio',
	'options'			=> array('none','png','jpeg'),
	'reference'			=> &$GLOBALS['TL_LANG']['tl_content']['ifReflections'],
	'eval'				=> array('submitOnChange'=>true, 'tl_class'=>'clr')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifReflectionP'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifReflectionP'],
	'exclude'			=> true,
	'default'			=> '0.5',
	'inputType'			=> 'select',
	'options'			=> array('0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1.0'),
	'reference'			=> &$GLOBALS['TL_LANG']['tl_content']['ifReflectionP'],
	'eval'				=> array('tl_class'=>'clr w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifImageFocusMax'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifImageFocusMax'],
	'exclude'			=> true,
	'default'			=> '4',
	'inputType'			=> 'select',
	'options'			=> array('1', '2', '3', '4', '5', '6'),
	'eval'				=> array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifStartID'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifStartID'],
	'exclude'			=> true,
	'default'			=> '1',
	'inputType'			=> 'text',
	'eval'				=> array('regex'=>'digit', 'maxlength'=>3, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifBgColor'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifBgColor'],
	'exclude'			=> true,
	'inputType'			=> 'text',
	'eval'				=> array('maxlength'=>6, 'rgxp'=>'alnum', 'tl_class'=>'w50 wizard'),
	'wizard' => array
	(
		array('tl_style', 'colorPicker')
	)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifParameters'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifParameters'],
	'exclude'			=> true,
	'inputType'			=> 'multitextWizard',
	'eval'				=> array
	(
		'style'=>'width:100%;',
		'columns' => array
		(
			array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_content']['ifParameters']['key'],
				'width' => '20%'
			),
			array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_content']['ifParameters']['value']
			)
		)
	)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifLicense'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifLicense'],
	'exclude'			=> true,
	'inputType'			=> 'license',
	'eval'				=> array('submitOnChange'=>true, 'license'=>&$GLOBALS['TL_LANG']['MSC']['imageflow_license'], 'doNotShow'=>true, 'doNotCopy'=>true, 'tl_class'=>'long')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifAnimationSpeed'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifAnimationSpeed'],
	'exclude'			=> true,
	'default'			=> '50',
	'inputType'			=> 'text',
	'eval'				=> array('mandatory'=>true, 'regex'=>'digit', 'maxlength'=>6,  'tl_class'=>'clr')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifConfigBlob'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob'],
	'exclude'			=> true,
	'inputType'			=> 'checkbox',
	'options'			=> array
	(
		'preloadImages',
		'startAnimation',
		'slider',
		'buttons',
		'captions',
		'opacity',
		'circular',
		'glideToStartID'
	),
	'reference'			=> &$GLOBALS['TL_LANG']['tl_content']['ifConfigBlob'],
	'eval'				=> array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifAddSlideShow'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifAddSlideShow'],
	'exclude'			=> true,
	'inputType'			=> 'checkbox',
	'eval'				=> array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifSlideShowSpeed'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifSlideShowSpeed'],
	'exclude'			=> true,
	'default'			=> '1500',
	'inputType'			=> 'text',
	'eval'				=> array('regex'=>'digit', 'maxlength'=>5, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ifSlideShowAutoPlay'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['ifSlideShowAutoPlay'],
	'exclude'			=> true,
	'inputType'			=> 'checkbox',
	'eval'				=> array('tl_class'=>'w50 m12')
);

