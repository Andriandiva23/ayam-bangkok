<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$token = env('FONNTE_TOKEN');
$wa = env('ADMIN_WHATSAPP');

$imagePath = __DIR__ . '/public/storage/ayam_fotos/U3WIvm6u0UTwi6B0qtrvLNOuDE7k99dRtSNIxIf7.png';

$data = [
    'target' => $wa,
    'message' => 'Test file upload Fonnte via Local File (CURLFile)',
    'file' => new CURLFile($imagePath)
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.fonnte.com/send');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: ' . $token]);
$result = curl_exec($ch);
echo "Response dari Fonnte (dengan gambar): " . $result . "\n";
