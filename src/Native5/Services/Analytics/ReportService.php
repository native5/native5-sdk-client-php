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
 * ReportService 
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
class ReportService extends ApiClient 
{

    private $_logger;

    /**
     * Default constructor. 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_logger = $GLOBALS['logger'];

    }// end __construct() 


    /**
     * List reports which can accept given filters 
     * 
     * @param array $filters array of filters to be used to find/list reports
     *
     * @access public
     * @return Array of Reports
     */
    public function listReports($filters=array())
    {
        $reports = array();

        $path = 'analytics/reports';
        $request =  $this->_remoteServer->get($path);
        $query = $request->getQuery();
        $query->set('filters', implode(';', $filters));
        try{
            $response = $request->send();

        } catch(\Guzzle\Http\Exception\BadResponseException $e) {
            $logger->info($e->getResponse()->getBody('true'), array());
            return false;
        }
 
        $rawReports = $response->json();
        // TODO : Transform raw reports into reports and send back.
        return $reports;

    }//end listReports()


    /**
     * Fetch report with given name. 
     * 
     * @param mixed $name Name of the report. 
     * @param array $opts Options for the report.
     *
     * @access public
     * @return Report Data 
     */
    public function getReport($report)
    {

        if($report == null)
        {
            $report = new UsageReport();
        }

        if (!($report instanceof Report))
            throw new \Exception('Invalid Argument passed, expecting report object');

        $this->_logger->info('Fetching report '.$report->getName());
        
        /*
         *$path = 'analytics/reports/'.$report.getId();
         *$request =  $this->_remoteServer->get($path);
         *$query = $request->getQuery();
         *if(!empty($filters))
         *    $query->set('filters', implode(';', $filters));
         *try{
         *    $response = $request->send();
         *    $report->loadData($response);
         *} catch(\Guzzle\Http\Exception\BadResponseException $e) {
         *    $logger->info($e->getResponse()->getBody('true'), array());
         *    return false;
         *}
         */
        $report->loadData($this->_dummyUsageData());
       return $report; 

    }//end getReport()


   private function _dummyUsageData() {
        $usageData = array();
        $ctr = 0;
        $start = new DateTime();
        while($ctr < 30) {
            $start->sub(new DateInterval('P1D'));
            $usageData[] = json_encode(array($start->format('Y-m-d'), rand(0, 1000)));
            $ctr++;
        }
        return $usageData;
    }
    

}
?>
