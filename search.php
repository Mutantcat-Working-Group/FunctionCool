<?php
require_once __DIR__ . '/lib/ratelimit.php';
rate_limit_check();

// 语言别名：保留 VARILOG 兼容旧链接，规范名为 VERILOG
$LANG_ALIASES = ['VARILOG' => 'VERILOG'];

// 获取搜索参数
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$language = isset($_GET['lang']) ? $_GET['lang'] : 'all';
if (isset($LANG_ALIASES[strtoupper($language)])) {
    $language = $LANG_ALIASES[strtoupper($language)];
}

// 用于 SEO 动态标题与描述
$baseTitle = '搜索结果 - FunctionCool';
$humanLangMap = [
    'all' => '全部语言',
    'C' => 'C',
    'CPP' => 'C++',
    'GO' => 'Go',
    'PYTHON' => 'Python',
    'JAVA' => 'Java',
    'JAVASCRIPT' => 'JavaScript',
    'RUST' => 'Rust',
    'MATLAB' => 'MATLAB',
    'PHP' => 'PHP',
    'RUBY' => 'Ruby',
    'VERILOG' => 'Verilog'
];
$languageLabel = $humanLangMap[$language] ?? '全部语言';
$seoTitle = $baseTitle;
if ($query !== '') {
    $seoTitle = "{$query} - {$languageLabel} 函数搜索结果 | FunctionCool";
}
$metaDescription = $query === ''
    ? '函数库 FunctionCool 搜索页 - 支持多语言函数、描述、标签搜索，涵盖 C/C++, Go, Python, Java, JavaScript, Rust, MATLAB, PHP。'
    : "关于 '{$query}' 的 {$languageLabel} 函数搜索结果，涵盖名称、描述、标签及代码示例。";

// 搜索函数
function searchFunctions($query, $language) {
    $results = [];
    // 空或纯空白查询直接返回空结果，避免全文扫描和无意义显示
    if ($query === null || trim($query) === '') {
        return [];
    }
    $languages = ['C', 'CPP', 'GO', 'PYTHON', 'JAVA', 'JAVASCRIPT', 'RUST', 'MATLAB', 'PHP', 'RUBY', 'VERILOG'];
    
    // 如果指定了特定语言，只搜索该语言
    if ($language !== 'all' && in_array($language, $languages)) {
        $languages = [$language];
        // 特殊情况：搜索CPP时也包含C语言
        if ($language === 'CPP') {
            $languages = ['C', 'CPP'];
        }
    }
    
    foreach ($languages as $lang) {
        $filename = "functions/{$lang}/" . strtolower($lang) . ".json";
        if (file_exists($filename)) {
            $data = json_decode(file_get_contents($filename), true);
            if ($data) {
                foreach ($data as $func) {
                    // 搜索匹配逻辑
                    $searchText = strtolower($query);
                    $nameZh = strtolower($func['name-zh'] ?? '');
                    $nameEn = strtolower($func['name-en'] ?? '');
                    $descZh = strtolower($func['description-zh'] ?? '');
                    $descEn = strtolower($func['description-en'] ?? '');
                    $tags = implode(' ', array_map('strtolower', $func['tags'] ?? []));
                    
                    if (strpos($nameZh, $searchText) !== false ||
                        strpos($nameEn, $searchText) !== false ||
                        strpos($descZh, $searchText) !== false ||
                        strpos($descEn, $searchText) !== false ||
                        strpos($tags, $searchText) !== false) {
                        
                        $func['language'] = $lang;
                        $results[] = $func;
                    }
                }
            }
        }
    }
    
    return $results;
}

