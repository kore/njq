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

use PHPUnit\Framework\TestCase;

/**
 * Tests for shell job executor
 */
class ExecutorTest extends TestCase
{
    public function tearDown(): void
    {
        if (is_file('tmp')) {
            unlink('tmp');
        }
    }

    public function testExecuteSingleJob()
    {
        $executor = new Executor();
        $executor->run(
            new JobProvider\Shell(array(
                'echo "1" >> tmp',
            ))
        );

        $this->assertTrue(file_exists('tmp'));
        $this->assertEquals(
            "1\n",
            file_get_contents('tmp')
        );
    }

    public function testExecuteMultipleJobs()
    {
        $executor = new Executor();
        $executor->run(
            new JobProvider\Shell(array(
                'sleep 1 && echo "2" >> tmp',
                'echo "1" >> tmp',
            ))
        );

        $this->assertTrue(file_exists('tmp'));
        $this->assertEquals(
            "1\n2\n",
            file_get_contents('tmp')
        );
    }

    public function testExecuteMultipleJobsInParallel()
    {
        $executor = new Executor();
        $executor->run(
            new JobProvider\Shell(array(
                'echo "1" >> tmp',
                'sleep 1 && echo "2" >> tmp',
            ))
        );

        $this->assertTrue(file_exists('tmp'));
        $this->assertEquals(
            "1\n2\n",
            file_get_contents('tmp')
        );
    }

    public function testExecuteMultipleJobsNonParallel()
    {
        $executor = new Executor();
        $executor->run(
            new JobProvider\Shell(array(
                'echo "1" >> tmp',
                'sleep 1 && echo "2" >> tmp',
            )),
            1
        );

        $this->assertTrue(file_exists('tmp'));
        $this->assertEquals(
            "2\n1\n",
            file_get_contents('tmp')
        );
    }
}
