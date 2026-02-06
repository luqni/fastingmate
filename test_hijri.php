<?php
try {
    $fmt = IntlDateFormatter::create(
        'id_ID@calendar=islamic',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Asia/Jakarta',
        IntlDateFormatter::TRADITIONAL,
        'd MMMM y'
    );
    echo "Formatted: " . $fmt->format(time()) . PHP_EOL;
    
    // Also try simple english to see standard names
    $fmtEn = IntlDateFormatter::create(
        'en_US@calendar=islamic',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Asia/Jakarta',
        IntlDateFormatter::TRADITIONAL,
        'd MMMM y'
    );
    echo "English Formatted: " . $fmtEn->format(time()) . PHP_EOL;

} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
