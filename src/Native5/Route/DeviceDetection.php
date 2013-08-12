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
 * @category  Route 
 * @package   Native5\Route
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$ 
 * @link      http://www.docs.native5.com 
 */

namespace Native5\Route;

require 'UAParser.php';

/**
 * DeviceDetection 
 * 
 * @category  Route 
 * @package   Native5\Route
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached NOTICE.md for details
 * @version   Release: 1.0 
 * @link      http://www.docs.native5.com 
 * Created : 27-11-2012
 * Last Modified : Fri Dec 21 09:11:53 2012
 */
class DeviceDetection
{
    const HOST = "localhost";
    const DB = "native5";

    /**
     * Categorize Incoming Request  
     * 
     * @access public
     * @return category of request, typically of the order M00X. 
     */
    public function determineCategory() {
        // FIXME: Hardcoded category
        return 'M0101';

        $logger = $GLOBALS['logger'];
        try {
            $ua = \UA::parse();
            $browser = $this->_findMatch($ua);
            return $this->_computeCategory($browser);
        } catch (\Exception $ex) {
            $logger->info('No device database found, defaulting to local');
            return 'M0101';
        }
    }


    /**
     * Finds closest match given the user agent 
     * 
     * @param mixed $ua : User Agent Object parsed using UA 
     * @access private
     * @return void
     */
    private function _findMatch($ua) {
        $browser = new Browser();
        $browser->setGrade(Grades::UNSUPPORTED);

        $conn = new \Mongo('mongodb://'.DeviceDetection::HOST);
        $collection = $conn->selectDB(DeviceDetection::DB)->devices;

        // Determine Regex fitting the U/A Category. 
        $pattern = $this->_getMatchingRegex($ua);
        $uaRegex = new \MongoRegex($pattern);

        //$device = $collection->findOne(array("userAgent"=>$nameRegex));
        // Match User Agent independent of OS Version, Check OS Version >= Base Matching Version
        $device = $collection->findOne(
            array(
                '$and'=>array(
                    array("userAgent"=>$uaRegex), 
                    array('$or'=> array(
                        array("device.os.version"=>array('$exists'=>false)),
                        array("device.os.version"=>array('$lte'=>"".$ua->osVersion))
                    ))
                )
            )
        );
        if(!empty($device)) {
            $browser = $this->_createBrowser($device);
        } else {
            // TODO : Insert into DB & spawn Categorization task.
        }
        return $browser;
    }

    private function _getMatchingRegex($userAgent) {
        //$pattern = '/Mobile\/.* /';
        //$patternToSearch = preg_replace($pattern, 'Mobile/.* ',$ua->uaOriginal);
        //$patternToSearch = $ua->uaOriginal;
        //$patternToSearch = preg_replace('/\./', '\.',$patternToSearch);
        //$patternToSearch = preg_replace('/[\.]+[^\*\s]/', '.*',$patternToSearch);
        //echo $patternToSearch.'<hr>';
        $patternToSearch = preg_replace('/\d[\.]*[^\s\/]*/', '.',$userAgent->uaOriginal);
        $patternToSearch = preg_replace('/[\.\*]+/', '.*',$patternToSearch);
        $patternToSearch = str_replace(array('(', ')'), '.', $patternToSearch);
        $patternToSearch = '/'.$patternToSearch.'/';
        return $patternToSearch;	
    }

    private function _createBrowser($res) {
        $device = new Device($res['device']['name'], null, null, $res['device']['type']);
        $browser = new Browser($res['name'], $res['version'], null, null, $device);
        $browser->setGrade($res['grade']);
        return $browser;
    }

    // TODO: Browsers have been statically categorized,
    // Use dynamic categorization based on supported features 
    private function _computeCategory($browser) {
        return $browser->getGrade();
    }

}
?>
