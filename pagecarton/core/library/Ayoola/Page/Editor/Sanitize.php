<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Editor_Sanitize
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Sanitize.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see Ayoola_Page_Editor_Abstract
 */
   
require_once 'Ayoola/Page/Editor/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Editor_Sanitize
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Editor_Sanitize extends Ayoola_Page_Editor_Layout
{
	
    /**
     * To end infinite loop in refreshing
     * 
     * @var boolean 
     */
	protected static $_refreshed = array();
	
    /**
     * Switch whether to update layout on page load
     * 
     * @var boolean 
     */
	protected $_updateLayoutOnEveryLoad = true;

    /**
     *
     */
    public function init()
    {

		$this->createConfirmationForm( 'Sanitize Pages', 'Sanitize all page files and information' );
		$this->setViewContent(  '' . self::__( '<h3>NOTE:</h3>' ) . '', true  );		
		$this->setViewContent( self::__( '<p>This process will create a fresh copy of all the pages. A fresh copy of the layout template will be used in generating the new pages. A backup of the application is recommended.</p>' ) );		
	//	$this->setViewContent( self::__( '<a href="' . Ayoola_Application::getUrlPrefix() . '/ayoola/backup" class="pc-btn pc-btn-small">Backup Now!</a>' ) );
		$this->setViewContent( $this->getForm()->view() );
        if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( $this->sanitize() ){ $this->setViewContent(  '' . self::__( 'Pages Sanitized Successfully' ) . '', true  ); }
    }
		
    /**
     * Performs the sanitization process
     *
     * @param void
     * @return boolean
     */	
    public function sanitize( $themeName = null ) 
    {
		ignore_user_abort();
		$pages = new Ayoola_Page();
		$where = array();		
		$defaultPages = array();		
		if( $themeName )
		{
			$where['layout_name'] = array( $themeName );
		//	var_export( $themeName );
		//	var_export( Ayoola_Page_Editor_Layout::getDefaultLayout() );
			if( strtolower( $themeName ) === strtolower( Ayoola_Page_Editor_Layout::getDefaultLayout() ) )
			{
				$where['layout_name'][] = '';
			}
		}

		//	now  sanitize theme after normal pages
		if( $themeName )
		{
			$themePages = Ayoola_Page_Layout_Pages::getPages( $themeName );
			foreach( $themePages as $page )
			{
			//	var_export( $page );
				if( is_array( $page ) && ! empty( $page['url'] ) )
				{
					$page = $page['url'];
				//  throw new Exception();
				  //  var_export( get_ );
				}

				//	strictly do this to ensure this only saves if we have our own copy
				//	of the page include file. 
				//	theres error theme page saving empty content when saving from default
				$pageThemeFileUrl = $page;
				if( $pageThemeFileUrl == '/' )
				{
					$pageThemeFileUrl = '/index';
				}
				$fPaths = Ayoola_Page_Layout_Pages_Copy::getPagePaths( $themeName, $pageThemeFileUrl );
        		$from = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $fPaths['include'];
				if( ! is_file( $from ) )
				{
					continue;
				}

				if( ! Ayoola_Page_Layout_Pages_Copy::canCopy( $page, $themeName ) )
				{
					continue;
				}
			//	var_export( $from );
				$page = is_string( $page ) ? $page : $page['url'];
				$this->_parameter['page_editor_layout_name'] = $themeName;
				$this->refresh( $page );   
			}
		}

		//	let normal pages go first so that 
		//	theres error where old page was being restored after theme update
		//	let's see if this solves it.

		$pages = $pages->getDbTable()->select( null, $where );
		$pages = array_merge( $pages, $defaultPages );
	//	var_export( self::getDefaultLayout() );
	//	var_export( $pages );
	//	var_export( $where );
		foreach( $pages as $page )    
		{
			$page = is_string( $page ) ? $page : $page['url'];
			if( stripos( $page, '/layout/' ) === 0 || stripos( $page, '/default-layout' ) === 0 )
			{
				//	dont cause unfinite loop by updating theme when a theme is being sanitized
				continue;
			}
			//	var_export( $page );
			//	sanitize now on theme level
			$this->_parameter['page_editor_layout_name'] = null;

			//	old theme page needs to be deletee
			$pageThemeFileUrl = $page;
			if( $pageThemeFileUrl == '/' )
			{
				$pageThemeFileUrl = '/index';  
			}

        	//	let's remove dangling theme pages not completely deleted
			Ayoola_Page_Layout_Pages_Delete::deleteThemePageSupplementaryFiles( $pageThemeFileUrl, $themeName );
			//	var_export( $page );

			$this->refresh( $page );   
		}
		return true;
    }
		
    /**
     * Performs the sanitization process
     *
     * @param void
     * @return boolean
     */	
    public function refresh( $page, $themeName = null ) 
    {
		if( ! empty( $themeName ) )
		{
			$this->_parameter['page_editor_layout_name'] = $themeName;
		}
		$id = $page . Ayoola_Application::getApplicationNameSpace();
		if( ! empty( static::$_refreshed[$id] ) )
		{
		//		var_export( $page );
		//	if( $page === '/' )
			{
			//	$e = new \Exception;
			//	var_dump($e->getTraceAsString());			
			//	var_export( $page );
			}
			return false;
		}
	//	var_export( $page );
		static::$_refreshed[$id] = true;
		$this->setPageInfo( array( 'url' => $page ) );
	//	var_export( $page );
		$this->setPagePaths();
		$this->setValues();
		$this->_updateLayoutOnEveryLoad = true;
		$this->noLayoutView = true;
	//	if( $page === '/how-to' )
		{
		//	$e = new \Exception;
		//	var_export( $this->getPageInfo() );			
		//	var_export( $page );
		}

		parent::init(); // invoke the template update for this page.           
		return true;
    } 
	// END OF CLASS

}
