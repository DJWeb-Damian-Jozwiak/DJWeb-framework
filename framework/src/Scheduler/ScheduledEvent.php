<?php

declare(strict_types=1);

namespace DJWeb\Framework\Scheduler;

use DJWeb\Framework\Scheduler\Contracts\JobContract;

readonly class ScheduledEvent
{
    public function __construct(
        private string $expression,
        private JobContract $job
    ) {
    }

    public function isDue(): bool
    {
        return new CronExpression($this->expression)->isDue();
    }

    public function getJob(): JobContract
    {
        return $this->job;
    }
}
