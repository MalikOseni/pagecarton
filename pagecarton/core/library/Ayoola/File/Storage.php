<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_File_Storage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Storage.php 3.5.2010 8.11PM Ayoola $
 */

/**
 * @see Ayoola_
 */
 
require_once 'Ayoola/File.php';


/**
 * @category   PageCarton
 * @package    Ayoola_File_Storage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
 
class Ayoola_File_Storage extends Ayoola_File
{
    /**
     * File NameSpace
     *
     * @var string
     */
	protected $_namespace;
	
    /**
     * File NameSpace
     *
     * @var string
     */
	protected static $_name = 'AyoolaCmfFileId';
	
    /**
     * File object
     *
     * @var Ayoola_File
     */
	protected $_file;
	
    /**
     * Time in seconds to render expired.
     *
     * @var int seconds
     */
	public $timeOut;

    /**
     * Constructor
     *
     * @param string Namespace
     * 
     */
    public function __construct( $namespace = null )
    {	
		$this->setNamespace( $namespace );
    }
	
    /**
     * This method unsets a namespace in the File
     *
     * @param 
     * @return 
     */
    public function purgeNamespace( $namespace = null )
    {
	//	var_export( $namespace );
		$this->setNamespace( $namespace );
	//	var_export( $this->getNamespace() );
		$dir = dirname( $this->getFile()->getPath() );
	//	var_export( $dir );
	//	$dir = $class->getFile()->getDirectory() . DS . __CLASS__ . DS;
		Ayoola_Doc::deleteDirectoryPlusContent( $dir );
    } 
	
    /**
     * This method clears the cache of a particular domain.
     *
     * @param 
     * @return 
     */
    public static function purgeDomain( $domain = null )
    {
		$class = new self();  
		$domain = $domain ? : Ayoola_Application::getDomainName( array( 'no_cache' => true ) );
		$filter = new Ayoola_Filter_DomainName();
		$domain = $filter->filter( $domain );
		
/*		$filter = new Ayoola_Filter_Alnum();
		$filter->replace = DS;

		//	we cant use Ayoola_Application::getDomainName( array( 'no_cache' => true ) ) because it causes infinite loop
		$domain = $filter->filter( $domain );
		$firstPart = array_shift( explode( DS, $domain ) );
		
		$dir = $class->getFile()->getDirectory() . DS . 'STORAGE' . DS . $firstPart;
*/
        $cacheDestination = dirname( CACHE_DIR ) . DS . $domain;  
        Ayoola_Doc::deleteDirectoryPlusContent( $cacheDestination);
        $cacheDestination = dirname( CACHE_DIR ) . DS . 'www.' . $domain;  
        Ayoola_Doc::deleteDirectoryPlusContent( $cacheDestination);
/*	//	var_export( $dir );
		if( strlen( $domain ) < 1 || ! is_dir( $dir ) )
		{
			return false;
		}
	//	var_export( $dir );
	//	$dir = $class->getFile()->getDirectory() . DS . __CLASS__ . DS;
		Ayoola_Doc::deleteDirectoryPlusContent( $dir );

*/		return true;
    } 
	
    /**
     * Stores Data in the File
     * 
     * @param mixed Data to be stored
     * @return void
     */
    public function write( $data )
    {
		$path = $this->getFile()->getPath();
		Ayoola_Doc::createDirectory( dirname( $path ) );
        //	PageCarton_Widget::v( $path );
		Ayoola_File::putContents( $path, json_encode( $data ) );     
    } 
	
    /**
     * Reads Data that was previously stored in the File
     *
     * @param 
     * @return mixed Stored Data
     */
    public function read()
    {
		$path = $this->getFile()->getPath();
        $timeOut = intval( $this->timeOut );
        @$ctime = filectime( $path ) . filemtime( $path );
        $key = $path . $ctime;
        if( null !== @$this->readMCache[$key] )
        {
            return $this->readMCache[$key];
        }
	//	var_export( $this );
		if( is_file( $path ) )
		{
            if( $timeOut )
            {
                //	Check the time out 
                if( $timeOut < time() - $ctime )
                {
                    unlink( $path );
                    $this->readMCache[$key] = false;
                    return false;
                }
            }
        //	Ayoola_Document::createDirectory( dirname( $path ) );
        //	$data = include $path;
            $data = false;
            if( $content = file_get_contents( $path ) )   
            {
                $data = json_decode( $content, true );
            }
    //        var_export( $data );
            $this->readMCache[$key] = $data;
            return $data;
			
		}
        $this->readMCache[$key] = false;
        return false;
    } 
	
    /**
     * Retrieves Ayoola_File
     *
     * @param 
     * @return Ayoola_File
     */
    public function getFile()
    {
		if( is_null( $this->_file ) )
		{ 
			$this->_file = new Ayoola_File();
			$filter = new Ayoola_Filter_Alnum();
			$filter->replace = DS;
			$name = $filter->filter( $this->getNamespace() );
			$domain = $filter->filter( Ayoola_Application::getDomainName( array( 'no_cache' => true ) ) );
         //   PageCarton_Widget::v( Ayoola_Application::getPathPrefix() );
			$this->_file->setPath( 'STORAGE' . DS . $domain . Ayoola_Application::getPathPrefix() .  DS . $name . md5( $name ) );     
		}
        return $this->_file;
    } 
	
    /**
     * Clears data from storage
     *
     * @param void
     */
    public static function destroy()
    {
		$class = new self();
	//	$dir = $class->getFile()->getDirectory() . DS . __CLASS__ . DS;
		$dir = $class->getFile()->getDirectory() . DS . 'STORAGE' . DS;
		Ayoola_Doc::deleteDirectoryPlusContent( $dir );
    } 
	
    /**
	 * Returns the _name property
	 * 
     * @param void
     * @return string The Name
     */
    public static function getName()
	{
		return self::$_name;
	}
	
    /**
	 * Returns the _namespace property
	 * 
     * @param void
     * @return string The Namespace
     */
    public function getNamespace()
	{
		if( is_null( $this->_namespace ) ){ $this->setNamespace(); }
		return $this->_namespace;
	}
	
    /**
	 * Set the _namespace property
     *
     * @param string
     */
    public function setNamespace( $namespace = null )
	{
		if( ! $namespace )
		{ 
			$namespace = __CLASS__; 
		}
        require_once 'Ayoola/Filter/Alnum.php';
		$filter = new Ayoola_Filter_Alnum();
		$filter->replace = DS;
		$namespace = $filter->filter( $namespace );
		$this->_namespace = $namespace;
	}
}
