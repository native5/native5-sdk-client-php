<?php
/**
 *  Copyright 2013 Native5. All Rights Reserved
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
 * @author    Shamik Datta <shamik@native5.com>
 * @copyright 2013 Native5. All Rights Reserved
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$
 * @link      http://www.docs.native5.com
 */

namespace Native5;

/**
 * ConfigurationFactory
 *
 * @category  Configuration
 * @package   Native5
 * @author    Shamik Datta <shamik@native5.com>
 * @copyright 2013 Native5. All Rights Reserved
 * @license   See attached NOTICE.md for details
 * @version   Release: 1.0
 * @link      http://www.docs.native5.com
 * Created : 27-11-2012
 * Last Modified : Fri Dec 21 09:11:53 2012
 */
class ConfigurationFactory extends \Native5\Core\Configuration\YamlConfigFactory
{
    protected $_configuration;

    /**
     * makeConfig Wrap the associative configuration array inside a Configuration class
     *
     * @access public
     * @return void
     * @note the ftp configuration should be of the following format:
     *  mixed[] $configuration {
     *     @type string "host" domain name or IP address of ftp host
     *     @type string "port" port where ftp service is running on the host
     *     @type string "user" ftp username
     *     @type string "private_key" ftp user ssh private key for login into ftp host
     *     @type string "public_key" ftp user ssh public key for login into ftp host
     *     @type string "directory" directory on ftp host (optional)
     * }
     */
    protected function makeConfig() {
        $this->_checkConfig();

        // Construct the Configuration object
        if (empty($this->_configuration) || !($this->_configuration instanceof Configuration)) {
            $this->_configuration = new Configuration(trim($this->_config['app']['name']));
            // Local Environment
            if (isset($this->_config['environment']) && (strcasecmp($this->_config['environment'], 'local') == 0))
                $this->_configuration->setLocal();
            
            if (isset($this->_config['app']['preventMultipleLogins']) 
                && (strcasecmp($this->_config['app']['preventMultipleLogins'], 'true') == 0 || $this->_config['app']['preventMultipleLogins']))
                $this->_configuration->setPreventMultipleLogins();

            // App Configuration
            // Default Grade
            if (isset($this->_config['app']['defaultGrade']))
                $this->_configuration->setDefaultGrade(trim($this->_config['app']['defaultGrade']));
            // Log Level
            if (isset($this->_config['app']['logLevel']))
                $this->_configuration->setLogLevel(trim($this->_config['app']['logLevel']));

            //Track Analytics
            if (isset($this->_config['app']['trackAnalytics']))
                $this->_configuration->setLogAnalytics(trim($this->_config['app']['trackAnalytics']));

            // Api Configuration
            // Url
            $this->_configuration->setApiUrl(trim($this->_config['api']['url']));
            // Shared Key
            $sharedKey = getenv('NATIVE5_API_SHARED_KEY');
            if(empty($sharedKey))
                $this->_configuration->setSharedKey(trim($this->_config['api']['sharedKey']));
            else 
                $this->_configuration->setSharedKey(trim($sharedKey));
            // Secret Key
            $secretKey = getenv('NATIVE5_API_SECRET_KEY');
            if(empty($secretKey)) 
                $this->_configuration->setSecretKey(trim($this->_config['api']['secretKey']));
            else
                $this->_configuration->setSecretKey(trim($secretKey));
            if (isset($this->_config['nativeBinaryOnly']))
                $this->_configuration->setNativeBinary(trim($this->_config['nativeBinaryOnly']));

            // Set the raw config
            $this->_configuration->setRawConfiguration($this->_config);
        }

        return $this->_configuration;
    }


    // ****** Private Functions Follow ****** //

    private function _checkConfig() {
        // Check each configuration entry
        if (empty($this->_config) || !is_array($this->_config))
            throw new \InvalidArgumentException("Empty configuration or invalid configuration format");
        else if (empty($this->_config['app']))
            throw new \InvalidArgumentException("Application configuration not specified");
        else if (empty($this->_config['app']['name']))
            throw new \InvalidArgumentException("No application name specified under application configuration");
        else if (empty($this->_config['api']))
            throw new \InvalidArgumentException("Api configuration not specified");
        else if (empty($this->_config['api']['url']))
            throw new \InvalidArgumentException("No url specified under api configuration");
        else if (empty($this->_config['api']['sharedKey']))
            throw new \InvalidArgumentException("No shared key specified under api configuration");
        else if (empty($this->_config['api']['secretKey']))
            throw new \InvalidArgumentException("No secret key specified under api configuration");
    }
}

