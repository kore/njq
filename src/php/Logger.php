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
 */

namespace Kore\njq;

/*
 * Interface for loggers
 *
 * Implements the mthods to log the execution.
 */
interface Logger
{
    /**
     * Method called, when the executor run is started
     *
     * @param Executor $executor
     * @return void
     */
    public function startExecutor(Executor $executoar, JobProvider $jobProvider);

    /**
     * Method called, when all jobs are executed
     *
     * @return void
     */
    public function finishedExecutor();

    /**
     * Method called, when all jobs are executed
     *
     * @return void
     */
    public function progressJob($nr);
}
