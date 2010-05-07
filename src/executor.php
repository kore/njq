<?php
/**
 * Native PHP job queue
 *
 * This file is part of njq.
 *
 * njq is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation; version 3 of the License.
 *
 * njq is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for
 * more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with njq; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package VCSWrapper
 * @subpackage Core
 * @version $Revision: 954 $
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt LGPLv3
 */

namespace njq;

/*
 * Executor class for job queue
 *
 * Executes all jobs provided by a job provider. Forks multiple process to 
 * execute multiple jobs in the background.
 *
 * Requires the PCNTL extension.
 */
class Executor
{
    /**
     * Run jobs
     *
     * Run all jobs provided by the job provider.
     *
     * Jobs are run parallel in the background. The number of jobs executed in 
     * parallel can be specified as the second parameter.
     *
     * Returns once all jobs have been executed.
     * 
     * @param JobProvider $jobs 
     * @param int $parallel 
     * @return void
     */
    public function run( JobProvider $jobs, $parallel = 4 )
    {
        $forks = array();
        while ( $jobs->hasJobs() ||
                count( $forks ) )
        {

            while ( ( count( $forks ) < $parallel ) &&
                    ( $job = $jobs->getNextJob() ) )
            {
                if ( ( $forks[] = pcntl_fork() ) === 0 )
                {
                    // We are the newly forked child, just execute the job
                    call_user_func( $job );
                    exit( 0 );
                }
            }

            do {
                // Check if the registered jobs are still alive
                if ( $pid = pcntl_wait( $status ) )
                {
                    // Job has finished
                    $jobId = array_search( $pid, $forks );
                    unset( $forks[$jobId] );
                }
            } while ( count( $forks ) >= $parallel );
        }
    }
}

