<?php
declare(strict_types=1);

function start_app_session(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params([
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        ]);
        session_start();
    }
}
function json_response(array $data, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
function request_data(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: '{}', true);
    return is_array($data) ? $data : [];
}
function clean_text(mixed $value): string {
    return trim((string)($value ?? ''));
}
function current_user(PDO $pdo): ?array {
    if (empty($_SESSION['user_id'])) return null;
    $stmt = $pdo->prepare('SELECT id, username, email FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    return $user ?: null;
}
