<?php
// MCP 可集成接口
// 1. 无参数时显示说明界面（含广告与获取 token 按钮）
// 2. 带 token/关键词/语言时返回 JSON 查询结果
// 3. token 只能通过按钮获取，半小时有效


header('X-Robots-Tag: noindex, nofollow', true); // 防爬虫

// MCP token 统一时段逻辑（全站共享，每 30 分钟刷新一次）
define('MCP_TOKEN_FILE', __DIR__ . '/data/mcp_token.json');
define('MCP_TOKEN_PERIOD', 1800); // 30分钟

function get_current_period() {
	// 返回当天 0 点起第几个 30 分钟段
	$now = time();
	$day_start = strtotime(date('Y-m-d 00:00:00', $now));
	return intval(($now - $day_start) / MCP_TOKEN_PERIOD);
}

function get_token_data() {
	if (!file_exists(MCP_TOKEN_FILE)) return ['token'=>'','ts'=>0,'period'=>-1];
	$data = json_decode(file_get_contents(MCP_TOKEN_FILE), true);
	if (!$data || !isset($data['token'],$data['ts'],$data['period'])) return ['token'=>'','ts'=>0,'period'=>-1];
	return $data;
}

function save_token_data($token, $ts, $period) {
	file_put_contents(MCP_TOKEN_FILE, json_encode(['token'=>$token,'ts'=>$ts,'period'=>$period]));
}

function get_or_refresh_token() {
	$period = get_current_period();
	$data = get_token_data();
	if ($data['period'] === $period && $data['token'] && $data['ts'] > 0) {
		return ['token'=>$data['token'],'expire'=>MCP_TOKEN_PERIOD-($GLOBALS['now']=time())+$data['ts']];
	}
	// 新时段，生成新 token
	$token = bin2hex(random_bytes(16));
	$ts = time();
	save_token_data($token, $ts, $period);
	return ['token'=>$token,'expire'=>MCP_TOKEN_PERIOD];
}

function token_valid($token) {
	$period = get_current_period();
	$data = get_token_data();
	// 检查永久 token
	$permfile = __DIR__ . '/data/mcp_token_permanent.json';
	if (file_exists($permfile)) {
		$perms = json_decode(file_get_contents($permfile), true);
		if (is_array($perms) && in_array($token, $perms, true)) return true;
	}
	// 检查时段 token
	return ($data['token'] === $token && $data['period'] === $period);
}

// 处理 token 获取请求（AJAX）
if (isset($_GET['get_token']) && $_GET['get_token'] === '1') {
	$tokendata = get_or_refresh_token();
	header('Content-Type: application/json');
	echo json_encode($tokendata);
	exit;
}

// 处理查询请求（带 token、q、lang 参数）
if (isset($_GET['token'], $_GET['q'], $_GET['lang'])) {
	$token = $_GET['token'];
	$query = trim($_GET['q']);
	$lang = $_GET['lang'];
	header('Content-Type: application/json');
	if (!token_valid($token)) {
		echo json_encode(['error' => 'Invalid or expired token']);
		exit;
	}
	// 支持 all 或具体语言
	$languages = [
		'C','CPP','GO','PYTHON','JAVA','JAVASCRIPT','RUST','MATLAB','PHP','RUBY','VARILOG'
	];
	$results = [];
	$search_langs = ($lang === 'all') ? $languages : [strtoupper($lang)];
	$search_text = mb_strtolower($query);
	foreach ($search_langs as $l) {
		$jsonfile = __DIR__ . "/functions/$l/" . strtolower($l) . ".json";
		if (!file_exists($jsonfile)) continue;
		$data = json_decode(file_get_contents($jsonfile), true);
		if (!$data) continue;
		foreach ($data as $func) {
			// 支持 name-zh/name-en/description-zh/description-en/tags
			$fields = [
				mb_strtolower($func['name-zh'] ?? ''),
				mb_strtolower($func['name-en'] ?? ''),
				mb_strtolower($func['description-zh'] ?? ''),
				mb_strtolower($func['description-en'] ?? ''),
				implode(' ', array_map('mb_strtolower', $func['tags'] ?? []))
			];
			$matched = false;
			foreach ($fields as $f) {
				if ($search_text === '' || mb_strpos($f, $search_text) !== false) {
					$matched = true;
					break;
				}
			}
			if ($matched) {
				$func['language'] = $l;
				$results[] = $func;
			}
		}
	}
	echo json_encode(['results' => $results, 'query' => $query, 'lang' => $lang]);
	exit;
}

// 说明界面（无参数）
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>MCP可集成接口 - 函数库 FunctionCool</title>
	<meta name="robots" content="noindex,nofollow">
	<link rel="canonical" href="https://www.functioncool.xyz/mcpapi">
	<link rel="stylesheet" href="assets/style.css">
