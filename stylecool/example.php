<?php
// ============================================================
// StyleCool — 设计样式示例浏览（带缩略图、关键词检索、分页）
// 独立部署于 style.functioncool.xyz 时使用
// ============================================================
// URL：  stylecool/example.php
//   ?q={关键词} 关键词检索（模糊匹配 名称/描述/标签/分类）
//   &p={页码}   分页（每页 9 个）
// 数据： 此页内置 $EXAMPLES 占位临时数据
//        后续可平滑替换为读取 list/index.json 等外置数据
// 后续：  list/{id}.html 由 Step 2 创建并自动被 card 链接打开
// ============================================================

$IS_HTTP = (php_sapi_name() !== 'cli');
if ($IS_HTTP) { header('X-Robots-Tag: noindex, nofollow', true); }

// ── 关键操作日志：方便定位分页/检索异常，杜绝玄学问题 ──
$logTag = '[stylecool/example]';
error_log($logTag . ' hit q=' . var_export($_GET['q'] ?? null, true)
    . ' p=' . var_export($_GET['p'] ?? null, true)
    . ' ua=' . substr($_SERVER['HTTP_USER_AGENT'] ?? '-', 0, 60));

// ── 入参（防御性处理） ──
$query   = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$page    = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$page    = max(1, $page);                       // 页码不能小于 1
$perPage = 9;                                   // 每页 9 个（3×3 网格）

// ── 分类元数据 ──
$CAT_META = [
    'web'     => ['en' => 'Web',         'zh' => '网站 / Web App'],
    'desktop' => ['en' => 'Desktop',     'zh' => '桌面端'],
    'miniapp' => ['en' => 'Mini Program', 'zh' => '小程序'],
    'mobile'  => ['en' => 'Mobile',      'zh' => '手机 / 竖屏设备'],
];

