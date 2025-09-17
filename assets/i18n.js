// 国际化文本配置
const i18nTexts = {
    zh: {
        // 网站标题
        'site-title': '函数库',
        'site-subtitle': '全世界开发者的函数库',
        'main-title': '搜索函数库',
        'main-description': '快速查找您需要的编程函数和代码示例',
        'search-btn': '搜索',
        'lang-btn': 'English',
        
        // 搜索相关
        'search-input': '搜索函数名称、描述或标签...',
        'lang-filter-label': '编程语言：',
        'lang-all': '全部',
        'opt-all': '全部语言',
        
        // 特性介绍
        'feature-1-title': '多语言支持',
        'feature-1-desc': '支持C、C++、Go、Python、Java、JavaScript、Rust、MATLAB等主流编程语言',
        'feature-2-title': '快速搜索',
        'feature-2-desc': '通过函数名、描述、标签快速定位所需函数',
        'feature-3-title': '代码示例',
        'feature-3-desc': '每个函数都提供完整的代码示例和使用说明',
        'feature-4-title': '性能评分',
        'feature-4-desc': '提供时间复杂度和空间复杂度评分参考',
        
        // 搜索结果页
        'results-title': '搜索结果',
        'params-label': '参数：',
        'returns-label': '返回：',
        'no-params': '无',
        'time-score-label': '时间复杂度:',
        'memory-score-label': '空间复杂度:',
        'show-code': '显示代码',
        'hide-code': '隐藏代码',
    'results-count-pattern': '为 "${query}" 找到 ${count} 个结果',
        
        // 无结果页面
        'no-results-title': '未找到匹配的结果',
        'no-results-desc': '请尝试使用不同的关键词或选择其他编程语言。',
        'empty-query-title': '请输入搜索关键词',
        'empty-query-desc': '在上方搜索框中输入您要查找的函数名称或描述。',
        
        // 底部
        'footer-text': '© 2025 函数库 | Powered by Mutantcat',
        
        // 404 页面
        'error-404-title': '页面未找到',
        'error-404-message': '抱歉，您访问的页面不存在。可能是链接已过期，或者您输入了错误的网址。',
        'back-home': '返回首页',
        'goto-search': '搜索函数',
        'suggestions-title': '您可以尝试：',
        'suggestion-1': '检查网址拼写是否正确',
        'suggestion-2': '使用上方的搜索功能查找函数',
        'suggestion-3': '访问 MCP API 了解接口文档',
        'suggestion-4': '如果问题持续，请联系我们：shun_@outlook.com',
        
        // MCP 接口页面
        'home-link': '返回首页',
        'mcp-title': '函数库-MCP可集成接口',
        'mcp-subtitle': '为自动化工具/平台提供函数库检索服务',
        'mcp-description-title': '接口说明',
        'mcp-description-content': '本接口用于自动化平台、MCP 工具等集成查询函数库。<br>支持按关键词和语言检索，返回 JSON 格式结果。<br>需先获取临时 token，免费 token 有效期 30 分钟。',
        'get-token-btn': '获取免费 token',
        'quicktip-title': '快捷提示词',
        'quicktip-copy-btn': '一键复制',
        'api-endpoint-desc': '接口地址：<code>/mcpapi?token={你的token}&q={关键词}&lang={编程语言}</code>',
        'token-requirement-desc': 'token 需通过下方按钮获取，不能直接爬取，谢谢配合',
        'response-fields-desc': '返回字段：results（函数列表[数组]）、query、lang',
        'addr-table-caption': '接口地址表',
        'region-header': '地区',
        'address-header': '推荐地址',
        'international-region': '国际',
        'china-region': '中国地区',
        'lang-table-caption': '支持语言及 lang 参数对照表',
        'language-header': '语言名称',
        'param-header': 'lang 参数值',
        'token-result-pattern': '您的 token：${token}（30分钟有效）',
        'quicktip-zh-title': '【中文】',
        'quicktip-zh-request': '请向以下地址发送 GET 请求，获取基础函数与相关函数：',
        'quicktip-zh-params': '参数：token=临时或永久 token；q=搜索关键词；lang=语言代码或 all（可选：C、CPP、GO、PYTHON、JAVA、JAVASCRIPT、RUST、MATLAB、PHP、RUBY、VARILOG）。响应：JSON，包含 results（函数数组）、query（原查询）、lang（语言）。',
        'quicktip-en-title': '[English]',
        'quicktip-en-request': 'Send a GET request to fetch base and related functions:',
        'quicktip-en-params': 'Params: token=temporary or permanent token; q=search keyword; lang=language code or all (allowed: C, CPP, GO, PYTHON, JAVA, JAVASCRIPT, RUST, MATLAB, PHP, RUBY, VARILOG). Response: JSON with results (array of functions), query (string), lang (string).'
    },
    en: {
        // 网站标题
        'site-title': 'FunctionCool',
        'site-subtitle': 'Programmer\'s Function Library',
        'main-title': 'Search Function Library',
        'main-description': 'Quickly find the programming functions and code examples you need',
        'search-btn': 'Search',
        'lang-btn': '中文',
        
        // 搜索相关
        'search-input': 'Search function names, descriptions or tags...',
        'lang-filter-label': 'Programming Language:',
        'lang-all': 'All',
        'opt-all': 'All Languages',
        
        // 特性介绍
        'feature-1-title': 'Multi-language Support',
        'feature-1-desc': 'Support for mainstream programming languages like C, C++, Go, Python, Java, JavaScript, Rust, MATLAB',
        'feature-2-title': 'Fast Search',
        'feature-2-desc': 'Quickly locate functions by name, description, or tags',
        'feature-3-title': 'Code Examples',
        'feature-3-desc': 'Complete code examples and usage instructions for every function',
        'feature-4-title': 'Performance Scores',
        'feature-4-desc': 'Time and space complexity score references',
        
        // 搜索结果页
        'results-title': 'Search Results',
        'params-label': 'Parameters:',
        'returns-label': 'Returns:',
        'no-params': 'None',
        'time-score-label': 'Time Complexity:',
        'memory-score-label': 'Space Complexity:',
        'show-code': 'Show Code',
        'hide-code': 'Hide Code',
    'results-count-pattern': 'Found ${count} results for "${query}"',
        
        // 无结果页面
        'no-results-title': 'No matching results found',
        'no-results-desc': 'Please try using different keywords or select other programming languages.',
        'empty-query-title': 'Please enter search keywords',
        'empty-query-desc': 'Enter the function name or description you want to find in the search box above.',
        
        // 底部
        'footer-text': '© 2025 FunctionCool | Powered by Mutantcat',
        
        // 404 页面
        'error-404-title': 'Page Not Found',
        'error-404-message': 'Sorry, the page you are looking for does not exist. The link may have expired, or you may have entered an incorrect URL.',
        'back-home': 'Back to Home',
        'goto-search': 'Search Functions',
        'suggestions-title': 'You can try:',
        'suggestion-1': 'Check if the URL spelling is correct',
        'suggestion-2': 'Use the search function above to find functions',
        'suggestion-3': 'Visit MCP API to learn about interface documentation',
        'suggestion-4': 'If the problem persists, please contact us: shun_@outlook.com',
        
        // MCP 接口页面
        'home-link': 'Home',
        'mcp-title': 'MCP Integration API',
        'mcp-subtitle': 'Function library search service for automation tools/platforms',
        'mcp-description-title': 'API Documentation',
        'mcp-description-content': 'This API is for automation platforms, MCP tools and other integrations to query function library.<br>Supports keyword and language search, returns JSON format results.<br>Temporary token required, free token valid for 30 minutes.',
        'get-token-btn': 'Get Free Token',
        'quicktip-title': 'Quick Prompt',
        'quicktip-copy-btn': 'Copy',
        'api-endpoint-desc': 'API Endpoint: <code>/mcpapi?token={your_token}&q={keyword}&lang={language}</code>',
        'token-requirement-desc': 'Token must be obtained via the button below, direct crawling not allowed',
        'response-fields-desc': 'Response fields: results (function array), query, lang',
        'addr-table-caption': 'API Endpoint Addresses',
        'region-header': 'Region',
        'address-header': 'Recommended Address',
        'international-region': 'International',
        'china-region': 'China',
        'lang-table-caption': 'Supported Languages & lang Parameter Reference',
        'language-header': 'Language Name',
        'param-header': 'lang Parameter Value',
        'token-result-pattern': 'Your token: ${token} (valid for 30 minutes)',
        'quicktip-zh-title': '【中文】',
        'quicktip-zh-request': 'Please send a GET request to the following address to get basic and related functions:',
        'quicktip-zh-params': 'Parameters: token=temporary or permanent token; q=search keywords; lang=language code or all (options: C, CPP, GO, PYTHON, JAVA, JAVASCRIPT, RUST, MATLAB, PHP, RUBY, VARILOG). Response: JSON containing results (function array), query (original query), lang (language).',
        'quicktip-en-title': '[English]',
        'quicktip-en-request': 'Send a GET request to fetch base and related functions:',
        'quicktip-en-params': 'Params: token=temporary or permanent token; q=search keyword; lang=language code or all (allowed: C, CPP, GO, PYTHON, JAVA, JAVASCRIPT, RUST, MATLAB, PHP, RUBY, VARILOG). Response: JSON with results (array of functions), query (string), lang (string).'
    }
};

