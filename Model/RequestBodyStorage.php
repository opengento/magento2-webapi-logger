<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;

use function bin2hex;
use function date;
use function file_exists;
use function file_put_contents;
use function ltrim;
use function random_bytes;
use function rtrim;
use function str_starts_with;
use function strlen;
use function substr;
use function trim;

class RequestBodyStorage
{
    private const STORAGE_PREFIX = '@disk:';
    private const ENCRYPTED_PREFIX = '@enc:';
    private const STORAGE_DIR = 'webapi-logger/request-bodies';
    private const HTACCESS_CONTENT = "<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n    Deny from all\n</IfModule>\n";
    private const WEB_CONFIG_CONTENT = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<configuration>\n    <system.webServer>\n        <authorization>\n            <add accessType=\"Deny\" users=\"*\" />\n        </authorization>\n    </system.webServer>\n</configuration>\n";
    private const INDEX_PHP_CONTENT = "<?php\nhttp_response_code(403);\nexit;\n";

    public function __construct(
        private Filesystem $filesystem,
        private EncryptorInterface $encryptor,
        private LoggerInterface $logger
    ) {}

    public function store(string $requestBody): string
    {
        try {
            $this->ensureStorageDirectoryIsProtected();
            $relativePath = $this->buildRelativePath();
            $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR)->writeFile(
                $relativePath,
                $this->encodeForStorage($requestBody)
            );

            return self::STORAGE_PREFIX . $relativePath;
        } catch (Exception $exception) {
            $this->logger->error('Could not store request body on disk: ' . $exception->getMessage());

            return $requestBody;
        }
    }

    public function resolve(string $value): string
    {
        if (!$this->isDiskReference($value)) {
            return $value;
        }

        $relativePath = $this->getRelativePathFromReference($value);
        if ($relativePath === null) {
            return '';
        }

        try {
            $this->ensureStorageDirectoryIsProtected();
            $directory = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
            if (!$directory->isExist($relativePath)) {
                return '';
            }

            return $this->decodeFromStorage($directory->readFile($relativePath));
        } catch (Exception $exception) {
            $this->logger->error('Could not read request body from disk: ' . $exception->getMessage());

            return '';
        }
    }

    public function isDiskReference(string $value): bool
    {
        return str_starts_with($value, self::STORAGE_PREFIX);
    }

    public function delete(string $value): void
    {
        if (!$this->isDiskReference($value)) {
            return;
        }

        $relativePath = $this->getRelativePathFromReference($value);
        if ($relativePath === null) {
            return;
        }

        try {
            $directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            if ($directory->isExist($relativePath)) {
                $directory->delete($relativePath);
            }
        } catch (Exception $exception) {
            $this->logger->error('Could not delete request body from disk: ' . $exception->getMessage());
        }
    }

    private function getRelativePathFromReference(string $value): ?string
    {
        if (!$this->isDiskReference($value)) {
            return null;
        }

        $relativePath = trim(substr($value, strlen(self::STORAGE_PREFIX)));
        if ($relativePath === '') {
            return null;
        }

        return ltrim($relativePath, '/');
    }

    private function buildRelativePath(): string
    {
        return self::STORAGE_DIR . '/' . date('Y/m/d') . '/' . bin2hex(random_bytes(16)) . '.json';
    }

    private function ensureStorageDirectoryIsProtected(): void
    {
        $directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);

        if (!$directory->isExist(self::STORAGE_DIR . '/web.config')) {
            $directory->writeFile(self::STORAGE_DIR . '/web.config', self::WEB_CONFIG_CONTENT);
        }
        if (!$directory->isExist(self::STORAGE_DIR . '/index.php')) {
            $directory->writeFile(self::STORAGE_DIR . '/index.php', self::INDEX_PHP_CONTENT);
        }

        $storageAbsolutePath = rtrim($directory->getAbsolutePath(self::STORAGE_DIR), '/');
        $htaccessAbsolutePath = $storageAbsolutePath . '/.htaccess';
        if (!file_exists($htaccessAbsolutePath)) {
            file_put_contents($htaccessAbsolutePath, self::HTACCESS_CONTENT);
        }
    }

    private function encodeForStorage(string $requestBody): string
    {
        return self::ENCRYPTED_PREFIX . $this->encryptor->encrypt($requestBody);
    }

    private function decodeFromStorage(string $storedPayload): string
    {
        if (!str_starts_with($storedPayload, self::ENCRYPTED_PREFIX)) {
            return $storedPayload;
        }

        return $this->encryptor->decrypt(substr($storedPayload, strlen(self::ENCRYPTED_PREFIX)));
    }
}
