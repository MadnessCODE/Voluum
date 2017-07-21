<?php
    namespace MadnessCODE\Voluum\Response;

    use MadnessCODE\Voluum\Exceptions;

    /**
     *
     *  Response class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    abstract class Response
    {
        /** @var array $response_data Response data */
        protected $response = [
            "http_status" => null,
            "data" => null
        ];

        /**
         * @var array $api_response_codes_success Api response codes success
         */
        protected $api_response_codes_success = [200, 201, 202];

        /**
         * Response constructor.
         *
         * @param int    $http_status HTTP status code
         * @param string $response    Response
         *
         * @throws Exceptions\ResponseException
         *
         * @return void
         */
        public function __construct($http_status, $response)
        {
            $response_obj = json_decode($response);
            if (!in_array($http_status, $this->api_response_codes_success)) {
                throw new Exceptions\ResponseException($response_obj->error->description, (int)$response_obj->error->code);
            }

            $this->setResponse($http_status, $response);
        }

        /**
         * Set response properties values
         *
         * @param $http_status
         * @param $data
         *
         * @return void
         */
        public function setResponse($http_status, $data)
        {
            $this->response["http_status"] = $http_status;
            $this->response["data"] = $data;
        }

        /**
         * Return the HTTP status
         *
         * @return int
         */
        public function getHttpStatus()
        {
            return $this->response["http_status"];
        }

        /**
         * Get response as object
         *
         * @return Object
         */
        public function getData()
        {
            return json_decode($this->response["data"]);
        }

        /**
         * Get response as json
         *
         * @return string
         */
        public function getJSON()
        {
            return $this->response["data"];
        }
    }