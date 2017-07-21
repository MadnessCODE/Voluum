<?php
    namespace MadnessCODE\Voluum\Auth;

    use MadnessCODE\Voluum\Exceptions;

    /**
     *
     * Password credentials class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class PasswordCredentials implements OAuthInterface
    {
        /** @var string $password Password */
        protected $password;

        /** @var string $username Username */
        protected $username;

        /** @var string $_auth_type Auth type */
        private $_auth_type = 'password_credentials';

        /** @var string $token Token */
        protected $token = null;

        /** @var string $authentication_url Authentication url */
        protected $authentication_url = "https://api.voluum.com/auth/session";

        /**
         * Constructor
         *
         * @param string $username Username to use for authentication
         * @param string $password Password to authenticate
         *
         * @throws Exceptions\AuthException
         */
        public function __construct($username, $password = '')
        {
            if (empty($username)) {
                throw new Exceptions\AuthException("Username is missing.");
            }
            if (empty($password)) {
                throw new Exceptions\AuthException("Password is missing.");
            }

            $this->username = $username;
            $this->password = $password;
        }

        /**
         * Get the auth username
         *
         * @return string
         */
        public function getUsername()
        {
            return $this->username;
        }

        /**
         * Get the auth password
         *
         * @return string
         */
        public function getPassword()
        {
            return $this->password;
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
                "email" => $this->getUsername(),
                "password" => $this->getPassword()
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