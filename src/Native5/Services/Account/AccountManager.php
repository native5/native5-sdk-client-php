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
 * @category  Native5 App Account Services
 * @package   Native5\Accounts
 * @author    Shamik Datta <shamik@native5.com>
 * @copyright 2013 Native5. All Rights Reserved 
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$ 
 * @link      http://www.docs.native5.com 
 */

namespace Native5\Services\Account;

/**
 * AccountManager 
 * 
 * @version $id$
 * @author  Shamik Datta <shamik@native5.com>
 */
interface AccountManager {
    public function getApps($code);
    public function getAccountDetails($code);
    public function createApp($code, $name, $options);
}


