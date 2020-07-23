<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Disqus_Comment
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Comment.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Disqus_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Disqus/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Disqus_Comment
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Disqus_Comment extends Application_Disqus_Abstract
{
	
    /**
     * Drives the class
     * 
     * @return boolean
     */
	public function init()
    {
		self::load();
		$this->setViewContent(   '' . self::__( '<div id="disqus_thread"></div>' ) . '', true  );
    } 
	// END OF CLASS
}
