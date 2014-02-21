<?php

/**
 * licensewidget extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2008-2014, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-licensewidget
 */

namespace Contao;

class ImageflowLicenseWidget extends \Widget
{
    /**
     * Submit user input
     * @var boolean
     */
    protected $blnSubmitInput = true;

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_widget';


    /**
     * Add specific attributes
     * @param string
     * @param mixed
     */
    public function __set($strKey, $varValue)
    {
        switch ($strKey)
        {
            case 'mandatory':
                $this->arrConfiguration['mandatory'] = $varValue ? true : false;
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }


    /**
     * Generate the widget and return it as string
     * @return string
     */
    public function generate()
    {
        return sprintf('<div id="ctrl_%s" class="tl_checkbox_container%s">%s<input type="checkbox" name="%s" id="opt_%s" class="tl_checkbox" value="1"%s%s onfocus="Backend.getScrollOffset();" /> <label for="opt_%s">%s</label></div>',
                        $this->strId,
                        (strlen($this->strClass) ? ' ' . $this->strClass : ''),
                        ($this->varValue ? '' : $this->license[0] . '<br />'),
                        $this->strName,
                        $this->strId,
                        (($this->varValue == 1) ? ' checked="checked"' : ''),
                        $this->getAttributes(),
                        $this->strId,
                        $this->license[1]);
    }
}