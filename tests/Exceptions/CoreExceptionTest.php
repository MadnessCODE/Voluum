<?php
    namespace MadnessCODE\Voluum\Tests\Exceptions;

    use MadnessCODE\Voluum\Exceptions;

    /**
     *
     * Test class for CoreException
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class CoreExceptionTest extends \PHPUnit_Framework_TestCase
    {
        /** @var CoreException */
        protected $object;

        /**
         * This method is called before a test is executed.
         */
        protected function setUp()
        {
            $this->object = new Exceptions\CoreException('Test CoreExceptionTest');
        }

        /**
         * @test
         */
        public function testErrorMessage()
        {
            $this->assertEquals('Test CoreExceptionTest', $this->object->getMessage());
        }
    }
