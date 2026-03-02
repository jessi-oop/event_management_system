<?php

require_once __DIR__ . '/../../Core/Database.php';

class RateLimitRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function find(string $identifier, string $action): ?array {
        $stmt = $this->db->prepare("
            SELECT * FROM rate_limits 
            WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$identifier, $action]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(string $identifier, string $action): void {
        $stmt = $this->db->prepare("
            INSERT INTO rate_limits (identifier, action) VALUES (?, ?)
        ");
        $stmt->execute([$identifier, $action]);
    }

    public function increment(string $identifier, string $action): void {
        $stmt = $this->db->prepare("
            UPDATE rate_limits 
            SET attempts = attempts + 1 
            WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$identifier, $action]);
    }

    public function reset(string $identifier, string $action): void {
        $stmt = $this->db->prepare("
            UPDATE rate_limits 
            SET attempts = 1, first_attempt_at = NOW() 
            WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$identifier, $action]);
    }

    public function delete(string $identifier, string $action): void {
        $stmt = $this->db->prepare("
            DELETE FROM rate_limits WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$identifier, $action]);
    }
}