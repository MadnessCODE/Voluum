<?php

    namespace MadnessCODE\Voluum\Transport;

    use MadnessCODE\Voluum\Client;
    use MadnessCODE\Voluum\Exceptions;
    use MadnessCODE\Voluum\Response;

    /**
     *
     *  Curl Transport class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class Curl implements TransportInterface
    {

        /**
         * @var string Api url
         */
        protected $url = "https://api.voluum.com/";


        /**
         * @var null Client\APIInterface
         */
        protected $client;

        /**
         * Curl constructor.
         */
        public function __construct(Client\APIInterface $client)
        {
            $this->client = $client;

            if ($this->client->auth->getToken() == null) {
                $this->getToken();
            }
        }

        /**
         * Get data
         *
         * @param string $endpoint
         * @param array  $params
         *
         * @return array
         */
        public function get($endpoint, array $params)
        {
            return $this->curl($this->url . $endpoint, "GET", $params);
        }

        /**
         * Send data
         *
         * @param string $endpoint
         * @param array  $params
         *
         * @return array
         */
        public function post($endpoint, array $params)
        {
            return $this->curl($this->url . $endpoint, "POST", $params);
        }


        /**
         * Update data
         *
         * @param string $endpoint
         * @param array  $params
         *
         * @return array
         */
        public function put($endpoint, array $params)
        {
            return $this->curl($this->url . $endpoint, "PUT", $params);
        }

        /**
         * Delete data
         *
         * @param string $endpoint
         * @param array  $params
         *
         * @return array
         */
        public function delete($endpoint, array $params)
        {
            return $this->curl($this->url . $endpoint, "DELETE", $params);
        }

        /**
         * Get Token
         *
         * @throws Exceptions\AuthException
         *
         * @return boolean
         */
        public function getToken()
        {
            $response = $this->curl($this->client->auth->getAuthenticationUrl(), "POST", $this->client->auth->getAuthenticationData(), true);

            if (isset($response->getData()->token)) {
                $this->client->auth->setToken($response->getData()->token);
            } else {
                throw new Exceptions\AuthException("Invalid token");
            }

            return true;
        }


        /**
         * Curl
         *
         * @param string $url            Url
         * @param string $request_method Request method
         * @param array  $params         Parameters
         * @param bool   $token          Token
         *
         * @return Response\Single
         * @throws Exceptions\AuthException
         * @throws Exceptions\TransportException
         */
        private function curl($url, $request_method, $params, $token = false)
        {
            $curl = curl_init();
            $header = [
                "Content-Type: application/json; charset=utf-8",
                "Accept: application/json"
            ];

            if (count($params) && $request_method == "GET") {
                $query = http_build_query($params);
                $query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $query);
                $url .= '/?' . $query;
            }

            if (!$token) {
                $header[] = "cwauth-token: " . $this->client->auth->getToken();
            }

            $setopt_array = array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => $request_method,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_HTTPHEADER => $header
            );

            curl_setopt_array($curl, $setopt_array);

            $response = curl_exec($curl);
            $error = curl_error($curl);
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($error) {
                if ($token == false) {
                    throw new Exceptions\AuthException($error);
                } else {
                    throw new Exceptions\TransportException($error);
                }
            }

            return new Response\Single($http_status, $response);
        }
    }