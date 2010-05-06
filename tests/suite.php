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

namespace njq\Tests;

require dirname( __FILE__ ) . '/../src/environment.php';

/*
 * Require test suites.
 */
require 'job_provider_suite.php';
require 'executor_suite.php';

/**
* Main test suite
*/
class Suite extends \PHPUnit_Framework_TestSuite
{
    /**
     * Basic constructor for test suite
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setName( 'Native Job queue' );

        $this->addTestSuite( \njq\Tests\JobProvider\Suite::suite() );
        $this->addTestSuite( \njq\Tests\Executor\Suite::suite() );
    }

    /**
     * Return test suite
     * 
     * @return prpTestSuite
     */
    public static function suite()
    {
        return new Suite( __CLASS__ );
    }
}
