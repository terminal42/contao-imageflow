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
		{
			return '';
		}		
		
		return parent::generate();
	}
	
	
	protected function compile()
	{
		$GLOBALS['TL_CSS']['imageflow'] = 'plugins/imageflow/imageflow.css';
		$GLOBALS['TL_JAVASCRIPT']['imageflow'] = 'plugins/imageflow/imageflow.js';
		
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
		
		// Prepare reflection get parameters
		$arrGetParameters = deserialize($this->ifGetParameters);
		if (is_array($arrGetParameters) && strlen($arrGetParameters[0][0]))
		{
			$strGetParameters = '';
			foreach( $arrGetParameters as $arrParameter )
			{
				if (strlen($arrParameter[0]) && strlen($arrParameter[1]))
				{
					$strGetParameters .= '&amp;' . $arrParameter[0] . '=' . $arrParameter[1];
				}
			}
		}
		

		$this->Template->divId = 'if'.$this->id;
		$this->Template->lightboxId = 'lb' . $this->id;
		$this->Template->images = $arrImages;
		
		$this->Template->preloadImages = $this->ifPreload ? 'true' : 'false';
		$this->Template->reflections = $this->ifReflections ? 'true' : 'false';
		$this->Template->reflectionP = $this->ifReflectionP;
		$this->Template->reflectionPNG = $this->ifReflectionPNG ? 'true' : 'false';
		$this->Template->reflectionGET = '&amp;bgc='.(strlen($this->ifBgColor) ? $this->ifBgColor : '000000').$strGetParameters;
		$this->Template->imageFocusMax = $this->ifImageFocusMax;
		$this->Template->startID = $this->ifStartID;
		$this->Template->startAnimation = $this->ifStartAnimation ? 'true' : 'false';
		$this->Template->slider = $this->ifSlider ? 'true' : 'false';
		$this->Template->buttons = $this->ifButtons ? 'true' : 'false';
		$this->Template->captions = $this->ifCaptions ? 'true' : 'false';
		$this->Template->opacity = $this->ifOpacity ? 'true' : 'false';
		$this->Template->fullsize = $this->fullsize;
		$this->Template->parameters = false;
		$this->Template->slimbox = version_compare(VERSION, '2.7', '>=') ? true : false;
		
		// Pass ImageFlow parameters
		$arrParameters = deserialize($this->ifParameters);
		if (is_array($arrParameters) && strlen($arrParameters[0][0]))
		{
			$this->Template->parameters = $arrParameters;
		}
	}
}