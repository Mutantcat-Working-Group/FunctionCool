<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>函数库助力AI - MCP接口发布</title>
	<meta name="description" content="函数库 FunctionCool 发布可被 MCP/自动化工具集成的查询接口，支持关键词与语言参数、Token 访问与文档示例。">
	<link rel="canonical" href="https://www.functioncool.xyz/notes/001">
		<link rel="stylesheet" href="../assets/style.css?v=20250917">
	<link rel="icon" type="image/png" href="../assets/logo.png">
	<link rel="apple-touch-icon" href="../assets/logo.png">
	<style>
		.article { background:#fff;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.08);padding:1.4rem 1.4rem;border:1px solid rgba(102,126,234,0.12);margin-top:20px;}
		.article h1 {font-size:1.8rem;color:#1f2937;margin-bottom:.6rem}
		.article .meta {color:#64748b;font-size:.92rem;margin-bottom:1rem}
		.article p {color:#4b5563;line-height:1.8;font-size:1.02rem;margin:0.6rem 0}
		.article code, .article pre {font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace}
		.article pre {background:#0f172a;color:#e2e8f0;border-radius:10px;padding:1rem;overflow:auto;border:1px solid #334155}
		.back-link {display:inline-block;margin-top:0rem;color:#4f46e5;text-decoration:none}
		.back-link:hover {text-decoration:underline}
	</style>
	<meta name="robots" content="index,follow">
	<meta property="og:title" content="函数库助力AI - MCP接口发布">
	<meta property="og:description" content="函数库 FunctionCool 发布可被 MCP/自动化工具集成的查询接口，支持关键词与语言参数、Token 访问与文档示例。">
	<meta property="og:type" content="article">
	<meta property="og:image" content="https://www.functioncool.xyz/assets/logo.png">
	<meta property="og:url" content="https://www.functioncool.xyz/notes/001">
</head>
<body>
	<header>
		<div class="container">
			<div class="logo">
				<h1><a href="/" aria-label="函数库首页">函数库 FunctionCool</a></h1>
				<p>全世界开发者的函数库</p>
			</div>
			<div class="language-switcher">
				<a href="/mcpapi" class="mcp-link" aria-label="MCP API">MCP</a>
				<a href="/" class="back-link" style="color:#fff;text-decoration:none;border:1px solid rgba(255,255,255,.3);padding:.45rem .9rem;border-radius:20px;background:rgba(255,255,255,.18)">返回首页</a>
			</div>
		</div>
	</header>

	<main>
		<div class="container">
			<article class="article">
				<h1>函数库助力AI - MCP接口发布</h1>
				<div class="meta">发布日期：2025-09-17 · 分类：公告 · 标签：MCP / API / 集成</div>
				<p>我们上线了面向自动化平台与 MCP 工具的查询接口（<a href="/mcpapi">/mcpapi</a>）。通过该接口，您可以按关键词与编程语言检索函数库，获取结构化 JSON 结果，方便在智能体与工作流中复用。</p>
				<p>接口采用 <strong>Token</strong> 访问：支持 30 分钟周期 Token 与永久 Token。您可以在文档页一键获取 Token 并查看快速调用示例。</p>
				<h2 style="margin-top:1rem;color:#4f46e5">快速开始</h2>
				<pre><code>GET https://www.functioncool.xyz/mcpapi?token={your_token}&q={keyword}&lang={language}</code></pre>
				<p>响应字段：<code>results</code>（函数数组）、<code>query</code>、<code>lang</code>。支持的 <code>lang</code> 包括 C、CPP、GO、PYTHON、JAVA、JAVASCRIPT、RUST、MATLAB、PHP、RUBY、VARILOG 或 <code>all</code>。</p>
				<p>欢迎各类 MCP 客户端、自动化平台、插件与机器人接入。如果您在使用过程中遇到问题或有功能建议，欢迎发邮件到 <strong>feedback@mutantcat.org</strong> 与我们交流。</p>
			</article>
		</div>
	</main>

	<footer>
		<div class="container">
			<p>&copy; 2025-2026 函数库 | Powered by Mutantcat</p>
			<div class="friend-links">
				<span>友情链接：</span>
				<a href="https://www.mutantcat.org/" target="_blank" rel="noopener">异猫工作群</a>
				<a href="https://www.fcnesyouxi.top/" target="_blank" rel="noopener">FC/NES游戏</a>
				<a href="https://www.jqshengtian.top/" target="_blank" rel="noopener">学习资料</a>
			</div>
		</div>
	</footer>
</body>
</html>
