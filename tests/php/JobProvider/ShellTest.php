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

namespace Kore\njq\JobProvider;

use PHPUnit\Framework\TestCase;

/**
 * Tests for shell job provider
 */
class ShellTest extends TestCase
{
    public function testNoJobs()
    {
        $provider = new Shell(array());
        $this->assertFalse($provider->hasJobs());
    }

    public function testNoJobsGetJob()
    {
        $provider = new Shell(array());
        $this->assertNull($provider->getNextJob());
    }

    public function testGetSingleJob()
    {
        $provider = new Shell(array( 'echo 1' ));
        $this->assertTrue($provider->hasJobs());

        $job = $provider->getNextJob();

        $this->assertFalse($provider->hasJobs());
    }

    public function testExecuteShellJob()
    {
        $provider = new Shell(array( 'echo "Hello world!"' ));
        $job = $provider->getNextJob();
        $this->assertSame(
            "Hello world!\n",
            call_user_func($job)
        );
    }
}
