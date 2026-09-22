<div align="center">
<img src="assets/logo.png" style="width:120px;" width="120" alt="FunctionCool"/>
<h2>FunctionCool · 函数库</h2>
<p>The multi-language programming function library for developers &amp; AI</p>
<p>
  <b><a href="README.en.md">English</a></b> |
  <a href="README.md">简体中文</a>
</p>
<p>
  <a href="https://functioncool.mutantcat.org/">🌐 functioncool.mutantcat.org</a>
</p>
</div>

### 1. Overview
- A searchable library of common programming functions with full code examples, covering **C/C++, Go, Python, Java, JavaScript, Rust, MATLAB, PHP, Ruby and Verilog**.
- Every entry ships with bilingual (zh/en) names & descriptions, usage notes, dependencies, tags, input/return types, and `timer_score` / `memory_score` performance ratings.
- **Skill API** for AI agents: `GET /skillapi?token=&q=&lang=` returns structured JSON, so an LLM can "search first, then write" — trading expensive output tokens for cheap input tokens.
- Bilingual UI with one-click zh/en switching; SEO ready (sitemap.xml, OG/Twitter cards, JSON-LD, pretty URLs).
- IP rate limiting (20 req / 10 s) with 429 + `Retry-After`.
- **StyleCool** design-style API is now an independent project: <a href="https://stylecool.mutantcat.org/">stylecool.mutantcat.org</a>.

Core value:

- Public goods, not commodities: the library stays free and open.
- Search-first prompting for AI: hitting the JSON index before generation cuts cost and hallucination.
- Structured data: every function carries performance scores and type info, ready to feed LLM contexts.
- Bilingual + SEO: zh/en UI, dynamic sitemap, canonical URLs, structured data.

### 2. Features

#### Multi-language function library

- Ten languages of common function lookups, each with a full runnable code example.
- Entries include bilingual names and descriptions, usage notes, dependencies, tags, input/return types, and performance ratings.

#### Site-wide search

- Fuzzy matching by name, description, or tags, with per-language filtering.

#### Skill API

- A structured retrieval endpoint for AI agents and automation workflows, returning ready-to-use JSON.
- Permanent key mechanism with IP rate limiting.

### 3. Installation & Deployment

1. Requirements: **PHP 8.x + Apache** with `mod_rewrite` enabled.
2. Copy the project into your web root; `.htaccess` handles pretty URLs and 301 redirects (old domain `functioncool.xyz` migrates to `functioncool.mutantcat.org`).
3. Or build the Docker image:
    ```bash
    docker build -t functioncool .
    docker run -p 80:80 functioncool
    ```
    A prebuilt multi-arch image is published by CI to `ghcr.io/mutantcat-working-group/functioncool`.
4. Token: edit `data/skill_token_permanent.json` (permanent key, default `mutantcat`) — change it in production and protect `data/` permissions.

### 4. Quick Start

1. Open the homepage, type a keyword (e.g. `array`, `sort`) and optionally pick a language.
2. Toggle the **English / 中文** button in the header to switch UI language.
3. For AI integration, call the Skill API (see section 5).

### 5. API Reference — SkillAPI

- Endpoint: `https://functioncool.mutantcat.org/skillapi`
- Method: `GET` (no params render the human-readable docs page)
- Params:
    - `token` (required) — permanent key, e.g. `mutantcat`
    - `q` (required) — search keyword
    - `lang` (optional) — `all` or one of `C, CPP, GO, PYTHON, JAVA, JAVASCRIPT, RUST, MATLAB, PHP, RUBY, VERILOG` (`VARILOG` accepted as alias)
- Example:
    ```bash
    curl "https://functioncool.mutantcat.org/skillapi?token=mutantcat&q=sort&lang=PHP"
    ```
- Response (JSON):
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
- Rate limit: 20 requests per 10 s per IP (`429` + `Retry-After` on overflow).

StyleCool (independent project) offers design styles per platform:
`https://stylecool.mutantcat.org/skillapi?token=mutantcat&q={keyword}&cat={web|desktop|miniapp|mobile|all}`

### 6. Roadmap

- [x] Multi-language function library (JSON data)
- [x] Site search (zh/en name, description, tags, language filter)
- [x] Skill API with permanent token
- [x] Bilingual UI (i18n)
- [x] Blog notes
- [x] Sitemap / SEO
- [x] IP rate limiting
- [x] StyleCool independent sub-project
- [x] Docker image + GitHub Actions CI/Release
- [ ] Continuous expansion of function entries
- [ ] Community contribution / PR workflow

---
<p align="center">© 2025-2026 FunctionCool · Powered by <a href="https://www.mutantcat.org/">Mutantcat Working Group</a></p>
