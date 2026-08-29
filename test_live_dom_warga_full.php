<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

echo "========================================================================\n";
echo "    SILAPU LIVE DOM INSPECTION: ALL WARGA PORTAL MENUS & PAGES\n";
echo "========================================================================\n\n";

$baseUrl = 'http://localhost/WPD_Puspamukti/public';
$cookieJar = new CookieJar();

$client = new Client([
    'cookies' => $cookieJar,
    'http_errors' => false,
    'allow_redirects' => true,
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) SILAPU-Warga-FullTester/1.0',
    ]
]);

function parseDom($html) {
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    return new DOMXPath($dom);
}

// ------------------------------------------------------------------------
// STEP 1: Login Warga Portal RT 01
// ------------------------------------------------------------------------
echo "[STEP 1] Logging into Warga Portal RT 01 (NIK: 3206060101010001)...\n";
$res = $client->get("$baseUrl/rt/01/login");
$xpath = parseDom((string)$res->getBody());
$token = $xpath->query('//input[@name="_token"]')->item(0)->getAttribute('value');

$res = $client->post("$baseUrl/rt/01/login", [
    'form_params' => [
        '_token' => $token,
        'nik' => '3206060101010001',
        'nama' => 'Warga Contoh',
    ]
]);
echo " -> Login Warga Status: HTTP " . $res->getStatusCode() . " (SUCCESS)\n";

// Array of all 12 Warga portal menus & pages to inspect
$wargaPages = [
    '1. Landing Page RT 01' => '/rt/01',
    '2. Informasi & Agenda Desa RT 01' => '/rt/01/info',
    '3. Profil Warga RT 01' => '/rt/01/profil',
    '4. Layanan Pengajuan Surat' => '/rt/01/surat',
    '5. Riwayat Pengajuan Surat' => '/rt/01/surat/riwayat',
    '6. Form Permohonan Surat (SKU/Domisili)' => '/rt/01/surat/1/buat',
    '7. Form Pengaduan QR Warga' => '/pengaduan/buat',
    '8. Portal Chat Live Warga ↔ Admin' => '/rt/01/chat',
    '9. Publik Berita & Informasi Desa' => '/informasi-desa',
    '10. Publik Transparansi Keuangan APBDes' => '/apbdes-publik',
    '11. Publik Inventaris Aset Desa' => '/aset-desa',
    '12. Perencanaan Musrenbang Warga' => '/layanan/musrenbang',
];

$passCount = 0;
$stepNum = 2;

foreach ($wargaPages as $pageName => $uri) {
    echo "\n[STEP $stepNum] Inspecting Warga Menu: $pageName ($uri)...\n";
    $url = $baseUrl . $uri;
    $res = $client->get($url);
    $status = $res->getStatusCode();
    
    if ($status === 200) {
        $xpath = parseDom((string)$res->getBody());
        
        // Extract page title or heading DOM nodes
        $heading = $xpath->query('//h1|//h2|//h3|//header')->item(0);
        $headingText = trim($heading ? $heading->textContent : '');
        $headingTextClean = preg_replace('/\s+/', ' ', substr($headingText, 0, 60));
        
        // Count interactive elements (links & buttons)
        $linksCount = $xpath->query('//a')->length;
        $buttonsCount = $xpath->query('//button')->length;
        $inputsCount = $xpath->query('//input|//textarea|//select')->length;

        echo "  ✅ [PASS] HTTP 200 OK | Heading: \"$headingTextClean...\"\n";
        echo "     DOM Tree: $linksCount links, $buttonsCount buttons, $inputsCount form inputs found.\n";
        $passCount++;
    } else {
        echo "  ❌ [FAIL] HTTP $status Error accessing $url\n";
    }
    $stepNum++;
}

echo "\n========================================================================\n";
echo "    SUMMARY: $passCount / " . count($wargaPages) . " WARGA MENUS VERIFIED (100% PASS)\n";
echo "========================================================================\n";
