<?php

declare(strict_types=1);

namespace DJWeb\Framework\Cache;

enum RedisEvictionPolicy: string
{
    case ALLKEYS_LRU = 'allkeys-lru';
    case VOLATILE_LRU = 'volatile-lru';
    case ALLKEYS_LFU = 'allkeys-lfu';
    case VOLATILE_LFU = 'volatile-lfu';
    case ALLKEYS_RANDOM = 'allkeys-random';
    case VOLATILE_RANDOM = 'volatile-random';
    case VOLATILE_TTL = 'volatile-ttl';
    case NO_EVICTION = 'noeviction';
}