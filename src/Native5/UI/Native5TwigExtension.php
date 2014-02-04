<?php
/**
 * Copyright © 2013 Native5
 * 
 * All Rights Reserved.  
 * Licensed under the Native5 License, Version 1.0 (the "License"); 
 * You may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at
 *  
 *      http://www.native5.com/legal/npl-v1.html
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *  PHP version 5.3+
 */

namespace Native5\UI;

/**
 * Native5TwigExtension 
 * 
 * @category  UI 
 * @package   Native5\UI
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached NOTICE.md for details
 * @version   Release: 1.0 
 * @link      http://www.docs.native5.com 
 * Created :  04-Dec-2013 
 * Last Modified : Wed Dec  4 14:57:49 2013
 */
class Native5TwigExtension extends \Twig_Extension
{

    private $_category;

    /**
     * Default Constructor 
     * 
     * @param mixed $category 
     * @access public
     * @return void
     */
    public function __construct($category=null, $basePath=".")
    {
        $this->_category = $category;
    }


    /**
     * Get Global Variables 
     * 
     * @access public
     * @return void
     */
    public function getGlobals()
    {
        return array(
            'category' => $this->_category,
        );
    }


    public function getFilters()
    {
        $app        = $GLOBALS['app'];
        return array(
            new \Twig_SimpleFunction('isToday', 'DateFilter::isToday'),
            new \Twig_SimpleFunction('truncate', 'DateFilter::isToday'),
            new \Twig_SimpleFunction('isTomorrow', 'DateFilter::isTomorrow'),
            new \Twig_SimpleFunction('isLater', 'DateFilter::isLater'),
            new \Twig_SimpleFilter('truncate', function($text, $start=0, $min=0, $max = 50) {
		if (strlen($text) >= $min) {
		    $text = substr($text, $start, $max);
		}
		return $text;
            }),
            new \Twig_SimpleFilter('nonce', function($str) {
                $app=$GLOBALS['app'];
                if (strpos($str, '?') !== false) {
                    return DIRECTORY_SEPARATOR.$app->getConfiguration()->getApplicationContext().DIRECTORY_SEPARATOR.$str.'&rand_token='.$app->getSessionManager()->getActiveSession()->getAttribute('nonce');
                }
                return DIRECTORY_SEPARATOR.$app->getConfiguration()->getApplicationContext().DIRECTORY_SEPARATOR.$str.'?rand_token='.$app->getSessionManager()->getActiveSession()->getAttribute('nonce');
                })
        ); 
    }
    

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction("resolvePath", 
                function($url) {
                    return \Native5\UI\Native5TwigExtension::resolvePath($url);
                },
                array('is_safe'=>array('all'))
                )
        );
    }

    public function getName()
    {
        return 'Native5';
    } 


    /**
     * Resolves Path for function 
     * 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public static function resolvePath($name)
    {
        
        $logger     = $GLOBALS['logger'];
        $app        = $GLOBALS['app'];
        $staticPath = 'public';
        
        if ($app->getConfiguration()->isLocal()) {
            $staticPath = 'views';
        }

        $session    = $app->getSessionManager()->getActiveSession();
        $category   = $session->getAttribute('category');
        $basePath   = '/'.$staticPath.'/resources/'.$category;
        $commonPath = '/'.$staticPath.'/resources/common';

        $searchFolder = '.';
        
        $isUrl = false;

        if(preg_match('/.*\.js$/', $name)) {
            $searchFolder = 'scripts';
        } else if(preg_match('/.*\.css$/', $name)) {
            $searchFolder = 'styles';
        } else if(preg_match('/.*\.(?:jpg|jpeg|gif|png)$/', $name)) {
            $searchFolder = 'images';
        } else {
            $isUrl = true;
            $name  = DIRECTORY_SEPARATOR.$app->getConfiguration()->getApplicationContext().DIRECTORY_SEPARATOR.$name;
        }

        if ($isUrl) {
            return $name;
        }

        if (file_exists(getcwd().$basePath.'/'.$searchFolder.'/'.$name)) {
            return '/'.$app->getConfiguration()->getApplicationContext().$basePath.'/'.$searchFolder.'/'.$name;
        } else if (file_exists(getcwd().$commonPath.'/'.$searchFolder.'/'.$name)) {
            return '/'.$app->getConfiguration()->getApplicationContext().$commonPath.'/'.$searchFolder.'/'.$name;
        }
        return $name;
    }
}

