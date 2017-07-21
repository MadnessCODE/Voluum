<?php
    namespace MadnessCODE\Voluum\Auth;

    use MadnessCODE\Voluum\Exceptions;

    /**
     *
     * Access key credentials class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class AccessKeyCredentials implements OAuthInterface
    {
        /** @var string $access_key Key */
        protected $access_key;

        /** @var string $access_id id */
        protected $access_id;

        /** @var string $_auth_type Auth type */
        private $_auth_type = 'access_key_credentials';

        /** @var string $token Token */
        protected $token = null;

        /** @var string $authentication_url Authentication url */
        protected $authentication_url = "https://api.voluum.com/auth/access/session";

        /**
         * Constructor
         *
         * @param string $username Username to use for authentication
         * @param string $password Password to authenticate
         *
         * @throws Exceptions\AuthException
         */
        public function __construct($access_id, $access_key)
        {
            if (empty($access_id)) {
                throw new Exceptions\AuthException("Access id is missing.");
            }
            if (empty($access_key)) {
                throw new Exceptions\AuthException("Access key is missing.");
            }

            $this->access_id = $access_id;
            $this->access_key = $access_key;
        }

        /**
         * Get the auth username
         *
         * @return string
         */
        public function getAccessID()
        {
            return $this->access_id;
        }

        /**
         * Get the auth password
         *
         * @return string
         */
        public function getAccessKey()
        {
            return $this->access_key;
        }

        /**
         * Get Auth type
         *
         * @return string
         */
        public function getAuthType()
        {
            return $this->_auth_type;
        }

        /**
         * Get token
         *
         * @return string
         */
        public function getToken()
        {
            return $this->token;
        }

        /**
         * Get Authentication Url
         *
         * @return string
         */
        public function getAuthenticationUrl()
        {
            return $this->authentication_url;
        }

        /**
         * Get authenticatio data
         *
         * @return array
         */
        public function getAuthenticationData()
        {
            return [
                "accessId" => $this->getAccessID(),
                "accessKey" => $this->getAccessKey()
            ];
        }

        /**
         * Set new token
         *
         * @param string $token
         *
         * @return bool
         */
        public function setToken($token)
        {
            if (empty($token)) {
                return false;
            }

            $this->token = $token;

            return true;
        }
    }