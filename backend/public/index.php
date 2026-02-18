<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

 $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
 $allowedOriginsEnv = getenv('ALLOWED_ORIGINS') ?: '';
 $allowedOrigins = array_values(array_filter(array_map('trim', explode(',', $allowedOriginsEnv))));
 $defaultDevOrigins = ['http://localhost:5173'];
 $isAllowedOrigin = $origin !== '' && in_array($origin, array_merge($defaultDevOrigins, $allowedOrigins), true);
 if ($isAllowedOrigin) {
     header("Access-Control-Allow-Origin: {$origin}");
 }
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($path === '/' || $path === '/health') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "ok\n";
    exit;
}

if ($path === '/api/hello') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'message' => 'Hello World! I\'m the Backend!',
    ], JSON_UNESCAPED_SLASHES);
    exit;
}

http_response_code(404);
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'error' => 'Not Found',
    'path' => $path,
], JSON_UNESCAPED_SLASHES);
