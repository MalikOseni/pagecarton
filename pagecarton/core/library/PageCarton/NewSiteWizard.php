<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_NewSiteWizard
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: NewSiteWizard.php Monday 24th of December 2018 01:10AM ayoola@ayoo.la $
 */
/**
 * @see PageCarton_Widget
 */
class PageCarton_NewSiteWizard extends PageCarton_Widget
{

    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
    protected static $_accessLevel = array(0);

    /**
     *
     *
     * @var string
     */
    protected static $_objectTitle = 'New Site Wizard';
    /**
     * Performs the whole widget running process
     *
     */
    public function init()
    {
        try
        {
            //  Code that runs the widget goes here...
            if (!self::hasPriviledge(98) && !Ayoola_Application::isFirstAdminUser()) {
                $this->setViewContent(Ayoola_Access_Login::viewInLine(array('no_redirect' => true)));
                if (!self::hasPriviledge(98)) {
                    return false;
                }
            }
            //  Causing page to take too long to load
            //  may be the cause of cache clearing every time
            //    Application_Personalization::viewInLine();
            $stages = array(
                array('key' => self::__( 'Update Basic Info' ), 'title' => '' . self::__( 'Set site basic branding information' ) . '', 'class' => 'Application_Personalization'),
                array('key' => '' . self::__( 'Browse themes' ) . '', 'title' => '' . self::__( 'Choose from hundreds of great themes for your site' ) . '', 'class' => 'Ayoola_Page_Layout_Repository'),
                array('key' => '' . self::__( 'Choose a theme' ) . '', 'title' => '' . self::__( 'Make a theme the default site theme' ) . '', 'class' => 'Ayoola_Page_Settings'),
                array('key' => '' . self::__( 'Set static content' ) . '', 'title' => '' . self::__( 'Update site static/dummy text content' ) . '', 'class' => 'Ayoola_Page_Layout_ReplaceText'),
                array('key' => '' . self::__( 'Update pictures' ) . '', 'title' => '' . self::__( 'Change some of the theme dummy pictures' ) . '', 'class' => 'Ayoola_Page_Layout_Images'),
                array('key' => '' . self::__( 'Publish Content' ) . '', 'title' => '' . self::__( 'Start building up the site by adding some structured posts' ) . '', 'class' => 'Application_Article_Publisher'),
                array('key' => '' . self::__( 'Share the site now' ) . '', 'title' => '' . self::__( 'Your are done building your site. Next is to share with the world with social tools' ) . '', 'class' => 'Application_Share_Website'),
            );

            if (@$_GET['mode'] === 'publisher' || $this->getParameter('publisher_mode')) {
                unset($stages[1], $stages[2]);
            } else {
                #   Remove image from new site wizard, retain in publisher mode
                #   We have not figured how updating images will enable a smooth progress
            //    unset($stages[4]);
            }
            //  reset keys because those that left
            $stages = array_values($stages);
            $html   = null;
            $html .= '<ol class="cd-multi-steps text-bottom count">';
            $lastCompleted = false;
            $break         = false;
            //  $class = $stages[0]['class'];
           // $setStage = @$_GET['stage'];
            $storedStages = (array) self::getObjectStorage( 'my-stages' )->retrieve() ? : array();
            if( Ayoola_Loader::loadClass( @$_GET['stage'] ) ) 
            {
                $storedStages[] = $_GET['stage'];
                self::getObjectStorage( 'my-stages' )->store( $storedStages );
            }
        //    var_export( $setStage );

            foreach( $stages as $key => $each) 
            {
                $xT[$each['class']]       = $each;
                $xT[$each['class']]['id'] = $key;
                //    $html .= '<li><a rel="" href="?stage=' . $each['class'] . '">' . $each['title'] . '</a></li>';
                $percentageText = '';
                $percentage     = 0;
                if( Ayoola_Loader::loadClass( $each['class'] ) ) 
                {
                    if( method_exists( $each['class'], 'getPercentageCompleted' ) ) 
                    {
                        $percentage     = $each['class']::getPercentageCompleted();
                        $percentageText = '(' . $percentage . '%)';
                    }
                }
                if( ( $percentage == 100 && ! $break ) ) 
                {
                    $lastCompleted = true;
                    $html .= '<li class="visited"><a onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . $each['class'] . '?mini_info=1&close_on_success=1\', \'' . $this->getObjectName() . '\' );" href="javascript:;">' . $each['key'] . '</a></li>';
                } 
                elseif( ( $lastCompleted == true || $key === 0 ) || in_array( $each['class'], $storedStages ) ) 
                {
                    $class         = $each['class'];
                    $lastCompleted = false;
                    $break         = true;
                    $html .= '<li class="current"><em><a onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . $each['class'] . '?mini_info=1&close_on_success=1\', \'' . $this->getObjectName() . '\' );" href="javascript:;">' . $each['key'] . '</a></em></li>';
                } 
                else 
                {
                    $html .= '<li><em>' . $each['key'] . '</em></li>';
                }
            }
            $html .= '</ol>';
            if( @$_GET['stage'] ) 
            {
                $class = $xT[$_GET['stage']]['class'];
            }
            $weAreOn = @$xT[$class]['id'] + 1;
            if ($this->getParameter('hide_if_stages_completed') && $weAreOn == count( $stages ) ) 
            {
                return false;
            }
            //  Output demo content to screen
        //    $this->setViewContent($html, true);
            if( Ayoola_Loader::loadClass( $class ) ) 
            {
                $query = $_GET;
                $query['stage'] = $stages[$weAreOn]['class'];
                $query = http_build_query( $query );
                $this->setViewContent( '<div style="text-align:center;">
                <br><br>
                ' . sprintf( PageCarton_Widget::__( ' Step %d of %d' ), $weAreOn, count( $stages ) ) . '
              <br><br>
              ' . $html . ' <br><br>
               ' . $xT[$class]['title'] . ' <br><br>
                <a class="pc-btn" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . $xT[$class]['class'] . '?mini_info=1&close_on_success=1\', \'' . $this->getObjectName() . '\' );" href="javascript:;">  ' . $xT[$class]['key'] . ' <i  style="margin:5px;" class="fa fa-external-link"></i></a>
                
                <br><br>
                <p style="text-transform:uppercase;font-size:x-small;">
                ' . ( $weAreOn === count( $stages ) ? null : ( '
                
                <a class="" href="?' . $query . '"> <i  style="margin:5px;" class="fa fa-angle-double-right"></i>  ' . sprintf( PageCarton_Widget::__( ' Skip Step %d' ), $weAreOn ) . ' </a> ' ) ) . '
                <span class="pc_give_space"></span> 
                <a class="" href="' . Ayoola_Application::getUrlPrefix() . '/" target="_blank">  <i  style="margin:5px;" class="fa fa-external-link"></i> ' . self::__( 'Preview Site' ) . ' </a><br><br>
                </p>
                </div>');
                //    $this->setViewContent( $class::viewInLine() );
            }
            // end of widget process

        }
        catch (Exception $e) 
        {
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) );
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) );
            return false;
        }
    }
    // END OF CLASS
}
