<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Help_ForgotUsernameOrPassword
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ForgotUsernameOrPassword.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Help_Abstract
 */
 
require_once 'Application/User/Help/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Help_ForgotUsernameOrPassword
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Help_ForgotUsernameOrPassword extends Application_User_Help_Abstract
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$this->createForm( 'Reset Password' );
	//		$this->setViewContent( self::__( '<h3>Reset Password</h3>' ) );
			$this->setViewContent( self::__( '<p class="pc-notify-info">Fill the following information to reset your password</p>' ) );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			$identifier = array( 'email' => strtolower( trim( $values['email'] ) ) );
			
			
			//	First seek in the local flatfile
            $table = "Ayoola_Access_LocalUser";
            $table = $table::getInstance( $table::SCOPE_PROTECTED );
            $table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PROTECTED );
            $table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PROTECTED );
			if( $info = $table->selectOne( null, $identifier ) )
			{
				if( $info['user_information'] )  
				{
					$info = $info['user_information'];
					$requiredFields = array();
					
					//	Super users cant do this...
					if( intval( $info['access_level'] ) == 99 )
					{
						$this->getForm()->setBadnews( 'Invalid Request' );
						$this->setViewContent( $this->getForm()->view(), true );
						$info = array();
					}
					
					foreach( $requiredFields as $each )
					{
						if( strtolower( $values[$each] ) !== strtolower( $info[$each] ) )
						{ 
					//		$this->getForm()->setBadnews( 'Invalid Information - ' . $each . ' - ' . $info[$each] . ' - ' . $values[$each] );
							$this->getForm()->setBadnews( 'Invalid Information' );
							$this->setViewContent( $this->getForm()->view(), true );
                            $info = array();
						}
					}
				}
			}
			$captcha = new Ayoola_Captcha();
			$password = $captcha->getCode();
		//	$password = 'ayoola';
			$informationToSend = array( 'password' => $password );
			$informationToUpdate = $informationToSend;
			require_once 'Ayoola/Filter/Hash.php';
			$filter = new Ayoola_Filter_Hash( 'sha512' );
			$informationToUpdate['password'] = $filter->filter( $informationToUpdate['password'] );
			
			$result = false;
			do
			{
				if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
				{
			//		$database = 'cloud';
				}
				switch( $database )
				{
					case 'cloud':
						$response = Application_User_Help_ForgotUsernameOrPassword_Api::send( $values );
					//	var_export( $response );
						if( true !== $response['data'] )
						{
							$this->getForm()->setBadnews( $response, 'server' );
							break 2;
						}

					break;
					case 'relational':
						if( ! $data = $this->getDbTable()->selectOne( null, strtolower( implode( ', ', $this->_otherTables ) ), $identifier ) )
						{
							break 2;
						}
						$requiredFields = array( 'firstname', 'lastname', 'sex', 'birth_date' );
						foreach( $requiredFields as $each )
						{
						//	var_export( $values[$each] );
						//	var_export( $data[$each] );
							if( $values[$each] != $data[$each] ){ break 3; }
						}
						$result = true;
						$table = new Application_User_UserPassword();
						if( ! $table->update( $informationToUpdate, array( 'user_id' => $data['user_id'] ) ) )
						{
							throw new Application_User_Help_Exception( 'Error while updating password' );
							break 2;
						}
						if( ! $values['username'] != $data['username'] ){ $informationToSend['username'] = $data['username']; }
					break;
				
				}
				//	Change in the flat-file
				try
				{
					if( $info )
					{
                        $informationToSend['email'] = $info['email'];
						$mailInfo = array( 'to' => $info['email'] );
						$mailInfo['body'] = null;
						$mailInfo['subject'] = 'Password Update';
						foreach( $informationToSend as $key => $value )
						{
							$mailInfo['body'] .= ucfirst( $key ) . ': ' . $value . "\r\n";
						}
						
						//	Log Success
						$data['result'] = 'success';
						Application_Log_View_ForgetUsernameOrPassword::log( $data );

						//	var_export( $info );
						//	var_export( $mailInfo );
						$this->sendMail( $mailInfo );
						$info = array_merge( $info, $informationToUpdate );
						
						unset( $values['password2'] );
					//	var_export( $info );
						if( Ayoola_Access_Localize::info( $info ) )
						{
							$this->setViewContent(  '' . sprintf( self::__( 'Password reset was successful; a new password has been sent to your email address %s. If you cannot find it in the inbox, kindly check the SPAM or JUNK folder of the mailbox.' ), $mailInfo['to'] ) . '', true  );
						}
                    }
                    else
                    {
                        $this->setViewContent(  '<div class="badnews">' . sprintf( self::__( 'Password for this account could not be reset through this method. Kindly contact one of the technical administrators of the website for help to retrieve your account.' ) ) . '</div>', true  );
                    }
				}
				catch( Exception $e )
				{
				//	var_export( $e->getMessage() );
				//	var_export( $e->getTraceAsString() );
				}
				
				return true;
			}
			while( false );
            $this->setViewContent(  '' . sprintf( self::__( 'Password for %s could not be reset through this method. Kindly contact one of the technical administrators of the website for help to retrieve your account.' ), $mailInfo['to'] ) . '', true  );
			
			//	Log Failure
			$values['result'] = 'failed';
			Application_Log_View_ForgetUsernameOrPassword::log( $values );
		}
		catch( Application_User_Help_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
