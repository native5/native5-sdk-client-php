<?php
/*
 *  Copyright 2012 Native5. All Rights Reserved 
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *	You may not use this file except in compliance with the License.
 *		
 *	You may obtain a copy of the License at
 *	http://www.apache.org/licenses/LICENSE-2.0
 *  or in the "license" file accompanying this file.
 *
 *	Unless required by applicable law or agreed to in writing, software
 *	distributed under the License is distributed on an "AS IS" BASIS,
 *	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *	See the License for the specific language governing permissions and
 *	limitations under the License.
 *
 */

/**
 * 
 * @version 
 * @license See attached NOTICE.md for details
 * @copyright See attached LICENSE for details
 *
 * Created : 23-11-2012
 * Last Modified : Fri Nov 23 15:11:07 2012
 */
abstract class BaseTemplate {
    protected $_TEMPLATE;
    protected $_twig;
    protected $_variableMap;

    protected function __construct() {
        $templates_path = "templates/grade".$_SESSION['category'];
        $loader = new Twig_Loader_Filesystem($templates_path);
        $this->_twig = new Twig_Environment($loader, 
            Array(
                'debug'=> true,
                'autoreload'=>false,
                'autoescape'=>true,
                'cache' => CACHE_PATH,
            ));
    }

    protected function render() {
        return $this->_twig->render($this->_TEMPLATE, $this->_variableMap);	
    }
}
?>
