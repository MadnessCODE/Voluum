<?php

    namespace MadnessCODE\Voluum\Tests\Exceptions;

    use MadnessCODE\Voluum\Exceptions;

    /**
     *
     * Test class for ResponseException
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class ResponseExceptionTest extends \PHPUnit_Framework_TestCase
    {
        /** @var ResponseException */
        protected $object;

        /**
         * This method is called before a test is executed.
         */
        protected function setUp()
        {
            $this->object = new Exceptions\ResponseException('Test ResponseExceptionTest');
        }

        /**
         * @test
         */
        public function testErrorMessage()
        {
            $this->assertEquals('Test ResponseExceptionTest', $this->object->getMessage());
        }
    }
