<?php
// 简易 IP 限流：每 10 秒最多 20 次请求（与首页公示一致）。
// - 按 IP 哈希存为单文件，避免单一聚合文件并发写入冲突。
// - 使用 LOCK_EX 串行化同 IP 写入；不同 IP 之间互不影响。
// - 触发上限返回 429 并设置 Retry-After，调用方应在响应前 require 本文件后调用 rate_limit_check()。

define('RATELIMIT_DIR', __DIR__ . '/../data/ratelimit');
define('RATELIMIT_WINDOW', 10);   // 秒
define('RATELIMIT_MAX', 20);      // 每窗口最大次数
define('RATELIMIT_GC_PROB', 100); // 1/N 概率触发清理

function rl_client_ip() {
	// 仅信任 REMOTE_ADDR，避免伪造 X-Forwarded-For 绕过限流。
	// 若实际部署在反向代理后，可在此显式信任已知代理白名单。
	return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function rl_bucket_file($ip) {
	if (!is_dir(RATELIMIT_DIR)) {
		@mkdir(RATELIMIT_DIR, 0775, true);
	}
	return RATELIMIT_DIR . '/' . substr(hash('sha256', $ip), 0, 24) . '.json';
}

function rl_gc() {
	// 偶发清理过期桶文件，避免目录无限增长。
	if (!is_dir(RATELIMIT_DIR)) return;
	$cutoff = time() - RATELIMIT_WINDOW * 6;
	$dh = @opendir(RATELIMIT_DIR);
	if (!$dh) return;
	while (($f = readdir($dh)) !== false) {
		if ($f[0] === '.') continue;
		$p = RATELIMIT_DIR . '/' . $f;
		if (@filemtime($p) < $cutoff) @unlink($p);
	}
	closedir($dh);
}

function rate_limit_check() {
	$file = rl_bucket_file(rl_client_ip());
	$now = time();
	$window_start = intdiv($now, RATELIMIT_WINDOW) * RATELIMIT_WINDOW;

	$fp = @fopen($file, 'c+');
	if (!$fp) return; // 文件层不可用时静默放行，避免限流挂掉整站。
	flock($fp, LOCK_EX);
	$raw = stream_get_contents($fp);
	$data = $raw ? json_decode($raw, true) : null;
	if (!is_array($data) || ($data['w'] ?? 0) !== $window_start) {
		$data = ['w' => $window_start, 'c' => 0];
	}
	$data['c']++;
	$count = $data['c'];

	ftruncate($fp, 0);
	rewind($fp);
	fwrite($fp, json_encode($data));
	fflush($fp);
	flock($fp, LOCK_UN);
	fclose($fp);

	if (mt_rand(1, RATELIMIT_GC_PROB) === 1) rl_gc();

	if ($count > RATELIMIT_MAX) {
		$retry = ($window_start + RATELIMIT_WINDOW) - $now;
		if ($retry < 1) $retry = 1;
		header('Retry-After: ' . $retry);
		http_response_code(429);
		$accept = $_SERVER['HTTP_ACCEPT'] ?? '';
		if (stripos($accept, 'application/json') !== false || !empty($_GET['token']) || !empty($_GET['get_token'])) {
			header('Content-Type: application/json');
			echo json_encode(['error' => 'Too many requests', 'retry_after' => $retry]);
		} else {
			header('Content-Type: text/plain; charset=utf-8');
			echo "请求过于频繁，请 {$retry} 秒后再试。\nToo many requests, retry after {$retry}s.";
		}
		exit;
	}
}
