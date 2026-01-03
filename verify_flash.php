<?php

require __DIR__.'/vendor/autoload.php';

// Mock session helper
function session($key) {
    if ($key === 'error') return 'Hanya bisa mencatat siklus haid di bulan Ramadhan.';
    return null;
}

$success = session('success');
$error = session('error');

echo "window.flashMessages = {\n";
echo "    success: " . json_encode($success) . ",\n";
echo "    error: " . json_encode($error) . "\n";
echo "};\n";
