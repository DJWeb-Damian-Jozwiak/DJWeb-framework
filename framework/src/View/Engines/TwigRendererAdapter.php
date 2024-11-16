<?php

namespace DJWeb\Framework\View\Engines;

use DJWeb\Framework\Config\Config;
use DJWeb\Framework\View\Contracts\RendererContract;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRendererAdapter implements RendererContract
{
    protected Environment $twig;
    protected array $data = [];

    public function __construct(
        string $template_path,
        string $cache_path,
    ) {
        $loader = new FilesystemLoader($template_path);
        $this->twig = new Environment($loader);
    }

    /**
     * @param string $template
     * @param array<string, mixed> $data
     * @return string
     */
    public function render(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }

    public static function buildDefault(): RendererContract
    {
        $config = Config::get('views.engines.twig.paths');
        $template_path = $config['template_path'];
        $cache_path = $config['cache_path'];
        return new TwigRendererAdapter($template_path, $cache_path);
    }

    public function clearCache(string $cache_path): void
    {
        if (is_dir($cache_path)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($cache_path, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
        }
    }
}