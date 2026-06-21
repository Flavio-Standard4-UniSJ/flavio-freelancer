<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$apiKey = getenv('GOOGLE_PLACES_API_KEY');
$placeId = getenv('GOOGLE_PLACES_PLACE_ID') ?: ($_GET['place_id'] ?? '');

if (!$apiKey || !$placeId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing Google Places API credentials or place_id']);
    exit;
}

$fields = 'name,rating,reviews';
$url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . urlencode($placeId)
    . '&fields=' . urlencode($fields)
    . '&language=pt-BR&key=' . urlencode($apiKey);

$response = null;

if (function_exists('curl_version')) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao buscar dados do Google Places API', 'detail' => $curlError]);
        exit;
    }
} else {
    $response = @file_get_contents($url);
    if ($response === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao buscar dados do Google Places API. Habilite cURL ou allow_url_fopen.']);
        exit;
    }
}

$data = json_decode($response, true);
if (!is_array($data) || ($data['status'] ?? '') !== 'OK') {
    http_response_code(500);
    echo json_encode(['error' => 'Google Places API returned an error', 'detail' => $data['status'] ?? 'unknown']);
    exit;
}

$result = $data['result'] ?? [];
$reviews = [];

if (!empty($result['reviews']) && is_array($result['reviews'])) {
    foreach (array_slice($result['reviews'], 0, 5) as $review) {
        $reviews[] = [
            'author' => $review['author_name'] ?? 'Cliente',
            'rating' => $review['rating'] ?? 0,
            'text' => $review['text'] ?? '',
            'time' => $review['relative_time_description'] ?? '',
        ];
    }
}

echo json_encode([
    'place_name' => $result['name'] ?? null,
    'reviews' => $reviews,
]);
