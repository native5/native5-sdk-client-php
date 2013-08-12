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

class ApplicationRepository {
    private $_type;
    private $_url;

    public function setType ($type) {
        $this->_type = $type;
    }

    public function getType () {
        return $this->_type;
    }

    public function setUrl ($url) {
        $this->_url = $url;
    }

    public function getUrl () {
        return $this->_url;
    }

    public function serialize($format) {
        if($format == 'json') {
            $output = array();
            $output['type'] = $this->_type;
            $output['url'] = $this->_url;
            return $output;
        }
    }
}   

