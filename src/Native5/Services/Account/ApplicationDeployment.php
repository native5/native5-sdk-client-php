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
 * @category        Native5 Application Services
 * @package         Native5\Core\<package>
 * @author          Shamik <shamik@native5.com>
 * @copyright       2013 Native5. All Rights Reserved 
 * @license         See attached LICENSE for details
 * @version         GIT: $gitid$ 
 * @link            http://www.docs.native5.com 
 */

namespace Native5\Services\Account;

class ApplicationDeployment {
    private $_serverName;
    private $_userName;
    private $_directory;
    private $_env;

    // Deployment type configs
    const ENV_SANDBOX = 'sandbox';
    const ENV_PRODUCTION = 'production';

    public function setServerName ($name) {
        $this->_serverName = $name;
    }

    public function getServerName () {
        return $this->_serverName;
    }

    public function setUserName ($userName) {
        $this->_userName = $userName;
    }

    public function getUserName () {
        return $this->_userName;
    }

    public function setDirectory ($dir) {
        $this->_directory = $dir;
    }

    public function getDirectory () {
        return $this->_directory;
    }

    public function setEnvironment ($env) {
        $this->_env = $env;
    }

    public function getEnvironment () {
        return $this->_env;
    }

    public function serialize($format) {
        if($format == 'json') {
            $output = array();
            $output['serverName'] = $this->_serverName;
            $output['userName'] = $this->_userName;
            $output['directory'] = $this->_directory; 

            return $output;
        } 
    }
}   

