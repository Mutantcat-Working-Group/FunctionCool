<?php
// Skill 可集成接口
// 1. 无参数时显示说明界面（直接展示提示词与仓库地址，无需点击获取密钥）
// 2. 带 token/关键词/语言时返回 JSON 查询结果
// 3. 仅保留一个永久密钥：mutantcat（见 data/skill_token_permanent.json）

require_once __DIR__ . '/lib/ratelimit.php';
rate_limit_check();

header('X-Robots-Tag: noindex, nofollow', true); // 防爬虫

// 语言别名：保留 VARILOG 兼容旧调用，规范名为 VERILOG
define('LANG_ALIASES', ['VARILOG' => 'VERILOG']);

/* ============================================================
   定时刷新密钥机制已停用（保留代码以备日后恢复）。
   现在只使用 data/skill_token_permanent.json 中的永久密钥。
   ------------------------------------------------------------
define('SKILL_TOKEN_FILE', __DIR__ . '/data/skill_token.json');
define('SKILL_TOKEN_PERIOD', 1800); // 30分钟

function get_current_period() {
	// 返回当天 0 点起第几个 30 分钟段
	$now = time();
	$day_start = strtotime(date('Y-m-d 00:00:00', $now));
	return intval(($now - $day_start) / SKILL_TOKEN_PERIOD);
}

function get_token_data() {
	if (!file_exists(SKILL_TOKEN_FILE)) return ['token'=>'','ts'=>0,'period'=>-1];
	$data = json_decode(file_get_contents(SKILL_TOKEN_FILE), true);
	if (!$data || !isset($data['token'],$data['ts'],$data['period'])) return ['token'=>'','ts'=>0,'period'=>-1];
	return $data;
}

function save_token_data($token, $ts, $period) {
	// LOCK_EX 避免跨 30 分钟边界的并发写入互相覆盖 / 写出半截 JSON
	file_put_contents(SKILL_TOKEN_FILE, json_encode(['token'=>$token,'ts'=>$ts,'period'=>$period]), LOCK_EX);
}

function get_or_refresh_token() {
	$period = get_current_period();
	$data = get_token_data();
	if ($data['period'] === $period && $data['token'] && $data['ts'] > 0) {
		$expire = SKILL_TOKEN_PERIOD - (time() - $data['ts']);
		if ($expire < 1) $expire = 1;
		return ['token'=>$data['token'],'expire'=>$expire];
	}
	// 新时段，生成新 token
	$token = bin2hex(random_bytes(16));
	$ts = time();
	save_token_data($token, $ts, $period);
	return ['token'=>$token,'expire'=>SKILL_TOKEN_PERIOD];
}

// 处理 token 获取请求（AJAX）—— 已停用
if (isset($_GET['get_token']) && $_GET['get_token'] === '1') {
	$tokendata = get_or_refresh_token();
	header('Content-Type: application/json');
	echo json_encode($tokendata);
	exit;
}
   ============================================================ */

