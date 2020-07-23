<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Category
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Category.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Category
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Category extends Ayoola_Abstract_Table
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Show Post Categories'; 
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Category';

    /**
     * The xml string
     * 
     * @var string
     */
	protected $_xml;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			$this->createConfirmationForm( 'Show all',  '' );
			if( ! $this->getPublicDbData() )
			{ 
				$this->_parameter['markup_template'] = null;

				//	refresh
				//	workaround for markup template not working
				$this->_markupTemplate = null;
				return $this->setViewContent(  '', true  ); 
			}
			$this->setViewContent( self::getXml() );

		}
		catch( Application_Article_Exception $e ) 
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		}

		//	refresh
		//	workaround for markup template not working
		$this->_markupTemplate = null;
    } 
	
    /**
     * Returns _dbData for public use
     * 
     * return array
     */
	public function getPublicDbData()
    {
		//	if available, only allowed categories are listed
		if( $this->getParameter( 'show_only_post_categories' ) )
		{
			$this->_dbWhereClause['category_name'] = Ayoola_Application::$GLOBAL['post']['category_name'];
		}
		if( $this->getParameter( 'parent_category_name' ) )
		{
			$this->_dbWhereClause['parent_category_name'] = $this->getParameter( 'parent_category_name' );
		}
		else
		{
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( $articleSettings['allowed_categories'] )
			{
				$this->_dbWhereClause['category_id'] = $articleSettings['allowed_categories'];
			}
		}
		return $this->getDbData();
    } 
	
    /**
     * Returns the Xml
     * 
     * @return string
     */
	public function getXml()
    {
		if( is_null( $this->_xml ) ){ $this->setXml(); }
		return $this->_xml;
    } 
	
    /**
     * Sets the xml
     * 
     */
	public function setXml()
    {
		$this->_xml = '<ul style="margin:0;padding:0;list-style:none;"> ';
		$values = $this->getPublicDbData();
		shuffle( $values );
	//	self::v( $values );
		$i = 0; //	5 is our max articles to show
		$j = 10;
		$form = $this->getForm()->view();
		if( $a = $this->getForm()->getValues() ){ $j = 500; }   
		$template = null;
	//	var_Export( $a );
	//	var_Export( $_POST );
		
		while( $values && $i != $j )
		{
			$this->_xml .= '<li style="display:inline-block;margin:0.5em;">';
			$i++;
			$data = array_shift( $values );
			
			if( $this->getParameter( 'length_of_description' ) )
			{
				@$data['category_description'] = strlen( $data['category_description'] ) < $this->getParameter( 'length_of_description' ) ? $data['category_description'] : substr( $data['category_description'], 0, $this->getParameter( 'length_of_description' ) ) . '...';
			}
	
			
			if( $this->getParameter( 'length_of_title' ) )
			{
				@$data['category_label'] = strlen( $data['category_label'] ) < $this->getParameter( 'length_of_title' ) ? $data['category_label'] : substr( $data['category_label'], 0, $this->getParameter( 'length_of_title' ) ) . '...';  
			}
			
			//	content
			
			$url = ( @$data['category_url'] ? : ( '' . Application_Article_Abstract::getPostUrl() . '/category/' . $data['category_name'] . '/' ) );
			@$data['category_url'] = $data['category_url'] ? : $url;
			$content = '<a href="' . $url . '" title="Click here to view posts in the ' . $data['category_label'] . ' category."> ' . $data['category_label'] . ' </a>';
			$this->_xml .= @$_GET['category'] === $data['category_name'] ? "<strong>{$content}</strong>" : "<span>{$content}</span>";
			
			$this->_xml .= '</li>';
			if( $this->getParameter( 'markup_template' ) )
			{
			
				$template .= self::replacePlaceholders( $this->getParameter( 'markup_template' ), $data + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
				
			}
			$this->_objectData[] = $data; 

		}
		//	update the markup template
		$this->_parameter['markup_template'] = $template; 
        if( self::hasPriviledge() )
        {
            $this->_xml .= '<span class="goodnews" >
                                <a class="" title="Add a new category" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_Creator/"> + </a> 
                                <a class="badnews" title="View categories" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_List/"> - </a>
                            </span>';
        }
		$this->_xml .= '' . $form . '';
		$this->_xml .= '</ul>';
    } 
	// END OF CLASS
}
