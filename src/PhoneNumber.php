<?php

/*
 * This file is part of the xyqweb/easy-sms.
 *
 * (c) xyqweb <xyqweb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace xyqWeb\sms;

use xyqWeb\sms\Contracts\PhoneNumberInterface;

/**
 * Class PhoneNumberInterface.
 *
 * @author xyqweb <xyqweb@126.com>
 */
class PhoneNumber implements PhoneNumberInterface
{
    /**
     * @var int
     */
    protected $number;

    /**
     * @var int
     */
    protected $IDDCode;

    /**
     * PhoneNumberInterface constructor.
     *
     * @param int    $numberWithoutIDDCode
     * @param string $IDDCode
     */
    public function __construct($numberWithoutIDDCode, $IDDCode = null)
    {
        $this->number = $numberWithoutIDDCode;
        $this->IDDCode = $IDDCode ? intval(ltrim($IDDCode, '+0')) : null;
    }

    /**
     * 86.
     *
     * @return int
     */
    public function getIDDCode()
    {
        return $this->IDDCode;
    }

    /**
     * 18888888888.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * +8618888888888.
     *
     * @return string
     */
    public function getUniversalNumber()
    {
        return $this->getPrefixedIDDCode('+').$this->number;
    }

    /**
     * 008618888888888.
     *
     * @return string
     */
    public function getZeroPrefixedNumber()
    {
        return $this->getPrefixedIDDCode('00').$this->number;
    }

    /**
     * @param string $prefix
     *
     * @return string|null
     */
    public function getPrefixedIDDCode($prefix)
    {
        return $this->IDDCode ? $prefix.$this->IDDCode : null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUniversalNumber();
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @see  http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->getUniversalNumber();
    }
}
