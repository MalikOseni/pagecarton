<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Settings_SettingsName_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Settings_SettingsName_Abstract
 */
 
require_once 'Application/Settings/SettingsName/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Settings_SettingsName_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Settings_SettingsName_Editor extends Application_Settings_SettingsName_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
		//	var_export( $data );
			$this->createForm( 'Save', 'Edit "' . $data['settingsname_name'] . '" settings widget', $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( ! $this->updateDb( $values ) ){ return false; }
			$this->setViewContent(  '' . self::__( '<p class="goodnews">Settings widget saved successfully</p>' ) . '', true  );
		}
		catch( Application_Settings_SettingsName_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
