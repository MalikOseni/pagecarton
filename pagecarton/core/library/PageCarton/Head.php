<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Head
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Head.php Monday 31st of December 2018 12:35PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Head extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'HTML Head Section for PageCarton'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    { 
     //   echo __CLASS__;   
		try
		{ 
            $title = strip_tags( htmlentities( trim( Ayoola_Page::$title . ' ' . Ayoola_Page::getCurrentPageInfo( 'title' ) ), ENT_QUOTES, "UTF-8", false ) ? : ( Application_SiteInfo::getInfo( 'site_headline' ) ? : Ayoola_Page::getDefaultDomain() ) );
            $description = strip_tags( htmlentities( trim( Ayoola_Page::$description . ' ' . Ayoola_Page::getCurrentPageInfo( 'description' ) ), ENT_QUOTES, "UTF-8", false ) ? : ( Application_SiteInfo::getInfo( 'site_description' ) ) );
            $keywords = strip_tags( htmlentities( Ayoola_Page::getCurrentPageInfo( 'keywords' ), ENT_QUOTES, "UTF-8", false ) );
            $url = strip_tags( Ayoola_Page::getCanonicalUrl() );
            $html = 
            '
<title>' . $title  . '</title>
<meta name="Description" content="' . $description . '" />
<meta name="Keywords" content="' .  $keywords . '" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta property="og:title" content="' . $title . '"/>
<meta property="og:type" content="' . ( Ayoola_Page::$isHome ? 'website' : 'article' ) . '"/> 
<meta property="og:url" content="' .  $url . '"/>
<meta property="og:image" content="' .  Ayoola_Page::getThumbnail() . '"/>
<meta property="og:site_name" content="' .  ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ) . '"/>
<meta property="og:description" content="' .  $description . '"/>  
<link rel="icon" href="' .  Ayoola_Page::getFavicon() . '" type="image/x-icon" />
<link rel="shortcut icon" href="' .  Ayoola_Page::getFavicon() . '" type="image/x-icon" />
<link rel="bookmark icon" href="' .  Ayoola_Page::getFavicon() . '" type="image/x-icon" />
<link rel="canonical" href="' .  $url . '" />    
<link rel="apple-touch-icon" href="' .  Ayoola_Page::getFavicon() . '">
<link rel="image_src" href="' .  Ayoola_Page::getThumbnail() . '"> 
<meta itemprop="image" content="' .  Ayoola_Page::getThumbnail() . '"> 
                     
            ' .  Application_Style::getAll() . '';             
            if( Application_Settings_Abstract::getSettings( 'Page', 'background_color' ) )
            {
                $html .= '
<style>
    .pc-bg-color
    {
        background-color: ' . Application_Settings_Abstract::getSettings( 'Page', 'background_color' ) . ';
        color: ' . Application_Settings_Abstract::getSettings( 'Page', 'font_color' ) . ';
    }
</style>';
            }     
            $widgets = Ayoola_Page_Editor_Layout::getSiteWideWidgets( 'pc_section_within_head' ); 
            foreach( $widgets as $widget )
            {
                $html .= $widget->view();
            }           
            echo $html;
            // end of widget process
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) ); 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
