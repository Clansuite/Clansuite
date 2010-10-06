<?php
    /**
     * Clansuite - just an eSports CMS
     * Jens-André Koch © 2005 - onwards
     * http://www.clansuite.com/
     *
     * This file is part of "Clansuite - just an eSports CMS".
     *
     * LICENSE:
     *
     *    This program is free software; you can redistribute it and/or modify
     *    it under the terms of the GNU General Public License as published by
     *    the Free Software Foundation; either version 2 of the License, or
     *    (at your option) any later version.
     *
     *    This program is distributed in the hope that it will be useful,
     *    but WITHOUT ANY WARRANTY; without even the implied warranty of
     *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *    GNU General Public License for more details.
     *
     *    You should have received a copy of the GNU General Public License
     *    along with this program; if not, write to the Free Software
     *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
     *
     * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
     * @author     Jens-André Koch <vain@clansuite.com>
     * @copyright  Jens-André Koch (2005 - onwards)
     * @link       http://www.clansuite.com
     * 
     * @version    SVN: $Id$
     */

# Security Handler
/**
 * f (defined('IN_CS') === false)
 {
 * ie('Clansuite not loaded. Direct Access forbidden.');
 }
 */
$cron = new Clansuite_Cronjobs;

/**
 * Clansuite Cronjobs is a service wrapper class for stack processing of regular tasks.
 *
 * This is a fork of Kai Blankenhorn's pseudo-cron v1.3
 * (c) 2003,2004 Kai Blankenhorn, www.bitfolge.de/pseudocron, <kaib@bitfolge.de>
 * (c) 2008,2009 Jens-André Koch, www.clansuite.com, <jakoch@web.de>
 *
 * Usually regular tasks like backup up the site's database are run using cron
 * jobs. With cron jobs, you can exactly plan when a certain command is to be
 * executed. But most homepage owners can't create cron jobs on their web
 * server – providers demand some extra money for that.
 * The only thing that's certain to happen quite regularly on a web page are
 * page requests. This is where pseudo-cron comes into play: With every page
 * request it checks if any cron jobs should have been run since the previous
 * request. If there are, they are run and logged.
 *
 * Pseudo-cron uses a syntax very much like the Unix cron's one. For an
 * overview of the syntax used, see a page of the UNIXGEEKS. The syntax
 * pseudo-cron uses is different from the one described on that page in
 * the following points:
 *
 *   -  there is no user column
 *   -  the executed command has to be an include()able file (which may contain further PHP code)
 *
 *
 * All job definitions are made in a text file on the server with a
 * user-definable name. A valid command line in this file is, for example:
 *
 *          *   2   1,15    *   *   samplejob.inc.php
 *
 * This runs samplejob.inc.php at 2am on the 1st and 15th of each month.
 *
 * Features:
 * ---------
 *  -  runs any PHP script
 *  -  periodical or time-controlled script execution
 *  -  logs all executed jobs
 *  -  can be run from an IMG tag in an HTML page
 *  -  follow Unix cron syntax for crontabs
 *
 * Usage:
 * ------
 * -  Modify the variables in the config section below to match your server.
 * -  Write a PHP script that does the job you want to be run regularly. Be
 *    sure that any paths in it are relative to pseudo-cron.
 * -  Set up your crontab file with your script
 * -  put an include("pseudo-cron.inc.php"); statement somewhere in your most
 *      accessed page or call pseudo-cron-image.php from an HTML img tag
 * -  Wait for the next scheduled run :)
 *
 * Note:
 * ------
 * You can log messages to pseudo-cron's log file from cron jobs by calling:
 *  logMessage("log a message");
 *
 * Release notes for v1.2.2:
 * -------------------------
 *
 * This release changed the way cron jobs are called. The file paths you specify in
 * the crontab file are now relative to the location of pseudo-cron.inc.php, instead
 * of to the calling script. Example: If /include/pseudo-cron.inc.php is included
 * in /index.php and your cronjobs are in /include/cronjobs, then your crontab file
 * looked like this:
 *
 * 10    1    *    *    *    include/cronjobs/dosomething.php    # do something
 *
 * Now you have to change it to
 *
 * 10    1    *    *    *    cronjobs/dosomething.php            # do something
 *
 * After you install the new version, each of your cronjobs will be run once,
 * and the .job files will have different names than before.
 *
 * ----------
 * Changelog:
 * ----------
 *
 * v1.4 10-05-2008
 *  fork:     removed globals, added constant for debugging
 *            added handlers for cronjobs from file and from database
 *
 * v1.3    06-15-2004
 *     added:      the number of jobs run during one call of pseudocron
 *               can now be limited.
 *     added:      additional script to call pseudocron from an HTML img tag
 *     improved: storage of job run times
 *     fixed:    bug with jobs marked as run although they did not complete
 *
 * v1.2.2 01-17-2004
 *     added:      send an email for each completed job
 *     improved: easier cron job configuration (relative to pseudo-cron, not
 *               to calling script. Please read the release notes on this)
 *
 * v1.2.1 02-03-2003
 *     fixed:     jobs may be run too often under certain conditions
 *     added:     global debug switch
 *     changed: typo in imagecron.php which prevented it from working
 *
 * v1.2    01-31-2003
 *     added:   more documentation
 *     changed: log file should now be easier to use
 *     changed: log file name
 *
 * v1.1    01-29-2003
 *     changed: renamed pseudo-cron.php to pseudo-cron.inc.php
 *     fixed:   comments at the end of a line don't work
 *     fixed:   empty lines in crontab file create nonsense jobs
 *     changed: log file grows big very quickly
 *     changed: included config file in main file to avoid directory confusion
 *     added:   day of week abbreviations may now be used (three letters, english)
 *
 * v1.0    01-17-2003
 *     initial release
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cron
 */
