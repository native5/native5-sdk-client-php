<?php
/**
 *  Copyright 2012 Native5. All Rights Reserved
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *      You may not use this file except in compliance with the License.
 *
 *      Unless required by applicable law or agreed to in writing, software
 *      distributed under the License is distributed on an "AS IS" BASIS,
 *      WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *      See the License for the specific language governing permissions and
 *      limitations under the License.
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

require 'cron.settings.inc';

/**
 * CronScheduler 
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
class CronScheduler implements Scheduler
{

    private $_jobs;


    /**
     * Default Constructor
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_jobs = $this->_initializeJobs();

    }//end __construct()


    /**
     * scheduleOneTimeJob 
     * 
     * @param mixed $jobName   Job Name 
     * @param mixed $jobParams Job Parameters
     * @param mixed $time      Time at which to run the job
     *
     * @access public
     * @return void
     */
    public function scheduleOneTimeJob($jobName, $jobParams, $time)
    {
        return $this->scheduleJob(Scheduler::ONE_SHOT, $jobName, $jobParams, $time);

    }//end scheduleOneTimeJob()


    /**
     * scheduleRepeatingJob 
     * 
     * @param mixed $jobName   Job Name 
     * @param mixed $jobParams Job Parameters
     * @param mixed $schedule  Job Schedule
     *
     * @access public
     * @return void
     */
    public function scheduleRepeatingJob($jobName, $jobParams, $schedule)
    {
        return $this->scheduleJob(
            Scheduler::REPEATING,
            $jobName,
            $jobParams,
            $schedule
        );

    }//end scheduleRepeatingJob()


    /**
     * Schedule a Job 
     * 
     * @param mixed $type      Job Type i.e One Shot or Repeating. 
     * @param mixed $jobName   Name/Identifier for job
     * @param mixed $jobParams Job Parameters
     * @param mixed $schedule  Job Schedule 
     * 
     * @access public
     * @return job identifier 
     * @throws Exception Job Not Found 
     */
    protected function scheduleJob($type, $jobName, $jobParams, $schedule)
    {
        // Check Job has essential params.
        $job = $this->_findJob($jobName);
        if (empty($job) === true) {
            throw new Exception('No Job found with given name');
        }

        $cron           = array();
        $cron['name']   = $job['script'];
        $cron['params'] = implode(',', $jobParams);

        // Throw Exception if no user exists.
        if ($type === Scheduler::ONE_SHOT) {
            $cron['schedule'] = $this->_getATSchedule(
                array('time' => strtotime($schedule['time']))
            );
            $command .= 'echo "';
            $command .= $cron['name'];
            $command .= ' '.$cron['params'];
            $command .= '" | at -t '.$cron['schedule'];
            exec($command, $output, $returnVal);
        } else {
            // Append newline to end as Crontab requires it on *NIX platforms.
            $cronEntry = $this->_getCronSchedule($schedule).' root '.$cron['name'].' '.$cron['params']."\n";
            $cronFile  = $this->_writeCronFile($cronEntry);
            $command   = 'sudo '.CronSettings::CRON_ADD_SCRIPT.' '.__DIR__.'/tmp/'.$cronFile;
            exec($command, $output, $returnVal);
            return $cronFile;
        }

        // TODO : Store Job ID in DB.
        $returnVal = 0;
        return $returnVal;

    }//end scheduleJob()


    /**
     * removeJob 
     * 
     * @param mixed $jobId The Job Identifier
     * 
     * @access public
     * @return void
     */
    public function removeJob($jobId)
    {
        return $this->_removeFromCron($jobId);

    }//end removeJob()


    /**
     * exists 
     * 
     * @param mixed $jobID Job Identifier 
     * 
     * @access public
     * @return void
     */
    public function exists($jobID)
    {
        return file_exists(CronSettings::CRON_HOME.'/'.$jobID);

    }//end exists()


    /**
     * _getCronEntry 
     * 
     * @param mixed $jobName   Job Name
     * @param mixed $jobParams Job Parameters
     *
     * @access private
     * @return void
     */
    private function _getCronEntry($jobName, $jobParams)
    {
        $cronEntry  = '';
        $cronEntry .= $this->_getCronSchedule($schedule);
        $cronEntry .= ' root ';
        $cronEntry .= $cron['name'];
        $cronEntry .= ' '.$cron['params'];
        $cronEntry .= "\n";
        return $cronEntry;

    }//end _getCronEntry()


    /**
     * _removeFromCron  
     * 
     * @param mixed $jobID Job ID to remove
     *
     * @access private
     * @return void
     */
    private function _removeFromCron($jobID)
    {
        exec('sudo '.CronSettings::CRON_DEL_SCRIPT.' '.$jobID, $output, $returnVal);
        return $output;

    }//end _removeFromCron()


    /**
     * _getATSchedule
     * 
     * @param mixed $schedule The schedule for the cron scripts
     * 
     * @access private
     * @return void
     */
    private function _getATSchedule($schedule)
    {
        $year    = date('Y', $schedule['time']);
        $month   = date('m', $schedule['time']);
        $day     = date('d', $schedule['time']);
        $hours   = date('H', $schedule['time']);
        $minutes = date('i', $schedule['time']);
        return $year.$month.$day.$hours.$minutes;

    }//end _getATSchedule()


    /**
     * _getCronSchedule 
     * 
     * @param mixed $schedule The schedule for the cron scripts
     * 
     * @access private
     * @return void
     */
    private function _getCronSchedule($schedule)
    {
        $cronSchedule          = array();
        $cronSchedule['min']   = '*';
        $cronSchedule['hour']  = '*';
        $cronSchedule['dMon']  = '*';
        $cronSchedule['mon']   = '*';
        $cronSchedule['dWeek'] = '*';

        switch($schedule['type']) {
            case 'weekly':
                $cronSchedule['min']   = isset($schedule['minute']) ? $schedule['minute'] : '0';
                $cronSchedule['hour']  = isset($schedule['hour']) ? $schedule['hour'] : '0';
                $cronSchedule['dMon']  = isset($schedule['dMon']) ? $schedule['dMon'] : '*';
                $cronSchedule['mon']   = isset($schedule['mon']) ? $schedule['mon'] : '*';
                $cronSchedule['dWeek'] = isset($schedule['dWeek']) ? $schedule['dWeek'] : '0';
                break;
            case 'monthly':
                $cronSchedule['min']   = isset($schedule['minute']) ? $schedule['minute'] : '0';
                $cronSchedule['hour']  = isset($schedule['hour']) ? $schedule['hour'] : '0';
                $cronSchedule['dMon']  = isset($schedule['dMon']) ? $schedule['dMon'] : '1';
                $cronSchedule['mon']   = isset($schedule['mon']) ? $schedule['mon'] : '*';
                $cronSchedule['dWeek'] = isset($schedule['dWeek']) ? $schedule['dWeek'] : '*';
                break;
            case 'daily':
                $cronSchedule['min']   = isset($schedule['minute']) ? $schedule['minute'] : '0';
                $cronSchedule['hour']  = isset($schedule['hour']) ? $schedule['hour'] : '0';
                $cronSchedule['dMon']  = isset($schedule['dMon']) ? $schedule['dMon'] : '*';
                $cronSchedule['mon']   = isset($schedule['mon']) ? $schedule['mon'] : '*';
                $cronSchedule['dWeek'] = isset($schedule['dWeek']) ? $schedule['dWeek'] : '*';
                break;
        }//end switch

        return implode(' ', $cronSchedule);

    }//end _getCronSchedule()


    /**
     * _initializeJobs 
     * 
     * @access private
     * @return void
     */
    private function _initializeJobs()
    {
        // Read YAML file for jobs.
        $jobs = yaml_parse_file(__DIR__.'/jobs.yaml');
        return $jobs['Jobs'];

    }//end _initializeJobs()


    /**
     * _writeCronFile 
     *
     * @param mixed $cronStatement The cron statement to write.
     *  
     * @access private
     * @return void
     */
    private function _writeCronFile($cronStatement)
    {
        $jobPrefix    = 'job.'.md5($cronStatement);
        $cronFileName = $jobPrefix;
        $cronFilePath = __DIR__.'/tmp/'.$cronFileName;
        $fp = fopen($cronFilePath, 'w');
        fwrite($fp, $cronStatement);
        fclose($fp);
        return $cronFileName;

    }//end _writeCronFile()


    /**
     * _findJob 
     * 
     * @param mixed $jobName Job to find
     * 
     * @access private
     * @return void
     */
    private function _findJob($jobName)
    {
        // Find Job from list of jobs.
        foreach ($this->_jobs as $job) {
            if ($job['name'] === $jobName) {
                return $job;
            }
        }//end foreach

        return null;

    }//end _findJob()


}//end class

?>
