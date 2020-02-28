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
 * Class LuoSiMaoGateway.
 *
 * @see https://luosimao.com/docs/api/
 */
class LuoSiMaoGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_TEMPLATE = 'https://%s.luosimao.com/%s/%s.%s';

    const ENDPOINT_VERSION = 'v1';

    const ENDPOINT_FORMAT = 'json';

    /**
     * @param \xyqWeb\sms\Contracts\PhoneNumberInterface $to
     * @param \xyqWeb\sms\Contracts\MessageInterface     $message
     * @param \xyqWeb\sms\Support\Config                 $config
     *
     * @return array
     *
     * @throws \xyqWeb\sms\Exceptions\GatewayErrorException ;
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $endpoint = $this->buildEndpoint('sms-api', 'send');

        $result = $this->post($endpoint, [
            'mobile' => $to->getNumber(),
            'message' => $message->getContent($this),
        ], [
            'Authorization' => 'Basic '.base64_encode('api:key-'.$config->get('api_key')),
        ]);

        if ($result['error']) {
            throw new GatewayErrorException($result['msg'], $result['error'], $result);
        }

        return $result;
    }

    /**
     * Build endpoint url.
     *
     * @param string $type
     * @param string $function
     *
     * @return string
     */
    protected function buildEndpoint($type, $function)
    {
        return sprintf(self::ENDPOINT_TEMPLATE, $type, self::ENDPOINT_VERSION, $function, self::ENDPOINT_FORMAT);
    }
}
