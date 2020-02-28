<?php

/*
 * This file is part of the xyqweb/easy-sms.
 *
 * (c) xyqweb <xyqweb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace xyqWeb\sms\Contracts;

use xyqWeb\sms\Support\Config;

/**
 * Class GatewayInterface.
 */
interface GatewayInterface
{
    /**
     * Get gateway name.
     *
     * @return string
     */
    public function getName();

    /**
     * Send a short message.
     *
     * @param \xyqWeb\sms\Contracts\PhoneNumberInterface $to
     * @param \xyqWeb\sms\Contracts\MessageInterface $message
     * @param \xyqWeb\sms\Support\Config $config
     *
     * @return array
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config);
}
