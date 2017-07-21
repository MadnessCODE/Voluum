<?php
    namespace MadnessCODE\Voluum\Exceptions;

    /**
     *
     *  Transport exception class
     *
     * @author        <Marko Janosevic> <madness.studio@gmail.com>
     * @copyright (c) MadnessCODE
     *
     *  For the full copyright and license information, please view the LICENSE
     *  file that was distributed with this source code.
     *
     */


    class TransportException extends \Exception
    {
        /**
         * Constructor
         *
         * @param string     $message  Exception message
         * @param int        $code     Exception code
         * @param \Exception $previous Previous
         *
         */
        public function __construct($message = '', $code = 0, $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }