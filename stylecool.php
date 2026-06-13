<?php
// StyleCool thin proxy — 委托给 stylecool/index.php
// 主域 www.functioncool.xyz/stylecool 和独立域 style.functioncool.xyz 都经由这里

if (!defined('STYLECOOL_VIA_MAIN')) { define('STYLECOOL_VIA_MAIN', true); }
require_once __DIR__ . '/lib/ratelimit.php'; // 主站的限流（子文件夹独立部署时没有这个）
rate_limit_check();

require __DIR__ . '/stylecool/index.php';
