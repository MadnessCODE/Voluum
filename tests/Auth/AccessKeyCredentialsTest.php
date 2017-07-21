<?php
    namespace MadnessCODE\Voluum\Tests\Auth;

    use \MadnessCODE\Voluum;

    /**
     *
     *  Test AccessKeys class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class AccessKeyCredentialsTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Voluum\Auth\AccessKeyCredentials */
        private $auth;

        /**
         * This method is called before a test is executed.
         */
        protected function setUp()
        {
            $this->auth = new Voluum\Auth\AccessKeyCredentials("access_id", "access_key");
        }

        /**
         * Test invalid credentials
         */
        public function testInvalidCredentials()
        {
            $this->setExpectedException('\MadnessCODE\Voluum\Exceptions\AuthException');

            new Voluum\Auth\AccessKeyCredentials("", "ACCESS_KEY");
            new Voluum\Auth\AccessKeyCredentials("ACCESS_ID", "");
        }

        /**
         * Test get access id and key
         */
        public function testNotEmpty()
        {
            $this->assertNotEmpty($this->auth->getAccessID());
            $this->assertNotEmpty($this->auth->getAccessKey());
        }
    }
