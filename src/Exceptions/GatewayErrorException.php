<?php

/*
 * This file is part of the xyqweb/easy-sms.
 *
 * (c) xyqweb <xyqweb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace xyqWeb\sms\Exceptions;

/**
 * Class GatewayErrorException.
 */
class GatewayErrorException extends Exception
{
    /**
     * @var array
     */
    public $raw = [];

    /**
     * GatewayErrorException constructor.
     *
     * @param string $message
     * @param int    $code
     * @param array  $raw
     */
    public function __construct($message, $code, array $raw = [])
    {
        parent::__construct($message, intval($code));

        $this->raw = $raw;
    }
}
