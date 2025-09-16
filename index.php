<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>函数库 - 全世界开发者的函数库 | Programmer's Function Library</title>
    <link rel="stylesheet" href="assets/style.css">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3718441900987965"
     crossorigin="anonymous"></script>
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
                
                <form class="search-form" action="search.php" method="GET">
                    <div class="search-input-wrapper">
                        <input 
                            type="text" 
                            id="search-input" 
                            name="q" 
                            placeholder="搜索函数名称、描述或标签..." 
                            required
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