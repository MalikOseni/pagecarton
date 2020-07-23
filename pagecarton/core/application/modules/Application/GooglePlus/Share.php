<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_GooglePlus_Share
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Share.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_GooglePlus_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/GooglePlus/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_GooglePlus_Share
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_GooglePlus_Share extends Application_GooglePlus_Abstract
{
	
    /**
     * Drives the class
     * 
     * @return boolean
     */
	public function init()
    {
		self::load();
		$usernames = self::getSettings();
	//	if( empty( $usernames['googleplus_id'] ) ){ return; }
		$usernames = @$usernames['googleplus_id'];
		$username = array_shift( explode( ',', $usernames ) );
		$this->setViewContent(  '' . self::__( '<div class="g-plus" data-annotation="bubble" data-action="share" data-href="' . $this->getUrl() . '"></div>' ) . '', true  );
    } 
	// END OF CLASS
}
