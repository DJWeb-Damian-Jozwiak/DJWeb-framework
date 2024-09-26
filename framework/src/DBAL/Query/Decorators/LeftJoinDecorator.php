<?php

declare(strict_types=1);

namespace DJWeb\Framework\DBAL\Query\Decorators;

class LeftJoinDecorator extends JoinDecorator
{
    protected function joinType(): string
    {
        return 'LEFT JOIN';
    }
}