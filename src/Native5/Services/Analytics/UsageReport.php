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
 * UsageReport 
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
class UsageReport extends DurationReport
{

    private static $_NAME = 'Usage Report';


    /**
     * __construct 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_filters     = array();
        $this->_aggFilters  = array();
        $startDate = new DateTime();
        $end = new DateTime();
        $end->sub(new DateInterval('P7D')); // Before 7 days.
        $this->_duration = new Duration($start, $end);

    }//end __construct()


    public function loadData($rawData)
    {
        $data = array();

        foreach($rawData as $rawItem) {
            $item = json_decode($rawItem);
            $data[] = array('time'=>$item->time, 'views' => $item->views);
        }
        $this->_data = $data;
    
    }//end setData()


    /**
     * Gets the data corresponding to the report. 
     * 
     * @access public
     * @return void
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * getName of Report 
     * 
     * @access public
     * @return void
     */
    public function getName()
    {
        return self::$_NAME;
    
    }//end getName()


    /**
     * Gets the list of filters for the report 
     * 
     * @access public
     * @return void
     */
    public function getFilters()
    {
        return $this->_filters;
    }
}
?>
