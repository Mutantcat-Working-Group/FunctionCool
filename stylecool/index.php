<?php
// ============================================================
// StyleCool — 设计模式可集成接口
// 独立部署于 style.functioncool.xyz（文件夹自包含，无外部依赖）
// ============================================================
// URL： / 或 /skillapi
//   ?token=mutantcat&q={关键词}&cat={web|desktop|miniapp|mobile|all} → JSON
//   无参数 → HTML 文档页
// ============================================================

$IS_HTTP = (php_sapi_name() !== 'cli');
if ($IS_HTTP) { header('X-Robots-Tag: noindex, nofollow', true); }

// ── 分类 ──
$CATEGORIES = ['web', 'desktop', 'miniapp', 'mobile'];
$CAT_NAMES  = [
    'web'     => ['en' => 'Web',          'zh' => '网站 / Web App',     'scene' => 'SSR / SPA / PWA / 静态站'],
    'desktop' => ['en' => 'Desktop',       'zh' => '桌面端',             'scene' => 'Electron / 原生 / 大屏 Web'],
    'miniapp' => ['en' => 'Mini Program',  'zh' => '小程序',             'scene' => '微信 / 支付宝 / 抖音小程序'],
    'mobile'  => ['en' => 'Mobile',        'zh' => '手机 / 竖屏设备',   'scene' => 'iOS / Android / H5 移动端'],
];

// ── 密钥校验 ──
if (!function_exists('stylecool_valid')) { function stylecool_valid($token) {
    // 优先读外置 JSON（兼容主站 include），否则用内置白名单
    $ext = __DIR__ . '/../data/skill_token_permanent.json';
    if (file_exists($ext)) {
        $perms = @json_decode(file_get_contents($ext), true);
        if (is_array($perms) && in_array($token, $perms, true)) return true;
    }
    // 内置白名单（独立部署时使用）
    $builtin = ['mutantcat'];
    if (in_array($token, $builtin, true)) return true;
    return false;
} }

// ── JSON 查询 ──
if (isset($_GET['token'], $_GET['q'], $_GET['cat'])) {
    $token = $_GET['token'];
    $query = trim($_GET['q']);
    $cat   = strtolower($_GET['cat']);

    if (!stylecool_valid($token)) {
        if ($IS_HTTP) header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Invalid token'], JSON_UNESCAPED_UNICODE);
        return;
    }

    $cats = $CATEGORIES;
    if ($cat !== 'all' && in_array($cat, $cats, true)) {
        $cats = [$cat];
    }

    $results = [];
    $search  = mb_strtolower($query);

    foreach ($cats as $c) {
        $file = __DIR__ . "/{$c}.json";
        if (!file_exists($file)) continue;
        $data = @json_decode(file_get_contents($file), true);
        if (!$data) continue;
        foreach ($data as $entry) {
            $fields = [
                mb_strtolower($entry['name-en']        ?? ''),
                mb_strtolower($entry['name-zh']        ?? ''),
                mb_strtolower($entry['description-en'] ?? ''),
                mb_strtolower($entry['description-zh'] ?? ''),
                implode(' ', array_map('mb_strtolower', $entry['tags'] ?? [])),
            ];
            $hit = false;
            foreach ($fields as $f) {
                if ($search === '' || mb_strpos($f, $search) !== false) { $hit = true; break; }
            }
            if ($hit) {
                $entry['category'] = $c;
                $results[] = $entry;
            }
        }
    }

    if ($IS_HTTP) header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'results' => $results,
        'query'   => $query,
        'cat'     => $cat,
        'count'   => count($results),
    ], JSON_UNESCAPED_UNICODE);
    return;
}

