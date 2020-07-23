<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_View_Time
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Time.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_View_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_View_Time
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_View_Time extends Application_Article_View_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $data = self::getArticleInfo() ){ return; }
		//	var_export( $data );
		//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
			$this->setViewContent( self::filterTime( $data ), true );
		}
		catch( Exception $e )
		{ 
		//	$this->setViewContent(  '' . self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) . '', true  );
			return $this->setViewContent( self::__( '<p class="badnews">Error with article package.</p>' ) ); 
		}
	//	var_export( $this->_xml );
    } 
	
	// END OF CLASS
}
