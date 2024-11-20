<?php

declare(strict_types=1);

namespace DJWeb\Framework\ErrorHandling\Renderers\Partials;

use DJWeb\Framework\Console\Output\Contacts\OutputContract;
use DJWeb\Framework\ErrorHandling\Renderers\Helpers\PathNormalizer;
use DJWeb\Framework\ErrorHandling\TraceCollection;
use DJWeb\Framework\ErrorHandling\TraceFrame;

readonly class ConsoleTraceRenderer
{
    private const LINE_LENGTH = 80;

    public function __construct(
        private OutputContract $output,
        private PathNormalizer $pathNormalizer
    ) {
    }

    public function render(TraceCollection $trace): void
    {
        foreach ($trace->frames as $index => $frame) {
            $this->renderFrame($frame, $index);
            $this->renderSeparator($index, $trace);
        }
    }

    private function renderFrame(TraceFrame $frame, int $index): void
    {
        $this->output->writeln(sprintf(
            '#%d %s(%s)',
            $index,
            $frame->class ? $frame->class . '::' : '',
            $frame->function
        ));

        $this->output->writeln(sprintf(
            '   -> %s:%d',
            $this->pathNormalizer->getRelativePath($frame->file),
            $frame->line
        ));
    }

    private function renderSeparator(int $index, TraceCollection $trace): void
    {
        if ($index < count($trace->frames) - 1) {
            $this->output->writeln(str_repeat('-', self::LINE_LENGTH));
            $this->output->writeln('');
        }
    }
}
