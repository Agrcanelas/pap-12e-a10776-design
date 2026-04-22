<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$SUPPORTED_LANGS = ['pt', 'en', 'fr', 'es', 'de'];

function _normalize_lang(?string $lang, array $supported): string {
    $lang = strtolower(trim((string)$lang));
    return in_array($lang, $supported, true) ? $lang : 'pt';
}

// Idioma: GET > SESSION > COOKIE > pt
$lang = 'pt';
if (isset($_GET['lang'])) {
    $lang = _normalize_lang((string)$_GET['lang'], $SUPPORTED_LANGS);
    $_SESSION['lang'] = $lang;
    setcookie('lang', $lang, time() + 60 * 60 * 24 * 30, '/');
} elseif (isset($_SESSION['lang'])) {
    $lang = _normalize_lang((string)$_SESSION['lang'], $SUPPORTED_LANGS);
} elseif (isset($_COOKIE['lang'])) {
    $lang = _normalize_lang((string)$_COOKIE['lang'], $SUPPORTED_LANGS);
    $_SESSION['lang'] = $lang;
}

function _load_translations_file(string $path): array {
    if (!file_exists($path)) {
        return [];
    }

    $t = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) return [];

    foreach ($lines as $line) {
        $line = trim((string)$line);
        if ($line === '' || str_starts_with($line, '#')) continue;

        // Formato: chave|valor
        if (strpos($line, '|') === false) continue;
        [$key, $value] = explode('|', $line, 2);
        $key = trim($key);
        if ($key === '') continue;
        $t[$key] = trim($value);
    }

    return $t;
}

// Fallback PT + override do idioma escolhido
$t_pt = _load_translations_file(__DIR__ . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'pt.txt');
$t_lang = ($lang === 'pt') ? [] : _load_translations_file(__DIR__ . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $lang . '.txt');
$t = array_merge($t_pt, $t_lang);

function t(string $key, array $vars = []): string {
    global $t;
    $value = $t[$key] ?? $key;
    foreach ($vars as $k => $v) {
        $value = str_replace('{' . $k . '}', (string)$v, $value);
    }
    return $value;
}

// Compatibilidade com o teu código atual
function __(string $key, array $vars = []): string {
    return t($key, $vars);
}

function lang_url(string $newLang): string {
    global $SUPPORTED_LANGS;
    $newLang = _normalize_lang($newLang, $SUPPORTED_LANGS);

    $uri = (string)($_SERVER['REQUEST_URI'] ?? '');
    $parts = parse_url($uri);
    $path = $parts['path'] ?? '';
    $query = [];
    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
    }
    $query['lang'] = $newLang;

    return $path . '?' . http_build_query($query);
}
