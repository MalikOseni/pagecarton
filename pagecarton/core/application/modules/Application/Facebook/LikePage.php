<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Facebook_LikePage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: LikePage.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Facebook_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Facebook/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Facebook_LikePage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Facebook_LikePage extends Application_Facebook_Like
{
	
    /**
     * Drives the class
     * 
     * @return boolean
     */
	public function init()
    {
		self::load();
		$username = self::getSettings();
	//	var_export( $appId );
		if( empty( $username['page_url'] ) ){ return; }
		$username = $username['page_url'];
		$this->setViewContent(  '' . self::__( '<div class="fb-like" data-send="false" data-layout="button_count" data-width="10" data-show-faces="false" data-href="https://www.facebook.com/' . $username . '"></div>' ) . '', true  );
    } 
	// END OF CLASS
}
