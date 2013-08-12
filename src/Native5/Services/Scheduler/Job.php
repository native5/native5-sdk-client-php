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
 * @category  Scheduling 
 * @package   Native5\Scheduler
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$ 
 * @link      http://www.docs.native5.com 
 */

namespace Native5\Scheduler;

/**
 * Job 
 * 
 * @category  Scheduling 
 * @package   Native5\Scheduler
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached NOTICE.md for details
 * @version   Release: 1.0 
 * @link      http://www.docs.native5.com 
 * Created : 27-11-2012
 * Last Modified : Fri Dec 21 09:11:53 2012
 */
interface Job
{


    /**
     * start 
     * 
     * @param mixed $jobData The Job Data
     * 
     * @access public
     * @return void
     */
    public function start($jobData=null);


    /**
     * stop 
     * 
     * @access public
     * @return void
     */
    public function stop();


    /**
     * pause 
     * 
     * @access public
     * @return void
     */
    public function pause();


    /**
     * resume 
     * 
     * @access public
     * @return void
     */
    public function resume();


    /**
     * isRunning 
     * 
     * @access public
     * @return void
     */
    public function isRunning();


    /**
     * getProgress 
     * 
     * @access public
     * @return void
     */
    public function getProgress();


}//end interface

?>