class Clansuite_Cronjobs
{
    # Setting: How to load cronjobs from FILE or DB

    private $loadCronjobsFrom = 'FILE';

    # Constants
    const const_PC_MINUTE = '1';
    const const_PC_HOUR = '2';
    const const_PC_DOM = '3';
    const const_PC_MONTH = '4';
    const const_PC_DOW = '5';
    const const_PC_CMD = '7';
    const const_PC_COMMENT = '8';
    const const_PC_CRONLINE = '20';

    /**
     * The file that contains the job descriptions.
     * For a description of the format, see
     * @link http://www.unixgeeks.org/security/newbie/unix/cron-1.html
     * @link http://www.bitfolge.de/pseudocron
     */
    private $cronTabFile = '';

    /**
     * The directory where the script can store information on completed jobs and its log file.
     * include trailing slash
     */
    private $writeDirectory = '';
    private $runMaximalJobs = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cronTabFile = ROOT_CORE . 'cronjobs/crontab.txt';
        $this->writeDirectory = ROOT_CORE . 'cronjobs/';
        $this->execute();
    }

    /**
     * loadCronjobs from file or db
     */
    public function loadCronjobs()
    {
        $cronjobs = '';

        if($this->loadCronjobsFrom == 'FILE')
        {
            $cronjobs = $this->parseCronFile($this->cronTabFile);
        }
        # else { get cronjobs from alternative resource: database, etc. }

        return $cronjobs;
    }

    /**
     * Process all registered cronjobs
     */
    public function execute()
    {
        $cronjobs = $this->loadCronjobs();
        #var_dump($cronjobs);

        $jobsRun = 0;
        foreach($cronjobs as $cronjob)
        {
            if($this->runMaximalJobs == 0 or $jobsRun < $this->runMaximalJobs)
            {
                #echo "Executing $cronjob";
                $this->runJob($cronjob);
            }
            $jobsRun++;
        }
    }

    /**
     * parseElement
     *
     * @param $element
     * @param $targetArray
     * @param $numberOfElements
     */
    private function parseElement($element, &$targetArray, $numberOfElements)
    {
        $subelements = explode(',', $element);

        for($i = 0; $i < $numberOfElements; $i++)
        {
            $targetArray[$i] = $subelements[0] == '*';
        }

        $nr_subelements = 0;
        $nr_subelements = count($subelements);
        for($i = 0; $i < $nr_subelements; $i++)
        {
            if(preg_match('~^(\\*|([0-9]{1,2})(-([0-9]{1,2}))?)(/([0-9]{1,2}))?$~', $subelements[$i], $matches))
            {
                if($matches[1] == '*')
                {
                    $matches[2] = 0;                    # from
                    $matches[4] = $numberOfElements;    #to
                }
                elseif($matches[4] == '')
                {
                    $matches[4] = $matches[2];
                }

                if($matches[5][0] != '/')
                {
                    $matches[6] = 1;        # step
                }

                $a = $this->leftTrimZeros($matches[2]);
                $b = $this->leftTrimZeros($matches[4]);
                $c = $this->leftTrimZeros($matches[6]);

                for($j = $a; $j<=$b; $j+=$c)
                {
                    $targetArray[$j] = true;
                }
            }
        }
    }

    /**
     * multisort asc/desc
     */
    private function multisort(&$array, $sortby, $order='asc')
    {
        foreach($array as $val)
        {
            $sortarray[] = $val[$sortby];
        }

        $c = $array;

        if($order == 'asc')
        {
            $const = SORT_ASC;
        }
        else
        {
            $const = SORT_DESC;
        }

        $s = array_multisort($sortarray, $const, $c, $const);
        $array = $c;

        return $s;
    }

    /**
     * leftTrimZeros
     */
    private function leftTrimZeros($number)
    {
        while($number[0] == '0')
        {
            $number = mb_substr($number, 1);
        }
        return $number;
    }

    private function incrementDate(&$dateArr, $amount, $unit)
    {
        /* if ($debug)
         {
         * cho sprintf('Increasing from %02d.%02d. %02d:%02d by %d %6s ',
         * dateArr[mday],$dateArr[mon],$dateArr[hours],$dateArr[minutes],$amount,$unit);
         } */

        if($unit == 'mday')
        {
            $dateArr['hours'] = 0;
            $dateArr['minutes'] = 0;
            $dateArr['seconds'] = 0;
            $dateArr['mday'] += $amount;
            $dateArr['wday'] += $amount % 7;
            if($dateArr['wday'] > 6)
            {
                $dateArr['wday']-=7;
            }

            $months28 = Array(2);
            $months30 = Array(4, 6, 9, 11);
            $months31 = Array(1, 3, 5, 7, 8, 10, 12);

            if(
                    (in_array($dateArr['mon'], $months28) && $dateArr['mday']==28) ||
                    ( in_array($dateArr['mon'], $months30) && $dateArr['mday']==30) ||
                    ( in_array($dateArr['mon'], $months31) && $dateArr['mday']==31)
            )
            {
                $dateArr['mon']++;
                $dateArr['mday'] = 1;
            }
        }
        elseif($unit == 'hour')
        {
            if($dateArr['hours']==23)
            {
                $this->incrementDate($dateArr, 1, 'mday');
            }
            else
            {
                $dateArr['minutes'] = 0;
                $dateArr['seconds'] = 0;
                $dateArr['hours']++;
            }
        }
        elseif($unit == 'minute')
        {
            if($dateArr['minutes']==59)
            {
                $this->incrementDate($dateArr, 1, 'hour');
            }
            else
            {
                $dateArr['seconds'] = 0;
                $dateArr['minutes']++;
            }
        }

        #if ($debug) echo sprintf('to %02d.%02d. %02d:%02d',$dateArr[mday],$dateArr[mon],$dateArr[hours],$dateArr[minutes]).CR;
    }

    /**
     * runJob
     *
     * @params $job Job to run
     * @return boolean
     */
    private function runJob($job)
    {
        $lastActual = $job['lastActual'];
        $lastScheduled = $job['lastScheduled'];

        if($lastScheduled < time())
        {
            #echo '<br>Running     '.$job[self::const_PC_CRONLINE];
            #echo '<br> Last run:       '.date('r',$lastActual);
            #echo '<br> Last scheduled: '.date('r',$lastScheduled);
            #Clansuite_Logger::writeLog('Running     '.$job[self::const_PC_CRONLINE]);
            #Clansuite_Logger::writeLog('  Last run:       '.date('r',$lastActual));
            #Clansuite_Logger::writeLog('  Last scheduled: '.date('r',$lastScheduled));

            /* if ($debug)
             {
             */
            include dirname(__FILE__) . '/' . $job[self::const_PC_CMD];

            $jobname = mb_substr($job[self::const_PC_CMD], 9, -12);

            # instantiate job
            $classname = 'Clansuite_Cronjob_' . ucfirst($jobname);
            $job_object = new $classname;
            # execute
            $job_object->execute();


            /* }
             * lse
             {
             * nclude($job[self::const_PC_CMD]);
             } */

            $this->markLastRun($job[self::const_PC_CMD], $lastScheduled);

            #echo 'Completed    '.$job[self::const_PC_CRONLINE];

            /* @todo log
             * ogMessage('Completed    '.$job[self::const_PC_CRONLINE]);
             * f ($sendLogToEmail != '')
             {
             * ail($sendLogToEmail, '[cron] '.$job[self::const_PC_COMMENT], $resultsSummary);
             }
             */

            return true;
        }
        else
        {
            if($debug)
            {
                Clansuite_Logger::writeLog('Skipping     ' . $job[self::const_PC_CRONLINE]);
                Clansuite_Logger::writeLog('  Last run:       ' . date('r', $lastActual));
                Clansuite_Logger::writeLog('  Last scheduled: ' . date('r', $lastScheduled));
                Clansuite_Logger::writeLog('Completed    ' . $job[self::const_PC_CRONLINE]);
            }

            return false;
        }
    }

    /**
     * Parses a Crontab File
     *
     * @param $cronTabFile
     * @return $jobs array
     */
    private function parseCronFile($cronTabFile)
    {
        #echo $cronTabFile;
        # incomming file
        $file = file($cronTabFile);

        # init
        $job = array();
        $jobs = array();

        $file_count = count($file);

        for($i = 0; $i < $file_count; $i++)
        {
            if($file[$i][0]!='#')
            {
                #old regex, without dow abbreviations:
                #if (preg_match("~^([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-7,/*]+|Sun|Mon|Tue|Wen|Thu|Fri|Sat)\\s+([^#]*)(#.*)?$~i",$file[$i],$job)) {
                if(preg_match('~^([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-7,/*]+|(-|/|Sun|Mon|Tue|Wed|Thu|Fri|Sat)+)\\s+([^#]*)\\s*(#.*)?$~i', $file[$i], $job))
                {
                    $jobNumber = count($jobs);
                    $jobs[$jobNumber] = $job;
                    if($jobs[$jobNumber][self::const_PC_DOW][0]!='*' and ! is_numeric($jobs[$jobNumber][self::const_PC_DOW]))
                    {
                        $jobs[$jobNumber][self::const_PC_DOW] = str_replace(
                                        array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'),
                                        array(0, 1, 2, 3, 4, 5, 6),
                                        $jobs[$jobNumber][self::const_PC_DOW]);
                    }
                    $jobs[$jobNumber][self::const_PC_CMD] = trim($job[self::const_PC_CMD]);
                    $jobs[$jobNumber][self::const_PC_COMMENT] = trim(mb_substr($job[self::const_PC_COMMENT], 1));
                    $jobs[$jobNumber][self::const_PC_CRONLINE] = $file[$i];
                }

                $jobfile = $this->getJobFileName($jobs[$jobNumber][self::const_PC_CMD]);

                $jobs[$jobNumber]['lastActual'] = $this->getLastActualRunTime($jobs[$jobNumber][self::const_PC_CMD]);
                $jobs[$jobNumber]['lastScheduled'] = $this->getLastScheduledRunTime($jobs[$jobNumber]);
            }
        }

        $this->multisort($jobs, 'lastScheduled');

        # Debug Display
        /* if (defined('DEBUG') and DEBUG == true)
         {
         * ar_dump($jobs);
         } */

        return $jobs;
    }

    function getLastScheduledRunTime($job)
    {
        $extjob = Array();

        $this->parseElement($job[self::const_PC_MINUTE], $extjob[self::const_PC_MINUTE], 60);
        $this->parseElement($job[self::const_PC_HOUR], $extjob[self::const_PC_HOUR], 24);
        $this->parseElement($job[self::const_PC_DOM], $extjob[self::const_PC_DOM], 31);
        $this->parseElement($job[self::const_PC_MONTH], $extjob[self::const_PC_MONTH], 12);
        $this->parseElement($job[self::const_PC_DOW], $extjob[self::const_PC_DOW], 7);

        $dateArr = getdate($this->getLastActualRunTime($job[self::const_PC_CMD]));

        $minutesAhead = 0;
        while($minutesAhead < 525600 and
        ( !$extjob[self::const_PC_MINUTE][$dateArr['minutes']] or
        ! $extjob[self::const_PC_HOUR][$dateArr['hours']] or
        ( !$extjob[self::const_PC_DOM][$dateArr['mday']] or ! $extjob[self::const_PC_DOW][$dateArr['wday']]) or
        ! $extjob[self::const_PC_MONTH][$dateArr['mon']])
        )
        {

            if(!$extjob[self::const_PC_DOM][$dateArr['mday']] or ! $extjob[self::const_PC_DOW][$dateArr['wday']])
            {
                $this->incrementDate($dateArr, 1, 'mday');
                $minutesAhead+=1440;
                continue;
            }

            if(!$extjob[self::const_PC_HOUR][$dateArr['hours']])
            {
                $this->incrementDate($dateArr, 1, 'hour');
                $minutesAhead+=60;
                continue;
            }

            if(!$extjob[self::const_PC_MINUTE][$dateArr['minutes']])
            {
                $this->incrementDate($dateArr, 1, 'minute');
                $minutesAhead++;
                continue;
            }
        }

        //if ($debug) print_r($dateArr);

        return mktime($dateArr['hours'], $dateArr['minutes'], 0, $dateArr['mon'], $dateArr['mday'], $dateArr['year']);
    }

    private function getJobFileName($jobname)
    {
        $jobfile = $this->writeDirectory . urlencode($jobname) . '.job';
        return $jobfile;
    }

    private function getLastActualRunTime($jobname)
    {
        $jobfile = $this->getJobFileName($jobname);
        if(is_file($jobfile) === true)
        {
            return filemtime($jobfile);
        }
        return 0;
    }

    private function markLastRun($jobname, $lastRun)
    {
        $jobfile = $this->getJobFileName($jobname);
        touch($jobfile);
    }

}

/**
 * Interface for Clansuite_Cronjob
 */
interface Clansuite_Cronjob_Interface
{
    function execute();
}
?>