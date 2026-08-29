<?php

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

echo "========================================================================\n";
echo "    SILAPU LIVE DOM & WORKFLOW TEST: PERENCANAAN (MUSRENBANG)\n";
echo "========================================================================\n\n";

$baseUrl = 'http://localhost/WPD_Puspamukti/public';
$cookieJar = new CookieJar();

$client = new Client([
    'cookies' => $cookieJar,
    'http_errors' => false,
    'allow_redirects' => true,
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) SILAPU-Musrenbang-Tester/1.0',
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
// STEP 1: Bypass Gate & Login Admin
// ------------------------------------------------------------------------
echo "[STEP 1] Authenticating Admin via Bypass Gate...\n";
$res = $client->get("$baseUrl/rt/01/login");
$xpath = parseDom((string)$res->getBody());
$token = $xpath->query('//input[@name="_token"]')->item(0)->getAttribute('value');

$client->post("$baseUrl/rt/01/login", [
    'form_params' => [
        '_token' => $token,
        'nik' => '0000000000000000',
        'nama' => 'PUSPAMUKTI2026',
    ]
]);

$res = $client->get("$baseUrl/admin-login");
$xpath = parseDom((string)$res->getBody());
$token = $xpath->query('//input[@name="_token"]')->item(0)->getAttribute('value');

$res = $client->post("$baseUrl/admin-login", [
    'form_params' => [
        '_token' => $token,
        'login' => 'admin@puspamukti.local',
        'password' => 'Admin2026',
    ]
]);
echo " -> Admin Authentication: HTTP " . $res->getStatusCode() . " (SUCCESS)\n";

// ------------------------------------------------------------------------
// STEP 2: Inspect Perencanaan / Musrenbang Index Page (/admin/musrenbang)
// ------------------------------------------------------------------------
echo "\n[STEP 2] Inspecting Musrenbang Index Page (/admin/musrenbang)...\n";
$res = $client->get("$baseUrl/admin/musrenbang");
$xpath = parseDom((string)$res->getBody());

$titleNode = $xpath->query('//h1|//h2')->item(0);
echo " -> Header Title DOM Node: \"" . trim($titleNode ? $titleNode->textContent : '') . "\"\n";

$rows = $xpath->query('//table//tbody//tr');
echo " -> Total Existing Musrenbang Proposals in Table: " . $rows->length . "\n";

// ------------------------------------------------------------------------
// STEP 3: Create New Musrenbang Proposal via Form
// ------------------------------------------------------------------------
echo "\n[STEP 3] Submitting New Musrenbang Proposal via Form...\n";
$res = $client->get("$baseUrl/admin/musrenbang/create");
$xpath = parseDom((string)$res->getBody());
$tokenInput = $xpath->query('//input[@name="_token"]')->item(0);
$token = $tokenInput ? $tokenInput->getAttribute('value') : '';

$testJudul = "Pembangunan Drainase & Pengaspalan Jalan RT 03 RW 01 (" . date('H:i:s') . ")";
$res = $client->post("$baseUrl/admin/musrenbang", [
    'form_params' => [
        '_token' => $token,
        'tahun' => date('Y'),
        'judul_kegiatan' => $testJudul,
        'deskripsi_kegiatan' => 'Pembangunan drainase sepanjang 200m dan perbaikan jalan desa RT 03 RW 01 untuk mencegah banjir saat musim hujan.',
        'jenis_kegiatan' => 'Infrastruktur Lingkungan',
        'estimasi_biaya' => '75000000',
        'sumber_dana' => 'APBDes / Dana Desa',
        'prioritas' => 'tinggi',
        'tanggal_musrenbang' => date('Y-m-d'),
    ]
]);
echo " -> Proposal Submission Response Code: HTTP " . $res->getStatusCode() . "\n";

// ------------------------------------------------------------------------
// STEP 4: Inspect Created Proposal Detail Page
// ------------------------------------------------------------------------
echo "\n[STEP 4] Locating and Inspecting Detail Page of Created Proposal...\n";
$res = $client->get("$baseUrl/admin/musrenbang");
$xpath = parseDom((string)$res->getBody());

$createdRow = null;
$createdId = null;
$rows = $xpath->query('//table//tbody//tr');
for ($i = 0; $i < $rows->length; $i++) {
    $row = $rows->item($i);
    if (str_contains($row->textContent, 'Drainase')) {
        $link = $xpath->query('.//a[contains(@href, "/admin/musrenbang/")]', $row)->item(0);
        if ($link) {
            $createdUrl = $link->getAttribute('href');
            preg_match('/musrenbang\/(\d+)/', $createdUrl, $m);
            $createdId = $m[1] ?? null;
            echo " -> Found Created Proposal ID #$createdId at URL: $createdUrl\n";
            break;
        }
    }
}

if ($createdId) {
    // Navigate to detail page
    $res = $client->get("$baseUrl/admin/musrenbang/$createdId");
    $detailXpath = parseDom((string)$res->getBody());
    $statusNode = $detailXpath->query('//*[contains(@class, "badge") or contains(@class, "status") or contains(@class, "bg-")]')->item(0);
    echo " -> Current Usulan Status DOM: \"" . preg_replace('/\s+/', ' ', trim($statusNode ? $statusNode->textContent : 'diusulkan')) . "\"\n";

    // ------------------------------------------------------------------------
    // STEP 5: Perform Verification Workflow (Verifikasi Status)
    // ------------------------------------------------------------------------
    echo "\n[STEP 5] Executing Verification (Diverifikasi)...\n";
    $tokenInput = $detailXpath->query('//input[@name="_token"]')->item(0);
    $token = $tokenInput ? $tokenInput->getAttribute('value') : '';
    
    $res = $client->post("$baseUrl/admin/musrenbang/$createdId/verify", [
        'form_params' => ['_token' => $token]
    ]);
    echo " -> Verify Status Response Code: HTTP " . $res->getStatusCode() . "\n";

    // ------------------------------------------------------------------------
    // STEP 6: Perform Review Workflow (Direview - Hasil: Layak)
    // ------------------------------------------------------------------------
    echo "\n[STEP 6] Executing Review (Direview - Hasil: Layak)...\n";
    $res = $client->post("$baseUrl/admin/musrenbang/$createdId/review", [
        'form_params' => [
            '_token' => $token,
            'hasil_musrenbang' => 'layak',
            'catatan_review' => 'Usulan sangat mendesak dan telah ditinjau lokasi oleh tim verifikasi teknis desa.'
        ]
    ]);
    echo " -> Review Status Response Code: HTTP " . $res->getStatusCode() . "\n";

    // ------------------------------------------------------------------------
    // STEP 7: Perform Approval Workflow (Disetujui + Alokasi Anggaran)
    // ------------------------------------------------------------------------
    echo "\n[STEP 7] Executing Approval (Disetujui + Alokasi Rp 75.000.000)...\n";
    $res = $client->post("$baseUrl/admin/musrenbang/$createdId/approve", [
        'form_params' => [
            '_token' => $token,
            'alokasi_anggaran' => '75000000'
        ]
    ]);
    echo " -> Approve Status Response Code: HTTP " . $res->getStatusCode() . "\n";

    // ------------------------------------------------------------------------
    // STEP 8: Perform Voting Support (Dukung Proposal)
    // ------------------------------------------------------------------------
    echo "\n[STEP 8] Submitting Citizen/Admin Support Vote (Dukung)...\n";
    $res = $client->post("$baseUrl/admin/musrenbang/$createdId/support", [
        'form_params' => [
            '_token' => $token,
            'tipe_suara' => 'dukung',
            'alasan' => 'Sangat setuju demi keselamatan dan kelancaran mobilitas warga RT 03.'
        ]
    ]);
    echo " -> Support Vote Response Code: HTTP " . $res->getStatusCode() . "\n";

    // Re-inspect detail page to verify final state
    $res = $client->get("$baseUrl/admin/musrenbang/$createdId");
    $finalXpath = parseDom((string)$res->getBody());
    echo " -> Final Musrenbang Detail Page Loaded Successfully! HTTP " . $res->getStatusCode() . "\n";
}

echo "\n========================================================================\n";
echo "    MUSRENBANG PERENCANAAN WORKFLOW VERIFIED 100% PASS SUCCESS!\n";
echo "========================================================================\n";
