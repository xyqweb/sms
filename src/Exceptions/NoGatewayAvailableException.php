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

use Throwable;

/**
 * Class NoGatewayAvailableException.
 *
 * @author xyqweb <xyqweb@126.com>
 */
class NoGatewayAvailableException extends Exception
{
    /**
     * @var array
     */
    public $results = [];

    /**
     * @var array
     */
    public $exceptions = [];

    /**
     * NoGatewayAvailableException constructor.
     *
     * @param array $results
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(array $results = [], $code = 0, Throwable $previous = null)
    {
        $this->results = $results;
        $this->exceptions = array_column($results, 'exception', 'gateway');

        parent::__construct('All the gateways have failed. You can get error details by `$exception->getExceptions()`', $code, $previous);
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param string $gateway
     *
     * @return mixed|null
     */
    public function getException($gateway)
    {
        return isset($this->exceptions[$gateway]) ? $this->exceptions[$gateway] : null;
    }

    /**
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @return mixed
     */
    public function getLastException()
    {
        return end($this->exceptions);
    }
}
