<?php

/*
 * This file is part of the xyqweb/easy-sms.
 *
 * (c) xyqweb <xyqweb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace xyqWeb\sms\Gateways;

use xyqWeb\sms\Contracts\MessageInterface;
use xyqWeb\sms\Contracts\PhoneNumberInterface;
use xyqWeb\sms\Exceptions\GatewayErrorException;
use xyqWeb\sms\Support\Config;
use xyqWeb\sms\Traits\HasHttpRequest;

/**
 * Class KingttoGateWay.
 *
 * @see http://www.kingtto.cn/
 */
class KingXinTongGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'http://101.201.41.194:9999/sms.aspx';

    const ENDPOINT_METHOD = 'send';

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface     $message
     * @param Config               $config
     *
     * @return \Psr\Http\Message\ResponseInterface|array|string
     *
     * @throws GatewayErrorException
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $params = [
            'action' => self::ENDPOINT_METHOD,
            'userid' => $config->get('userid'),
            'account' => $config->get('account'),
            'password' => $config->get('password'),
            'mobile' => $to->getNumber(),
            'content' => $message->getContent(),
        ];

        $result = $this->post(self::ENDPOINT_URL, $params);

        if ('Success' != $result['returnstatus']) {
            throw new GatewayErrorException($result['message'], $result['remainpoint'], $result);
        }

        return $result;
    }
}
