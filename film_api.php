<?php
header('Content-Type: application/json; charset=utf-8');

const TMDB_BEARER_TOKEN = '';
const TMDB_API_BASE = 'https://api.themoviedb.org/3';
const TMDB_IMAGE_BASE = 'https://image.tmdb.org/t/p/w600_and_h900_bestv2';

function json_hata($code, $message) {
    http_response_code($code);
    echo json_encode([
        'ok' => false,
        'message' => $message
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function get_query_value($key) {
    return isset($_GET[$key]) ? trim((string) $_GET[$key]) : '';
}

function tmdb_token() {
    $envToken = getenv('TMDB_BEARER_TOKEN');
    if (is_string($envToken) && trim($envToken) !== '') {
        return trim($envToken);
    }

    return trim(TMDB_BEARER_TOKEN);
}

function fallback_filmler() {
    return [
        'featured' => [
            ['title' => 'Interstellar', 'year' => '2014', 'genre' => 'Bilim Kurgu', 'poster' => TMDB_IMAGE_BASE . '/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg', 'url' => 'https://www.themoviedb.org/movie/157336', 'description' => 'Uzay, zaman ve duygu tarafini ayni anda tasiyan buyuk olcekli bir film deneyimi.', 'rating' => '8.4'],
        ],
        'action' => [
            ['title' => 'Mad Max: Fury Road', 'year' => '2015', 'genre' => 'Aksiyon', 'poster' => TMDB_IMAGE_BASE . '/hA2ple9q4qnwxp3hKVNhroipsir.jpg', 'url' => 'https://www.themoviedb.org/movie/76341', 'description' => 'Durmayan enerjisiyle cok guclu bir aksiyon deneyimi.', 'rating' => '7.6'],
        ],
        'scifi' => [
            ['title' => 'Arrival', 'year' => '2016', 'genre' => 'Bilim Kurgu', 'poster' => TMDB_IMAGE_BASE . '/x2FJsf1ElAgr63Y3PNPtJrcmpoe.jpg', 'url' => 'https://www.themoviedb.org/movie/329865', 'description' => 'Daha sakin ama duygusal tonu kuvvetli bir bilim kurgu.', 'rating' => '7.6'],
        ],
        'drama' => [
            ['title' => 'The Green Mile', 'year' => '1999', 'genre' => 'Dram', 'poster' => TMDB_IMAGE_BASE . '/8VG8fDNiy50H4FedGwdSVUPoaJe.jpg', 'url' => 'https://www.themoviedb.org/movie/497', 'description' => 'Karakter odakli ve duygusal agirligi yuksek bir klasik.', 'rating' => '8.5'],
        ],
        'animation' => [
            ['title' => 'Spider-Man: Into the Spider-Verse', 'year' => '2018', 'genre' => 'Animasyon', 'poster' => TMDB_IMAGE_BASE . '/iiZZdoQBEYBv6id8su7ImL0oCbD.jpg', 'url' => 'https://www.themoviedb.org/movie/324857', 'description' => 'Renkli, yaratici ve cok enerjik bir animasyon deneyimi.', 'rating' => '8.4'],
        ],
        'thriller' => [
            ['title' => 'Prisoners', 'year' => '2013', 'genre' => 'Gerilim', 'poster' => TMDB_IMAGE_BASE . '/uhviyknTT5cEQXbn6vWIqfM4vGm.jpg', 'url' => 'https://www.themoviedb.org/movie/146233', 'description' => 'Karanlik havasi ve surekli artan tansiyonuyla cok guclu.', 'rating' => '8.1'],
        ]
    ];
}

function fallback_search($query) {
    $query = mb_strtolower($query, 'UTF-8');
    $all = [];

    foreach (fallback_filmler() as $group) {
        foreach ($group as $film) {
            $all[] = $film;
        }
    }

    $matches = [];
    foreach ($all as $film) {
        $haystack = mb_strtolower($film['title'] . ' ' . $film['genre'] . ' ' . $film['description'], 'UTF-8');
        if (str_contains($haystack, $query)) {
            $matches[] = $film;
        }
    }

    if (empty($matches) && (str_contains($query, 'film') || str_contains($query, 'oner') || str_contains($query, 'izle'))) {
        return fallback_filmler()['featured'];
    }

    return $matches;
}

function tmdb_fetch($path, $params = []) {
    $token = tmdb_token();
    if ($token === '') {
        return [null, 'TMDB bearer token eksik.'];
    }

    if (!function_exists('curl_init')) {
        return [null, 'Sunucuda cURL destegi bulunamadi.'];
    }

    $url = TMDB_API_BASE . $path . '?' . http_build_query($params);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        return [null, $curlError !== '' ? $curlError : 'TMDB istegi gonderilemedi.'];
    }

    $decoded = json_decode($response, true);
    if (!is_array($decoded)) {
        return [null, 'TMDB gecersiz veri dondurdu.'];
    }

    if ($httpCode >= 400) {
        return [null, $decoded['status_message'] ?? 'TMDB hata dondurdu.'];
    }

    return [$decoded, null];
}

function category_map() {
    return [
        'featured' => ['path' => '/movie/popular', 'params' => ['language' => 'tr-TR', 'page' => 1]],
        'action' => ['path' => '/discover/movie', 'params' => ['language' => 'tr-TR', 'page' => 1, 'sort_by' => 'popularity.desc', 'with_genres' => '28']],
        'scifi' => ['path' => '/discover/movie', 'params' => ['language' => 'tr-TR', 'page' => 1, 'sort_by' => 'popularity.desc', 'with_genres' => '878']],
        'drama' => ['path' => '/discover/movie', 'params' => ['language' => 'tr-TR', 'page' => 1, 'sort_by' => 'popularity.desc', 'with_genres' => '18']],
        'animation' => ['path' => '/discover/movie', 'params' => ['language' => 'tr-TR', 'page' => 1, 'sort_by' => 'popularity.desc', 'with_genres' => '16']],
        'thriller' => ['path' => '/discover/movie', 'params' => ['language' => 'tr-TR', 'page' => 1, 'sort_by' => 'popularity.desc', 'with_genres' => '53']],
    ];
}

function genre_name_from_ids($ids) {
    $map = [
        28 => 'Aksiyon',
        16 => 'Animasyon',
        18 => 'Dram',
        35 => 'Komedi',
        53 => 'Gerilim',
        80 => 'Suc',
        878 => 'Bilim Kurgu',
        9648 => 'Gizem',
    ];

    if (!is_array($ids)) {
        return 'Film';
    }

    foreach ($ids as $id) {
        if (isset($map[$id])) {
            return $map[$id];
        }
    }

    return 'Film';
}

function tmdb_result_to_movie($item) {
    $posterPath = trim((string) ($item['poster_path'] ?? ''));
    $title = trim((string) ($item['title'] ?? $item['original_title'] ?? 'Bilinmeyen Film'));
    $releaseDate = trim((string) ($item['release_date'] ?? ''));

    return [
        'title' => $title,
        'year' => $releaseDate !== '' ? substr($releaseDate, 0, 4) : '',
        'genre' => genre_name_from_ids($item['genre_ids'] ?? []),
        'poster' => $posterPath !== '' ? TMDB_IMAGE_BASE . $posterPath : 'https://via.placeholder.com/600x900/0f172a/94a3b8?text=Film',
        'url' => 'https://www.themoviedb.org/movie/' . (int) ($item['id'] ?? 0),
        'description' => trim((string) ($item['overview'] ?? '')),
        'rating' => isset($item['vote_average']) ? number_format((float) $item['vote_average'], 1) : '',
    ];
}

function unique_limit($movies, $limit = 12) {
    $seen = [];
    $results = [];

    foreach ($movies as $movie) {
        $key = mb_strtolower($movie['title'] . '|' . $movie['year'], 'UTF-8');
        if (isset($seen[$key])) {
            continue;
        }

        $seen[$key] = true;
        $results[] = $movie;

        if (count($results) >= $limit) {
            break;
        }
    }

    return $results;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_hata(405, 'Sadece GET istegi kabul edilir.');
}

$type = get_query_value('type');
if ($type === '') {
    json_hata(422, 'Istek tipi eksik.');
}

if ($type === 'category') {
    $category = get_query_value('category');
    $fallback = fallback_filmler();
    $categoryMap = category_map();

    if (!isset($fallback[$category]) || !isset($categoryMap[$category])) {
        json_hata(422, 'Gecersiz kategori secildi.');
    }

    [$remoteData, $remoteError] = tmdb_fetch($categoryMap[$category]['path'], $categoryMap[$category]['params']);
    if ($remoteError !== null) {
        echo json_encode([
            'ok' => true,
            'results' => unique_limit($fallback[$category], 12),
            'fallback' => true,
            'message' => $remoteError
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $results = [];
    foreach (($remoteData['results'] ?? []) as $item) {
        $results[] = tmdb_result_to_movie($item);
    }

    echo json_encode([
        'ok' => true,
        'results' => unique_limit($results, 12)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($type === 'search') {
    $query = get_query_value('query');
    if (mb_strlen($query) < 2) {
        json_hata(422, 'Arama terimi en az 2 karakter olmali.');
    }

    [$remoteData, $remoteError] = tmdb_fetch('/search/movie', [
        'query' => $query,
        'language' => 'tr-TR',
        'page' => 1,
        'include_adult' => 'false'
    ]);

    if ($remoteError !== null) {
        echo json_encode([
            'ok' => true,
            'results' => unique_limit(fallback_search($query), 12),
            'fallback' => true,
            'message' => $remoteError
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $results = [];
    foreach (($remoteData['results'] ?? []) as $item) {
        $results[] = tmdb_result_to_movie($item);
    }

    echo json_encode([
        'ok' => true,
        'results' => unique_limit($results, 12)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

json_hata(422, 'Desteklenmeyen istek tipi.');
