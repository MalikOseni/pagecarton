<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Slideshow_Template_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php date time ayoola $
 */

/**
 * @see Application_Slideshow_Template_Abstract
 */
 
//require_once 'Ayoola/Slideshow/Abstract.php';  


/**
 * @category   PageCarton
 * @package    Application_Slideshow_Template_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Application_Slideshow_Template_Editor extends Application_Slideshow_Template_Abstract
{
	
    /**   
     * This method starts the chain for update
     *
     * @param void
     * @return null
     */
    public function init()
    {
		try
		{
			if( ! $data = $this->getIdentifierData() ){ return false; } 
			$this->createForm( 'Edit Slideshow', 'Edit ' . $data['template_label'] . ' (' . $data['template_name'] . ') ', $data );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( $_POST );
		//	var_export( $this->getForm()->getValues() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
						
			if( ! $this->updateDb( $values ) ){ return false; }
			
	//		var_export( $data );
			$this->setViewContent(  '' . self::__( 'Slideshow template edited successfully' ) . '', true  );
			
		}
		catch( Exception $e ){ return false; }
		
    } 
}
