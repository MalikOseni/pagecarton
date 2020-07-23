<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Menu_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $
 */

/**
 * @see Ayoola_Menu_Abstract
 */
 
require_once 'Ayoola/Menu/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Menu_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Menu_Delete extends Ayoola_Menu_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 		
			if( ! $data = $this->getIdentifierData() ){ return false; } 
			$this->createConfirmationForm( 'Delete', 'Delete this menu template, "' . $data['url'] . '" and all its associated files? This cannot be undone.' );
			$this->setViewContent( $this->getForm()->view(), true );
			$this->setViewContent(  '' . self::__( 'Menu deleted successfully' ) . '', true  );
		}
		catch( Ayoola_Exception $e ){ return false; }
    } 
}
