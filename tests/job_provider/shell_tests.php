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

namespace njq\Tests\JobProvider;

/**
 * Tests for shell job provider
 */
class ShellTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Return test suite
     *
     * @return PHPUnit_Framework_TestSuite
     */
	public static function suite()
	{
		return new \PHPUnit_Framework_TestSuite( __CLASS__ );
	}

    public function testNoJobs()
    {
        $provider = new \njq\ShellJobProvider( array() );
        $this->assertFalse( $provider->hasJobs() );
    }

    public function testNoJobsGetJob()
    {
        $provider = new \njq\ShellJobProvider( array() );
        $this->assertNull( $provider->getNextJob() );
    }

    public function testGetSingleJob()
    {
        $provider = new \njq\ShellJobProvider( array( 'echo 1' ) );
        $this->assertTrue( $provider->hasJobs() );

        $job = $provider->getNextJob();

        $this->assertFalse( $provider->hasJobs() );
    }

    public function testExecuteShellJob()
    {
        $provider = new \njq\ShellJobProvider( array( 'echo "Hello world!"' ) );
        $job = $provider->getNextJob();
        $this->assertSame(
            "Hello world!\n",
            call_user_func( $job )
        );
    }
}

