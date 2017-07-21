<?php
    namespace MadnessCODE\Voluum;

    use MadnessCODE\Voluum\Exceptions;

    /**
     *
     *  Core class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */
    class Core
    {
        /**
         *
         * Magic call
         *
         * @param $method
         * @param $params
         *
         * @return Response\Single
         * @throws Exceptions\CoreException
         */
        public function __call($method, $params)
        {
            if (!method_exists($this->client->transport, $method)) {
                throw new Exceptions\CoreException("Method does not exist.");
            }

            return call_user_func_array([$this->client->transport, $method], $params);
        }
    }