</head>
<body>
	<header>
		<div class="container">
			<div class="logo">
				<h1>MCP可集成接口</h1>
				<p>为自动化工具/平台提供函数库检索服务</p>
			</div>
		</div>
	</header>
	<main>
		<div class="container" style="max-width:600px;margin:0 auto;">
			<section class="mcpapi-intro" style="text-align:center;padding:2rem 1rem;">
				<h2>接口说明</h2>
				<p>本接口用于自动化平台、MCP 工具等集成查询函数库。<br>支持按关键词和语言检索，返回 JSON 格式结果。<br>需先获取临时 token，免费 token 有效期 30 分钟。</p>
								<ul style="text-align:left;display:inline-block;margin:1rem auto 2rem;padding:0 1rem;">
										<li>接口地址：<code>/mcpapi?token={你的token}&q={关键词}&lang={编程语言}</code></li>
										<li>token 需通过下方按钮获取，不能直接爬取，谢谢配合</li>
										<li>返回字段：results（函数列表[数组]）、query、lang</li>
								</ul>
																<div class="mcpapi-addr-table-wrapper" style="overflow-x:auto;margin-bottom:1.2rem;">
																<table class="mcpapi-addr-table" border="1" cellpadding="6" style="border-collapse:collapse;margin:0 auto;background:#f8f9fa;min-width:220px;max-width:100%;font-size:1rem;">
																	<caption style="font-weight:bold;margin-bottom:0.5rem;">接口地址表</caption>
																	<thead style="background:#e2e8f0;font-weight:bold;">
																		<tr><td style="min-width:110px;">地区</td><td style="min-width:110px;">推荐地址</td></tr>
																	</thead>
																	<tbody>
																		<tr><td>国际</td><td><code>www.functioncool.xyz</code></td></tr>
																		<tr><td>中国地区</td><td><code>cn.functioncool.xyz</code></td></tr>
																	</tbody>
																</table>
																</div>
																<div class="mcpapi-lang-table-wrapper" style="overflow-x:auto;margin-bottom:2rem;">
																<table class="mcpapi-lang-table" border="1" cellpadding="6" style="border-collapse:collapse;margin:0 auto;background:#f8f9fa;min-width:260px;max-width:100%;font-size:1rem;">
																	<caption style="font-weight:bold;margin-bottom:0.5rem;">支持语言及 lang 参数对照表</caption>
																	<thead style="background:#e2e8f0;font-weight:bold;">
																		<tr><td style="min-width:90px;">语言名称</td><td style="min-width:90px;">lang 参数值</td></tr>
																	</thead>
																	<tbody>
																		<tr><td>C</td><td>C</td></tr>
																		<tr><td>C++</td><td>CPP</td></tr>
																		<tr><td>Go</td><td>GO</td></tr>
																		<tr><td>Python</td><td>PYTHON</td></tr>
																		<tr><td>Java</td><td>JAVA</td></tr>
																		<tr><td>JavaScript</td><td>JAVASCRIPT</td></tr>
																		<tr><td>Rust</td><td>RUST</td></tr>
																		<tr><td>MATLAB</td><td>MATLAB</td></tr>
																		<tr><td>PHP</td><td>PHP</td></tr>
																		<tr><td>Ruby</td><td>RUBY</td></tr>
																		<tr><td>Verilog</td><td>VARILOG</td></tr>
																		<tr><td>全部语言</td><td>all</td></tr>
																	</tbody>
																</table>
																</div>
																<style>
																@media (max-width: 600px) {
																	.mcpapi-addr-table-wrapper, .mcpapi-lang-table-wrapper { margin-bottom:1rem; }
																	.mcpapi-addr-table, .mcpapi-lang-table {
																		font-size:0.92rem;
																		min-width:140px;
																		max-width:100vw;
																	}
																	.mcpapi-addr-table caption, .mcpapi-lang-table caption {
																		font-size:1rem;
																	}
																	.mcpapi-addr-table td, .mcpapi-lang-table td {
																		padding: 0.45rem 0.5rem;
																		min-width:70px;
																		word-break:break-all;
																	}
																}
																</style>
												<style>
												@media (max-width: 600px) {
													.mcpapi-lang-table-wrapper { margin-bottom:1.2rem; }
													.mcpapi-lang-table {
														font-size:0.92rem;
														min-width:180px;
														max-width:100vw;
													}
													.mcpapi-lang-table caption {
														font-size:1rem;
													}
													.mcpapi-lang-table td {
														padding: 0.45rem 0.5rem;
														min-width:70px;
														word-break:break-all;
													}
												}
												</style>
												<style>
												/* 强制快捷提示词区域左对齐，避免上层 text-align:center 影响 */
												#quicktip-block, #quicktip-block * { text-align: left !important; }
												</style>
				<button id="get-token-btn" style="padding:0.7rem 2rem;font-size:1.1rem;">获取免费 token</button>
								<div id="token-result" style="margin-top:1.2rem;font-size:1.1rem;color:#2563eb;"></div>
								<div id="quicktip-block" style="margin:1.2rem 0 1.2rem;display:none;text-align:left!important;">
									<div style="position:relative;margin-bottom:0.5rem;">
										<div style="font-weight:bold;display:inline-block;">快捷提示词</div>
										<button id="quicktip-copy" style="position:absolute;right:0;top:1px;padding:0.15rem 0.5rem;font-size:0.85rem;border-radius:3px;background:#f0f4f8;border:1px solid #d0d7de;">一键复制</button>
									</div>
									<div id="mcpapi-quicktip" style="background:#f8f9fa;border-radius:8px;padding:0.7rem;margin:0;font-size:0.95rem;border:1px solid #e2e8f0;text-align:left!important;line-height:1.5;">
										<p><strong>【中文】</strong> 请向以下地址发送 GET 请求，获取基础函数与相关函数：</p>
										<p style="margin:0.2rem 0 0.6rem 0;"><code style="word-break:break-word;overflow-wrap:anywhere;">https://www.functioncool.xyz/mcpapi?token={你的token}&q={关键词}&lang={编程语言}</code></p>
										<p>参数：token=临时或永久 token；q=搜索关键词；lang=语言代码或 all（可选：C、CPP、GO、PYTHON、JAVA、JAVASCRIPT、RUST、MATLAB、PHP、RUBY、VARILOG）。响应：JSON，包含 results（函数数组）、query（原查询）、lang（语言）。</p>
										<p style="margin-top:0.6rem;"><strong>[English]</strong> Send a GET request to fetch base and related functions:</p>
										<p style="margin:0.2rem 0 0.6rem 0;"><code style="word-break:break-word;overflow-wrap:anywhere;">https://www.functioncool.xyz/mcpapi?token={your_token}&q={keyword}&lang={language}</code></p>
										<p>Params: token=temporary or permanent token; q=search keyword; lang=language code or all (allowed: C, CPP, GO, PYTHON, JAVA, JAVASCRIPT, RUST, MATLAB, PHP, RUBY, VARILOG). Response: JSON with results (array of functions), query (string), lang (string).</p>
									</div>
								</div>
				<div style="margin:2.5rem 0 1.5rem;">
					<!-- Google AdSense 广告位 -->
					<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3718441900987965" crossorigin="anonymous"></script>
					<ins class="adsbygoogle"
						 style="display:block;text-align:center;"
						 data-ad-client="ca-pub-3718441900987965"
						 data-ad-slot="1234567890"
						 data-ad-format="auto"
						 data-full-width-responsive="true"></ins>
					<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
				</div>
			</section>
		</div>
	</main>
	<footer>
		<div class="container">
			<p>&copy; 2025 函数库 | Powered by Mutantcat</p>
		</div>
	</footer>
	<script>
	document.getElementById('get-token-btn').onclick = function() {
		fetch('?get_token=1').then(r => r.json()).then(data => {
			document.getElementById('token-result').textContent = '您的 token：' + data.token + '（30分钟有效）';
			// 显示快捷提示词块
			var quicktipBlock = document.getElementById('quicktip-block');
			quicktipBlock.style.display = 'block';
			// 使用初始模板（HTML）进行替换，避免重复点击导致叠加
			var tipEl = document.getElementById('mcpapi-quicktip');
			var template = tipEl.getAttribute('data-template');
			if (!template) {
				template = tipEl.innerHTML; // 存储原始 HTML 模板
				tipEl.setAttribute('data-template', template);
			}
			// 全局替换中英文占位符
			var replaced = template
				.replace(/\{你的token\}/g, data.token)
				.replace(/\{your_token\}/g, data.token);
			tipEl.innerHTML = replaced;
		});
	};
	document.getElementById('quicktip-copy').onclick = function() {
		var tip = document.getElementById('mcpapi-quicktip').innerText;
		if (navigator.clipboard) {
			navigator.clipboard.writeText(tip);
		} else {
			// 兼容旧浏览器
			var textarea = document.createElement('textarea');
			textarea.value = tip;
			document.body.appendChild(textarea);
			textarea.select();
			document.execCommand('copy');
			document.body.removeChild(textarea);
		}
		this.textContent = '已复制';
		setTimeout(()=>{this.textContent='一键复制';},1200);
	};
	</script>
</body>
</html>
