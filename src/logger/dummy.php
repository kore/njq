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
 * CLI logger
 *
 * Prints the current executor status to STDERR. If the job provider implements 
 * Countable also a progress bar is printed.
 */
class DummyLogger implements Logger
{
    /**
     * Method called, when the executor run is started
     *
     * @param Executor $executor 
     * @return void
     */
    public function startExecutor( Executor $executoar, JobProvider $jobProvider )
    {
        // Intentionally do nothing
    }

    /**
     * Method called, when all jobs are executed
     * 
     * @return void
     */
    public function finishedExecutor()
    {
        // Intentionally do nothing
    }

    /**
     * Method called, when all jobs are executed
     * 
     * @return void
     */
    public function progressJob( $nr )
    {
        // Intentionally do nothing
    }
}

