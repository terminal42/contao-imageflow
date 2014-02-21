<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * @copyright  Andreas Schempp 2008-2012
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class ImageFlowRunonce extends Frontend
{

    /**
     * Initialize the object
     */
    public function __construct()
    {
        parent::__construct();

        $this->import('Database');
    }


    /**
     * Run the controller
     */
    public function run()
    {
        // Update imageflow to 1.3.0 - merge single booleans to a checkboxwizard blob
        if(!$this->Database->fieldExists('ifConfigBlob', 'tl_content'))
        {
            // add ifConfigBlob field
            $this->Database->query("ALTER TABLE `tl_content` ADD `ifConfigBlob` blob NULL");

            // all database columns to update (column name => param name in checkboxwizard)
            $arrFieldsToUpdate = array
            (
                'ifPreload'            => 'preloadImages',
                'ifStartAnimation'    => 'startAnimation',
                'ifSlider'            => 'slider',
                'ifButtons'            => 'buttons',
                'ifCaptions'        => 'captions',
                'ifOpacity'            => 'opacity'
            );

            // get all content elements using imageflow
            $objCEs = $this->Database->prepare("SELECT * FROM tl_content WHERE type=?")->execute('imageflow');

            while($objCEs->next())
            {
                // get all values of the fields to update
                $objValues = $this->Database->query("SELECT " . implode(',', array_keys($arrFieldsToUpdate)) . " FROM tl_content WHERE id={$objCEs->id}");

                $arrConfigBlob = array();

                foreach($arrFieldsToUpdate as $col => $newKey)
                {
                    if($objValues->$col == 1)
                    {
                        $arrConfigBlob[] = $newKey;
                    }
                }

                // update ifConfigBlob
                $this->Database->prepare("UPDATE tl_content SET ifConfigBlob=? WHERE id=?")->execute(serialize($arrConfigBlob), $objCEs->id);
            }

            // everything is updated now, we delete the columns
            foreach($arrFieldsToUpdate as $col => $newKey)
            {
                $this->Database->execute(sprintf("ALTER TABLE tl_content DROP COLUMN %s", $col));
            }
        }


        // Update imageflow to 1.3.0 - merge reflection script parameters
        if($this->Database->fieldExists('ifGetParameters', 'tl_content'))
        {
            // get all content elements using imageflow
            $objCEs = $this->Database->prepare("SELECT * FROM tl_content WHERE type=?")->execute('imageflow');

            while($objCEs->next())
            {
                // get all values of the fields to update
                $objValues = $this->Database->query("SELECT ifParameters,ifGetParameters FROM tl_content WHERE id={$objCEs->id}");

                $arrParameters = deserialize($objValues->ifParameters);
                $arrGetParameters = deserialize($objValues->ifGetParameters);

                if (is_array($arrGetParameters))
                {
                    $strGetParameters = '';
                    foreach( $arrGetParameters as $arrParameter )
                    {
                        if($arrParameter[0] != '' && $arrParameter[1] != '')
                        {
                            $strGetParameters .= '&amp;' . $arrParameter[0] . '=' . $arrParameter[1];
                        }
                    }

                    if ($strGetParameters != '')
                    {
                        // add the param
                        if (is_array($arrParameters) && $arrParameters[0][0] != '')
                        {
                            $arrParameters[] = array('reflectionGET', $strGetParameters);
                        }
                        else
                        {
                            $arrParameters = array(array('reflectionGET', $strGetParameters));
                        }

                        // update ifParameters
                        $this->Database->prepare("UPDATE tl_content SET ifParameters=? WHERE id=?")->execute(serialize($arrParameters), $objCEs->id);
                    }
                }
            }

            // everything is updated now, we delete the column
            $this->Database->query("ALTER TABLE tl_content DROP COLUMN ifGetParameters");
        }


        // Update imageflow to 1.3.0 - adapt reflection settings
        if($this->Database->fieldExists('ifReflectionPNG', 'tl_content'))
        {
            // change the type of "ifReflections"
            $this->Database->query("ALTER TABLE tl_content CHANGE COLUMN ifReflections ifReflections varchar(4) NOT NULL default 'none'");

            // get all content elements using imageflow
            $objCEs = $this->Database->prepare("SELECT * FROM tl_content WHERE type=?")->execute('imageflow');

            while($objCEs->next())
            {
                // get all values of the fields to update
                $objValues = $this->Database->query("SELECT ifReflections, ifReflectionPNG FROM tl_content WHERE id={$objCEs->id}");

                // by default it's none
                $value = 'none';

                // otherwise it's either jpeg or png. it's at least jpeg anyway
                if($objValues->ifReflections == 1)
                {
                    $value = 'jpeg';
                }

                // if this checkbox was set, it's png
                if($objValues->ifReflectionPNG == 1)
                {
                    $value = 'png';
                }

                // update ifReflections
                $this->Database->prepare("UPDATE tl_content SET ifReflections=? WHERE id=?")->execute($value, $objCEs->id);
            }

            // everything is updated now, we delete the column
            $this->Database->query("ALTER TABLE tl_content DROP COLUMN ifReflectionPNG");
        }
    }
}


/**
 * Instantiate controller
 */
$objImageFlowRunonce = new ImageFlowRunonce();
$objImageFlowRunonce->run();

