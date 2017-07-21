<?php
    namespace MadnessCODE\Voluum\Test\Response;

    use MadnessCODE\Voluum\Response;

    /**
     *
     *  Single response test class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class SingleTest extends \PHPUnit_Framework_TestCase
    {
        /** @var string $response MadnessCODE\Voluum\Response\Single */
        private $response;

        /** @var string $body */
        private $body = '{"token": "nmB8C_e8rcUXHnEix1ecoHMfCqX_rXdz", "expirationTimestamp": "2017-05-16T20:16:06.714Z", "inaugural": false}';

        /** @var string $bad_request_body */
        private $bad_request_body = '{"error": {"description": "test", "code": "400"}}';

        /** @var int $http_status_ok */
        private $http_status_ok = 200;

        /** @var int $http_status_bad_request */
        private $http_status_bad_request = 400;

        /**
         * This method is called before a test is executed.
         */
        protected function setUp()
        {
            $this->response = new Response\Single($this->http_status_ok, $this->body);
        }

        /**
         * Test getHttpStatus
         */
        public function testGetHttpStatus()
        {
            $this->assertEquals(200, $this->response->getHttpStatus());
        }

        /**
         * Test getData
         */
        public function testGetData()
        {
            $this->assertObjectHasAttribute('token', $this->response->getData());
        }

        /**
         * Test getJSON
         */
        public function testGetJSON()
        {
            $this->assertJson($this->response->getJSON());
        }

        /**
         * Test bad request
         */
        public function testBadRequest()
        {
            $this->setExpectedException('MadnessCODE\Voluum\Exceptions\ResponseException');

            new Response\Single($this->http_status_bad_request, $this->bad_request_body);
        }
    }
