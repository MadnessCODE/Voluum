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
    class PasswordCredentialsTest extends \PHPUnit_Framework_TestCase
    {

        /** @var Voluum\Auth\PasswordCredentials */
        private $auth;

        /**
         * This method is called before a test is executed.
         */
        protected function setUp()
        {
            $this->auth = new Voluum\Auth\PasswordCredentials("email", "password");
        }

        /**
         * Test invalid credentials
         */
        public function testInvalidCredentials()
        {
            $this->setExpectedException('\MadnessCODE\Voluum\Exceptions\AuthException');

            new Voluum\Auth\PasswordCredentials("", "password");
            new Voluum\Auth\PasswordCredentials("email", "");
        }

        /**
         * Test get username and password
         */
        public function testNotEmpty()
        {
            $this->assertNotEmpty($this->auth->getUsername());
            $this->assertNotEmpty($this->auth->getPassword());
        }
    }
