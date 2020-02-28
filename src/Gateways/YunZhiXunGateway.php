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
 * Class YunZhiXunGateway.
 *
 * @author her-cat <i@her-cat.com>
 *
 * @see http://docs.ucpaas.com/doku.php?id=%E7%9F%AD%E4%BF%A1:sendsms
 */
class YunZhiXunGateway extends Gateway
{
    use HasHttpRequest;

    const SUCCESS_CODE = '000000';

    const FUNCTION_SEND_SMS = 'sendsms';

    const FUNCTION_BATCH_SEND_SMS = 'sendsms_batch';

    const ENDPOINT_TEMPLATE = 'https://open.ucpaas.com/ol/%s/%s';

    /**
     * Send a short message.
     *
     * @param \xyqWeb\sms\Contracts\PhoneNumberInterface $to
     * @param \xyqWeb\sms\Contracts\MessageInterface     $message
     * @param \xyqWeb\sms\Support\Config                 $config
     *
     * @return array
     *
     * @throws GatewayErrorException
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $data = $message->getData($this);

        $function = isset($data['mobiles']) ? self::FUNCTION_BATCH_SEND_SMS : self::FUNCTION_SEND_SMS;

        $endpoint = $this->buildEndpoint('sms', $function);

        $params = $this->buildParams($to, $message, $config);

        return $this->execute($endpoint, $params);
    }

    /**
     * @param $resource
     * @param $function
     *
     * @return string
     */
    protected function buildEndpoint($resource, $function)
    {
        return sprintf(self::ENDPOINT_TEMPLATE, $resource, $function);
    }

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface     $message
     * @param Config               $config
     *
     * @return array
     */
    protected function buildParams(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $data = $message->getData($this);

        return [
            'sid' => $config->get('sid'),
            'token' => $config->get('token'),
            'appid' => $config->get('app_id'),
            'templateid' => $message->getTemplate($this),
            'uid' => isset($data['uid']) ? $data['uid'] : '',
            'param' => isset($data['params']) ? $data['params'] : '',
            'mobile' => isset($data['mobiles']) ? $data['mobiles'] : $to->getNumber(),
        ];
    }

    /**
     * @param $endpoint
     * @param $params
     *
     * @return array
     *
     * @throws GatewayErrorException
     */
    protected function execute($endpoint, $params)
    {
        try {
            $result = $this->postJson($endpoint, $params);

            if (!isset($result['code']) || self::SUCCESS_CODE !== $result['code']) {
                $code = isset($result['code']) ? $result['code'] : 0;
                $error = isset($result['msg']) ? $result['msg'] : json_encode($result, JSON_UNESCAPED_UNICODE);

                throw new GatewayErrorException($error, $code);
            }

            return $result;
        } catch (\Exception $e) {
            throw new GatewayErrorException($e->getMessage(), $e->getCode());
        }
    }
}
