<?php declare(strict_types=1);

namespace Bio\Core;

use Nette\Configurator;
use Nette\Utils\Arrays;


class ConfiguratorFactory {

    private const DEBUG_KEY = 'spy';
    private const ALLOWED_IPS = ['127.0.0.1', '::1'];



    public function create(): Configurator {
        $configurator = new Configurator();

        $configurator->addConfig(__DIR__ . '/../Config/config.neon');

        if (file_exists(__DIR__ . '/../Config/config.local.neon')) {
            $configurator->addConfig(__DIR__ . '/../Config/config.local.neon');
        }

        $configurator->addParameters(['appDir' => __DIR__ . '/../', 'baseDir' => __DIR__ . '/../../']);

        $configurator->setTempDirectory(__DIR__ . '/../../data/Temp');

        $configurator->setDebugMode($this->isDebugMode());

        $configurator->enableTracy(__DIR__ . '/../../data/Log');

        return $configurator;
    }



    protected function isDebugMode(): bool {
        $argv = Arrays::get($_SERVER, 'argv', []);
        $userAgent = Arrays::get($_SERVER, 'HTTP_USER_AGENT', '');
        $ipAddress = Arrays::get($_SERVER, 'REMOTE_ADDR', '');

        if (PHP_SAPI === 'cli' && \in_array('-vvv', $argv, true)) {
            return true;
        }

        if ($userAgent === self::DEBUG_KEY && \in_array($ipAddress, self::ALLOWED_IPS, true)) {
            return true;
        }

        return false;
    }

}
