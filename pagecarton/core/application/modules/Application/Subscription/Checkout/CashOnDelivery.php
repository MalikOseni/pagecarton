<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_CashOnDelivery
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CashOnDelivery.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_CashOnDelivery
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_CashOnDelivery extends Application_Subscription_Checkout_Abstract
{
		
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
	protected static $_currency= array( 'whitelist' => '', 'blacklist' => '' ); 

    /**
     * Plays the process
     * 
     */
	protected function init()
    {		
	//	$this->setViewContent( self::__( '<h4>NOTE:</h4>' ) );
	//	$this->setViewContent( self::__( '<p>' ) );
	//	$this->setViewContent( self::__( '<p>Payments must be made in ' . $values['settings']['currency_abbreviation'] . '.</p>' ) );
	//	$this->setViewContent( self::__( '<p>Please ensure p <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/site/contact/">contacting us</a>.</p>' ) );
		$this->setViewContent( self::__( '<h2 class="badnews">Notice!</h2>' ) );
		$this->setViewContent( self::__( '<p class="badnews">Your online order is NOT yet completed until you confirm you will be making payment on the point of delivery.</p>' ) );
		$this->setViewContent( self::__( '<h2><a href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Subscription_Checkout_Confirmation/get/api/CashOnDelivery/status/1/"><input name="' . __CLASS__ . '_confirm_order" onClick="ayoola.div.selectElement( this )" class="boxednews goodnews" value="Confirm order" type="button" /></a></h2>' ) );
	//	$this->setViewContent( self::__( '</p>' ) );   
		
		
    } 
	// END OF CLASS
}
