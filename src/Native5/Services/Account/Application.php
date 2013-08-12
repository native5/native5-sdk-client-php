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
 * @category  Application 
 * @package   Native5\Core\<package>
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$ 
 * @link      http://www.docs.native5.com 
 */

namespace Native5\Services\Account;

/**
 * Application 
 */
class Application
{
    private $_name;
    private $_urls = array();
    private $_created;
    private $_modified;
    private $_repoConfig;
    private $_platforms;
    private $_deploymentConfigs = array();

    private $_releases;

    public function __construct($name) {
        $this->_name = $name;
    }

    public function setName($name) {
        $this->_name = $name;
    }

    public function getName() {
        return $this->_name;
    }

    public function setUrl($url) {
        if (!is_array($url))
            return false;

        $this->_urls = array_merge($this->_urls, $url);
        return true;
    }

    public function getUrl($env) {
        if (isset($this->_urls[$env]))
            return $this->_urls[$env];
        else
            return false;
    }

    public function setCreatedOn($dateTime) {
        $this->_created = $dateTime;
    }

    public function getCreatedOn() {
        return $this->_created;
    }
    
    public function setModifiedOn($dateTime) {
        $this->_modified = $dateTime;
    }

    public function getModifiedOn() {
        return $this->_modified;
    }

    public function setPlatforms($platforms) {
        $this->_platforms = $platforms;
    }

    public function getPlatforms() {
        return $this->_platforms;
    }

    public function setRepositoryConfig(ApplicationRepository $repoConfig) {
        $this->_repoConfig = $repoConfig;
    }

    public function getRepositoryConfig() {
        return $this->_repoConfig;
    }

    public function addDeploymentConfig(ApplicationDeployment $deployConfig) {
        $this->_deploymentConfigs[] = $deployConfig;
    }

    public function getDeploymentConfigs() {
        return $this->_deploymentConfigs;
    }

    public function serialize($format) {
        if($format == 'json') {
            $output = array();
            $output['name'] = $this->_name;
            $output['created'] = $this->_created;
            $output['modified'] = $this->_modified;
            $output['platforms'] = $this->_platforms;
            if (!empty($this->_repoConfig))
                $output['repo'] = $this->_repoConfig->serialize($format);
            if (!empty($this->_deploymentConfigs)) {
                $output['deployConfig'] = array();
                foreach ($this->_deploymentConfigs as $deploy)
                    $output['deployConfig'][] = $deploy->serialize($format);
            }

            return $output;
        }
    }
}


