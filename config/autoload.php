<?php

/**
 * imageflow extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2008-2014, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-imageflow
 */

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    'Contao\ContentImageFlow' => 'system/modules/imageflow/elements/ContentImageFlow.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    'ce_imageflow' => 'system/modules/imageflow/templates/elements'
));
