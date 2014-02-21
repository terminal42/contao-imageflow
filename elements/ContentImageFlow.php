<?php

/**
 * imageflow extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2008-2014, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-imageflow
 */

namespace Contao;

/**
 * class for content element "imageflow"
 */
class ContentImageFlow extends \ContentElement
{

    /**
     * Template
     */
    protected $strTemplate = 'ce_imageflow';


    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### IMAGEFLOW GALLERY ###';

            return $objTemplate->parse();
        }

        // Use the home directory of the current user as file source
        if ($this->useHomeDir && FE_USER_LOGGED_IN)
        {
            $this->import('FrontendUser', 'User');

            if ($this->User->assignDir && $this->User->homeDir)
            {
                $this->multiSRC = array($this->User->homeDir);
            }
        }
        else
        {
            $this->multiSRC = deserialize($this->multiSRC);
        }

        // Return if there are no files
        if (!is_array($this->multiSRC) || empty($this->multiSRC))
        {
            return '';
        }

        // Get the file entries from the database
        $this->objFiles = \FilesModel::findMultipleByUuids($this->multiSRC);

        if ($this->objFiles === null)
        {
            if (!\Validator::isUuid($this->multiSRC[0]))
            {
                return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
            }

            return '';
        }

        return parent::generate();
    }


    protected function compile()
    {
        $GLOBALS['TL_CSS'][] = 'system/modules/imageflow/assets/imageflow.css';
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/imageflow/assets/imageflow.js';

        global $objPage;
        $images = array();
        $auxDate = array();
        $objFiles = $this->objFiles;

        // Get all images
        while ($objFiles->next())
        {
            // Continue if the files has been processed or does not exist
            if (isset($images[$objFiles->path]) || !file_exists(TL_ROOT . '/' . $objFiles->path))
            {
                continue;
            }

            // Single files
            if ($objFiles->type == 'file')
            {
                $objFile = new \File($objFiles->path, true);

                if (!$objFile->isGdImage)
                {
                    continue;
                }

                $arrMeta = $this->getMetaData($objFiles->meta, $objPage->language);

                // Use the file name as title if none is given
                if ($arrMeta['title'] == '')
                {
                    $arrMeta['title'] = specialchars(str_replace('_', ' ', $objFile->filename));
                }

                // Add the image
                $images[$objFiles->path] = array
                (
                    'id'        => $objFiles->id,
                    'uuid'      => $objFiles->uuid,
                    'name'      => $objFile->basename,
                    'singleSRC' => $objFiles->path,
                    'alt'       => $arrMeta['title'],
                    'imageUrl'  => $arrMeta['link'],
                    'caption'   => $arrMeta['caption']
                );

                $auxDate[] = $objFile->mtime;
            }

            // Folders
            else
            {
                $objSubfiles = \FilesModel::findByPid($objFiles->uuid);

                if ($objSubfiles === null)
                {
                    continue;
                }

                while ($objSubfiles->next())
                {
                    // Skip subfolders
                    if ($objSubfiles->type == 'folder')
                    {
                        continue;
                    }

                    $objFile = new \File($objSubfiles->path, true);

                    if (!$objFile->isGdImage)
                    {
                        continue;
                    }

                    $arrMeta = $this->getMetaData($objSubfiles->meta, $objPage->language);

                    // Use the file name as title if none is given
                    if ($arrMeta['title'] == '')
                    {
                        $arrMeta['title'] = specialchars(str_replace('_', ' ', $objFile->filename));
                    }

                    // Add the image
                    $images[$objSubfiles->path] = array
                    (
                        'id'        => $objSubfiles->id,
                        'uuid'      => $objSubfiles->uuid,
                        'name'      => $objFile->basename,
                        'singleSRC' => $objSubfiles->path,
                        'alt'       => $arrMeta['title'],
                        'imageUrl'  => $arrMeta['link'],
                        'caption'   => $arrMeta['caption']
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

            case 'meta': // Backwards compatibility
            case 'custom':
                if ($this->orderSRC != '')
                {
                    $tmp = deserialize($this->orderSRC);

                    if (!empty($tmp) && is_array($tmp))
                    {
                        // Remove all values
                        $arrOrder = array_map(function(){}, array_flip($tmp));

                        // Move the matching elements to their position in $arrOrder
                        foreach ($images as $k=>$v)
                        {
                            if (array_key_exists($v['uuid'], $arrOrder))
                            {
                                $arrOrder[$v['uuid']] = $v;
                                unset($images[$k]);
                            }
                        }

                        // Append the left-over images at the end
                        if (!empty($images))
                        {
                            $arrOrder = array_merge($arrOrder, array_values($images));
                        }

                        // Remove empty (unreplaced) entries
                        $images = array_values(array_filter($arrOrder));
                        unset($arrOrder);
                    }
                }
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
            $objFile = new \File($image['singleSRC']);

            $arrImages[] = array
            (
                'href' => str_replace(' ', '%20', $image['singleSRC']),
                'width' => $objFile->width,
                'height' => $objFile->height,
                'alt' => htmlspecialchars($image['alt']),
                'link' => str_replace(' ', '%20', $image['link']),
                'caption' => $image['caption'],
                'imgSize' => $imgSize,
                'src' => str_replace(' ', '%20', $image['singleSRC']),
            );
        }

        $this->Template->divId = 'if'.$this->id;
        $this->Template->lightboxId = 'lb' . $this->id;
        $this->Template->images = $arrImages;
        $this->Template->jQuery = $objPage->getRelated('layout')->addJQuery ? true : false;

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
                if(!strlen($v['ifp_key']))
                {
                    unset($arrParameters[$k]);
                }

                if($v['ifp_key'] == 'reflectionGET')
                {
                    $strGetParameters = $v['ifp_value'];
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

