<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_SocialMedia_Like
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Like.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_SocialMedia_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/SocialMedia/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_SocialMedia_Like
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_SocialMedia_Like extends Application_SocialMedia_Abstract
{
	
    /**
     * Drives the class
     * 
     * @return boolean
     */
	public function init()
    {
		self::load();
		$this->setViewContent(  '' . self::__( '<div class="fb-like" data-href="' . $this->getUrl() . '" data-send="true" data-width="520" data-show-faces="false"></div>' ) . '', true  );
    } 
	// END OF CLASS
}