// 获取当前语言
function getCurrentLanguage() {
    return localStorage.getItem('language') || detectBrowserLanguage();
}

// 检测浏览器语言
function detectBrowserLanguage() {
    const browserLang = navigator.language || navigator.userLanguage;
    return browserLang.toLowerCase().startsWith('zh') ? 'zh' : 'en';
}

// 设置语言
function setLanguage(lang) {
    localStorage.setItem('language', lang);
    document.documentElement.setAttribute('data-lang', lang);
    updateTexts(lang);
    updatePlaceholders(lang);
}

// 更新页面文本
function updateTexts(lang) {
    const texts = i18nTexts[lang];
    
    // 处理有 ID 的元素
    Object.keys(texts).forEach(key => {
        const element = document.getElementById(key);
        if (element) {
            if (element.tagName === 'INPUT' && element.type === 'text') {
                element.placeholder = texts[key];
            } else if (element.tagName === 'OPTION') {
                element.textContent = texts[key];
            } else {
                // 检查是否包含 HTML 标签，如果包含则使用 innerHTML，否则使用 textContent
                if (texts[key].includes('<br>') || texts[key].includes('<code>')) {
                    element.innerHTML = texts[key];
                } else {
                    element.textContent = texts[key];
                }
            }
        }
    });

    // 批量更新 data-i18n，这是主要的更新逻辑
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (texts[key]) {
            // 同样检查是否包含 HTML
            if (texts[key].includes('<br>') || texts[key].includes('<code>')) {
                el.innerHTML = texts[key];
            } else {
                el.textContent = texts[key];
            }
        }
    });
    
    // 更新页面语言属性
    document.documentElement.lang = lang === 'zh' ? 'zh-CN' : 'en';
    
    // 更新搜索结果中的中英文显示
    updateSearchResults(lang);
    
    // 更新代码显示/隐藏按钮文本
    updateCodeToggleButtons(lang);
    updateResultsCount(lang);
    
    // 调试信息
    console.log('Language updated to:', lang);
    console.log('Updated elements with data-i18n:', document.querySelectorAll('[data-i18n]').length);
}

