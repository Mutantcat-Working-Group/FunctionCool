<div align="center">
<img src="assets/logo.png" style="width:120px;" width="120" alt="函数库"/>
<h2>函数库 · FunctionCool</h2>
<p>为开发者与 AI 打造的多语言编程函数库</p>
<p>
  <a href="README.md">English</a> |
  <b><a href="README.zh-CN.md">简体中文</a></b>
</p>
<p>
  <a href="https://functioncool.mutantcat.org/">🌐 functioncool.mutantcat.org</a>
</p>
</div>

### 一、功能简述
- 覆盖 **C/C++、Go、Python、Java、JavaScript、Rust、MATLAB、PHP、Ruby、Verilog** 的多语言常用函数速查库，每条均带完整代码示例。
- 每个函数条目包含中英文名称与描述、用途、依赖、标签、输入/返回类型，以及 `timer_score` / `memory_score` 性能评分。
- 站内搜索：按名称、描述、标签模糊匹配，支持按语言过滤。
- **Skill API（AI 可集成接口）**：`GET /skillapi?token=&q=&lang=` 返回结构化 JSON，让 AI “先查再写”——把昂贵的输出 token 折成便宜的输入 token。
- 中英双语界面一键切换；SEO 完善（sitemap.xml、OG/Twitter Card、JSON-LD、伪静态 URL）。
- IP 限流：每 IP 10 秒 20 次，超限返回 429 并附 `Retry-After`。
- **StyleCool**（设计样式接口）已独立为单独项目：<a href="https://stylecool.mutantcat.org/">stylecool.mutantcat.org</a>。

### 二、部署方式
1. 环境要求：**PHP 8.x + Apache**，需开启 `mod_rewrite`。
2. 将项目拷贝到站点根目录即可，`.htaccess` 已处理伪静态与 301（老域名 `functioncool.xyz` 自动迁移到 `functioncool.mutantcat.org`）。
3. 或使用 Docker 构建运行：
   ```bash
   docker build -t functioncool .
   docker run -p 80:80 functioncool
   ```
   CI 已发布多架构镜像到 `ghcr.io/mutantcat-working-group/functioncool`。
4. 密钥：修改 `data/skill_token_permanent.json`（永久密钥，默认 `mutantcat`），生产环境务必更换并保护 `data/` 目录权限。

### 三、使用教程
1. 打开首页，输入关键词（如 `数组`、`sort`），可按语言过滤。
2. 右上角 **English / 中文** 按钮一键切换界面语言。
3. AI 集成请调用 Skill API（见第四节）。

### 四、接口文档 — SkillAPI
- 接口地址：`https://functioncool.mutantcat.org/skillapi`
- 请求方式：`GET`（无参数时返回说明文档页）
- 请求参数：
  - `token`（必填）—— 永久密钥，如 `mutantcat`
  - `q`（必填）—— 搜索关键词
  - `lang`（可选）—— `all` 或 `C, CPP, GO, PYTHON, JAVA, JAVASCRIPT, RUST, MATLAB, PHP, RUBY, VERILOG` 之一（`VARILOG` 作为兼容别名）
- 调用示例：
  ```bash
  curl "https://functioncool.mutantcat.org/skillapi?token=mutantcat&q=sort&lang=PHP"
  ```
- 返回示例（JSON）：
  ```json
  {
    "results": [
      {
        "name-zh": "数组排序",
        "name-en": "Array Sort",
        "tags": ["array", "sort"],
        "timer_score": 90,
        "memory_score": 95,
        "code": ["<?php ..."]
      }
    ],
    "query": "sort",
    "lang": "PHP"
  }
  ```
- 限流：每 IP 10 秒 20 次，超限返回 429 + `Retry-After`。

StyleCool（独立项目）按平台提供设计样式检索：
`https://stylecool.mutantcat.org/skillapi?token=mutantcat&q={关键词}&cat={web|desktop|miniapp|mobile|all}`

### 五、专注的点
- **知识是公共品**：函数库与 StyleCool 坚持永久免费开放，详见 <a href="https://functioncool.mutantcat.org/notes/004">永久免费的承诺</a>。
- **先查再写**：AI 生成前先检索 JSON 索引，省 token、降低幻觉。
- **结构化数据**：每条函数带性能评分与类型标注，可直接喂给 LLM 上下文。
- **双语 + SEO**：中英界面、动态 sitemap、canonical 与结构化数据齐全。

### 六、开发进度
- [x] 多语言函数库（JSON 数据）
- [x] 站内搜索（中英名称/描述/标签、语言过滤）
- [x] Skill API（永久密钥 + JSON）
- [x] 中英双语界面（i18n）
- [x] 博客笔记
- [x] Sitemap / SEO
- [x] IP 限流
- [x] StyleCool 独立子项目
- [x] Docker 镜像 + GitHub Actions CI/Release
- [ ] 持续扩充函数条目
- [ ] 社区贡献 / PR 流程

---
<p align="center">© 2025-2026 函数库 · Powered by <a href="https://www.mutantcat.org/">Mutantcat Working Group</a></p>
