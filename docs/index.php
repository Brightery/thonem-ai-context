<?php
// index.php

// 1. Configuration
define('DOCS_PATH', __DIR__ . '/content');
$current_page = $_GET['page'] ?? 'home';

// 2. Helper: Scan directories for navigation
function getNavigation($path) {
    $nav = [];
    $items = scandir($path);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $fullPath = $path . '/' . $item;
        if (is_dir($fullPath)) {
            $nav[$item] = getNavigation($fullPath);
        } elseif (pathinfo($item, PATHINFO_EXTENSION) === 'md') {
            // Clean filename for display
            $name = pathinfo($item, PATHINFO_FILENAME);
            // Create a clean slug
            $slug = str_replace([DOCS_PATH . '/', '.md'], '', $fullPath);
            $nav[] = ['name' => $name, 'slug' => $slug];
        }
    }
    return $nav;
}

// 3. Helper: Simple Markdown Parser (Basic support for headers, code, tables)
function parseMarkdown($text) {
    // Code blocks
    $text = preg_replace_callback('/```(.*?)```/s', function($matches) {
        return '<pre><code>' . htmlspecialchars($matches[1]) . '</code></pre>';
    }, $text);

    // Inline Code
    $text = preg_replace('/`(.*?)`/', '<code>$1</code>', $text);

    // Headers
    $text = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $text);
    $text = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $text);
    $text = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $text);

    // Tables (Basic)
    $text = preg_replace_callback('/((?:\|.*\|(?:\r\n|\n))+)/', function($matches) {
        $rows = array_filter(explode("\n", trim($matches[1])));
        $html = '<div class="table-responsive"><table>';
        foreach($rows as $index => $row) {
            if(strpos($row, '---') !== false) continue; // Skip separator
            $cols = array_filter(explode('|', trim($row, '|')));
            $tag = ($index === 0) ? 'th' : 'td';
            $html .= '<tr>';
            foreach($cols as $col) $html .= "<$tag>" . trim($col) . "</$tag>";
            $html .= '</tr>';
        }
        return $html . '</table></div>';
    }, $text);

    // Paragraphs (simple split)
    $lines = explode("\n", $text);
    $final = "";
    foreach($lines as $line) {
        if(trim($line) == "" || strpos($line, '<') === 0) {
            $final .= $line . "\n";
        } else {
            $final .= "<p>$line</p>";
        }
    }
    return $final;
}

// 4. Logic
$nav = getNavigation(DOCS_PATH);
$file_path = DOCS_PATH . '/' . $current_page . '.md';

if (file_exists($file_path) && strpos(realpath($file_path), realpath(DOCS_PATH)) === 0) {
    $content = file_get_contents($file_path);
    $htmlContent = parseMarkdown($content);
} else {
    $htmlContent = "<h1>Welcome to Thonem Framework</h1><p>Select a topic from the sidebar.</p>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thonem Docs</title>
    <link rel="stylesheet" href="/docs/style.css">
</head>
<body>
<div class="sidebar">
    <div class="brand">Thonem Docs</div>
    <ul>
        <?php foreach($nav as $category => $items): ?>
            <?php if(is_array($items)): ?>
                <li class="category"><?= str_replace('_', ' ', $category) ?></li>
                <?php foreach($items as $item): ?>
                    <?php if(isset($item['slug'])): ?>
                        <li><a href="?page=<?= $item['slug'] ?>" class="<?= $current_page == $item['slug'] ? 'active' : '' ?>"><?= $item['name'] ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<div class="main">
    <?= $htmlContent ?>
</div>
</body>
</html>