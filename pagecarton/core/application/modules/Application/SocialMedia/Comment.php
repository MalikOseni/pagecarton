<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_SocialMedia_Comment
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Comment.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_SocialMedia_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/SocialMedia/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Application_SocialMedia_Comment
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_SocialMedia_Comment extends Application_SocialMedia_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Facebook Comment Box';   
	
    /**
     * Drives the class
     * 
     * @return boolean
     */
	public function init()
    {
		self::load();
		$this->setViewContent(  '' . self::__( '<div class="fb-comments" data-href="' . $this->getUrl() . '" data-num-posts="2" data-width="470"></div>' ) . '', true  );
    } 
	// END OF CLASS
}
