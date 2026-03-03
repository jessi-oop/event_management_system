<?php

/**
 * Configuration: Rate limit rules only
 * No logic, no dependencies
 */

class RateLimitConfig
{
    public const RULES = [
        'login' => [
            'max_attempts' => 5,
            'window_minutes' => 15,
            'identifier_type' => 'ip_email'
        ],
        'register' => [
            'max_attempts' => 3,
            'window_minutes' => 60,
            'identifier_type' => 'ip_only'
        ],
        'create_event' => [
            'max_attempts' => 10,
            'window_minutes' => 1,
            'identifier_type' => 'user_id'
        ],
        'update_event' => [
            'max_attempts' => 10,
            'window_minutes' => 1,
            'identifier_type' => 'user_id'
        ],
        'update_user' => [
            'max_attempts' => 3,
            'window_minutes' => 60,
            'identifier_type' => 'user_id'
        ]
    ];

    public static function get(string $action): ?array
    {
        return self::RULES[$action] ?? null;
    }
}
