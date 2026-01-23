<?php

declare(strict_types=1);

function fail(string $message): void
{
    fwrite(STDERR, $message . "\n");
    exit(1);
}

function http_get_json(string $url): array
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'ignore_errors' => true,
            'timeout' => 2,
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    if ($body === false) {
        fail("Request failed: {$url}");
    }

    $decoded = json_decode($body, true);
    if (!is_array($decoded)) {
        fail("Expected JSON response from {$url}, got: {$body}");
    }

    return $decoded;
}

function http_get_text(string $url): string
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'ignore_errors' => true,
            'timeout' => 2,
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    if ($body === false) {
        fail("Request failed: {$url}");
    }

    return $body;
}

function try_http_get_text(string $url): ?string
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'ignore_errors' => true,
            'timeout' => 1,
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    if ($body === false) {
        return null;
    }

    return $body;
}

$root = dirname(__DIR__, 2);
$publicDir = $root . '/backend/public';
$host = '127.0.0.1';
$port = random_int(20000, 60000);

$cmd = sprintf('php -S %s:%d -t %s', $host, $port, escapeshellarg($publicDir));

$descriptorspec = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
];

$process = proc_open($cmd, $descriptorspec, $pipes, $root);
if (!is_resource($process)) {
    fail('Failed to start PHP built-in server');
}

try {
    $started = false;
    $start = microtime(true);

    while ((microtime(true) - $start) < 5.0) {
        $health = try_http_get_text("http://{$host}:{$port}/health");
        if (is_string($health) && str_starts_with($health, 'ok')) {
            $started = true;
            break;
        }
        usleep(50_000);
    }

    if (!$started) {
        fail('Server did not become healthy in time');
    }

    $json = http_get_json("http://{$host}:{$port}/api/hello");
    if (($json['message'] ?? null) !== 'Hello from PHP') {
        fail('Unexpected /api/hello response: ' . json_encode($json));
    }

    fwrite(STDOUT, "backend smoke test: ok\n");
} finally {
    proc_terminate($process);
    proc_close($process);
}
