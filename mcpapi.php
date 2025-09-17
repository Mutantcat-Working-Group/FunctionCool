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
	<script src="assets/i18n.js"></script>
</head>
<body>
	<!-- 顶部右侧操作：返回首页 & 语言切换 -->
	<div class="page-actions" style="position:fixed;top:16px;right:16px;display:flex;gap:10px;z-index:1000;">
		<a href="/" class="home-link" data-i18n="home-link" style="padding:8px 14px;border:1px solid #e5e7eb;border-radius:8px;background:rgba(255,255,255,0.95);color:#374151;text-decoration:none;font-size:0.875rem;font-weight:500;box-shadow:0 1px 3px rgba(0,0,0,0.1);backdrop-filter:blur(8px);transition:all 0.2s ease;">返回首页</a>
		<button id="lang-btn" type="button" style="padding:8px 14px;border:1px solid #e5e7eb;border-radius:8px;background:rgba(249,250,251,0.95);color:#374151;font-size:0.875rem;font-weight:500;cursor:pointer;box-shadow:0 1px 3px rgba(0,0,0,0.1);backdrop-filter:blur(8px);transition:all 0.2s ease;">English</button>
	</div>
	<style>
	.page-actions .home-link:hover {
		background: rgba(243,244,246,0.95);
		border-color: #d1d5db;
		transform: translateY(-1px);
		box-shadow: 0 2px 6px rgba(0,0,0,0.15);
	}
	.page-actions button:hover {
		background: rgba(243,244,246,0.95);
		border-color: #d1d5db;
		transform: translateY(-1px);
		box-shadow: 0 2px 6px rgba(0,0,0,0.15);
	}
	@media (max-width: 640px) {
		.page-actions {
			top: 10px;
			right: 10px;
			gap: 6px;
		}
		.page-actions .home-link, .page-actions button {
			padding: 6px 10px;
			font-size: 0.8rem;
		}
	}
	</style>
	<header>
		<div class="container">
			<div class="logo">
				<h1 data-i18n="mcp-title">MCP可集成接口</h1>
				<p data-i18n="mcp-subtitle">为自动化工具/平台提供函数库检索服务</p>
			</div>
		</div>
	</header>
	<main>
		<div class="container" style="max-width:600px;margin:0 auto;">
			<section class="mcpapi-intro" style="text-align:center;padding:2rem 1rem;">
				<h2 data-i18n="mcp-description-title">接口说明</h2>
				<p data-i18n="mcp-description-content">本接口用于自动化平台、MCP 工具等集成查询函数库。<br>支持按关键词和语言检索，返回 JSON 格式结果。<br>需先获取临时 token，免费 token 有效期 30 分钟。</p>
								<ul style="text-align:left;display:inline-block;margin:1rem auto 2rem;padding:0 1rem;">
										<li data-i18n="api-endpoint-desc">接口地址：<code>/mcpapi?token={你的token}&q={关键词}&lang={编程语言}</code></li>
										<li data-i18n="token-requirement-desc">token 需通过下方按钮获取，不能直接爬取，谢谢配合</li>
										<li data-i18n="response-fields-desc">返回字段：results（函数列表[数组]）、query、lang</li>
								</ul>
																<div class="mcpapi-addr-table-wrapper" style="overflow-x:auto;margin-bottom:1.2rem;">
																<table class="mcpapi-addr-table" border="1" cellpadding="6" style="border-collapse:collapse;margin:0 auto;background:#f8f9fa;min-width:220px;max-width:100%;font-size:1rem;">
																	<caption style="font-weight:bold;margin-bottom:0.5rem;" data-i18n="addr-table-caption">接口地址表</caption>
																	<thead style="background:#e2e8f0;font-weight:bold;">
																		<tr><td style="min-width:110px;" data-i18n="region-header">地区</td><td style="min-width:110px;" data-i18n="address-header">推荐地址</td></tr>
																	</thead>
																	<tbody>
																		<tr><td data-i18n="international-region">国际</td><td><code>www.functioncool.xyz</code></td></tr>
																		<tr><td data-i18n="china-region">中国地区</td><td><code>cn.functioncool.xyz</code></td></tr>
																	</tbody>
																</table>
																</div>
																<div class="mcpapi-lang-table-wrapper" style="overflow-x:auto;margin-bottom:2rem;">
																<table class="mcpapi-lang-table" border="1" cellpadding="6" style="border-collapse:collapse;margin:0 auto;background:#f8f9fa;min-width:260px;max-width:100%;font-size:1rem;">
																	<caption style="font-weight:bold;margin-bottom:0.5rem;" data-i18n="lang-table-caption">支持语言及 lang 参数对照表</caption>
																	<thead style="background:#e2e8f0;font-weight:bold;">
																		<tr><td style="min-width:90px;" data-i18n="language-header">语言名称</td><td style="min-width:90px;" data-i18n="param-header">lang 参数值</td></tr>
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
				<button id="get-token-btn" data-i18n="get-token-btn" style="padding:0.7rem 2rem;font-size:1.1rem;">获取免费 token</button>
								<div id="token-result" style="margin-top:1.2rem;font-size:1.1rem;color:#2563eb;"></div>
								<div id="quicktip-block" style="margin:1.2rem 0 1.2rem;display:none;text-align:left!important;">
									<div style="position:relative;margin-bottom:0.5rem;">
										<div style="font-weight:bold;display:inline-block;" data-i18n="quicktip-title">快捷提示词</div>
										<button id="quicktip-copy" data-i18n="quicktip-copy-btn" style="position:absolute;right:0;top:1px;padding:0.15rem 0.5rem;font-size:0.85rem;border-radius:3px;background:#f0f4f8;border:1px solid #d0d7de;">一键复制</button>
									</div>
									<div id="mcpapi-quicktip" style="background:#f8f9fa;border-radius:8px;padding:0.7rem;margin:0;font-size:0.95rem;border:1px solid #e2e8f0;text-align:left!important;line-height:1.5;">
										<div class="quicktip-section zh-section">
											<p><strong data-i18n="quicktip-zh-title">【中文】</strong> <span data-i18n="quicktip-zh-request">请向以下地址发送 GET 请求，获取基础函数与相关函数：</span></p>
											<p style="margin:0.2rem 0 0.6rem 0;"><code class="api-url-zh" style="word-break:break-word;overflow-wrap:anywhere;">https://www.functioncool.xyz/mcpapi?token={你的token}&q={关键词}&lang={编程语言}</code></p>
											<p data-i18n="quicktip-zh-params">参数：token=临时或永久 token；q=搜索关键词；lang=语言代码或 all（可选：C、CPP、GO、PYTHON、JAVA、JAVASCRIPT、RUST、MATLAB、PHP、RUBY、VARILOG）。响应：JSON，包含 results（函数数组）、query（原查询）、lang（语言）。</p>
										</div>
										<div class="quicktip-section en-section" style="margin-top:0.6rem;">
											<p><strong data-i18n="quicktip-en-title">[English]</strong> <span data-i18n="quicktip-en-request">Send a GET request to fetch base and related functions:</span></p>
											<p style="margin:0.2rem 0 0.6rem 0;"><code class="api-url-en" style="word-break:break-word;overflow-wrap:anywhere;">https://www.functioncool.xyz/mcpapi?token={your_token}&q={keyword}&lang={language}</code></p>
											<p data-i18n="quicktip-en-params">Params: token=temporary or permanent token; q=search keyword; lang=language code or all (allowed: C, CPP, GO, PYTHON, JAVA, JAVASCRIPT, RUST, MATLAB, PHP, RUBY, VARILOG). Response: JSON with results (array of functions), query (string), lang (string).</p>
										</div>
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
			<p data-i18n="footer-text">&copy; 2025 函数库 | Powered by Mutantcat</p>
		</div>
	</footer>
	<script>
	// 初始化语言按钮与文案
	document.addEventListener('DOMContentLoaded', function() {
		try {
			// 等待 i18n.js 加载完成
			setTimeout(function() {
				console.log('Initializing language system...');
				var current = window.getCurrentLanguage ? window.getCurrentLanguage() : 'zh';
				console.log('Detected current language:', current);
				
				// 初始化页面语言
				if (window.setLanguage) {
					console.log('Setting initial language...');
					window.setLanguage(current);
				}
				
				var langBtn = document.getElementById('lang-btn');
				if (langBtn) {
					langBtn.textContent = current === 'zh' ? 'English' : '中文';
					langBtn.onclick = function() {
						console.log('Language button clicked');
						if (window.toggleLanguage) {
							console.log('Calling toggleLanguage...');
							window.toggleLanguage();
							var cur = window.getCurrentLanguage ? window.getCurrentLanguage() : 'zh';
							console.log('Current language after toggle:', cur);
							langBtn.textContent = cur === 'zh' ? 'English' : '中文';
							// 手动更新复制按钮的文本
							updateCopyButtonText(cur);
							// 更新 token 结果文本
							updateTokenResultText(cur);
						} else {
							console.error('toggleLanguage function not available');
						}
					};
				}
			}, 100);
		} catch (e) { 
			console.error('Language initialization error:', e); 
		}
	});

	// 更新复制按钮文本的辅助函数
	function updateCopyButtonText(lang) {
		var copyBtn = document.getElementById('quicktip-copy');
		if (copyBtn && !copyBtn.textContent.includes('已复制') && !copyBtn.textContent.includes('copied')) {
			copyBtn.textContent = lang === 'zh' ? '一键复制' : 'Copy';
		}
	}

	// 更新 token 结果文本的辅助函数
	function updateTokenResultText(lang) {
		var tokenResult = document.getElementById('token-result');
		if (tokenResult && tokenResult.textContent.trim()) {
			// 提取现有的 token
			var tokenMatch = tokenResult.textContent.match(/[a-f0-9]{32}/);
			if (tokenMatch) {
				var token = tokenMatch[0];
				var pattern = lang === 'zh' ? 
					'您的 token：${token}（30分钟有效）' : 
					'Your token: ${token} (valid for 30 minutes)';
				tokenResult.textContent = pattern.replace('${token}', token);
			}
		}
	}

	document.getElementById('get-token-btn').onclick = function() {
		fetch('?get_token=1').then(r => r.json()).then(data => {
			// 获取当前语言并使用对应的文本模板
			var currentLang = window.getCurrentLanguage ? window.getCurrentLanguage() : 'zh';
			var tokenPattern = currentLang === 'zh' ? 
				'您的 token：${token}（30分钟有效）' : 
				'Your token: ${token} (valid for 30 minutes)';
			document.getElementById('token-result').textContent = tokenPattern.replace('${token}', data.token);
			
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
		setTimeout(()=>{
			var currentLang = window.getCurrentLanguage ? window.getCurrentLanguage() : 'zh';
			this.textContent = currentLang === 'zh' ? '一键复制' : 'Copy';
		}, 1200);
	};
	</script>
</body>
</html>
