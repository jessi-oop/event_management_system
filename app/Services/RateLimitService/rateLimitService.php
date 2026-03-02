<?php

require_once __DIR__ . '/../../Repositories/RateLimitRepository/rateLimitRepository.php';
require_once __DIR__ . '/../../Utils/rateLimitConfig.php';

class RateLimitService {
    private RateLimitRepository $repository;

    public function __construct() {
        $this->repository = new RateLimitRepository();
    }

    public function attempt(string $action, string $identifier): bool {
        $config = RateLimitConfig::get($action);
        if (!$config) {
            return true;
        }

        $record = $this->repository->find($identifier, $action);
        $now = time();
        $windowStart = $now - ($config['window_minutes'] * 60);

        if (!$record) {
            $this->repository->create($identifier, $action);
            return true;
        }

        $firstAttempt = strtotime($record['first_attempt_at']);

        if ($firstAttempt < $windowStart) {
            $this->repository->reset($identifier, $action);
            return true;
        }

        if ($record['attempts'] >= $config['max_attempts']) {
            return false;
        }

        $this->repository->increment($identifier, $action);
        return true;
    }

    public function clear(string $action, string $identifier): void {
        $this->repository->delete($identifier, $action);
    }

    public function buildIdentifier(string $actionType, array $context): string {
        $config = RateLimitConfig::get($actionType);
        $type = $config['identifier_type'] ?? 'ip_only';

        return match($type) {
            'ip_email' => $this->hashIpEmail($context['ip'] ?? '', $context['email'] ?? ''),
            'user_id' => 'user_' . ($context['user_id'] ?? 0),
            default => 'ip_' . ($context['ip'] ?? 'unknown')
        };
    }

    private function hashIpEmail(string $ip, string $email): string {
        return md5($ip . '_' . strtolower(trim($email)));
    }
}