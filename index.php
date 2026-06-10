<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('#^/search/?$#', $path)) {
    require __DIR__ . '/search.php';
    exit;
}

if (preg_match('#^/skillapi/?$#', $path)) {
    require __DIR__ . '/skillapi.php';
    exit;
}

// 兼容旧链接：/mcpapi -> /skillapi (301)
if (preg_match('#^/mcpapi/?$#', $path)) {
    $qs = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';
    header('Location: /skillapi' . $qs, true, 301);
    exit;
}

if (preg_match('#^/notes/(\d{3,})/?$#', $path, $m)) {
    require __DIR__ . '/notes/note' . $m[1] . '.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>函数库 - 全世界开发者的函数库 | Programmer's Function Library</title>
    <meta name="description" content="函数库(FunctionCool) - 覆盖 C/C++, Go, Python, Java, JavaScript, Rust, MATLAB, PHP 等多语言的常用函数与代码示例，支持中英文切换，帮助开发者快速查找与复用。">
    <meta name="keywords" content="函数库, Function Library, 编程函数, 代码示例, C, C++, Go, Python, Java, JavaScript, Rust, MATLAB, PHP, 常用函数, 算法, 代码片段">
    <meta name="author" content="Mutantcat Working Group">
    <link rel="canonical" href="https://www.functioncool.xyz/">
    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow,sitelinkssearchbox">
    <meta name="bingbot" content="index,follow">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="zh_CN">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:site_name" content="函数库 FunctionCool">
    <meta property="og:title" content="函数库 - 多语言编程函数与代码示例库">
    <meta property="og:description" content="多语言常用函数速查：C/C++, Go, Python, Java, JavaScript, Rust, MATLAB, PHP。结构化整理 + 性能评分，支持中英文。">
    <meta property="og:url" content="https://www.functioncool.xyz/">
    <meta property="og:image" content="https://www.functioncool.xyz/assets/logo.png">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="函数库 FunctionCool - 多语言函数速查">
    <meta name="twitter:description" content="收录多语言常用函数与代码示例，支持中英文切换与搜索。">
    <meta name="twitter:image" content="https://www.functioncool.xyz/assets/logo.png">
    <meta name="referrer" content="no-referrer-when-downgrade" />

    <!-- Favicon Start -->
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="apple-touch-icon" href="assets/logo.png">
    <!-- Favicon End -->

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">{
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "函数库 FunctionCool",
      "url": "https://www.functioncool.xyz/",
      "inLanguage": ["zh-CN","en"],
      "description": "多语言编程函数与代码示例集合，支持搜索与中英文切换。",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://www.functioncool.xyz/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }</script>

    <link rel="stylesheet" href="assets/style.css?v=20260603">
    <meta name="google-site-verification" content="gDHkEX8quz2rZV-IhC2VDjSt8Lzva5bln1N3rkkBJPA" />
    <meta name="msvalidate.01" content="09EFDE13A2FAD0169413AF8FAFCC323A" />
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1 id="site-title">函数库</h1>
                <p id="site-subtitle">全世界开发者的函数库</p>
            </div>
            <div class="language-switcher">
                <a href="/skillapi" class="skill-link" aria-label="Skill API">Skill</a>
                <button id="lang-btn" onclick="toggleLanguage()">English</button>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="search-section reveal reveal-1">
                <h2 id="main-title">搜索函数库</h2>
                <p id="main-description">快速查找您需要的编程函数和代码示例</p>
                
                <form class="search-form" action="search" method="GET" role="search" aria-label="站内函数搜索">
                    <div class="search-input-wrapper">
                        <input 
                            type="text" 
                            id="search-input" 
                            name="q" 
                            placeholder="搜索函数名称、描述或标签..." 
                            required
                            autocomplete="off"
                        >
                    </div>
                    
                    <div class="language-filter">
                        <label id="lang-filter-label">编程语言：</label>
                        <div class="language-options">
                            <label class="lang-option">
                                <input type="radio" name="lang" value="all" checked>
                                <span id="lang-all">全部</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="C">
                                <span>C</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="CPP">
                                <span>C++</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="GO">
                                <span>Go</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="PYTHON">
                                <span>Python</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="JAVA">
                                <span>Java</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="JAVASCRIPT">
                                <span>JavaScript</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="RUST">
                                <span>Rust</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="MATLAB">
                                <span>MATLAB</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="PHP">
                                <span>PHP</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="RUBY">
                                <span>Ruby</span>
                            </label>
                            <label class="lang-option">
                                <input type="radio" name="lang" value="VERILOG">
                                <span>Verilog</span>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="search-btn" id="search-btn">搜索</button>
                </form>
            </div>

            <div class="features-section">
                <div class="features-grid">
                    <div class="feature-card reveal reveal-1">
                        <h3 id="feature-2-title">快速搜索</h3>
                        <p id="feature-2-desc">通过函数名、描述、标签快速定位所需函数</p>
                    </div>
                    <div class="feature-card reveal reveal-2">
                        <h3 id="feature-3-title">代码示例</h3>
                        <p id="feature-3-desc">每个函数都提供完整的代码示例和使用说明</p>
                    </div>
                    <div class="feature-card reveal reveal-3">
                        <h3 id="feature-4-title">性能评分</h3>
                        <p id="feature-4-desc">提供时间复杂度和空间复杂度评分参考</p>
                    </div>
                    <a class="feature-card reveal reveal-4" href="/skillapi" style="text-decoration:none;color:inherit;display:block;">
                        <h3 id="feature-skill-title">AI Skill 接入</h3>
                        <p id="feature-skill-desc">让 AI 先查再写：把昂贵的输出 token 折成便宜的输入 token，并命中 Prompt 缓存</p>
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    <!-- 最新文章模块 -->
    <div class="container">
        <section class="latest-posts" aria-label="最新文章">
            <h2>最新文章</h2>
            <div class="latest-list">
                <a class="post-item reveal reveal-1" href="/notes/003">
                    <div class="post-meta">
                        <span class="post-date">2026-05-18</span>
                        <span class="post-tag">效率</span>
                    </div>
                    <h3 class="post-title">函数库加速开发的秘密</h3>
                    <p class="post-desc">探索函数库如何通过代码复用、减少重复造轮子、降低认知负荷来加速软件开发，让开发者聚焦业务逻辑而非基础实现。</p>
                </a>
                <a class="post-item reveal reveal-2" href="/notes/002">
                    <div class="post-meta">
                        <span class="post-date">2025-09-29</span>
                        <span class="post-tag">教育</span>
                    </div>
                    <h3 class="post-title">给大学编程课的有力帮助</h3>
                    <p class="post-desc">函数库为大学生提供编程学习资源，快速查询常用函数，帮助理解算法和数据结构，提高编程效率。</p>
                </a>
                <a class="post-item reveal reveal-3" href="/notes/001">
                    <div class="post-meta">
                        <span class="post-date">2025-09-17</span>
                        <span class="post-tag">公告</span>
                    </div>
                    <h3 class="post-title">函数库助力AI - Skill 接口发布</h3>
                    <p class="post-desc">我们上线了面向 AI 智能体的函数库 Skill 接口：让模型先查询方法索引再拼装代码，把贵的输出 token 折成便宜的输入 token，并最大化提示词缓存命中。</p>
                </a>
            </div>
        </section>
    </div>

    <!-- 信息区块：支持我们 / 勘误与贡献 / 免责声明 -->
    <div class="container">
        <section class="info-section" aria-label="站点说明与支持">
            <div class="info-grid">
                <div class="info-card support-card">
                    <h3>支持我们</h3>
                    <div class="support-content">
                        <p class="support-text">您可以使用 微信、支付宝 扫描下方的二维码进行支持。您的每一份赞助都将用于本站服务器的续费、域名维护以及日常运营开支。</p>
                        <img class="support-qr-image" src="assets/all_zsm.png" alt="微信、支付宝赞助二维码">
                    </div>
                </div>

                <div class="info-card">
                    <h3>勘误与贡献</h3>
                    <p>此项目是一个公益项目，我们为了减少成本（可持续发展），每10秒限制单一用户请求次数为20次。如果您想分享一些相关的资料或者发现哪些资料有问题或侵犯了您的权益，请发邮件到 feedback@mutantcat.org，我们会认真处理您的问题和反馈。</p>
                </div>

                <div class="info-card">
                    <h3>免责声明</h3>
                    <p>本站所有资源均来自互联网公开渠道，仅供学习交流使用；本站尽力确保资源的可用性，但无法保证所有内容的绝对准确与完整；如有侵权内容，请联系我们，核实后将立即删除；使用本站资源时应遵守相关法律法规，不得用于商业用途。</p>
                </div>
            </div>
        </section>
    </div>

    <footer>
        <div class="container">
            <p id="footer-text">&copy; 2025-2026 函数库 | Powered by Mutantcat</p>
            <div class="friend-links">
                <span id="friend-links-text">友情链接：</span>
                <a href="https://www.mutantcat.org/" target="_blank" rel="noopener">异猫工作群</a>
                <a href="https://www.fcnesyouxi.top/" target="_blank" rel="noopener">FC/NES游戏</a>
                <a href="https://www.jqshengtian.top/" target="_blank" rel="noopener">学习资料</a>
            </div>
        </div>
    </footer>

    <script src="assets/i18n.js?v=20260531"></script>
</body>
</html>