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
 * @category  Configuration 
 * @package   Native5
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$ 
 * @link      http://www.docs.native5.com 
 */

namespace Native5;

/**
 * Configuration 
 * 
 * @category  Configuration 
 * @package   Native5
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached NOTICE.md for details
 * @version   Release: 1.0 
 * @link      http://www.docs.native5.com 
 * Created : 27-11-2012
 * Last Modified : Fri Dec 21 09:11:53 2012
 */
class Configuration
{

    private $_config;

    public function __construct($configFile) {
        if(!file_exists($configFile)) {
            if (isset($GLOBALS['logger']))
                $GLOBALS['logger']->error('No configuration found', array($configFile));
            throw new \Exception('No configuration file found, Native5 application need a valid configuration file to be present in the config folder.');
        }
        $this->_config = yaml_parse_file($configFile);
    }

    /**
     * getSharedKey 
     * 
     * @access public
     * @return void
     */
    public function getSharedKey() {
        return $this->_config['sharedKey'];   
    }

    /**
     * getSecretKey 
     * 
     * @access public
     * @return void
     */
    public function getSecretKey() {
        return $this->_config['secretKey'];
    }


    /**
     * isLocal 
     * 
     * @access public
     * @return void
     */
    public function isLocal() {
        return array_key_exists('environment',$this->_config) && (strcasecmp($this->_config['environment'] , 'local')==0);

    }// check if local environment


    /**
     * getApplicationContext 
     * 
     * @access public
     * @return void
     */
    public function getApplicationContext() {
        return $this->_config['app']['name'];
    }
}