$searchResults = searchFunctions($query, $language);
$encodedQuery = urlencode($query);
$canonical = 'https://www.functioncool.xyz/search';
if ($query !== '') {
    // 仅对 query 添加，lang 为 all 不附加
    $canonical .= '?q=' . $encodedQuery . ($language !== 'all' ? '&lang=' . urlencode($language) : '');
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($seoTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical); ?>">
    <meta name="keywords" content="函数搜索, 编程函数, 代码示例, FunctionCool, 多语言函数库, <?php echo htmlspecialchars($query); ?>, <?php echo $languageLabel; ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="函数库 FunctionCool">
    <meta property="og:locale" content="zh_CN">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:title" content="<?php echo htmlspecialchars($seoTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical); ?>">
    <meta property="og:image" content="https://www.functioncool.xyz/assets/logo.png">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($seoTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="twitter:image" content="https://www.functioncool.xyz/assets/logo.png">

    <!-- JSON-LD: Breadcrumb + SearchAction (可扩展) -->
    <script type="application/ld+json">{
      "@context": "https://schema.org",
      "@type": "CollectionPage",
      "name": "函数搜索结果",
      "url": "<?php echo htmlspecialchars($canonical); ?>",
      "isPartOf": {"@type": "WebSite", "name": "函数库 FunctionCool", "url": "https://www.functioncool.xyz/"},
      "about": "多语言函数搜索结果页面",
      "inLanguage": ["zh-CN","en"],
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://www.functioncool.xyz/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }</script>

    <link rel="stylesheet" href="assets/style.css">
    <!-- Favicon Start -->
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="apple-touch-icon" href="assets/logo.png">
    <!-- Favicon End -->
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1><a href="/" id="site-title">函数库</a></h1>
                <p id="site-subtitle">全世界开发者的函数库</p>
            </div>
            <div class="language-switcher">
                <button id="lang-btn" onclick="toggleLanguage()">English</button>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="search-header">
                <form class="search-form compact" action="/search" method="GET" role="search" aria-label="站内函数搜索">
                    <div class="search-input-wrapper">
                        <input 
                            type="text" 
                            id="search-input" 
                            name="q" 
                            value="<?php echo htmlspecialchars($query); ?>"
                            placeholder="搜索函数名称、描述或标签..."
                            aria-label="搜索关键词"
                            autocomplete="off"
                        >
                    </div>
                    
                    <div class="language-filter inline">
                        <select name="lang" id="lang-select">
                            <option value="all" <?php echo $language === 'all' ? 'selected' : ''; ?> id="opt-all">全部语言</option>
                            <option value="C" <?php echo $language === 'C' ? 'selected' : ''; ?>>C</option>
                            <option value="CPP" <?php echo $language === 'CPP' ? 'selected' : ''; ?>>C++</option>
                            <option value="GO" <?php echo $language === 'GO' ? 'selected' : ''; ?>>Go</option>
                            <option value="PYTHON" <?php echo $language === 'PYTHON' ? 'selected' : ''; ?>>Python</option>
                            <option value="JAVA" <?php echo $language === 'JAVA' ? 'selected' : ''; ?>>Java</option>
                            <option value="JAVASCRIPT" <?php echo $language === 'JAVASCRIPT' ? 'selected' : ''; ?>>JavaScript</option>
                            <option value="RUST" <?php echo $language === 'RUST' ? 'selected' : ''; ?>>Rust</option>
                            <option value="MATLAB" <?php echo $language === 'MATLAB' ? 'selected' : ''; ?>>MATLAB</option>
                            <option value="PHP" <?php echo $language === 'PHP' ? 'selected' : ''; ?>>PHP</option>
                            <option value="RUBY" <?php echo $language === 'RUBY' ? 'selected' : ''; ?>>Ruby</option>
                            <option value="VERILOG" <?php echo $language === 'VERILOG' ? 'selected' : ''; ?>>Verilog</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="search-btn" id="search-btn">搜索</button>
                </form>
            </div>

            <div class="results-section">
                <div class="results-header">
                    <h2 id="results-title">搜索结果</h2>
                    <p id="results-count">
                        <?php if ($query !== '' && trim($query) !== ''): ?>
                            为 "<?php echo htmlspecialchars($query); ?>" 找到 <?php echo count($searchResults); ?> 个结果
                        <?php endif; ?>
                    </p>
                </div>

                <?php if (!empty($searchResults)): ?>
                    <div class="results-grid">
                        <?php foreach ($searchResults as $func): ?>
                            <div class="function-card">
                                <div class="function-header">
                                    <div class="function-name-section">
                                        <div class="function-name">
                                            <div class="name-wrapper">
                                                <h3 class="name-zh"><?php echo htmlspecialchars($func['name-zh'] ?? ''); ?></h3>
                                                <h3 class="name-en" style="display:none;">&shy;<?php echo htmlspecialchars($func['name-en'] ?? ''); ?></h3>
                                            </div>
                                            <?php if (!empty($func['tags'])): ?>
                                                <div class="function-tags">
                                                    <?php foreach ($func['tags'] as $tag): ?>
                                                        <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="language-tag"><?php echo $func['language']; ?></span>
                                </div>
                                
                                <div class="function-description">
                                    <p class="desc-zh"><?php echo htmlspecialchars($func['description-zh'] ?? ''); ?></p>
                                    <p class="desc-en" style="display:none;">&shy;<?php echo htmlspecialchars($func['description-en'] ?? ''); ?></p>
                                </div>

                                <div class="function-details">
                                    <div class="params">
                                        <strong data-i18n="params-label">参数：</strong>
                                        <?php if (!empty($func['input'])): ?>
                                            <?php for ($i = 0; $i < count($func['input']); $i++): ?>
                                                <span class="param"><?php echo $func['input_type'][$i] ?? 'unknown'; ?> <?php echo $func['input'][$i]; ?></span>
                                            <?php endfor; ?>
                                        <?php else: ?>
                                            <span data-i18n="no-params">无</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="returns">
                                        <strong data-i18n="returns-label">返回：</strong>
                                        <?php if (!empty($func['return'])): ?>
                                            <?php for ($i = 0; $i < count($func['return']); $i++): ?>
                                                <span class="return"><?php echo $func['return_type'][$i] ?? 'unknown'; ?> <?php echo $func['return'][$i]; ?></span>
                                            <?php endfor; ?>
                                        <?php else: ?>
                                            <span>void</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="scores">
                                    <div class="score">
                                        <span data-i18n="time-score-label">时间复杂度:</span>
                                        <span class="score-value"><?php echo $func['timer_score'] ?? 'N/A'; ?>/100</span>
                                    </div>
                                    <div class="score">
                                        <span data-i18n="memory-score-label">空间复杂度:</span>
                                        <span class="score-value"><?php echo $func['memory_score'] ?? 'N/A'; ?>/100</span>
                                    </div>
                                </div>

                                <div class="code-section">
                                    <div class="code-header">
                                        <button class="toggle-code" id="show-code">显示代码</button>
                                        <button class="copy-code" title="复制代码" style="display:none;">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="code-container" style="display:none;">
                                        <pre class="code-block"><code><?php echo htmlspecialchars($func['code'][0] ?? ''); ?></code></pre>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <?php if (!empty($query)): ?>
                            <h3 id="no-results-title">未找到匹配的结果</h3>
                            <p id="no-results-desc">请尝试使用不同的关键词或选择其他编程语言。</p>
                        <?php else: ?>
                            <h3 id="empty-query-title">请输入搜索关键词</h3>
                            <p id="empty-query-desc">在上方搜索框中输入您要查找的函数名称或描述。</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

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

    <script src="assets/i18n.js?v=20260518"></script>
    <script>
        // 代码显示/隐藏切换
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-code');
            const copyButtons = document.querySelectorAll('.copy-code');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const codeContainer = this.closest('.code-section').querySelector('.code-container');
                    const copyButton = this.closest('.code-section').querySelector('.copy-code');
                    
                    if (codeContainer.style.display === 'none') {
                        codeContainer.style.display = 'block';
                        copyButton.style.display = 'inline-flex';
                        this.textContent = getCurrentLanguage() === 'zh' ? '隐藏代码' : 'Hide Code';
                    } else {
                        codeContainer.style.display = 'none';
                        copyButton.style.display = 'none';
                        this.textContent = getCurrentLanguage() === 'zh' ? '显示代码' : 'Show Code';
                    }
                });
            });

            // 代码复制功能
            copyButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const codeBlock = this.closest('.code-section').querySelector('code');
                    const text = codeBlock.textContent;
                    
                    try {
                        await navigator.clipboard.writeText(text);
                        
                        // 显示复制成功提示
                        const originalTitle = this.title;
                        const originalHTML = this.innerHTML;
                        this.title = getCurrentLanguage() === 'zh' ? '已复制!' : 'Copied!';
                        this.innerHTML = `
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                            </svg>
                        `;
                        this.style.color = '#22c55e';
                        
                        // 2秒后恢复原状
                        setTimeout(() => {
                            this.title = originalTitle;
                            this.innerHTML = originalHTML;
                            this.style.color = '';
                        }, 2000);
                        
                    } catch (err) {
                        console.error('复制失败:', err);
                        // 降级方案：选中文本让用户手动复制
                        const range = document.createRange();
                        range.selectNode(codeBlock);
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(range);
                    }
                });
            });
        });
    </script>
</body>
</html>
