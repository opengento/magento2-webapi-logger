<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model;

class SecretParser
{
    public function __construct(private Config $config) {}

    public function parseIp(): string
    {
        return '***.***.***.***';
    }

    public function parseHeaders(string $header): string
    {
        $secrets = $this->config->getSecrets();
        $headers =  implode('|', $secrets);

        return preg_replace('/('.$headers.'):(.*)/', '$1: ********', $header);
    }

    public function parseBody(string $body): string
    {
        $secrets = $this->config->getSecrets();
        foreach ($secrets as $secret) {
            $body = preg_replace('/' . $secret . '(")*:( )*"(.*)"/', $secret . '$1: "********"', $body);
        }

        return $body;
    }
}
