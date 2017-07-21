<?php
    namespace MadnessCODE\Voluum\Client;

    use MadnessCODE\Voluum\Auth;

    /**
     *
     *  Interface for client class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */

    interface APIInterface
    {
        /**
         * APIInterface constructor.
         *
         * @param Auth\AuthInterface $auth
         */
        public function __construct(Auth\OAuthInterface $auth);
    }