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
class ShellLogger implements Logger
{
    /**
     * Number of jobs to execute
     * 
     * @var int
     */
    protected $count;

    /**
     * Stream to write to
     * 
     * @var resource
     */
    protected $stream;

    /**
     * Visual process indicators
     * 
     * @var array
     */
    protected $processIndicators = array( '|', '/', '-', '\\' );

    /**
     * Construct from output stream to write to
     *
     * Defaults to STDERR.
     * 
     * @param resource $output 
     * @return void
     */
    public function __construct( $output = STDERR )
    {
        $this->stream = $output;
    }

    /**
     * Method called, when the executor run is started
     *
     * @param Executor $executor 
     * @return void
     */
    public function startExecutor( Executor $executoar, JobProvider $jobProvider )
    {
        if ( $jobProvider instanceof \Countable )
        {
            $this->count = count( $jobProvider );
        }
    }

    /**
     * Method called, when all jobs are executed
     * 
     * @return void
     */
    public function finishedExecutor()
    {
        fprint( $this->stream, "\n" );
    }

    /**
     * Method called, when all jobs are executed
     * 
     * @return void
     */
    public function progressJob( $nr )
    {
        if ( $this->count )
        {
            fwrite( $this->stream, sprintf( "   \r% 4d / %d (% 2.2F%%) %s   ",
                $nr + 1,
                $this->count,
                ( $nr + 1 ) / $this->count * 100,
                $this->processIndicators[$nr % count( $this->processIndicators )]
            ) );
        }
        else
        {
            fwrite( $this->stream, sprintf( "   \r% 4d %s   ",
                $nr + 1,
                $this->processIndicators[$nr % count( $this->processIndicators )]
            ) );
        }
    }
}

