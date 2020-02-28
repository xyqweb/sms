<?php

/*
 * This file is part of the xyqweb/easy-sms.
 *
 * (c) xyqweb <xyqweb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace xyqWeb\sms\strategies;

use xyqWeb\sms\ContractsstrategyInterface;

/**
 * Class RandomStrategy.
 */
class RandomStrategy implements StrategyInterface
{
    /**
     * @param array $gateways
     *
     * @return array
     */
    public function apply(array $gateways)
    {
        uasort($gateways, function () {
            return mt_rand() - mt_rand();
        });

        return array_keys($gateways);
    }
}
