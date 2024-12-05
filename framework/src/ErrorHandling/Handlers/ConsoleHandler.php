<?php

declare(strict_types=1);

namespace DJWeb\Framework\ErrorHandling\Handlers;

use DJWeb\Framework\ErrorHandling\BaseHandler;
use DJWeb\Framework\ErrorHandling\Renderers\ConsoleRenderer;
use Throwable;

class ConsoleHandler extends BaseHandler
{
    public function __construct(private ConsoleRenderer $renderer) {
    }

    public function handleException(Throwable $exception): void
    {
        try {
            $this->renderer->render($exception);

        } catch (Throwable) {
            echo 'Critical error occurred. Please check error logs.';

        }
    }

}