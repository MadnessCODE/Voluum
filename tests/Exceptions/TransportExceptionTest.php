<?php
    namespace MadnessCODE\Voluum\Test\Exceptions;

    use MadnessCODE\Voluum\Exceptions;

    /**
     *
     * Test class for TransportException
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class TransportExceptionTest extends \PHPUnit_Framework_TestCase
    {
        /** @var TransportException */
        protected $object;

        /**
         * This method is called before a test is executed.
         */
        protected function setUp()
        {
            $this->object = new Exceptions\ResponseException('Test TransportExceptionTest');
        }

        /**
         * @test
         */
        public function testErrorMessage()
        {
            $this->assertEquals('Test TransportExceptionTest', $this->object->getMessage());
        }
    }
