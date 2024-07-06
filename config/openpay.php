<?php

return [
    'merchant_id' => env('OPENPAY_MERCHANT_ID'),
    'private_key' => env('OPENPAY_PRIVATE_KEY'),
    'public_key' => env('OPENPAY_PUBLIC_KEY'),
    'country' => env('OPENPAY_COUNTRY', 'PE'), // Asegúrate de cambiar a 'PE' para Perú
    'production' => env('OPENPAY_PRODUCTION', false), // true para producción, false para sandbox
];
