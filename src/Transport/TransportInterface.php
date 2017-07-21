<?php
    namespace MadnessCODE\Voluum\Transport;

    use MadnessCODE\Voluum\Client;
    use MadnessCODE\Voluum\Exceptions;

    /**
     *
     *  Interface for transport
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    interface TransportInterface
    {

        /**
         * TransportInterface constructor.
         */
        public function __construct(Client\APIInterface $client);

        /**
         * Get data
         *
         * @param string $endpoint
         * @param array  $params
         *
         * @return array
         */
        public function get($endpoint, array $params);

        /**
         * Send data
         *
         * @param string $endpoint
         * @param array  $params
         *
         * @return array
         */
        public function post($endpoint, array $params);

        /**
         * Update data
         *
         * @param string $endpoint
         * @param array  $params
         *
         * @return array
         */
        public function put($endpoint, array $params);

        /**
         * Delete data
         *
         * @param string $endpoint
         * @param array  $params
         *
         * @return array
         */
        public function delete($endpoint, array $params);

        /**
         * Get Token
         *
         * @throws Exceptions\AuthException
         *
         * @return boolean
         */
        public function getToken();
    }