<?php

return [
    'bank_transfer' => [
        'enabled' => env('PAYMENT_TRANSFER_ENABLED', true),
        'title' => 'Transfer Semua Bank (Midtrans VA)',
        'description' => env('PAYMENT_TRANSFER_DESCRIPTION', 'Bayar melalui virtual account Midtrans yang mendukung jaringan semua bank.'),
        'instructions' => [
            'Pilih metode Transfer Semua Bank saat membuat pesanan.',
            'Salin nomor virtual account atau rekening yang tertera setelah pesanan dibuat.',
            'Selesaikan pembayaran melalui mobile banking, ATM, atau teller lalu konfirmasi melalui dashboard atau tim support.',
        ],
        'support_contact' => env('PAYMENT_SUPPORT_CONTACT', 'support@deadlineku.id'),
        'accounts' => array_values(array_filter([
            [
                'bank' => env('PAYMENT_BANK_1_NAME'),
                'number' => env('PAYMENT_BANK_1_NUMBER'),
                'name' => env('PAYMENT_BANK_1_HOLDER'),
            ],
            [
                'bank' => env('PAYMENT_BANK_2_NAME'),
                'number' => env('PAYMENT_BANK_2_NUMBER'),
                'name' => env('PAYMENT_BANK_2_HOLDER'),
            ],
        ], function (array $account): bool {
            return filled($account['bank']) && filled($account['number']) && filled($account['name']);
        })),
    ],
];
