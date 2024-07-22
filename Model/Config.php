<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Opengento\WebapiLogger\Model\Config\SaveMode;

use function array_map;

class Config
{
    private const WEBAPI_LOGS_IS_ENABLED = 'webapi_logs/log/enabled';
    private const WEBAPI_LOGS_SAVE_MODES = 'webapi_logs/log/save_modes';
    private const WEBAPI_LOGS_LOG_SECRET_MODE = 'webapi_logs/log/secret_mode';
    private const WEBAPI_LOGS_LOG_SECRET_WORDS = 'webapi_logs/log/secret_words';
    private const WEBAPI_LOGS_LOG_CLEAN_OLDER_THAN_HOURS = 'webapi_logs/log/clean_older_than_hours';

    public function __construct(private ScopeConfigInterface $scopeConfig) {}

    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::WEBAPI_LOGS_IS_ENABLED, ScopeInterface::SCOPE_WEBSITE);
    }

    public function getSaveModes(): array
    {
        return array_map(
            static fn (string $saveMode): SaveMode => SaveMode::from($saveMode),
            $this->scopeConfig->getValue(self::WEBAPI_LOGS_SAVE_MODES)
        );
    }

    public function isSecretMode(): bool
    {
        return $this->scopeConfig->isSetFlag(self::WEBAPI_LOGS_LOG_SECRET_MODE, ScopeInterface::SCOPE_WEBSITE);
    }

    public function getSecrets(): array
    {
        return preg_split(
            '/\n|\r\n?/',
            (string)$this->scopeConfig->getValue(self::WEBAPI_LOGS_LOG_SECRET_WORDS, ScopeInterface::SCOPE_WEBSITE)
        );
    }

    public function getCleanOlderThanHours(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::WEBAPI_LOGS_LOG_CLEAN_OLDER_THAN_HOURS,
            ScopeInterface::SCOPE_WEBSITE
        );
    }
}
