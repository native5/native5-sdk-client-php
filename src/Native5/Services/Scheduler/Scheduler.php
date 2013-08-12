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
 * @category  Scheduler
 * @package   Native5\Scheduler
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached LICENSE for details
 * @version   GIT: $gitid$ 
 * @link      http://www.docs.native5.com 
 */

namespace Native5\Scheduler;

/**
 * Scheduler 
 * 
 * @category  Scheduler 
 * @package   Native5\Scheduler
 * @author    Barada Sahu <barry@native5.com>
 * @copyright 2012 Native5. All Rights Reserved 
 * @license   See attached NOTICE.md for details
 * @version   Release: 1.0 
 * @link      http://www.docs.native5.com 
 * Created : 27-11-2012
 * Last Modified : Fri Dec 21 09:11:53 2012
 */
interface Scheduler
{

    const ONE_SHOT  = 1;
    const REPEATING = 2;


    /**
     * Schedules Repeating Job
     *
     * @param : $jobName 	 : Job Name
     * @param : $jobParams : Parameters to be passed to the job
     * @param : $schedule  : Schedule of the repeating job
     *
     * @return $mixed job identifier 
     *
     **/
    public function scheduleRepeatingJob($jobName, $jobParams, $schedule);


    /**
     * Schedule One Time Job
     *
     * @param : $jobName 	 : Job Name
     * @param : $jobParams : Parameters to be passed to the job
     * @param : $time      : Time at which job should be run 
     *
     * @return $mixed job identifer 
     *
     **/
    public function scheduleOneTimeJob($jobName, $jobParams, $time);


    /**
     * removeJob 
     * 
     * @param mixed $jobName Job to remove
     * 
     * @access public
     * @return boolean true if successfully removed 
     */
    public function removeJob($jobName);


    /**
     * Check if job already exists 
     * 
     * @param mixed $jobID Job Identifier.
     * 
     * @access public
     * @return boolean  
     */
    public function exists($jobID);


}//end interface

?>
