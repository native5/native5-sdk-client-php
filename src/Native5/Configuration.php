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
 * Configuration
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
class Configuration {
    private $_applicationContext;
    private $_apiUrl;
    private $_sharedKey;
    private $_secretKey;
    private $_defaultGrade;
    private $_logLevel;
    private $_logAnalytics;
    private $_local;
    private $_nativeBinary;

    private $_config;

    public function __construct($applicationContext = null) {
        $this->_applicationContext = $applicationContext;
        $this->_nativeBinary = false;
        $this->_logAnalytics = false;
    }

    public function getApplicationContext() {
        return $this->_applicationContext;
    }

    public function setApplicationContext($applicationContext) {
        $this->_applicationContext = $applicationContext;
    }

    public function getApiUrl() {
        return $this->_apiUrl;
    }

    public function setApiUrl($apiUrl) {
        $this->_apiUrl = $apiUrl;
    }

    public function getSharedKey() {
        $sessionManager = $GLOBALS['app']->getSessionManager();
        if(!empty($sessionManager)) {
            $sessionSharedKey = $sessionManager->getActiveSession()->getAttribute('sharedKey');
            if (!empty($sessionSharedKey))
                return $sessionSharedKey;
        }
        return $this->_sharedKey;
    }

    public function setSharedKey($sharedKey) {
        $this->_sharedKey = $sharedKey;
    }

    public function getSecretKey() {
        $sessionManager = $GLOBALS['app']->getSessionManager();
        if(!empty($sessionManager)) {
            $sessionSecretKey = $sessionManager->getActiveSession()->getAttribute('secretKey');
            if (!empty($sessionSecretKey))
                return $sessionSecretKey;
        }
        return $this->_secretKey;
    }

    public function setSecretKey($secretKey) {
        $this->_secretKey = $secretKey;
    }

    public function getDefaultGrade() {
        return $this->_defaultGrade;
    }

    public function setDefaultGrade($defaultGrade) {
        $this->_defaultGrade = $defaultGrade;
    }

    public function getLogLevel() {
        return $this->_logLevel;
    }

    public function setLogLevel($logLevel) {
        $this->_logLevel = $logLevel;
    }

    public function logAnalytics()
    {
        return $this->_logAnalytics;
    }
    
    public function setLogAnalytics($logAnalytics)
    {
        return $this->_logAnalytics=$logAnalytics;
    }

    public function isPreventMultipleLogins() {
        return empty($this->_preventMultipleLogins) ? false : true;
    }
    
    public function setPreventMultipleLogins() {
        $this->_preventMultipleLogins = true;
    }

    public function isLocal() {
        return empty($this->_local) ? false : true;
    }

    public function setLocal() {
        $this->_local = true;
    }
    
    public function isNativeBinary() {
        return $this->_nativeBinary;
    }
    
    public function setNativeBinary() {
        $this->_nativeBinary = true;
    }

    public function setRawConfig($config) {
        $this->_config = $config;
    }

    public function getRawConfig($key = null) {
        if (empty($key))
            return $this->_config;

        return isset($this->_config[$key]) ? $this->_config[$key] : null;
    }
}

