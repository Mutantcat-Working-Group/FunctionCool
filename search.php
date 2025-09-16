<?php
// 获取搜索参数
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$language = isset($_GET['lang']) ? $_GET['lang'] : 'all';

// 搜索函数
function searchFunctions($query, $language) {
    $results = [];
    // 空或纯空白查询直接返回空结果，避免全文扫描和无意义显示
    if ($query === null || trim($query) === '') {
        return [];
    }
    $languages = ['C', 'CPP', 'GO', 'PYTHON'];
    
    // 如果指定了特定语言，只搜索该语言
    if ($language !== 'all' && in_array($language, $languages)) {
        $languages = [$language];
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
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搜索结果 - FunctionCool</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1><a href="index.php" id="site-title">函数库</a></h1>
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
                <form class="search-form compact" action="search.php" method="GET">
                    <div class="search-input-wrapper">
                        <input 
                            type="text" 
                            id="search-input" 
                            name="q" 
                            value="<?php echo htmlspecialchars($query); ?>"
                            placeholder="搜索函数名称、描述或标签..."
                            aria-label="搜索关键词"
                        >
                    </div>
                    
                    <div class="language-filter inline">
                        <select name="lang" id="lang-select">
                            <option value="all" <?php echo $language === 'all' ? 'selected' : ''; ?> id="opt-all">全部语言</option>
                            <option value="C" <?php echo $language === 'C' ? 'selected' : ''; ?>>C</option>
                            <option value="CPP" <?php echo $language === 'CPP' ? 'selected' : ''; ?>>C++</option>
                            <option value="GO" <?php echo $language === 'GO' ? 'selected' : ''; ?>>Go</option>
                            <option value="PYTHON" <?php echo $language === 'PYTHON' ? 'selected' : ''; ?>>Python</option>
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
                                    <div class="function-name">
                                        <h3 class="name-zh"><?php echo htmlspecialchars($func['name-zh'] ?? ''); ?></h3>
                                        <h3 class="name-en" style="display:none;"><?php echo htmlspecialchars($func['name-en'] ?? ''); ?></h3>
                                    </div>
                                    <span class="language-tag"><?php echo $func['language']; ?></span>
                                </div>
                                
                                <div class="function-description">
                                    <p class="desc-zh"><?php echo htmlspecialchars($func['description-zh'] ?? ''); ?></p>
                                    <p class="desc-en" style="display:none;"><?php echo htmlspecialchars($func['description-en'] ?? ''); ?></p>
                                </div>

                                <div class="function-details">
                                    <div class="params">
                                        <strong id="params-label">参数：</strong>
                                        <?php if (!empty($func['input'])): ?>
                                            <?php for ($i = 0; $i < count($func['input']); $i++): ?>
                                                <span class="param"><?php echo $func['input_type'][$i] ?? 'unknown'; ?> <?php echo $func['input'][$i]; ?></span>
                                            <?php endfor; ?>
                                        <?php else: ?>
                                            <span id="no-params">无</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="returns">
                                        <strong id="returns-label">返回：</strong>
                                        <?php if (!empty($func['return'])): ?>
                                            <?php for ($i = 0; $i < count($func['return']); $i++): ?>
                                                <span class="return"><?php echo $func['return_type'][$i] ?? 'unknown'; ?> <?php echo $func['return'][$i]; ?></span>
                                            <?php endfor; ?>
                                        <?php else: ?>
                                            <span>void</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if (!empty($func['tags'])): ?>
                                    <div class="tags">
                                        <?php foreach ($func['tags'] as $tag): ?>
                                            <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="scores">
                                    <div class="score">
                                        <span id="time-score-label">时间复杂度:</span>
                                        <span class="score-value"><?php echo $func['timer_score'] ?? 'N/A'; ?>/100</span>
                                    </div>
                                    <div class="score">
                                        <span id="memory-score-label">空间复杂度:</span>
                                        <span class="score-value"><?php echo $func['memory_score'] ?? 'N/A'; ?>/100</span>
                                    </div>
                                </div>

                                <div class="code-section">
                                    <button class="toggle-code" id="show-code">显示代码</button>
                                    <pre class="code-block" style="display:none;"><code><?php echo htmlspecialchars($func['code'][0] ?? ''); ?></code></pre>
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
            <p id="footer-text">&copy; 2025 函数库 | Powered by Mutantcat</p>
        </div>
    </footer>

    <script src="assets/i18n.js"></script>
    <script>
        // 代码显示/隐藏切换
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-code');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const codeBlock = this.nextElementSibling;
                    if (codeBlock.style.display === 'none') {
                        codeBlock.style.display = 'block';
                        this.textContent = getCurrentLanguage() === 'zh' ? '隐藏代码' : 'Hide Code';
                    } else {
                        codeBlock.style.display = 'none';
                        this.textContent = getCurrentLanguage() === 'zh' ? '显示代码' : 'Show Code';
                    }
                });
            });
        });
    </script>
</body>
</html>
