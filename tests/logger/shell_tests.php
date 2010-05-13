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

namespace njq\Tests\Logger;

class DummyJobProvider implements \njq\JobProvider
{
    protected $jobs = array( 1, 2, 3 );

    public function hasJobs()
    {
        return (bool) count( $this->jobs );
    }

    public function getNextJob()
    {
        $command = array_pop( $this->jobs );

        if ( $command === null )
        {
            return null;
        }

        return function()
        {
            return null;
        };
    }
}

/**
 * Tests for shell job executor
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

    public function tearDown()
    {
        if ( is_file( 'tmp' ) )
        {
            unlink( 'tmp' );
        }
    }

    public function testNoJobs()
    {
        $fp = fopen( 'tmp', 'w' );

        $executor = new \njq\Executor(
            new \njq\ShellLogger( $fp )
        );
        $executor->run(
            new \njq\ShellJobProvider( array() )
        );
        fclose( $fp );

        $this->assertEquals(
            "\n",
            file_get_contents( 'tmp' )
        );
    }

    public function testExecuteSingleJob()
    {
        $fp = fopen( 'tmp', 'w' );

        $executor = new \njq\Executor(
            new \njq\ShellLogger( $fp )
        );
        $executor->run(
            new \njq\ShellJobProvider( array(
                'echo "1"',
            ) )
        );
        fclose( $fp );

        $this->assertEquals(
            "   \r   1 / 1 (100.00%) /   " .
            "\n",
            file_get_contents( 'tmp' )
        );
    }

    public function testExecuteMultipleJobs()
    {
        $fp = fopen( 'tmp', 'w' );

        $executor = new \njq\Executor(
            new \njq\ShellLogger( $fp )
        );
        $executor->run(
            new \njq\ShellJobProvider( array(
                'echo "1"',
                'echo "2"',
                'echo "3"',
            ) )
        );
        fclose( $fp );

        $this->assertEquals(
            "   \r   1 / 3 (33.33%) /   " .
            "   \r   2 / 3 (66.67%) -   " .
            "   \r   3 / 3 (100.00%) \\   " .
            "\n",
            file_get_contents( 'tmp' )
        );
    }

    public function testExecuteNonCountableJobProvider()
    {
        $fp = fopen( 'tmp', 'w' );

        $executor = new \njq\Executor(
            new \njq\ShellLogger( $fp )
        );
        $executor->run( new DummyJobProvider() );
        fclose( $fp );

        $this->assertEquals(
            "   \r   1 /   " .
            "   \r   2 -   " .
            "   \r   3 \\   " .
            "\n",
            file_get_contents( 'tmp' )
        );
    }
}

