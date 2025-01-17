<?php

declare(strict_types=1);

namespace DJWeb\Framework\Routing;

class AdvancedRouteMatcher extends RouteMatcher
{
    protected function matchesPath(string $path, Route $route): bool
    {
        $path = $this->normalizePath($path);
        $pattern = $this->buildPatternFromPath($route->path);
        $matches = [];
        if (! preg_match($pattern, $path, $matches)) {
            return parent::matchesPath($path, $route);
        }
        $parameters = array_map(
            static fn (RouteParameter $definition) => $definition->getValue($matches),
            $route->parameterDefinitions
        );
        $route->withParameters($parameters);
        return true;
    }
    private function buildPatternFromPath(string $path): string
    {
        $pattern = $path;
        $pattern = str_replace('/', '\/', $pattern);

        $pattern = preg_replace_callback('/<(\w+)(?::([^>]+))?>/', static function ($matches) {
            $regexPart = $matches[2] ?? '[\d\p{L}-]+';
            return '(?<' . $matches[1] . '>' . $regexPart . ')';
        }, $pattern);
        return '#^' . $pattern . '$#';
    }

    private function normalizePath(string $path): string
    {
        $path = rtrim($path, '/');

        $path = preg_replace('#/+#', '/', $path);

        $path ??= '';
        if (! str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        return $path;
    }
}
