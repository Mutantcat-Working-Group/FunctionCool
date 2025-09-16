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
        'footer-text': '© 2025 函数库 | Powered by Mutantcat'
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
        'footer-text': '© 2025 FunctionCool | Powered by Mutantcat'
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
    
    Object.keys(texts).forEach(key => {
        const element = document.getElementById(key);
        if (element) {
            if (element.tagName === 'INPUT' && element.type === 'text') {
                element.placeholder = texts[key];
            } else if (element.tagName === 'OPTION') {
                element.textContent = texts[key];
            } else {
                element.textContent = texts[key];
            }
        }
    });

    // 批量更新 data-i18n
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (texts[key]) {
            el.textContent = texts[key];
        }
    });
    
    // 更新页面语言属性
    document.documentElement.lang = lang === 'zh' ? 'zh-CN' : 'en';
    
    // 更新搜索结果中的中英文显示
    updateSearchResults(lang);
    
    // 更新代码显示/隐藏按钮文本
    updateCodeToggleButtons(lang);
    updateResultsCount(lang);
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
