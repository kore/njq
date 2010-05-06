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
 * Shell job provider.
 *
 * Constructed from an array of shell commands, it returns those, to all be 
 * executed.
 */
class ShellJobProvider implements JobProvider
{
    /**
     * Array of shell commands, which are provided by this job provider and 
     * therefore executed.
     * 
     * @var array
     */
    protected $shellCmds = array();

    /**
     * Construct from an array of shell commands
     * 
     * @param array $shellCmds 
     * @return void
     */
    public function __construct( array $shellCmds )
    {
        $this->shellCmds = $shellCmds;
    }

    /**
     * Returns if the job provider has more jobs.
     *
     * Returns true, if there are more jobs available in the job provider and 
     * false, if the ally have been executed already.
     * 
     * @return bool
     */
    public function hasJobs()
    {
        return (bool) count( $this->shellCmds );
    }

    /**
     * Get next job from job provider
     *
     * Get the next job from the job provider. Should return a valid callback, 
     * which will then be called in the forked child.
     * 
     * @return Callback
     */
    public function getNextJob()
    {
        $command = array_pop( $this->shellCmds );
        return function() use ( $command )
        {
            shell_exec( $command );
        };
    }
}

