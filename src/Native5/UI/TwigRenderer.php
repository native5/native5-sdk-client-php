<?php
/**
 *  Copyright 2012 Native5. All Rights Reserved
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *	You may not use this file except in compliance with the License.
 *
 *	Unless required by applicable law or agreed to in writing, software
 *	distributed under the License is distributed on an "AS IS" BASIS,
 *	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *	See the License for the specific language governing permissions and
 *	limitations under the License.
 *  PHP version 5.3+
 *
 * @category  UI
 * @package   Native5\UI
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$
 * @link      http://www.docs.native5.com
 */

namespace Native5\UI;

use Native5\Sessions\WebSessionManager;

/**
 * TwigRenderer
 *
 * @category  UI
 * @package   Native5\UI
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved
 * @license   See attached NOTICE.md for details
 * @version   Release: 1.0
 * @link      http://www.docs.native5.com
 * Created : 27-11-2012
 * Last Modified : Fri Dec 21 09:11:53 2012
 */
class TwigRenderer implements Renderer
{

    private $_template;

    private $_basePath;

    private $_twig;


    /**
     * __construct 
     * 
     * @param mixed $template to use
     * @param mixed $basePath to use
     *
     * @access public
     * @return void
     */
    public function __construct($template=null, $basePath='templates')
    {
        $app=$GLOBALS['app'];

        if($app->getConfiguration()->isLocal()) {
            $basePath = 'views'.'/'.$basePath;
        }
        $this->_template = $template;
        $this->_basePath = $basePath;
        $this->_configure();

    }//end __construct()


    /**
     * render UI on basis of incoming data 
     * 
     * @param mixed $data The data to use. 
     *
     * @access public
     * @return Template
     */
    public function render($data)
    {
        global $app;
        $session = $app->getSessionManager()->getActiveSession();
        $category = $session->getAttribute('category').'/';
        $staticResourcesPath = $app->getConfiguration()->getApplicationContext().'/public/'.$category;
        if ($app->getConfiguration()->isLocal()) {
            $staticResourcesPath = '/views/resources/'.$category;
        }
        $in_data = array (
            'items'          => $data,
            'STATIC_RES_URL' => $staticResourcesPath
            );
        return $this->_twig->render($this->_template, $in_data);

    }//end render()


    /**
     * Set the template to be used for rendering 
     * 
     * @param mixed $template Template to use for rendering
     *
     * @access public
     * @return void
     */
    public function setTemplate($template)
    {
        $this->_template = $template;

    }//end setTemplate()


    /**
     * Get Rendering template 
     * 
     * @access public
     * @return Template
     */
    public function getTemplate()
    {
        return $this->_template;

    }//end getTemplate()


    /**
     * _configure 
     * 
     * @access private
     * @return void
     */
    private function _configure()
    {
        $app     = $GLOBALS['app'];
        $session = $app->getSessionManager()->getActiveSession();
        \Twig_Autoloader::register();

        $templatesPath  = $this->_basePath.DIRECTORY_SEPARATOR.$session->getAttribute('category');
        $loader         =  new \Twig_Loader_Filesystem($templatesPath);
        $commonPath = 'templates/common';
        if($app->getConfiguration()->isLocal()) {
            $commonPath = 'views'.'/'.$commonPath;
        } 
        $loader->prependPath('./'.$commonPath, 'common');
        $cache_path = defined('CACHE_PATH') ? CACHE_PATH : 'cache';
        $this->_twig = new \Twig_Environment($loader,
            array(
                'debug'      => true,
                'autoreload' => false,
                'autoescape' => true,
                'cache'      => $cache_path,
            ));
        $this->_twig->getExtension('core')->setNumberFormat(2, '.', ',');
        $this->_twig->addFilter(
            'truncate',
            new \Twig_Filter_Function('StringFilter::truncate')
        );
        $this->_twig->addFilter(
            'isToday',
            new \Twig_Filter_Function('DateFilter::isToday')
        );
        $this->_twig->addFilter(
            'isTomorrow',
            new \Twig_Filter_Function('DateFilter::isTomorrow')
        );
        $this->_twig->addFilter(
            'isLater',
            new \Twig_Filter_Function('DateFilter::isLater')
        );

    }//end _configure()


}//end class

?>
