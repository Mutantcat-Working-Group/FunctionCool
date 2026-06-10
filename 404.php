<?php
// 设置 404 状态码
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>页面未找到 - 函数库 FunctionCool</title>
    <meta name="description" content="抱歉，您访问的页面不存在。请返回函数库首页继续浏览编程函数与代码示例。">
    <meta name="robots" content="noindex,nofollow">
    <link rel="canonical" href="https://www.functioncool.xyz/">
    <link rel="stylesheet" href="assets/style.css?v=20250917">
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="apple-touch-icon" href="assets/logo.png">
    <script src="assets/i18n.js?v=20260518"></script>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1><a href="/" aria-label="函数库首页">函数库</a></h1>
                <p>全世界开发者的函数库</p>
            </div>
            <div class="language-switcher">
                <a href="/skillapi" class="skill-link" aria-label="Skill API">Skill</a>
                <button id="lang-btn" onclick="toggleLanguage()">English</button>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="error-section">
                <div class="error-card">
                    <div class="error-icon">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" stroke="#667eea" stroke-width="1.5"/>
                            <path d="M12 8v4m0 4h.01" stroke="#667eea" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h1 class="error-title" data-i18n="error-404-title">页面未找到</h1>
                    <p class="error-message" data-i18n="error-404-message">抱歉，您访问的页面不存在。可能是链接已过期，或者您输入了错误的网址。</p>
                    
                    <div class="error-actions">
                        <a href="/" class="btn-primary" data-i18n="back-home">返回首页</a>
                        <a href="/search" class="btn-secondary" data-i18n="goto-search">搜索函数</a>
                    </div>

                    <div class="error-suggestions">
                        <h3 data-i18n="suggestions-title">您可以尝试：</h3>
                        <ul>
                            <li data-i18n="suggestion-1">检查网址拼写是否正确</li>
                            <li data-i18n="suggestion-2">使用上方的搜索功能查找函数</li>
                            <li data-i18n="suggestion-3">访问 <a href="/skillapi">Skill 接口</a> 了解 AI 集成文档</li>
                            <li data-i18n="suggestion-4">如果问题持续，请联系我们：shun_@outlook.com</li>
                        </ul>
                    </div>
                </div>
            </div>
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

    <style>
        .error-section {
            padding: 1rem 0;
            text-align: center;
        }

        .error-card {
            background: white;
            border-radius: 15px;
            padding: 3rem 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .error-icon {
            margin-bottom: 2rem;
        }

        .error-icon svg {
            opacity: 0.8;
        }

        .error-title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .error-message {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .error-suggestions {
            text-align: left;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            border-left: 4px solid #667eea;
        }

        .error-suggestions h3 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .error-suggestions ul {
            list-style: none;
            padding: 0;
        }

        .error-suggestions li {
            color: #555;
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .error-suggestions li::before {
            content: "•";
            color: #667eea;
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        .error-suggestions a {
            color: #667eea;
            text-decoration: none;
        }

        .error-suggestions a:hover {
            text-decoration: underline;
        }

        @media (max-width: 640px) {
            .error-card {
                padding: 2rem 1rem;
            }

            .error-title {
                font-size: 2rem;
            }

            .error-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn-primary,
            .btn-secondary {
                width: 200px;
            }
        }
    </style>
</body>
</html>