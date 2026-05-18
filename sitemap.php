<?php
header('Content-Type: application/xml; charset=utf-8');

$base = 'https://www.functioncool.xyz';

$pages = [
    ['loc' => "$base/",       'changefreq' => 'daily',   'priority' => '1.0'],
    ['loc' => "$base/search", 'changefreq' => 'daily',   'priority' => '0.9'],
];

$noteFiles = glob(__DIR__ . '/notes/note*.php');
if ($noteFiles) {
    foreach ($noteFiles as $file) {
        $num = basename($file);          // note001.php
        $num = substr($num, 4, 3);       // 001
        $pages[] = [
            'loc'        => "$base/notes/$num",
            'lastmod'    => date('Y-m-d', filemtime($file)),
            'changefreq' => 'monthly',
            'priority'   => '0.7',
        ];
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as $p) {
    echo "  <url>\n";
    echo "    <loc>{$p['loc']}</loc>\n";
    if (isset($p['lastmod'])) {
        echo "    <lastmod>{$p['lastmod']}</lastmod>\n";
    }
    echo "    <changefreq>{$p['changefreq']}</changefreq>\n";
    echo "    <priority>{$p['priority']}</priority>\n";
    echo "  </url>\n";
}
echo '</urlset>' . "\n";
