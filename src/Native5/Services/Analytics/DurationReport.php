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
 *
 * @category  <category> 
 * @package   Native5\Core\<package>
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$ 
 * @link      http://www.docs.native5.com 
 */

namespace Native5\Services\Analytics;

/**
 * DurationReport 
 * 
 * @category  Analytics 
 * @package   Native5\Services\Analytics
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached NOTICE.md for details
 * @version   Release: 1.0 
 * @link      http://www.docs.native5.com 
 * Created : 27-11-2012
 * Last Modified : Fri Dec 21 09:11:53 2012
 */
abstract class DurationReport implements Report 
{

    protected $_duration;

    protected $_filters;

    protected $_aggFilters;

    protected $_data;

    /**
     * Duration Reports 
     * 
     * @access public
     * @return void
     */
    public function getType()
    {
        return Report::TYPE_DURATION;
    }

    /**
     * Gets duration/time interval for which report is to be generated. 
     * 
     * @access public
     * @return void
     */
    public function getDuration()
    {
        if ($this->_duration == null) {
            $startDate = new DateTime();
            $end = new DateTime();
            $end->sub(new DateInterval('P7D'));
            $this->_duration = new Duration($start, $end);
        }
        return $this->_duration;
    }
}
?>