// ── HTML 文档页（无查询参数时）──
?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StyleCool — 设计模式接口 | FunctionCool</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="canonical" href="https://style.functioncool.xyz/">
    <link rel="icon" href="https://www.functioncool.xyz/assets/logo.png">
    <style>
        :root {
            --sky: #73B9E6; --sky-50: #F0F7FC; --sky-100: #E1EFF8; --sky-300: #9CCBEC;
            --sky-500: #3A8DD0; --sky-700: #1B4E7A; --sky-900: #0E2E4A;
            --gold: #F2B53C; --gold-soft: #FFEAB0;
            --ink: #15324C; --ink-2: #51677C; --ink-3: #8395A6;
            --line: rgba(20,58,92,0.10); --line-2: rgba(20,58,92,0.18);
            --bg: #F4F8FC; --surface: #fff; --surface-2: #FBFDFF;
            --code-bg: #0F2438; --code-text: #DCEBF8;
            --r-sm: 6px; --r-md: 10px; --r-lg: 16px; --r-pill: 999px;
            --s-2: 8px; --s-3: 12px; --s-4: 16px; --s-5: 20px; --s-6: 24px;
            --s-8: 32px; --s-10: 40px;
            --shadow-sm: 0 4px 14px rgba(46,109,164,0.10);
            --shadow-md: 0 12px 30px rgba(46,109,164,0.12);
            --ease: cubic-bezier(0.22,1,0.36,1); --t: 0.25s;
        }
        *,::before,::after{margin:0;padding:0;box-sizing:border-box}
        html{scroll-behavior:smooth;-webkit-text-size-adjust:100%}
        body{font-family:-apple-system,BlinkMacSystemFont,'SF Pro Display','Helvetica Neue','PingFang SC','Microsoft YaHei',sans-serif;line-height:1.65;color:var(--ink);background:var(--bg);-webkit-font-smoothing:antialiased;overflow-x:hidden}
        .container{max-width:800px;margin:0 auto;padding:0 clamp(16px,4vw,28px)}
        header{background:var(--surface);border-bottom:1px solid var(--line);padding:var(--s-4) 0;display:flex;align-items:center;justify-content:space-between;gap:var(--s-4)}
        header h1{font-size:1.3rem;font-weight:700;letter-spacing:-0.02em;color:var(--ink)}
        header h1 span{font-weight:400;color:var(--ink-3);font-size:.75em;margin-left:.3em}
        header a{font-size:.85rem;color:var(--sky-700);text-decoration:none;font-weight:500}
        header a:hover{color:var(--sky-900)}
        main{padding:var(--s-8) 0 var(--s-10)}
        .hero{text-align:center;margin-bottom:var(--s-8)}
        .badge{display:inline-flex;align-items:center;gap:var(--s-2);padding:3px 10px;font-size:.65rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--sky-700);background:var(--sky-50);border:1px solid var(--sky-100);border-radius:var(--r-pill)}
        .badge::before{content:'';width:6px;height:6px;background:var(--gold);border-radius:50%;box-shadow:0 0 0 4px rgba(242,181,60,.18)}
        h2{font-size:clamp(1.6rem,3vw,2.2rem);font-weight:700;letter-spacing:-0.025em;color:var(--ink);margin:var(--s-4) 0 var(--s-2)}
        .hero p{color:var(--ink-2);max-width:52ch;margin:0 auto var(--s-5)}
        .card{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-md);padding:var(--s-6);margin-bottom:var(--s-5);box-shadow:var(--shadow-sm)}
        .card ul{margin:0 0 var(--s-4) 1.2em;color:var(--ink-2);line-height:1.8;font-size:.95rem}
        .card code,.card .mono{font-family:ui-monospace,'SF Mono',monospace;background:var(--sky-50);color:var(--sky-700);padding:1px 6px;border-radius:4px;font-size:.9em}
        .card h3{font-size:1.05rem;font-weight:600;color:var(--ink);margin-bottom:var(--s-4);display:flex;gap:var(--s-3);align-items:center}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:var(--s-4);margin:var(--s-4) 0}
        .vcard{position:relative;background:var(--surface);border:1px solid var(--line);border-radius:var(--r-md);padding:var(--s-4) var(--s-4) var(--s-4) var(--s-5)}
        .vcard::before{content:'';position:absolute;left:0;top:var(--s-4);bottom:var(--s-4);width:2px;background:var(--sky);border-radius:1px}
        .vcard h4{font-size:1rem;font-weight:600;color:var(--ink);margin-bottom:var(--s-2)}
        .vcard p{font-size:.88rem;color:var(--ink-2);line-height:1.55}
        .code-block{background:var(--code-bg);color:var(--code-text);border:1px solid rgba(115,185,230,.18);border-radius:var(--r-sm);padding:var(--s-4) var(--s-5);font-family:ui-monospace,'SF Mono',monospace;font-size:.85rem;line-height:1.65;overflow-x:auto;word-break:break-all}
        .code-block span{color:var(--gold-soft)}
        table{border-collapse:collapse;width:100%;font-size:.9rem}
        thead th{background:var(--sky-50);color:var(--ink);font-size:.75rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;padding:var(--s-3) var(--s-4);border-bottom:1px solid var(--line-2);text-align:left}
        tbody td{padding:var(--s-3) var(--s-4);border-bottom:1px solid var(--line);color:var(--ink-2)}
        .btn-copy{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;font-size:.78rem;font-weight:600;color:var(--ink-2);background:var(--surface);border:1px solid var(--line-2);border-radius:var(--r-sm);cursor:pointer;transition:color var(--t),border var(--t)}
        .btn-copy:hover{color:var(--sky-700);border-color:var(--sky-300)}
        .gh-link{display:inline-flex;align-items:center;gap:var(--s-2);padding:var(--s-3) var(--s-5);font-size:.9rem;font-weight:600;color:#fff;background:var(--sky-700);border-radius:var(--r-pill);text-decoration:none;transition:background var(--t)}
        .gh-link:hover{background:var(--sky-900)}
        .gh-link svg{width:18px;height:18px;fill:currentColor;flex:none}
        footer{background:var(--surface);border-top:1px solid var(--line);text-align:center;padding:var(--s-6) 0;color:var(--ink-3);font-size:.8rem}
        footer a{color:var(--ink-3);text-decoration:none;margin:0 8px;transition:color var(--t)}
        footer a:hover{color:var(--sky-700)}
        @media(max-width:600px){.grid-2{grid-template-columns:1fr}header{flex-direction:column;gap:var(--s-2)}}
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>StyleCool<span>设计模式接口</span></h1>
            <a href="https://www.functioncool.xyz/">← FunctionCool 函数库</a>
        </header>
        <main>

            <!-- Hero -->
            <section class="hero">
                <div class="badge">StyleCool</div>
                <h2>设计模式索引</h2>
                <p>面向 AI 编程助手的<span style="font-weight:600;">设计知识 JSON 接口</span>。按平台分类检索设计规则、组件模式与反模式，让 AI 据此生成高质量 UI，把昂贵的输出 token 折成便宜的输入 token。</p>
            </section>

            <!-- API 契约 -->
            <section class="card">
                <ul>
                    <li>端点：<code>/skillapi?token=mutantcat&q={关键词}&cat={分类}</code>（无参数时见此文档页）</li>
                    <li>密钥：<code>mutantcat</code>（永久有效，直接调用，无需获取）</li>
                    <li>返回：JSON — <code>results</code>（设计模式数组）、<code>query</code>、<code>cat</code>、<code>count</code></li>
                </ul>

                <table>
                    <thead><tr><th>cat 值</th><th>中文</th><th>覆盖场景</th></tr></thead>
                    <tbody>
                        <?php foreach ($CAT_NAMES as $k => $v): ?>
                        <tr><td><code><?=$k?></code></td><td><?=$v['zh']?></td><td><?=$v['scene']?></td></tr>
                        <?php endforeach; ?>
                        <tr><td><code>all</code></td><td>全平台</td><td>跨分类检索</td></tr>
                    </tbody>
                </table>
            </section>

            <!-- 价值主张 -->
            <section class="card" style="padding-bottom:0;">
                <h3>为什么这样设计</h3>
                <div class="grid-2">
                    <div class="vcard">
                        <h4>把输出折成输入</h4>
                        <p>AI 先查设计模式索引取回规则（name / 描述 / CSS / 禁止项），再据此生成 UI。模型不必从零「编造」样式——输出 token 折算为便宜的输入 token。</p>
                    </div>
                    <div class="vcard">
                        <h4>平台专项检索</h4>
                        <p>按 target 分类（网站 / 桌面 / 小程序 / 手机），每次查询只返回最相关的平台规则，不往上下文灌无关的样式噪音。</p>
                    </div>
                </div>
            </section>

            <!-- 快捷提示词 -->
            <section class="card">
                <h3 style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                    快捷提示词
                    <button id="copy-btn" class="btn-copy">复制</button>
                </h3>
                <p style="margin:0 0 8px;color:var(--ink-2);font-size:.9rem;">永久密钥 <code>mutantcat</code></p>
                <div class="code-block" id="quicktip">
                    <p style="margin:0 0 6px;"><strong>【中文】</strong> 向以下地址发送 GET 请求，取回设计模式索引：</p>
                    <p style="margin:0 0 8px;"><span>https://style.functioncool.xyz/skillapi?token=mutantcat&q=button&cat=web</span></p>
                    <p style="margin:0 0 10px;">cat = <span>web</span> | <span>desktop</span> | <span>miniapp</span> | <span>mobile</span> | <span>all</span></p>
                    <p style="margin:0 0 6px;"><strong>[English]</strong> <span>GET https://style.functioncool.xyz/skillapi?token=mutantcat&q=button&cat=web</span></p>
                </div>
            </section>

            <!-- 仓库 -->
            <section style="text-align:center;padding:var(--s-6) 0;">
                <p style="color:var(--ink-2);margin-bottom:var(--s-4);">设计模式 JSON 开源，欢迎扩充条目。</p>
                <a class="gh-link" href="https://github.com/Mutantcat-Working-Group/FunctionCool" target="_blank" rel="noopener">
                    <svg viewBox="0 0 16 16"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8z"/></svg>
                    <span>github.com/Mutantcat-Working-Group/FunctionCool</span>
                </a>
            </section>

        </main>
        <footer>
            <p>&copy; 2025-2026 函数库 · Powered by Mutantcat</p>
            <p style="margin-top:4px;">
                <a href="https://www.mutantcat.org/" target="_blank" rel="noopener">异猫工作群</a>
                <a href="https://www.fcnesyouxi.top/" target="_blank" rel="noopener">FC/NES游戏</a>
                <a href="https://www.jqshengtian.top/" target="_blank" rel="noopener">学习资料</a>
            </p>
        </footer>
    </div>
    <script>
    document.getElementById('copy-btn').onclick=function(){
        var t=document.getElementById('quicktip').innerText;
        if(navigator.clipboard){navigator.clipboard.writeText(t)}else{
            var ta=document.createElement('textarea');ta.value=t;document.body.appendChild(ta);ta.select();document.execCommand('copy');document.body.removeChild(ta)
        }
        this.textContent='✓ 已复制';var b=this;
        setTimeout(function(){b.textContent='复制'},1200)
    };
    </script>
</body>
</html>
