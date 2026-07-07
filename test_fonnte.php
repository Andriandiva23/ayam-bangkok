<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$token = env('FONNTE_TOKEN');
$adminWa = env('ADMIN_WHATSAPP');

echo "Sending to ADMIN: $adminWa with token $token\n";

$data = ['target' => $adminWa, 'message' => 'Tes WA Admin JagoFarm'];
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.fonnte.com/send',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => ['Authorization: ' . $token],
]);
$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

echo "Fonnte Response: " . $response . "\n";
if ($error) echo "CURL Error: " . $error . "\n";
