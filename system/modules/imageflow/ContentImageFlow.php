<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2008-2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


/**
 * class for content element "imageflow"
 */
class ContentImageFlow extends ContentElement
{

	/**
	 * Template
	 */
	protected $strTemplate = 'ce_imageflow';


	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### IMAGEFLOW GALLERY ###';

			return $objTemplate->parse();
		}

		$this->multiSRC = deserialize($this->multiSRC);

		// Use the home directory of the current user as file source
		if ($this->useHomeDir && FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');

			if ($this->User->assignDir && is_dir(TL_ROOT . '/' . $this->User->homeDir))
			{
				$this->multiSRC = array($this->User->homeDir);
			}
		}

		if (!is_array($this->multiSRC) || count($this->multiSRC) < 1)
			return '';

		return parent::generate();
	}


	protected function compile()
	{
		$GLOBALS['TL_CSS'][] = 'plugins/imageflow/imageflow.css';
		$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/imageflow/imageflow.js';

		$images = array();
		$auxDate = array();

		// Get all images
		foreach ($this->multiSRC as $file)
		{
			if (!is_dir(TL_ROOT . '/' . $file) && !file_exists(TL_ROOT . '/' . $file) || array_key_exists($file, $images))
			{
				continue;
			}

			// Single files
			if (is_file(TL_ROOT . '/' . $file))
			{
				$objFile = new File($file);
				$this->parseMetaFile(dirname($file), true);

				if ($objFile->isGdImage)
				{
					$images[$file] = array
					(
						'name' => $objFile->basename,
						'src' => $file,
						'alt' => (strlen($this->arrMeta[$objFile->basename][0]) ? $this->arrMeta[$objFile->basename][0] : ucfirst(str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename)))),
						'link' => (strlen($this->arrMeta[$objFile->basename][1]) ? $this->arrMeta[$objFile->basename][1] : ''),
						'caption' => (strlen($this->arrMeta[$objFile->basename][2]) ? $this->arrMeta[$objFile->basename][2] : '')
					);

					$auxDate[] = $objFile->mtime;
				}

				continue;
			}

			$subfiles = scan(TL_ROOT . '/' . $file);
			$this->parseMetaFile($file);

			// Folders
			foreach ($subfiles as $subfile)
			{
				if (is_dir(TL_ROOT . '/' . $file . '/' . $subfile))
				{
					continue;
				}

				$objFile = new File($file . '/' . $subfile);

				if ($objFile->isGdImage)
				{
					$images[$file . '/' . $subfile] = array
					(
						'name' => $objFile->basename,
						'src' => $file . '/' . $subfile,
						'alt' => (strlen($this->arrMeta[$subfile][0]) ? $this->arrMeta[$subfile][0] : ucfirst(str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename)))),
						'link' => (strlen($this->arrMeta[$subfile][1]) ? $this->arrMeta[$subfile][1] : ''),
						'caption' => (strlen($this->arrMeta[$subfile][2]) ? $this->arrMeta[$subfile][2] : '')
					);

					$auxDate[] = $objFile->mtime;
				}
			}
		}

		// Sort array
		switch ($this->sortBy)
		{
			default:
			case 'name_asc':
				uksort($images, 'basename_natcasecmp');
				break;

			case 'name_desc':
				uksort($images, 'basename_natcasercmp');
				break;

			case 'date_asc':
				array_multisort($images, SORT_NUMERIC, $auxDate, SORT_ASC);
				break;

			case 'date_desc':
				array_multisort($images, SORT_NUMERIC, $auxDate, SORT_DESC);
				break;

			case 'meta':
				$arrImages = array();
				foreach ($this->arrAux as $k)
				{
					if (strlen($k))
					{
						$arrImages[] = $images[$k];
					}
				}
				$images = $arrImages;
				break;
				
			case 'random':
				shuffle($images);
				break;
		}

		$images = array_values($images);

		$arrImages = array();

		// Rows
		foreach( $images as $image )
		{
			$objFile = new File($image['src']);

			$arrImages[] = array
			(
				'href' => str_replace(' ', '%20', $image['src']),
				'width' => $objFile->width,
				'height' => $objFile->height,
				'alt' => htmlspecialchars($image['alt']),
				'link' => str_replace(' ', '%20', $image['link']),
				'caption' => $image['caption'],
				'imgSize' => $imgSize,
				'src' => str_replace(' ', '%20', $image['src']),
			);
		}

		$this->Template->divId = 'if'.$this->id;
		$this->Template->lightboxId = 'lb' . $this->id;
		$this->Template->images = $arrImages;

		$this->Template->reflections = ($this->ifReflections != 'none') ? 'true' : 'false';
		$this->Template->reflectionP = $this->ifReflectionP;
		$this->Template->reflectionPNG = ($this->ifReflections == 'png') ? 'true' : 'false';
		$this->Template->imageFocusMax = $this->ifImageFocusMax;
		$this->Template->startID = $this->ifStartID;
		$this->Template->fullsize = $this->fullsize;
		$this->Template->parameters = false;
		$this->Template->reflectPath = 'plugins/imageflow/';
		$this->Template->imagePath = '../../';
		$this->Template->animationSpeed = is_numeric($this->ifAnimationSpeed) ? $this->ifAnimationSpeed : 50;

		// slideshow
		$this->Template->slideshow = ($this->ifAddSlideShow) ? 'true' : 'false';
		$this->Template->slideshowSpeed = $this->ifSlideShowSpeed ? $this->ifSlideShowSpeed : 1500;
		$this->Template->slideshowAutoplay = ($this->ifSlideShowAutoPlay) ? 'true' : 'false';

		// Pass ImageFlow parameters
		$arrParameters = array();
		$strGetParameters = '';
		$arrParameters = deserialize($this->ifParameters);

		if(is_array($arrParameters) && count($arrParameters))
		{
			foreach($arrParameters as $k => $v)
			{
				if(!strlen($v[0]))
				{
					unset($arrParameters[$k]);
				}
				
				if($v[0] == 'reflectionGET')
				{
					$strGetParameters = $v[1];
					unset($arrParameters[$k]);
				}
			}
		}

		$this->Template->reflectionGET = '&amp;bgc='.(strlen($this->ifBgColor) ? $this->ifBgColor : '000000') . specialchars($strGetParameters);	
		
		$arrConfigBlob = deserialize($this->ifConfigBlob);
		
		// make sure $arrConfigBlob is not empty
		if(!(is_array($arrConfigBlob) && count($arrConfigBlob)))
		{
			$arrConfigBlob[] = '';
		}
		
		// we need to set the values that are false here too
		$this->loadDataContainer('tl_content');
		$arrAllConfigValues = $GLOBALS['TL_DCA']['tl_content']['fields']['ifConfigBlob']['options'];
		
		foreach($arrAllConfigValues as $conf)
		{
			if(in_array($conf, $arrConfigBlob))
			{
				$arrParameters[] = array($conf, 1);
			}
			else
			{
				$arrParameters[] = array($conf, 'false');
			}
		}
		
		if (is_array($arrParameters) && count($arrParameters))
		{
			$this->Template->parameters = $arrParameters;
		}
	}
}