// 更新搜索结果的中英文显示
function updateSearchResults(lang) {
    const nameElements = document.querySelectorAll('.function-name h3');
    const descElements = document.querySelectorAll('.function-description p');
    
    nameElements.forEach(element => {
        if (element.classList.contains('name-zh')) {
            element.style.display = lang === 'zh' ? 'block' : 'none';
        } else if (element.classList.contains('name-en')) {
            element.style.display = lang === 'en' ? 'block' : 'none';
        }
    });
    
    descElements.forEach(element => {
        if (element.classList.contains('desc-zh')) {
            element.style.display = lang === 'zh' ? 'block' : 'none';
        } else if (element.classList.contains('desc-en')) {
            element.style.display = lang === 'en' ? 'block' : 'none';
        }
    });
}

// 更新输入框占位符
function updatePlaceholders(lang) {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.placeholder = i18nTexts[lang]['search-input'];
    }
}

// 更新代码切换按钮文本
function updateCodeToggleButtons(lang) {
    const toggleButtons = document.querySelectorAll('.toggle-code');
    toggleButtons.forEach(button => {
        const codeBlock = button.nextElementSibling;
        if (codeBlock && codeBlock.style.display === 'block') {
            button.textContent = i18nTexts[lang]['hide-code'];
        } else {
            button.textContent = i18nTexts[lang]['show-code'];
        }
    });
}

// 切换语言
function toggleLanguage() {
    const currentLang = getCurrentLanguage();
    const newLang = currentLang === 'zh' ? 'en' : 'zh';
    setLanguage(newLang);
    
    // MCP 页面特殊处理：更新复制按钮状态
    if (typeof updateCopyButtonText === 'function') {
        updateCopyButtonText(newLang);
    }
}

// 页面加载时初始化
document.addEventListener('DOMContentLoaded', function() {
    const currentLang = getCurrentLanguage();
    setLanguage(currentLang);
    updateResultsCount(currentLang);
});

// 更新结果统计文本
function updateResultsCount(lang) {
    const resultsCount = document.getElementById('results-count');
    if (!resultsCount) return;
    const searchQuery = new URLSearchParams(window.location.search).get('q');
    if (!searchQuery || !searchQuery.trim()) { resultsCount.textContent = ''; return; }
    const resultCount = document.querySelectorAll('.function-card').length;
    const pattern = i18nTexts[lang]['results-count-pattern'];
    if (pattern) {
        resultsCount.textContent = pattern
            .replace('${query}', searchQuery)
            .replace('${count}', resultCount);
    }
}

// 语言切换后的回调处理
function onLanguageChange() {
    // 可以在这里添加语言切换后的额外处理逻辑
    console.log('Language changed to:', getCurrentLanguage());
}

// 导出函数供其他脚本使用
window.getCurrentLanguage = getCurrentLanguage;
window.setLanguage = setLanguage;
window.toggleLanguage = toggleLanguage;
