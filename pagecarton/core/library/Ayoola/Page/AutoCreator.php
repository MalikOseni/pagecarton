<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_AutoCreator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: AutoCreator.php date time username $
 */

/**
 * @see Ayoola_Page_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_AutoCreator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Page_AutoCreator extends Ayoola_Page_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Show Link to Auto-Create Page'; 
		
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{
		//	var_export( Ayoola_Application::$mode );
			//_objectTemplateValues
			$this->_objectTemplateValues = array();
			$this->_objectTemplateValues['url'] = Ayoola_Application::getRuntimeSettings( 'url' );
			$this->_objectTemplateValues['edit_url'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Creator/?url=' . Ayoola_Application::getRuntimeSettings( 'url' );
			if( self::hasPriviledge( array( 99, 98 ) ) && strpos( Ayoola_Application::getPresentUri(), '.' ) === false )  
			{
				$this->setViewContent( self::__( '<span onClick="ayoola.spotLight.showLinkInIFrame( \'' . $this->_objectTemplateValues['edit_url'] . '\' );" class="pc-btn pc-bg-color">' . sprintf( self::__( 'Create this "%s" page now' ), '' . $this->_objectTemplateValues['url'] . ''  ) . '</span>' ) );
			}
		}
		catch( Exception $e ){ return false; }		
    } 
	
}
