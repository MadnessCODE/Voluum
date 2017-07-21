<?php

    namespace MadnessCODE\Voluum\Auth;

    /**
     *
     *  Interface for OAuth
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    interface OAuthInterface
    {
        /**
         * Constructor
         */
        public function __construct($username, $password);

        /**
         * Get Authorization type
         *
         * @return string
         */
        public function getAuthType();

        /**
         * Get token
         *
         * @return string
         */
        public function getToken();

        /**
         * Return authentication url
         *
         * @return string
         */
        public function getAuthenticationUrl();

        /**
         * Return authentication data
         *
         * @return array
         */
        public function getAuthenticationData();

        /**
         * Set new token
         *
         * @param string $token
         *
         * @return boolean
         */
        public function setToken($token);
    }