// ── 占位样例数据集 ──
// 字段约定：
//   id             唯一标识，对应 list/{id}.html
//   name-zh/en     中英文名（列表卡片显示 name-zh）
//   desc-zh/en     中英文描述
//   tags           检索关键词（中文/英文均可）
//   cat            分类（web/desktop/miniapp/mobile）
//   thumb          缩略图 CSS 背景（占位条目用渐变作为视觉标识）
//   thumb-image    缩略图真实截图 URL（存在时优先于 thumb，使用 background-image）
//   thumb-dark     缩略图文字是否采用深色（白底浅色背景时翻深；真实截图忽略）
//   file           完整示例文件路径（点击卡片跳转）
//   prompt         完整可复制的设计提示词（示例页内一键复制）
$EXAMPLES = [
    // ── 真实示例（已渲染截图）：液态玻璃 ──
    [
        'id'          => 'liquid-glass',
        'name-zh'     => '液态玻璃',
        'name-en'     => 'Liquid Glass',
        'desc-zh'     => '高斯模糊 + 流动渐变光晕 + 多层透明叠加，模拟液体玻璃质感',
        'desc-en'     => 'Gaussian blur + flowing gradient orbs + translucent layers, simulating liquid glass',
        'tags'        => ['liquid', 'glass', 'blur', 'orb', '液态', '玻璃', '玻璃拟态', '动画', 'web'],
        'cat'         => 'web',
        'thumb-image' => 'list/images/liquid-glass.png',
        'thumb'       => 'linear-gradient(135deg, #1e1b4b 0%, #4c1d95 50%, #831843 100%)',
        'thumb-dark'  => true,
        'file'        => 'list/liquid-glass.html',
        'prompt'      => "采用液态玻璃（Liquid Glass）设计风格。背景使用动态渐变（深紫 #1e1b4b → 紫 #4c1d95 → 深蓝 #1e3a8a → 玫红 #831843），多色径向光晕（紫水晶 #6B46C1 / 玫粉 #F472B6 / 青蓝 #38BDF8 / 深海军 #1B4E7A）以 18s 缓动周期缓慢漂移，形成液态流动感。卡片使用高斯模糊：backdrop-filter: blur(40px) saturate(180%)，背景色 rgba(255,255,255,0.18)，1px 白色半透明边框，圆角 24px，内嵌 1px 顶部反射阴影。所有按钮、输入框、标签、导航、警告框均沿用相同的玻璃语言：blur 20-40px + 半透明白底 + 渐变主色按钮。整体节奏轻盈、富有未来感。",
    ],
    // ── 真实示例（已渲染截图）：玻璃拟态 ──
    [
        'id'          => 'glassmorphism',
        'name-zh'     => '玻璃拟态',
        'name-en'     => 'Glassmorphism',
        'desc-zh'     => '中等强度模糊 + 浅色渐变光晕 + 柔和投影，轻盈梦幻',
        'desc-en'     => 'Medium blur + light gradient orbs + soft shadows, light and dreamy',
        'tags'        => ['glass', 'blur', 'translucent', 'soft', '玻璃', '浅色', '梦幻', 'web'],
        'cat'         => 'web',
        'thumb-image' => 'list/images/glassmorphism.png',
        'thumb'       => 'linear-gradient(135deg, #E0F4FF 0%, #FFE0EC 50%, #F0E5FF 100%)',
        'thumb-dark'  => false,
        'file'        => 'list/glassmorphism.html',
        'prompt'      => "采用玻璃拟态（Glassmorphism）设计风格。背景使用浅色渐变（浅天蓝 #E0F4FF → 浅樱粉 #FFE0EC → 浅薰衣草 #F0E5FF），柔和漂浮大色斑（天蓝 #4D96FF / 樱粉 #FF6B9D / 薰衣草 #A18CD1 / 樱花粉 #FBC2EB）以 16-20s 缓动周期缓慢漂移。卡片使用中等强度模糊：backdrop-filter: blur(20px) saturate(180%)，背景色 rgba(255,255,255,0.4)，1px 白色半透明边框，圆角 16-24px，单层柔和浅灰投影 0 8px 32px rgba(128,142,174,0.18)。所有按钮、输入框、标签、导航、警告框均沿用相同的玻璃语言：blur 20px + 半透明白底 + 圆角 12px + 浅灰边框。整体保持轻盈、梦幻、专业的玻璃质感。",
    ],
    // ── 真实示例（已渲染截图）：孟菲斯 ──
    [
        'id'          => 'memphis',
        'name-zh'     => '孟菲斯',
        'name-en'     => 'Memphis',
        'desc-zh'     => '大胆几何 + 多彩碰撞，年轻张扬的 80 年代设计复兴',
        'desc-en'     => 'Bold geometry + colorful clashes, 80s design revival',
        'tags'        => ['memphis', 'colorful', 'geometric', '80s', '几何', '多彩', '年轻', 'web'],
        'cat'         => 'web',
        'thumb-image' => 'list/images/memphis.png',
        'thumb'       => 'linear-gradient(135deg, #FF6B6B 0%, #FFEAB0 33%, #73B9E6 66%, #95E1D3 100%)',
        'thumb-dark'  => false,
        'file'        => 'list/memphis.html',
        'prompt'      => "采用孟菲斯（Memphis）设计风格。背景使用奶油色 #FFF8E7 浅暖基底，点缀大胆几何元素：随机散布的圆点（珊瑚红 #FF6B6B 8px）、斑马纹波浪（黑白相间 4px）、三角形（樱草黄 #FFD93D 异向）、网格线条（天蓝 #4D96FF 半透明）。配色高饱和：珊瑚红 #FF6B6B + 樱草黄 #FFD93D + 薄荷绿 #6BCB77 + 天蓝 #4D96FF + 樱花粉 #FF8FAB。主容器使用 12px 实色硬边框（无圆角）、背景纯色硬块（如 #FFEAB0 浅黄）。字体圆润粗体（Inter Black / 思源黑体 Heavy）。圆角 0-12px 极端对比。整体活泼、张扬、年轻、充满街头感。",
    ],
    // ── 真实示例（已渲染截图）：极简白 ──
    [
        'id'          => 'minimal-white',
        'name-zh'     => '极简白',
        'name-en'     => 'Minimal White',
        'desc-zh'     => '克制的白色 + 大量留白 + 单一蓝色重音 + 极轻描边',
        'desc-en'     => 'Restrained white, generous whitespace, single accent',
        'tags'        => ['minimal', 'white', 'clean', 'whitespace', '白', '极简', '克制', 'web'],
        'cat'         => 'web',
        'thumb-image' => 'list/images/minimal-white.png',
        'thumb'       => 'linear-gradient(135deg, #FFFFFF 0%, #F4F8FC 100%)',
        'thumb-dark'  => false,
        'file'        => 'list/minimal-white.html',
        'prompt'      => "采用极简白（Minimal White）设计风格。背景纯白 #FFFFFF，大量留白（区块间距 64-96px）。卡片背景 #FFFFFF，1px 边框 #EAF2F8，圆角 10px，无阴影。字体使用系统无衬线（-apple-system / Inter），标题字重 700，正文 16px / 行高 1.7，颜色极少：主色 #15324C 文字 + #8395A6 次要 + 单一重音 #73B9E6（仅用于强调、聚焦、选中态）。按钮无填充 + 1px 边框 #DCE6EE，hover 加深至 #15324C；主要按钮填充 #73B9E6 + 白字。输入框聚焦时蓝色 1px 边框 + 3px rgba(115,185,230,0.15) 光环。导航选中态用单色 2px 底部蓝色下划线。整页只用 1 个主色 + 1 个重音色，所有元素极度克制、留白充足、阅读优先。",
    ],
    // ── 下方 16 条为占位（仅 CSS 渐变作为视觉标识，无真实 HTML 文件）──
    [
        'id'         => 'dark-cyber',
        'name-zh'    => '暗色科技',
        'name-en'    => 'Dark Cyber',
        'desc-zh'    => '深海军蓝底色 + 霓虹强调，冷峻专业',
        'desc-en'    => 'Deep navy with neon accents, cool and precise',
        'tags'       => ['dark', 'cyber', 'tech', '暗', '霓虹', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #0E2E4A 0%, #15324C 50%, #1B4E7A 100%)',
        'thumb-dark' => true,
        'file'       => 'list/dark-cyber.html',
        'prompt'     => "暗色科技（Dark Cyber）风格。背景 #0E2E4A 深海军蓝。卡片背景 #15324C，1px 边框 #1B4E7A，圆角 6px。强调色 #73B9E6。霓虹 #F2B53C 作为高亮。代码块使用等宽字体。整体冷静、专业、未来感。",
    ],
    [
        'id'         => 'neumorphism',
        'name-zh'    => '新拟态',
        'name-en'    => 'Neumorphism',
        'desc-zh'    => '柔和阴影模拟凸起与凹陷',
        'desc-en'    => 'Soft shadows simulating extrusion and inset',
        'tags'       => ['soft', 'shadow', 'monochrome', '拟态', '阴影', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #E1EFF8 0%, #EAF2F8 100%)',
        'thumb-dark' => false,
        'file'       => 'list/neumorphism.html',
        'prompt'     => "新拟态（Neumorphism）风格。背景 #EAF2F8 浅灰蓝。卡片使用双重阴影：外向 8px 8px 16px rgba(163,177,198,0.6) + 内向 -8px -8px 16px rgba(255,255,255,0.5)，模拟凸起。圆角 16px。颜色克制，几乎无彩色。",
    ],
    [
        'id'         => 'bold-blocks',
        'name-zh'    => '大色块',
        'name-en'    => 'Bold Blocks',
        'desc-zh'    => '高饱和色块大胆拼接，强视觉冲击',
        'desc-en'    => 'High-saturation color blocks, strong visual impact',
        'tags'       => ['bold', 'color', 'flat', '色块', '扁平', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #FF6B6B 0%, #FFD93D 50%, #6BCB77 100%)',
        'thumb-dark' => false,
        'file'       => 'list/bold-blocks.html',
        'prompt'     => "大色块风格。配色高饱和：红 #FF6B6B、黄 #FFD93D、绿 #6BCB77、蓝 #4D96FF。色块之间无渐变、硬边分明。圆角 8px。字体粗体，标题特大。文字白色或黑色，取决于色块亮度。",
    ],
    [
        'id'         => 'grid-system',
        'name-zh'    => '网格系统',
        'name-en'    => 'Grid System',
        'desc-zh'    => '12 列网格骨架，结构清晰严谨',
        'desc-en'    => '12-column grid scaffold, clear structure',
        'tags'       => ['grid', 'system', '12col', '网格', '结构', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #15324C 0%, #1B4E7A 100%)',
        'thumb-dark' => true,
        'file'       => 'list/grid-system.html',
        'prompt'     => "网格系统风格。背景 #15324C。卡片使用 12 列 CSS Grid，gap 16px。卡片 1px 白色半透明边框，圆角 4px。文字白色 #FFFFFF，主色 #73B9E6。结构严谨、间距统一、所有元素都贴着网格。",
    ],
    [
        'id'         => 'editorial',
        'name-zh'    => '杂志编辑',
        'name-en'    => 'Editorial',
        'desc-zh'    => '衬线大字标题，多栏文本编排',
        'desc-en'    => 'Serif large headlines, multi-column layout',
        'tags'       => ['editorial', 'serif', 'magazine', '编辑', '杂志', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #F4F8FC 0%, #FBFDFF 100%)',
        'thumb-dark' => false,
        'file'       => 'list/editorial.html',
        'prompt'     => "杂志编辑风格。背景 #FFFFFF。标题使用衬线字体（如 Georgia、Playfair Display），字重 800。文本 2 列或 3 列编排，行高 1.8。引用、题花、首字下沉。颜色：黑 #15324C + 一抹金 #F2B53C。",
    ],
    [
        'id'         => 'business-classic',
        'name-zh'    => '经典商务',
        'name-en'    => 'Classic Business',
        'desc-zh'    => '深蓝主调，等级分明，稳重可靠',
        'desc-en'    => 'Deep blue, clear hierarchy, stable and reliable',
        'tags'       => ['business', 'corporate', 'blue', '商务', '稳重', 'desktop'],
        'cat'        => 'desktop',
        'thumb'      => 'linear-gradient(135deg, #1B4E7A 0%, #3A8DD0 100%)',
        'thumb-dark' => true,
        'file'       => 'list/business-classic.html',
        'prompt'     => "经典商务风格。背景 #F4F8FC 浅冷灰。导航深蓝 #1B4E7A 横栏。卡片白底，1px 边框 #EAF2F8，圆角 4px。按钮实色 #1B4E7A。字体无衬线，标题字重 600。整体稳重、可靠、克制。",
    ],
    [
        'id'         => 'dashboard',
        'name-zh'    => '仪表盘',
        'name-en'    => 'Dashboard',
        'desc-zh'    => '数据密集型控制台，深色高效',
        'desc-en'    => 'Data-dense console, dark and efficient',
        'tags'       => ['dashboard', 'data', 'console', '数据', '控制台', 'desktop'],
        'cat'        => 'desktop',
        'thumb'      => 'linear-gradient(135deg, #0F2438 0%, #15324C 100%)',
        'thumb-dark' => true,
        'file'       => 'list/dashboard.html',
        'prompt'     => "仪表盘风格。背景 #0F2438 深色。卡片背景 #15324C，1px 边框 #1B4E7A。数字使用大号等宽字体（JetBrains Mono），主色 #73B9E6，正增长 #6BCB77，负增长 #FF6B6B。圆角 6px。图表克制、留白适度。",
    ],
    [
        'id'         => 'mobile-card',
        'name-zh'    => '移动卡片',
        'name-en'    => 'Mobile Card',
        'desc-zh'    => '竖屏卡片流，便于一指操作',
        'desc-en'    => 'Vertical card stream, easy thumb operation',
        'tags'       => ['mobile', 'card', 'thumb', '卡片', '移动', '触屏'],
        'cat'        => 'mobile',
        'thumb'      => 'linear-gradient(135deg, #C5DFF0 0%, #EAF2F8 100%)',
        'thumb-dark' => false,
        'file'       => 'list/mobile-card.html',
        'prompt'     => "移动卡片风格。背景 #F4F8FC。卡片白底，圆角 12px，垂直间距 12px。底部 Tab Bar 高 56px，图标为主、文字为辅。点击态 0.96 缩放 100ms 反馈。间距偏宽，适合触摸。",
    ],
    [
        'id'         => 'video-stream',
        'name-zh'    => '视频流',
        'name-en'    => 'Video Stream',
        'desc-zh'    => '深色沉浸，封面 + 标题驱动',
        'desc-en'    => 'Dark immersive, cover + title driven',
        'tags'       => ['video', 'dark', 'media', '视频', '暗色', 'mobile'],
        'cat'        => 'mobile',
        'thumb'      => 'linear-gradient(135deg, #0E2E4A 0%, #1B4E7A 100%)',
        'thumb-dark' => true,
        'file'       => 'list/video-stream.html',
        'prompt'     => "视频流风格。背景 #0E2E4A 深色。卡片无边框，封面占主，标题压于底部渐变蒙层。文字白色，标题字重 600。圆角 8px。整体低对比、不抢戏，让视频成为主角。",
    ],
    [
        'id'         => 'miniapp-minimal',
        'name-zh'    => '小程序简约',
        'name-en'    => 'MiniApp Minimal',
        'desc-zh'    => '微信风绿色点缀，圆角统一',
        'desc-en'    => 'WeChat-style green accent, unified radii',
        'tags'       => ['miniapp', 'wechat', 'minimal', '小程序', '微信'],
        'cat'        => 'miniapp',
        'thumb'      => 'linear-gradient(135deg, #EAF2F8 0%, #F4F8FC 100%)',
        'thumb-dark' => false,
        'file'       => 'list/miniapp-minimal.html',
        'prompt'     => "小程序简约风格。背景 #F4F8FC。卡片背景 #FFFFFF，圆角 8px，1px 边框 #EAF2F8。主色 #1AAD19 微信绿（点缀不要多）。按钮实色 #1AAD19，圆角 4px。顶部胶囊菜单距右 88px。",
    ],
    [
        'id'         => 'retro-pixel',
        'name-zh'    => '复古像素',
        'name-en'    => 'Retro Pixel',
        'desc-zh'    => '8-bit 像素风，童年怀旧',
        'desc-en'    => '8-bit pixel aesthetic, nostalgic',
        'tags'       => ['pixel', 'retro', '8bit', '像素', '复古', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #FFD93D 0%, #FF6B6B 50%, #4D96FF 100%)',
        'thumb-dark' => false,
        'file'       => 'list/retro-pixel.html',
        'prompt'     => "复古像素风格。字体使用像素字体（如 Press Start 2P）。颜色 8-bit 调色板：红 #FF6B6B、黄 #FFD93D、蓝 #4D96FF、绿 #6BCB77。边框硬边 2px 实线，无圆角。image-rendering: pixelated。",
    ],
    [
        'id'         => 'japanese-wafu',
        'name-zh'    => '日式和风',
        'name-en'    => 'Japanese Wafu',
        'desc-zh'    => '米白 + 朱红，侘寂留白',
        'desc-en'    => 'Cream + vermilion, wabi-sabi whitespace',
        'tags'       => ['japanese', 'wafu', 'minimal', '和风', '侘寂', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #F5EDE0 0%, #E8D3B7 100%)',
        'thumb-dark' => false,
        'file'       => 'list/japanese-wafu.html',
        'prompt'     => "日式和风风格。背景 #F5EDE0 米白。主色 #B23A2F 朱红 + #1F1F1F 墨黑。字体衬线（如 Noto Serif JP）。圆角 0 或 4px。大量留白，整体侘寂。点缀以印章、竖排文字。",
    ],
    [
        'id'         => 'swiss-design',
        'name-zh'    => '瑞士极简',
        'name-en'    => 'Swiss Design',
        'desc-zh'    => '非对称网格，无衬线大字',
        'desc-en'    => 'Asymmetric grid, sans-serif large headlines',
        'tags'       => ['swiss', 'grid', 'helvetica', '瑞士', '极简', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #FFFFFF 0%, #F4F8FC 100%)',
        'thumb-dark' => false,
        'file'       => 'list/swiss-design.html',
        'prompt'     => "瑞士极简风格。背景 #FFFFFF。字体无衬线（Helvetica Neue / Inter），标题极大、字重 800。布局非对称网格，元素左对齐。强调色 #E63946 朱红。圆角 0。极致克制、理性、几何。",
    ],
    [
        'id'         => 'gradient-dream',
        'name-zh'    => '渐变梦幻',
        'name-en'    => 'Gradient Dream',
        'desc-zh'    => '粉紫渐变 + 玻璃卡，梦幻柔和',
        'desc-en'    => 'Pink-purple gradient + glass cards, dreamy',
        'tags'       => ['gradient', 'dream', 'pastel', '渐变', '梦幻', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #FFAFBD 0%, #A18CD1 50%, #FBC2EB 100%)',
        'thumb-dark' => false,
        'file'       => 'list/gradient-dream.html',
        'prompt'     => "渐变梦幻风格。背景 linear-gradient(135deg, #FFAFBD 0%, #A18CD1 50%, #FBC2EB 100%)。卡片背景 rgba(255,255,255,0.5) + backdrop-filter: blur(20px)，圆角 20px。文字深色 #2D1B4E。整体柔和、梦幻、女性向。",
    ],
    [
        'id'         => 'documentation',
        'name-zh'    => '文档型',
        'name-en'    => 'Documentation',
        'desc-zh'    => '代码块 + 侧边导航，开发者友好',
        'desc-en'    => 'Code blocks + sidebar nav, dev-friendly',
        'tags'       => ['docs', 'code', 'developer', '文档', '代码', 'web'],
        'cat'        => 'web',
        'thumb'      => 'linear-gradient(135deg, #FBFDFF 0%, #F4F8FC 100%)',
        'thumb-dark' => false,
        'file'       => 'list/documentation.html',
        'prompt'     => "文档型风格。背景 #FFFFFF。左侧固定导航 240px，主区最大宽 800px 居中。标题字重 700，2-3 级标题左侧色条。代码块背景 #0F2438 + 等宽字体。链接主色 #1B4E7A。圆角 6px。",
    ],
];
$totalAll = count($EXAMPLES);
error_log($logTag . ' dataset total=' . $totalAll);

// ── 关键词检索（模糊匹配 多字段） ──
$search = mb_strtolower($query);
$filtered = [];
if ($search === '') {
    $filtered = $EXAMPLES;
} else {
    foreach ($EXAMPLES as $ex) {
        $catLabel = $CAT_META[$ex['cat']]['zh'] ?? '';
        $haystack = mb_strtolower(
            ($ex['name-zh']    ?? '') . ' ' .
            ($ex['name-en']    ?? '') . ' ' .
            ($ex['desc-zh']    ?? '') . ' ' .
            ($ex['desc-en']    ?? '') . ' ' .
            implode(' ', $ex['tags'] ?? []) . ' ' .
            $catLabel
        );
        if (mb_strpos($haystack, $search) !== false) {
            $filtered[] = $ex;
        }
    }
}
error_log($logTag . ' search q=' . $query . ' hits=' . count($filtered));

// ── 分页 ──
$total      = count($filtered);
$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) { $page = $totalPages; }
$offset   = ($page - 1) * $perPage;
$pageItems = array_slice($filtered, $offset, $perPage);

// ── 工具：构造带搜索词的分页 URL ──
function example_url($q, $p) {
    $params = [];
    if ($q !== '') $params['q'] = $q;
    if ($p > 1)    $params['p'] = $p;
    return '?' . http_build_query($params);
}

// ── 国际化翻译键（集中维护，便于一次性落表） ──
$I18N = [
    'page-title'        => ['示例 — StyleCool',                                  'Examples — StyleCool'],
    'header-title'      => ['StyleCool',                                          'StyleCool'],
    'header-badge'      => ['设计样式示例',                                      'Style Examples'],
    'header-subtitle'   => ['在线预览设计样式效果，每个示例可一键复制完整提示词', 'Preview design styles online; copy the full prompt with one click'],
    'back-link'         => ['← 返回 StyleCool',                                  '← Back to StyleCool'],
    'lang-btn'          => ['English',                                            '中文'],
    'hero-badge'        => ['设计样式画廊',                                      'Design Gallery'],
    'hero-title'        => ['样式示例',                                          'Style Examples'],
    'hero-desc'         => ['从 18 种风格示例中找灵感。点击进入可一键复制生成该风格所需的完整提示词。', 'Browse 18 style examples. Click one to copy the full prompt that reproduces it.'],
    'search-placeholder'=> ['输入关键词检索，例如 glass、卡片、渐变…',          'Type a keyword, e.g. glass, card, gradient…'],
    'search-btn'        => ['搜索',                                               'Search'],
    'count-format'      => ['共 %d 个示例',                                      '%d examples'],
    'count-filtered'    => ['"%s" 匹配 %d / %d 个',                              '"%s" matches %d of %d'],
    'empty-title'       => ['没有匹配的样式',                                    'No matching styles'],
    'empty-desc'        => ['试试更宽泛的关键词，或清空搜索查看全部示例。',     'Try a broader keyword or clear the search to see all examples.'],
    'empty-clear'       => ['清空搜索',                                           'Clear search'],
    'prev-page'         => ['← 上一页',                                           '← Prev'],
    'next-page'         => ['下一页 →',                                           'Next →'],
    'page-info'         => ['第 %d / %d 页',                                      'Page %d / %d'],
    'tags-label'        => ['标签',                                               'Tags'],
    'cat-label'         => ['分类',                                               'Category'],
    'visit-example'     => ['查看示例',                                           'View example'],
    'footer-text'       => ['© 2025-2026 函数库 | Powered by Mutantcat',          '© 2025-2026 Function Library | Powered by Mutantcat'],
    'friend-links'      => ['友情链接：',                                         'Friends:'],
];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>样式示例 — StyleCool</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="canonical" href="https://style.functioncool.xyz/example">
    <link rel="stylesheet" href="https://www.functioncool.xyz/assets/style.css?v=20260610">
    <link rel="icon" type="image/png" href="https://www.functioncool.xyz/assets/logo.png">
    <link rel="apple-touch-icon" href="https://www.functioncool.xyz/assets/logo.png">
    <script src="https://www.functioncool.xyz/assets/i18n.js?v=20260610"></script>
    <style>
    /* ============================================================
       示例页专用样式 — 液态玻璃（Liquid Glass）主题
       ------------------------------------------------------------
       设计语言四要素：
       1. 高斯模糊背景：backdrop-filter: blur(40px) saturate(180%)
       2. 半透明叠加：rgba(255,255,255, 0.5~0.7) 营造玻璃质感
       3. 流动渐变光晕：背景色随时间缓慢漂移
       4. 折射边缘：圆角 + 1px 白色半透明边框 + 多层投影
       ============================================================ */

    /* —— 液态玻璃颜色变量 —— */
    :root {
        --liquid-1: #6B46C1;
        --liquid-2: #1B4E7A;
        --liquid-3: #F472B6;
        --liquid-4: #38BDF8;
        --glass-bg:     rgba(255, 255, 255, 0.55);
        --glass-bg-2:   rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.5);
        --glass-shadow: 0 8px 32px rgba(31, 38, 135, 0.08);
    }

    /* —— 页面背景：流动渐变 —— */
    body {
        background: linear-gradient(135deg,
            #f5f3ff 0%,      /* 薰衣草白 */
            #ffffff 25%,     /* 纯白 */
            #fff5f7 50%,     /* 樱花粉 */
            #f0f9ff 75%,     /* 天蓝白 */
            #f5f3ff 100%);
        background-size: 400% 400%;
        animation: liquid-bg 30s ease-in-out infinite;
    }
    @keyframes liquid-bg {
        0%, 100% { background-position: 0% 50%; }
        50%      { background-position: 100% 50%; }
    }

    /* —— 主容器 —— */
    .example-main {
        padding: clamp(32px, 5vw, 64px) clamp(20px, 4vw, 32px) clamp(48px, 6vw, 80px);
        max-width: 1200px;
        margin: 0 auto;
    }

    /* —— 标题区 —— */
    .example-intro {
        text-align: center;
        padding: var(--s-8) 0 var(--s-10);
    }
    .example-intro .kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 4px 14px;
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--liquid-1);
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 999px;
        margin-bottom: var(--s-3);
    }
    .example-intro .kicker::before {
        content: "";
        width: 6px; height: 6px;
        background: var(--liquid-3);
        border-radius: 50%;
    }
    .example-intro h2 {
        font-size: clamp(2rem, 4vw, 2.75rem);
        font-weight: 800;
        letter-spacing: -0.025em;
        margin: var(--s-3) 0;
        background: linear-gradient(135deg, var(--liquid-1) 0%, var(--liquid-2) 50%, var(--liquid-3) 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .example-intro p {
        max-width: 560px;
        margin: 0 auto;
        color: var(--ink-2);
        line-height: 1.7;
        font-size: 1.0625rem;
    }

    /* —— 搜索表单 —— */
    .example-search-form {
        display: flex;
        gap: var(--s-2);
        max-width: 560px;
        margin: 0 auto var(--s-6);
        padding: 8px;
        background: var(--glass-bg);
        backdrop-filter: blur(40px) saturate(180%);
        -webkit-backdrop-filter: blur(40px) saturate(180%);
        border: 1px solid var(--glass-border);
        border-radius: 999px;
        box-shadow: var(--glass-shadow);
    }
    .example-search-input {
        flex: 1;
        min-width: 0;
        padding: 12px 20px;
        font-size: var(--fs-base);
        color: var(--ink);
        background: transparent;
        border: 0;
        border-radius: 999px;
        outline: none;
        font-family: inherit;
    }
    .example-search-input::placeholder { color: var(--ink-3); }
    .example-search-input:focus {
        background: rgba(255, 255, 255, 0.4);
    }
    .example-search-btn {
        padding: 12px 28px;
        font-size: var(--fs-sm);
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--liquid-1), var(--liquid-2));
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.3s var(--ease, cubic-bezier(0.22, 1, 0.36, 1));
        white-space: nowrap;
        font-family: inherit;
        box-shadow: 0 4px 16px rgba(107, 70, 193, 0.3);
    }
    .example-search-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(107, 70, 193, 0.45);
    }

    /* —— 结果计数 —— */
    .example-meta {
        text-align: center;
        color: var(--ink-3);
        font-size: var(--fs-sm);
        margin-bottom: var(--s-6);
        font-variant-numeric: tabular-nums;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(20px);
        border-radius: 999px;
        display: inline-block;
        position: relative;
        left: 50%;
        transform: translateX(-50%);
    }

    /* —— 卡片网格 —— */
    .example-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--s-5);
        margin-bottom: var(--s-10);
    }

    /* —— 玻璃卡片 —— */
    .example-card {
        display: flex;
        flex-direction: column;
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.65),
            rgba(255, 255, 255, 0.4));
        backdrop-filter: blur(40px) saturate(180%);
        -webkit-backdrop-filter: blur(40px) saturate(180%);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        box-shadow: var(--glass-shadow);
        transition: transform 0.5s var(--ease, cubic-bezier(0.22, 1, 0.36, 1)),
                    box-shadow 0.5s var(--ease, cubic-bezier(0.22, 1, 0.36, 1));
        position: relative;
    }
    .example-card::before {
        /* 顶部反光线 */
        content: "";
        position: absolute;
        top: 0; left: 10%; right: 10%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
        pointer-events: none;
    }
    .example-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(31, 38, 135, 0.15);
    }
    .example-card:focus-visible {
        outline: 2px solid var(--liquid-1);
        outline-offset: 2px;
    }

    /* —— 缩略图 —— */
    .example-thumb {
        position: relative;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .example-thumb-image::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0) 60%, rgba(0, 0, 0, 0.05));
        pointer-events: none;
    }
    .example-thumb-label {
        font-size: var(--fs-lg);
        font-weight: 700;
        color: rgba(255, 255, 255, 0.94);
        letter-spacing: 0.05em;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.18);
        padding: 0 var(--s-4);
        text-align: center;
    }
    .example-thumb-label.dark {
        color: rgba(20, 58, 92, 0.85);
        text-shadow: none;
    }

    /* —— 卡片正文 —— */
    .example-body {
        padding: var(--s-4);
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .example-card-title {
        font-size: var(--fs-md);
        font-weight: 600;
        color: var(--ink);
        margin: 0 0 var(--s-1);
        letter-spacing: -0.005em;
    }
    .example-card-desc {
        font-size: var(--fs-sm);
        color: var(--ink-2);
        line-height: 1.5;
        margin: 0 0 var(--s-3);
        flex: 1;
    }
    .example-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .example-tag {
        font-size: var(--fs-xs);
        color: var(--ink-2);
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 2px 10px;
        border-radius: 999px;
        white-space: nowrap;
    }

    /* —— 空状态 —— */
    .example-empty {
        text-align: center;
        padding: var(--s-16) var(--s-4);
        background: var(--glass-bg);
        backdrop-filter: blur(40px) saturate(180%);
        -webkit-backdrop-filter: blur(40px) saturate(180%);
        border: 1px dashed rgba(107, 70, 193, 0.3);
        border-radius: 16px;
        color: var(--ink-2);
        margin-bottom: var(--s-10);
        box-shadow: var(--glass-shadow);
        position: relative;
    }
    .example-empty::before {
        content: "";
        position: absolute;
        top: 0; left: 10%; right: 10%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
    }
    .example-empty h3 {
        font-size: var(--fs-lg);
        font-weight: 600;
        color: var(--ink);
        margin: 0 0 var(--s-2);
    }
    .example-empty p {
        font-size: var(--fs-sm);
        margin: 0 0 var(--s-4);
    }
    .example-empty a {
        display: inline-block;
        padding: 10px 20px;
        font-size: var(--fs-sm);
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--liquid-1), var(--liquid-2));
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 999px;
        text-decoration: none;
        transition: all 0.3s var(--ease, cubic-bezier(0.22, 1, 0.36, 1));
        box-shadow: 0 4px 16px rgba(107, 70, 193, 0.3);
    }
    .example-empty a:hover {
        transform: scale(1.05);
    }

    /* —— 分页 —— */
    .example-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: var(--s-3);
        padding: var(--s-6) 0;
        font-size: var(--fs-sm);
        flex-wrap: wrap;
    }
    .example-pagination a,
    .example-pagination .example-pagination-disabled {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        color: var(--ink-2);
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 999px;
        text-decoration: none;
        transition: all 0.3s var(--ease, cubic-bezier(0.22, 1, 0.36, 1));
        min-width: 100px;
        justify-content: center;
        font-weight: 500;
    }
    .example-pagination a:hover {
        color: #fff;
        background: linear-gradient(135deg, var(--liquid-1), var(--liquid-2));
        border-color: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        box-shadow: 0 4px 16px rgba(107, 70, 193, 0.3);
    }
    .example-pagination .example-pagination-disabled {
        color: var(--ink-4);
        background: transparent;
        border-color: rgba(255, 255, 255, 0.3);
        cursor: not-allowed;
    }
    .example-pagination-info {
        color: var(--ink-2);
        font-variant-numeric: tabular-nums;
        padding: 10px 16px;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
        border-radius: 999px;
        font-weight: 500;
    }

    /* —— 液态玻璃底部（自定义） —— */
    .liquid-footer {
        margin: 32px clamp(20px, 4vw, 32px) 16px;
        padding: 24px 32px;
        background: var(--glass-bg);
        backdrop-filter: blur(40px) saturate(180%);
        -webkit-backdrop-filter: blur(40px) saturate(180%);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: var(--glass-shadow);
        text-align: center;
        position: relative;
    }
    .liquid-footer::before {
        content: "";
        position: absolute;
        top: 0; left: 10%; right: 10%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
        pointer-events: none;
    }
    .liquid-footer p {
        font-size: var(--fs-sm);
        color: var(--ink-2);
        margin: 0 0 8px;
    }
    .liquid-footer-links {
        font-size: var(--fs-xs);
        color: var(--ink-3);
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .liquid-footer-links span { margin-right: 8px; }
    .liquid-footer-links a {
        color: var(--ink-3);
        text-decoration: none;
        margin: 0 8px;
        padding: 4px 10px;
        border-radius: 999px;
        transition: all 0.3s var(--ease, cubic-bezier(0.22, 1, 0.36, 1));
    }
    .liquid-footer-links a:hover {
        color: var(--liquid-1);
        background: rgba(107, 70, 193, 0.08);
    }

    /* —— 响应式 —— */
    @media (max-width: 768px) {
        header {
            padding: var(--s-3) 0;
        }
        header .container {
            flex-direction: column;
            gap: var(--s-3);
            text-align: center;
        }
        .example-grid { grid-template-columns: 1fr; }
        .example-search-form { flex-direction: column; border-radius: 16px; }
        .example-search-btn { width: 100%; }
        .liquid-footer { padding: 20px 16px; border-radius: 20px; }
        .liquid-footer-links a { margin: 4px; }
    }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>
                    <a href="https://style.functioncool.xyz/" style="color:inherit;text-decoration:none;">
                        <span data-i18n="example-header-title"><?=$I18N['header-title'][0]?></span>
                        <span style="font-weight:400;color:var(--ink-3);font-size:.75em;margin-left:.3em;" data-i18n="example-header-badge"><?=$I18N['header-badge'][0]?></span>
                    </a>
                </h1>
                <p data-i18n="example-header-subtitle"><?=$I18N['header-subtitle'][0]?></p>
            </div>
            <div class="language-switcher">
                <a href="https://style.functioncool.xyz/" class="home-link" aria-label="<?=$I18N['back-link'][0]?>" data-i18n="example-back-link"><?=$I18N['back-link'][0]?></a>
                <button id="example-lang-btn" type="button"><?=$I18N['lang-btn'][0]?></button>
            </div>
        </div>
    </header>

    <main>
        <div class="example-main">

            <!-- 标题区 -->
            <section class="example-intro reveal reveal-1">
                <div class="skill-hero-badge" data-i18n="example-hero-badge"><?=$I18N['hero-badge'][0]?></div>
                <h2 data-i18n="example-hero-title"><?=$I18N['hero-title'][0]?></h2>
                <p data-i18n="example-hero-desc"><?=$I18N['hero-desc'][0]?></p>
            </section>

            <!-- 检索表单 -->
            <form class="example-search-form reveal reveal-2" method="get" action="" role="search">
                <input
                    class="example-search-input"
                    type="text"
                    name="q"
                    value="<?=htmlspecialchars($query, ENT_QUOTES, 'UTF-8')?>"
                    placeholder="<?=$I18N['search-placeholder'][0]?>"
                    aria-label="<?=$I18N['search-placeholder'][0]?>"
                    data-i18n-placeholder="example-search-placeholder"
                >
                <button class="example-search-btn" type="submit" data-i18n="example-search-btn"><?=$I18N['search-btn'][0]?></button>
            </form>

            <!-- 结果计数 -->
            <div class="example-meta reveal reveal-3">
                <?php if ($query === ''): ?>
                    <span data-i18n="example-count-format" data-count="<?=$totalAll?>"><?=str_replace('%d', $totalAll, $I18N['count-format'][0])?></span>
                <?php else: ?>
                    <span data-i18n="example-count-filtered" data-q="<?=htmlspecialchars($query, ENT_QUOTES)?>" data-hits="<?=$total?>" data-total="<?=$totalAll?>"><?=str_replace(['%s', '%d', '%d'], [htmlspecialchars($query, ENT_QUOTES), $total, $totalAll], $I18N['count-filtered'][0])?></span>
                <?php endif; ?>
            </div>

            <!-- 卡片网格 / 空状态 -->
            <?php if (empty($pageItems)): ?>
                <div class="example-empty reveal reveal-4">
                    <h3 data-i18n="example-empty-title"><?=$I18N['empty-title'][0]?></h3>
                    <p data-i18n="example-empty-desc"><?=$I18N['empty-desc'][0]?></p>
                    <a href="<?=example_url('', 1)?>" data-i18n="example-empty-clear"><?=$I18N['empty-clear'][0]?></a>
                </div>
            <?php else: ?>
                <div class="example-grid reveal reveal-4">
                    <?php foreach ($pageItems as $ex): ?>
                        <a class="example-card" href="<?=htmlspecialchars($ex['file'], ENT_QUOTES)?>" target="_blank" rel="noopener">
                            <div class="example-thumb<?php echo !empty($ex['thumb-image']) ? ' example-thumb-image' : ''; ?>"
                                <?php if (!empty($ex['thumb-image'])): ?>
                                    style="background-image: url('<?=htmlspecialchars($ex['thumb-image'], ENT_QUOTES)?>'); background-size: cover; background-position: center; background-color: <?=htmlspecialchars($ex['thumb'] ?? 'transparent', ENT_QUOTES)?>;"
                                <?php else: ?>
                                    style="background: <?=htmlspecialchars($ex['thumb'], ENT_QUOTES)?>;"
                                <?php endif; ?>
                            >
                                <?php if (empty($ex['thumb-image'])): ?>
                                    <span class="example-thumb-label <?=!empty($ex['thumb-dark']) ? 'dark' : ''?>">
                                        <?=htmlspecialchars($ex['name-zh'], ENT_QUOTES)?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="example-body">
                                <h3 class="example-card-title">
                                    <span class="name-zh"><?=htmlspecialchars($ex['name-zh'], ENT_QUOTES)?></span>
                                    <span class="name-en"><?=htmlspecialchars($ex['name-en'], ENT_QUOTES)?></span>
                                </h3>
                                <p class="example-card-desc">
                                    <span class="desc-zh"><?=htmlspecialchars($ex['desc-zh'], ENT_QUOTES)?></span>
                                    <span class="desc-en"><?=htmlspecialchars($ex['desc-en'], ENT_QUOTES)?></span>
                                </p>
                                <div class="example-tags">
                                    <?php foreach (array_slice($ex['tags'], 0, 4) as $tag): ?>
                                        <span class="example-tag">#<?=htmlspecialchars($tag, ENT_QUOTES)?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- 分页 -->
            <?php if ($total > 0 && $totalPages > 1): ?>
                <nav class="example-pagination reveal reveal-5" aria-label="分页">
                    <?php if ($page > 1): ?>
                        <a href="<?=htmlspecialchars(example_url($query, $page - 1), ENT_QUOTES)?>" data-i18n="example-prev-page"><?=$I18N['prev-page'][0]?></a>
                    <?php else: ?>
                        <span class="example-pagination-disabled" data-i18n="example-prev-page"><?=$I18N['prev-page'][0]?></span>
                    <?php endif; ?>

                    <span class="example-pagination-info" data-i18n="example-page-info" data-page="<?=$page?>" data-total="<?=$totalPages?>"><?=str_replace(['%d', '%d'], [$page, $totalPages], $I18N['page-info'][0])?></span>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?=htmlspecialchars(example_url($query, $page + 1), ENT_QUOTES)?>" data-i18n="example-next-page"><?=$I18N['next-page'][0]?></a>
                    <?php else: ?>
                        <span class="example-pagination-disabled" data-i18n="example-next-page"><?=$I18N['next-page'][0]?></span>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>

        </div>
    </main>

    <footer class="liquid-footer">
        <p data-i18n="example-footer-text"><?=$I18N['footer-text'][0]?></p>
        <div class="liquid-footer-links">
            <span data-i18n="example-friend-links"><?=$I18N['friend-links'][0]?></span>
            <a href="https://www.mutantcat.org/" target="_blank" rel="noopener">异猫工作群</a>
            <a href="https://www.fcnesyouxi.top/" target="_blank" rel="noopener">FC/NES游戏</a>
            <a href="https://www.jqshengtian.top/" target="_blank" rel="noopener">学习资料</a>
        </div>
    </footer>

    <script>
    // ── i18n 注入 ──
    // 将 PHP 端的 $I18N 翻译表注入到 i18n.js 期望的全局命名空间下
    // 这样 i18n.js 调用 t('key') 时即可拿到对应文案
    (function() {
        var I18N = <?php echo json_encode($I18N, JSON_UNESCAPED_UNICODE); ?>;
        // 兼容多种 i18n 工具的写法：window.I18N / window.__I18N__ / data-i18n
        window.I18N = I18N;
        window.__I18N__ = I18N;
        window.__STYLECOOL_EXAMPLE_I18N__ = I18N;

        // 简易翻译：扫描 [data-i18n]，按当前语言替换文本
        function applyI18n(lang) {
            var dict = window.I18N || {};
            document.querySelectorAll('[data-i18n]').forEach(function(el) {
                var key = el.getAttribute('data-i18n');
                if (!dict[key]) return;
                var val = dict[key][lang === 'en' ? 1 : 0];
                if (val !== undefined) el.textContent = val;
            });
            // 占位符
            document.querySelectorAll('[data-i18n-placeholder]').forEach(function(el) {
                var key = el.getAttribute('data-i18n-placeholder');
                if (!dict[key]) return;
                var val = dict[key][lang === 'en' ? 1 : 0];
                if (val !== undefined) el.setAttribute('placeholder', val);
            });
        }
        window.__applyI18n = applyI18n;
    })();

    // ── 初始化语言 ──
    document.addEventListener('DOMContentLoaded', function() {
        try {
            setTimeout(function() {
                var current = window.getCurrentLanguage ? window.getCurrentLanguage() : 'zh';
                if (window.setLanguage) window.setLanguage(current);
                if (window.__applyI18n) window.__applyI18n(current);
                var btn = document.getElementById('example-lang-btn');
                if (btn) {
                    btn.textContent = current === 'zh' ? 'English' : '中文';
                    btn.onclick = function() {
                        if (window.toggleLanguage) window.toggleLanguage();
                        var cur = window.getCurrentLanguage ? window.getCurrentLanguage() : 'zh';
                        if (window.__applyI18n) window.__applyI18n(cur);
                        btn.textContent = cur === 'zh' ? 'English' : '中文';
                    };
                }
            }, 100);
        } catch (e) {
            console.error('StyleCool example language init error:', e);
        }
    });
    </script>
</body>
</html>
