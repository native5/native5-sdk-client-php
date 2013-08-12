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
 * @category  Analytics 
 * @package   Native5\Services\Analytics
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$ 
 * @link      http://www.docs.native5.com 
 */

namespace Native5\Services\Analytics;

/**
 * Report 
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
interface Report
{

    const TYPE_DURATION = 'Duration';
    const TYPE_USAGE    = 'Usage';

    
    /**
     * gets report id 
     * 
     * @access public
     * @return void
     */
    public function getId();


    /**
     * get Report Type (supported types including : Duration, Usage, Interval) 
     * 
     * @access public
     * @return void
     */
    public function getType();


    /**
     * Name of report 
     * 
     * @access public
     * @return void
     */
    public function getName();


    /**
     * Gets the duration for the report. 
     * 
     * @access public
     * @return void
     */
    public function getDuration();


    /**
     * Get the list of available filters, e.g. duration 
     * 
     * @access public
     *
     * @return List of filters. 
     */
    public function getFilters();


    /**
     * Get Aggregation filters 
     * 
     * @access public
     *
     * @return list of aggregation filters
     */
    public function getAggregationFilters();


    /**
     * Get Report data (only available once report is executed by service,
     * sends data to server).
     * 
     * @access public
     * @return void
     */
    public function getData();

}
?>
