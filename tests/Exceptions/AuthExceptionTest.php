<?php
    namespace MadnessCODE\Voluum\Tests\Exceptions;

    use MadnessCODE\Voluum\Exceptions;

    /**
     *
     * Test class for AuthException
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class AuthExceptionTest extends \PHPUnit_Framework_TestCase
    {
        /** @var AuthException */
        protected $object;

        /**
         * This method is called before a test is executed.
         */
        protected function setUp() {
            $this->object = new Exceptions\AuthException('Test AuthExceptionTest');
        }

        /**
         * @test
         */
        public function testErrorMessage() {
            $this->assertEquals('Test AuthExceptionTest', $this->object->getMessage());
        }
    }
