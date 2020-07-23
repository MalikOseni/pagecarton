<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Facebook_Like
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Like.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Facebook_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Facebook/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Facebook_Like
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Facebook_Like extends Application_Facebook_Abstract
{
	
    /**
     * Drives the class
     * 
     * @return boolean
     */
	public function init()
    {
		self::load();
		$this->setViewContent(  '' . self::__( '<div style="display:inline;" class="fb-like" data-href="' . $this->getUrl() . '" data-show-faces="false"  data-layout="button_count" data-width="2"></div>' ) . '', true  );
    } 
	// END OF CLASS
}
