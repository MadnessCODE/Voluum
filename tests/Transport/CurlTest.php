<?php
    namespace MadnessCODE\Voluum\Test\Transport;

    use MadnessCODE\Voluum;

    /**
     *
     *  Test Curl class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class CurlTest extends \PHPUnit_Framework_TestCase
    {

        /** @var Voluum\Transport\Curl $voluum_api */
        private $voluum_api;


        /** @var Voluum\Client\APIInterface $client */
        private $client;

        /** @var Voluum\Response\Single $fake_json_response */
        private $fake_json_response;

        /**
         * This method is called before a test is executed.
         */
        protected function setUp()
        {
            $this->fake_json_response = new Voluum\Response\Single(200, '{"token": "nmB8C_e8rcUXHnEix1ecoHMfCqX_rXdz", "expirationTimestamp": "2017-05-16T20:16:06.714Z", "inaugural": false}');

            $auth = new Voluum\Auth\PasswordCredentials("testEmail", "testPassword");
            $auth->setToken("testToken");

            $this->client = $this->getMockBuilder('MadnessCODE\Voluum\Client\API')->setConstructorArgs([$auth])->getMock();
            $this->voluum_api = $this->getMockBuilder('MadnessCODE\Voluum\Transport\Curl')->setMethods(['post', 'get', 'put', 'delete'])->setConstructorArgs([$this->client])->getMock();
        }

        /**
         * Test get
         */
        public function testGet()
        {
            $this->voluum_api->expects($this->once())->method('get')->willReturn($this->fake_json_response);

            $this->assertInstanceOf('MadnessCODE\Voluum\Response\Single', $this->voluum_api->get("report", [
                "from" => date("Y-m-d"),
                "to" => date("Y-m-d"),
                "groupBy" => "campaign",
            ]));
        }

        /**
         * Test post
         */
        public function testPost()
        {
            $this->voluum_api->expects($this->once())->method('post')->willReturn($this->fake_json_response);

            $this->assertInstanceOf('MadnessCODE\Voluum\Response\Single', $this->voluum_api->post("lander", [
                "namePostfix" => "Test",
                "url" => "http://example.com"
            ]));
        }

        /**
         * Test put
         */
        public function testPut()
        {
            $this->voluum_api->expects($this->once())->method('put')->willReturn($this->fake_json_response);

            $this->assertInstanceOf('MadnessCODE\Voluum\Response\Single', $this->voluum_api->put("lander/xxxxx-xxxxxx-xxxxxx-xxxxx", [
                "namePostfix" => "Test 1",
                "url" => "http://example.com"
            ]));
        }

        /**
         * Test delete
         */
        public function testDelete()
        {
            $this->voluum_api->expects($this->once())->method('delete')->willReturn($this->fake_json_response);

            $this->assertInstanceOf('MadnessCODE\Voluum\Response\Single', $this->voluum_api->delete("lander/xxxxx-xxxxxx-xxxxxx-xxxxx", [
                "ids" => "xxxxx-xxxxxx-xxxxxx-xxxxx"
            ]));
        }
    }
