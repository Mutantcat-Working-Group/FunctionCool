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

    <link rel="stylesheet" href="assets/style.css">
    <meta name="google-site-verification" content="gDHkEX8quz2rZV-IhC2VDjSt8Lzva5bln1N3rkkBJPA" />
    <meta name="msvalidate.01" content="09EFDE13A2FAD0169413AF8FAFCC323A" />
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3718441900987965" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1 id="site-title">函数库</h1>
                <p id="site-subtitle">全世界开发者的函数库</p>
            </div>
            <div class="language-switcher">
                <button id="lang-btn" onclick="toggleLanguage()">English</button>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="search-section">
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
                        </div>
                    </div>
                    
                    <button type="submit" class="search-btn" id="search-btn">搜索</button>
                </form>
            </div>

            <div class="features-section">
                <div class="features-grid">
                    <div class="feature-card">
                        <h3 id="feature-2-title">快速搜索</h3>
                        <p id="feature-2-desc">通过函数名、描述、标签快速定位所需函数</p>
                    </div>
                    <div class="feature-card">
                        <h3 id="feature-3-title">代码示例</h3>
                        <p id="feature-3-desc">每个函数都提供完整的代码示例和使用说明</p>
                    </div>
                    <div class="feature-card">
                        <h3 id="feature-4-title">性能评分</h3>
                        <p id="feature-4-desc">提供时间复杂度和空间复杂度评分参考</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p id="footer-text">&copy; 2025 函数库 | Powered by Mutantcat</p>
        </div>
    </footer>

    <script src="assets/i18n.js"></script>
</body>
</html>