function token_valid($token) {
	// 仅校验永久密钥
	$permfile = __DIR__ . '/data/skill_token_permanent.json';
	if (file_exists($permfile)) {
		$perms = json_decode(file_get_contents($permfile), true);
		if (is_array($perms) && in_array($token, $perms, true)) return true;
	}
	return false;
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
		'C','CPP','GO','PYTHON','JAVA','JAVASCRIPT','RUST','MATLAB','PHP','RUBY','VERILOG'
	];
	$results = [];
	$requested = strtoupper($lang);
	if (isset(LANG_ALIASES[$requested])) $requested = LANG_ALIASES[$requested];
	$search_langs = ($lang === 'all') ? $languages : [$requested];
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
	<title>Skill 函数库接口 - FunctionCool</title>
	<meta name="robots" content="noindex,nofollow">
	<link rel="canonical" href="https://www.functioncool.xyz/skillapi">
	<link rel="stylesheet" href="assets/style.css?v=20260610">
	<script src="assets/i18n.js?v=20260610"></script>
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="apple-touch-icon" href="assets/logo.png">
</head>
<body>
	<header>
		<div class="container">
			<div class="logo">
				<h1 data-i18n="skill-title">Skill 函数库接口</h1>
				<p data-i18n="skill-subtitle">给 AI 与自动化工作流的函数库 Skill — 把输出折成输入、命中 Prompt 缓存</p>
			</div>
			<div class="language-switcher">
				<a href="/" class="home-link" data-i18n="home-link" aria-label="返回首页">返回首页</a>
					<a href="/stylecool" class="skill-link" aria-label="StyleCool">StyleCool</a>
				<button id="lang-btn" type="button">English</button>
			</div>
		</div>
	</header>
	<main>
		<div class="container" style="max-width:720px;margin:0 auto;">
			<section class="skillapi-intro reveal reveal-1" style="text-align:center;padding:2rem 0;">
				<div class="skill-hero-badge">AI SKILL</div>
				<h2 class="grad-text" data-i18n="skill-description-title" style="margin:1rem 0 0.6rem;font-size:2rem;font-weight:800;letter-spacing:-0.02em;">Skill 说明</h2>
				<p data-i18n="skill-description-content" style="color:var(--ink-2);">本 Skill 接口面向 AI 智能体、IDE 插件与自动化工作流。<br>支持按关键词与语言检索函数库，返回结构化 JSON。<br>使用永久密钥访问，无需轮换。</p>

				<!-- 价值主张：两点核心收益 -->
				<div class="skill-value-grid">
					<div class="skill-value-card">
						<h3 data-i18n="skill-value-tokens-title">把昂贵输出折成便宜输入</h3>
						<p data-i18n="skill-value-tokens-desc">让 AI 先调用本 Skill 取回方法索引（签名 / 说明 / 标签），再据此拼装最终代码。模型不必把整段函数体「打」出来——把贵的输出 token 折算成便宜得多的输入 token。</p>
					</div>
					<div class="skill-value-card">
						<h3 data-i18n="skill-value-cache-title">更高的 Prompt 缓存命中</h3>
						<p data-i18n="skill-value-cache-desc">函数库内容长期稳定，作为 Skill 上下文最契合各家厂商的提示词缓存特性。重复或近似查询的实际计费 token 趋近于零。</p>
					</div>
				</div>
			</section>

			<section class="skill-card reveal reveal-2" style="margin-bottom:2rem;">
				<ul style="margin:0 0 1.4rem 1.2rem;color:var(--ink-2);line-height:1.8;">
					<li data-i18n="api-endpoint-desc">接口地址：<code>/skillapi?token=mutantcat&q={关键词}&lang={编程语言}</code></li>
					<li data-i18n="response-fields-desc">返回字段：results（函数列表[数组]）、query、lang</li>
				</ul>

				<div class="skillapi-table-block">
					<h3 class="skillapi-table-caption" data-i18n="addr-table-caption">接口地址表</h3>
					<div class="skillapi-table-wrap">
						<table class="skillapi-addr-table">
							<thead><tr><th data-i18n="region-header">地区</th><th data-i18n="address-header">推荐地址</th></tr></thead>
							<tbody>
								<tr><td data-i18n="international-region">国际</td><td><code>www.functioncool.xyz</code></td></tr>
								<tr><td data-i18n="china-region">中国地区</td><td><code>cn.functioncool.xyz</code></td></tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="skillapi-table-block">
					<h3 class="skillapi-table-caption" data-i18n="lang-table-caption">支持语言及 lang 参数对照表</h3>
					<div class="skillapi-table-wrap">
						<table class="skillapi-lang-table">
							<thead><tr><th data-i18n="language-header">语言名称</th><th data-i18n="param-header">lang 参数值</th></tr></thead>
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
								<tr><td>Verilog</td><td>VERILOG</td></tr>
								<tr><td>全部语言</td><td>all</td></tr>
							</tbody>
						</table>
					</div>
				</div>
			</section>

			<!-- 永久密钥 + 快捷提示词（直接展示，无需点击获取） -->
			<section class="skill-card reveal reveal-3" style="margin-bottom:2rem;">
				<div style="display:flex;align-items:center;justify-content:space-between;gap:0.8rem;flex-wrap:wrap;margin-bottom:1rem;">
					<div style="font-weight:800;color:var(--ink);font-size:1.15rem;" data-i18n="quicktip-title">快捷提示词</div>
					<button id="quicktip-copy" class="skill-copy-btn" data-i18n="quicktip-copy-btn">复制</button>
				</div>
				<p style="margin:0 0 0.9rem;color:var(--ink-2);">
					<span data-i18n="perm-token-label">永久密钥</span>：
					<code style="background:#EAF4FC;color:var(--brand-ink);padding:0.2rem 0.6rem;border-radius:8px;font-weight:700;">mutantcat</code>
				</p>
				<div id="skillapi-quicktip" class="skill-prompt-box">
					<div class="quicktip-section zh-section">
						<p style="margin:0 0 0.3rem;"><strong data-i18n="quicktip-zh-title">【中文】</strong> <span data-i18n="quicktip-zh-request">请向以下地址发送 GET 请求，先取回方法索引，再据此拼装代码（输出 token → 输入 token，命中 Prompt 缓存）：</span></p>
						<p style="margin:0.2rem 0 0.7rem 0;"><code class="api-url-zh" style="word-break:break-word;overflow-wrap:anywhere;">https://www.functioncool.xyz/skillapi?token=mutantcat&q={关键词}&lang={编程语言}</code></p>
						<p data-i18n="quicktip-zh-params" style="margin:0;">参数：token=mutantcat（永久密钥）；q=搜索关键词；lang=语言代码或 all（可选：C、CPP、GO、PYTHON、JAVA、JAVASCRIPT、RUST、MATLAB、PHP、RUBY、VERILOG）。响应：JSON，包含 results（函数数组）、query（原查询）、lang（语言）。</p>
					</div>
					<div class="quicktip-section en-section" style="margin-top:0.8rem;">
						<p style="margin:0 0 0.3rem;"><strong data-i18n="quicktip-en-title">[English]</strong> <span data-i18n="quicktip-en-request">Send a GET request below to fetch method indices first, then assemble code from them (output → input tokens, prompt-cache friendly):</span></p>
						<p style="margin:0.2rem 0 0.7rem 0;"><code class="api-url-en" style="word-break:break-word;overflow-wrap:anywhere;">https://www.functioncool.xyz/skillapi?token=mutantcat&q={keyword}&lang={language}</code></p>
						<p data-i18n="quicktip-en-params" style="margin:0;">Params: token=mutantcat (permanent key); q=search keyword; lang=language code or all (allowed: C, CPP, GO, PYTHON, JAVA, JAVASCRIPT, RUST, MATLAB, PHP, RUBY, VERILOG). Response: JSON with results (array of functions), query (string), lang (string).</p>
					</div>
				</div>
			</section>

			<!-- 仓库地址 -->
			<section class="skill-card reveal reveal-4" style="margin-bottom:3rem;text-align:center;">
				<h3 style="color:var(--ink);font-size:1.15rem;margin-bottom:0.4rem;" data-i18n="skill-repo-title">Skill 安装与源码</h3>
				<p style="color:var(--ink-2);margin-bottom:1rem;" data-i18n="skill-repo-desc">在以下仓库获取可直接安装的 Skill 与接入示例：</p>
				<a class="skill-github-link" href="https://github.com/Mutantcat-Working-Group/FunctionCool-Skill" target="_blank" rel="noopener">
					<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8z"/></svg>
					<span>github.com/Mutantcat-Working-Group/FunctionCool-Skill</span>
				</a>
			</section>
		</div>
	</main>
	<footer>
		<div class="container">
			<p data-i18n="footer-text">&copy; 2025-2026 函数库 | Powered by Mutantcat</p>
			<div class="friend-links">
				<span data-i18n="friend-links">友情链接：</span>
				<a href="https://www.mutantcat.org/" target="_blank" rel="noopener">异猫工作群</a>
				<a href="https://www.fcnesyouxi.top/" target="_blank" rel="noopener">FC/NES游戏</a>
				<a href="https://www.jqshengtian.top/" target="_blank" rel="noopener">学习资料</a>
			</div>
		</div>
	</footer>
	<script>
	// 初始化语言按钮与文案
	document.addEventListener('DOMContentLoaded', function() {
		try {
			setTimeout(function() {
				var current = window.getCurrentLanguage ? window.getCurrentLanguage() : 'zh';
				if (window.setLanguage) window.setLanguage(current);
				var langBtn = document.getElementById('lang-btn');
				if (langBtn) {
					langBtn.textContent = current === 'zh' ? 'English' : '中文';
					langBtn.onclick = function() {
						if (window.toggleLanguage) {
							window.toggleLanguage();
							var cur = window.getCurrentLanguage ? window.getCurrentLanguage() : 'zh';
							langBtn.textContent = cur === 'zh' ? 'English' : '中文';
							updateCopyButtonText(cur);
						}
					};
				}
			}, 100);
		} catch (e) {
			console.error('Language initialization error:', e);
		}
	});

	function updateCopyButtonText(lang) {
		var copyBtn = document.getElementById('quicktip-copy');
		if (copyBtn && !copyBtn.textContent.includes('已复制') && !copyBtn.textContent.includes('copied') && copyBtn.textContent.indexOf('✓') === -1) {
			copyBtn.textContent = lang === 'zh' ? '复制' : 'Copy';
		}
	}

	document.getElementById('quicktip-copy').onclick = function() {
		var tip = document.getElementById('skillapi-quicktip').innerText;
		if (navigator.clipboard) {
			navigator.clipboard.writeText(tip);
		} else {
			var textarea = document.createElement('textarea');
			textarea.value = tip;
			document.body.appendChild(textarea);
			textarea.select();
			document.execCommand('copy');
			document.body.removeChild(textarea);
		}
		this.textContent = '✓ 已复制';
		setTimeout(()=>{
			var currentLang = window.getCurrentLanguage ? window.getCurrentLanguage() : 'zh';
			this.textContent = currentLang === 'zh' ? '复制' : 'Copy';
		}, 1200);
	};
	</script>
</body>
</html>
