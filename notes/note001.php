<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>函数库助力AI - Skill 接口发布</title>
	<meta name="description" content="函数库 FunctionCool 发布 Skill 接口：让 AI 先查询方法索引再拼装代码，把昂贵的输出 token 折成便宜的输入 token，并最大化 Prompt 缓存命中。">
	<link rel="canonical" href="https://functioncool.mutantcat.org/notes/001">
		<link rel="stylesheet" href="../assets/style.css?v=20260610">
	<link rel="icon" type="image/png" href="../assets/logo.png">
	<link rel="apple-touch-icon" href="../assets/logo.png">
	<style>
		.article { background:#fff;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.08);padding:1.4rem 1.4rem;border:1px solid rgba(46,109,164,0.12);margin-top:20px;}
		.article h1 {font-size:1.8rem;color:#1f2937;margin-bottom:.6rem}
		.article .meta {color:#64748b;font-size:.92rem;margin-bottom:1rem}
		.article p {color:#4b5563;line-height:1.8;font-size:1.02rem;margin:0.6rem 0}
		.article code, .article pre {font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace}
		.article pre {background:#0f172a;color:#e2e8f0;border-radius:10px;padding:1rem;overflow:auto;border:1px solid #334155}
		.article ul {margin:0.4rem 0 0.8rem 1.4rem;color:#4b5563;line-height:1.75}
		.article li {margin-bottom:0.4rem}
		.back-link {display:inline-block;margin-top:0rem;color:#1B4E7A;text-decoration:none}
		.back-link:hover {text-decoration:underline}
	</style>
	<meta name="robots" content="index,follow">
	<meta property="og:title" content="函数库助力AI - Skill 接口发布">
	<meta property="og:description" content="函数库 FunctionCool 发布 Skill 接口：让 AI 先查询方法索引再拼装代码，把昂贵的输出 token 折成便宜的输入 token，并最大化 Prompt 缓存命中。">
	<meta property="og:type" content="article">
	<meta property="og:image" content="https://functioncool.mutantcat.org/assets/logo.png">
	<meta property="og:url" content="https://functioncool.mutantcat.org/notes/001">
</head>
<body>
	<header>
		<div class="container">
			<div class="logo">
				<h1><a href="/" aria-label="函数库首页">函数库 FunctionCool</a></h1>
				<p>全世界开发者的函数库</p>
			</div>
			<div class="language-switcher">
				<a href="/skillapi" class="skill-link" aria-label="Skill API">Skill</a>
				<a href="/" class="back-link" style="color:#fff;text-decoration:none;border:1px solid rgba(255,255,255,.3);padding:.45rem .9rem;border-radius:20px;background:rgba(255,255,255,.18)">返回首页</a>
			</div>
		</div>
	</header>

	<main>
		<div class="container">
			<article class="article">
				<h1>函数库助力AI - Skill 接口发布</h1>
				<div class="meta">发布日期：2025-09-17 · 分类：公告 · 标签：Skill / API / AI 集成</div>
				<p>我们上线了面向 AI 智能体、IDE 插件与自动化工作流的 <a href="/skillapi">Skill 接口</a>。它不只是又一个"按关键词检索函数"的 API——它是为压缩大模型 token 成本而设计的工作流入口。</p>

				<h2 style="margin-top:1.2rem;color:#1B4E7A">为什么 AI 需要这个 Skill</h2>
				<p>让模型从零"现编"一个函数是昂贵且不可靠的：每一个 token 都按"输出"计费，且容易出现幻觉实现。Skill 接口换一种工作姿态：</p>
				<ul>
					<li><strong>把昂贵输出折为便宜输入</strong>：AI 先调用本 Skill 取回方法索引（签名 / 说明 / 标签 / 复杂度评分），再据此拼装最终代码。模型不必把整段函数体"打"出来——把贵的输出 token 折算成便宜得多的输入 token。</li>
					<li><strong>更高的 Prompt 缓存命中</strong>：函数库内容长期稳定，作为 Skill 上下文最契合各家厂商的提示词缓存特性。同一类查询重复出现时，实际计费 token 趋近于零。</li>
				</ul>

				<h2 style="margin-top:1.2rem;color:#1B4E7A">快速开始</h2>
				<pre><code>GET https://functioncool.mutantcat.org/skillapi?token={your_token}&q={keyword}&lang={language}</code></pre>
				<p>接口采用 <strong>Token</strong> 访问：支持 30 分钟周期 Token 与永久 Token。可以在 <a href="/skillapi">/skillapi</a> 文档页一键获取 Token 并查看快速调用示例。</p>
				<p>响应字段：<code>results</code>（函数数组，含名称 / 签名 / 描述 / 标签 / 时间空间评分 / 代码示例）、<code>query</code>、<code>lang</code>。支持的 <code>lang</code> 包括 C、CPP、GO、PYTHON、JAVA、JAVASCRIPT、RUST、MATLAB、PHP、RUBY、VERILOG 或 <code>all</code>。</p>

				<p>欢迎各类 AI 客户端、自动化平台、IDE 插件与机器人接入。如果您在使用过程中遇到问题或有功能建议，欢迎发邮件到 <strong>mutantcat_org@outlook.com</strong> 与我们交流。</p>

				<p style="color:#64748b;font-size:0.92rem;margin-top:1.2rem;">注：本接口此前以 "MCP 接口" 名义发布，现统一命名为 Skill 接口。旧的 <code>/mcpapi</code> 链接会自动 301 跳转到 <code>/skillapi</code>，老集成无需改动即可继续使用。</p>
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
