<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Confirmation
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Confirmation.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';  


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Confirmation
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_Confirmation extends Application_Subscription_Checkout_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Checkout Confirmation';       
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'api', 'status' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected static $_status = array( 1 => 'Your order was successful.', 0 => '<span class="badnews">PAYMENT FAILED</span>' );
	
    /**
     * Table where to store orders
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_Order';
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {		
		//	Identifiers are required
		if( ! $identifier = $this->getIdentifier() ){ return false; }
		if( ! $cart = self::getStorage()->retrieve() )
		{ 
			return $this->setViewContent(  '' . self::__( '<p class="badnews">ERROR - You need to have an item in your shopping cart to confirm checkout</p>' ) . '', true  );
		}
			
	//	self::v( $cart );
		$data = Application_Subscription_Checkout_CheckoutOption::getInstance()->selectOne( null, array( 'checkoutoption_name' => $identifier['api'] ) );

		//	lets see if we can ask the gateway for status
	//	var_export( $data );
		$className = $data['object_name'];

	//	var_export( $identifier );
		$orderNumber = self::getOrderNumber( $identifier['api'] );
	//	var_export( $orderNumber );
		if( Ayoola_Loader::loadClass( $className ) )
		{ 
			if( method_exists( $className, 'checkStatus' ) )
			{
				if( $orderInfo = $className::checkStatus( $orderNumber ) )
				{
					$identifier['status'] = 0;
					switch( strtolower( $orderInfo['order_status'] ) )
					{ 
						case 'payment successful':
						case '99':
						case '100':
							$identifier['status'] = 1;
						break;   
					}
				}
			}
		}
		else
		{
		//	$identifier['status'] = $_GET['status'];
		}


		$this->setViewContent( "<br><h3>Thank you! Order Confirmed! </h3><br>" );
		$this->setViewContent( "<h4>STATUS: "  . self::$_status[intval( $identifier['status'] )] . "</h4><br>" );
		$this->setViewContent( "<h4>ORDER NUMBER: " . $orderNumber . "</h4><br>" );
		$this->setViewContent( "<p>" . ( Application_Settings_Abstract::getSettings( 'Payments', 'order_confirmation_message' ) ? : "You can print this page for your records. Your order number is a unique identifier that should be mentioned when referencing this order." ) . "</p><br>" );
		$this->setViewContent( "<h4>Payment Option</h4><br>" );   
		$data['checkoutoption_logo'] = htmlspecialchars_decode( $data['checkoutoption_logo'] );
		$this->setViewContent( "<p>{$data['checkoutoption_logo']}</p><br>" );		
		if( $identifier['status'] )
		{
			$this->setViewContent( "<h4>Order Details</h4><br>" );
			$this->setViewContent( Application_Subscription_Cart::viewInLine() );
		//	self::getStorage()->clear(); 
		//	self::getOrderNumber(); //	Clear order number history
			$this->setViewContent( "
									<h4>What Next???</h4>
									<br>
									<p><a href='" . Ayoola_Application::getUrlPrefix() . "/widgets/Application_Subscription_Checkout_Order_View?order_id=" . $orderNumber . "'>Check order status</a>.</p>
									<p><a href='{$cart['settings']['return_url']}'>Go back</a>.</p>
									" 
									);
			$notes = Application_Settings_Abstract::getSettings( 'Payments', 'order_notes' );

			Application_Subscription_Cart::clear();

			$notes ? $this->setViewContent( "<h4>Note:</h4><br>" ) : null;
		//	$this->setViewContent( "<h4>Note:</h4><p>Orders can take up to 24 hours after payment is confirmed for fufillment ( depending on the payment method ). Please be patient.</p>" );
			$notes ? $this->setViewContent( $notes ) : null;          
		}
		else
		{
			$this->setViewContent( "<h4>What Next???</h4><br><p>You can checkout with other payment methods.</p><br>" );
			$this->setViewContent( Application_Subscription_Checkout::viewInLine() );
			$this->setViewContent( "<h4>Order Details</h4><br>" );
			$this->setViewContent( Application_Subscription_Cart::viewInLine() );
		}
		//	SEND THE user AN EMAIL IF HE IS LOGGED INN
		$emailAddress = array();
		if( Ayoola_Application::getUserInfo( 'email' ) )
		{
			$emailAddress[] = Ayoola_Application::getUserInfo( 'email' );
		}
		@$checkoutEmail = $cart['checkout_info']['email'] ? : $cart['checkout_info']['email_address'];
		if( $checkoutEmail )
		{
			$emailAddress[] = $checkoutEmail;   
		}

		$emailInfo = array(
							'subject' => 'Order Notification',
							'body' => '' . $this->view() . '',
		
		);
		$emailInfo['to'] = implode( ',', array_unique( $emailAddress ) );
	//	$emailInfo['from'] = '' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ) . ' <no-reply@' . Ayoola_Page::getDefaultDomain() . '>"';
		$emailInfo['html'] = true; 
		if( $emailAddress )
		{		
			@self::sendMail( $emailInfo );
		//	self::v( $emailInfo );
			
		}
		//	Notify Admin
	//	$mailInfo = array();
		$emailInfo['to'] = 	Ayoola_Application_Notification::getEmails();

		$emailInfo['subject'] = 'Checkout Confirmation';
		$emailInfo['body'] = 'Someone just confirmed their checkout. Here is the cart content: <br> 
		' . $this->view() . ' <br>  

		ORDER INFORMATION
		' . self::arrayToString( $orderInfo ) . ' <br>  
		';
		try
		{
		//	var_export( $emailInfo );
			@self::sendMail( $emailInfo );
		//	Ayoola_Application_Notification::mail( $emailInfo );
		}
		catch( Ayoola_Exception $e ){ null; }
		
    } 
	// END OF CLASS
}
