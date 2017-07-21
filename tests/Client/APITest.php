<?php
    namespace MadnessCODE\Voluum\Tests\Client;

    use MadnessCODE\Voluum;

    /**
     *
     *  Test client class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class APITest extends \PHPUnit_Framework_TestCase
    {

        /** @var $auth MadnessCODE\Voluum\Auth\OAuthInterface */
        private $auth;

        /** @var $client MadnessCODE\Voluum\Client\APIInterface */
        private $client;

        /**
         * This method is called before a test is executed.
         */
        protected function setUp()
        {
            $this->auth = $this->getMockBuilder('MadnessCODE\Voluum\Auth\OAuthInterface')->getMock();
            $this->client = $this->getMockBuilder('MadnessCODE\Voluum\Client\APIInterface')->setConstructorArgs([$this->auth])->getMock();
        }

        /**
         * Test instance
         */
        public function testInstance()
        {
            $this->assertInstanceOf('MadnessCODE\Voluum\Client\APIInterface', $this->client);
        }
    }
