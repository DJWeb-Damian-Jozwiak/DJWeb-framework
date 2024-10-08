<?php

declare(strict_types=1);

namespace DJWeb\Framework\Config;

use DJWeb\Framework\Base\Application;
use DJWeb\Framework\Base\DotContainer;
use Dotenv\Dotenv;

class ConfigBase extends DotContainer
{
    public function __construct(private readonly Application $app)
    {
        parent::__construct();
        $this->loadEnvironmentVariables();
        $this->loadConfigFiles();
    }

    private function loadEnvironmentVariables(): void
    {
        $dotenv = Dotenv::createImmutable($this->app->base_path);
        $dotenv->load();
    }

    private function loadConfigFiles(): void
    {
        $configPath = $this->app->base_path . DIRECTORY_SEPARATOR . 'config';
        /** @var list<string> $files */
        $files = scandir($configPath);
        $files = $files ? $files : [];
        $files = array_filter(
            $files,
            static fn ($file) => pathinfo($file, PATHINFO_EXTENSION) === 'php'
        );
        foreach ($files as $file) {
            $key = pathinfo($file, PATHINFO_FILENAME);
            $this->set(
                $key,
                require $configPath . DIRECTORY_SEPARATOR . $file
            );
        }
    }
}